<?php
/*
Plugin Name: Waybill Manager
Description: Manage waybill creation and generation
Version: 1.0
Author: Dee
*/

// Initialize plugin
add_action('init', 'waybill_manager_init', 1); // Priority 1 to ensure early execution

function waybill_manager_init() {
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }
    add_action('wp_ajax_waybill_login', 'handle_waybill_login_ajax');
    add_action('wp_ajax_nopriv_waybill_login', 'handle_waybill_login_ajax');
}

// Register activation hook
register_activation_hook(__FILE__, 'waybill_manager_activate');

function waybill_manager_activate() {
    global $wpdb;
    
    // Create database tables
    waybill_manager_install();
    
    // Create default admin user
    waybill_manager_create_default_user();
    
    // Create the login page
    $login_page = array(
        'post_title'    => 'Waybill Login',
        'post_name'     => 'waybill-login',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_content'  => '[waybill_login]'
    );
    
    // Create the dashboard page
    $dashboard_page = array(
        'post_title'    => 'Waybill Dashboard',
        'post_name'     => 'waybill-dashboard',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_content'  => '[waybill_dashboard]'
    );
    
    // Check if pages exist and create them if they don't
    if (!get_page_by_path('waybill-login')) {
        wp_insert_post($login_page);
    }
    
    if (!get_page_by_path('waybill-dashboard')) {
        wp_insert_post($dashboard_page);
    }
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Create default admin user on plugin activation
function waybill_manager_create_default_user() {
    global $wpdb;
    
    try {
        $table = $wpdb->prefix . 'waybill_users';
        
        // Check if admin exists
        $admin_exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE username = %s",
            'admin'
        ));
        
        if ($wpdb->last_error) {
            throw new Exception('Database error checking admin user: ' . $wpdb->last_error);
        }
        
        if (!$admin_exists) {
            $result = $wpdb->insert(
                $table,
                array(
                    'username' => 'admin',
                    'email' => 'admin@outrite.africa',
                    'password' => wp_hash_password('admin123'),
                    'is_admin' => 1
                ),
                array('%s', '%s', '%s', '%d')
            );
            
            if ($result === false) {
                throw new Exception('Failed to create admin user: ' . $wpdb->last_error);
            }
            
            error_log('Default admin user created successfully');
        }
        
        return true;
        
    } catch (Exception $e) {
        error_log('Error creating default user: ' . $e->getMessage());
        return false;
    }
}

// Database installation
function waybill_manager_install() {
    global $wpdb;
    $wpdb->show_errors();
    
    try {
        $charset_collate = $wpdb->get_charset_collate();
        
        // Create users table with error checking
        $sql_users = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}waybill_users (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            username varchar(50) NOT NULL,
            email varchar(100) NOT NULL,
            password varchar(255) NOT NULL,
            is_admin tinyint(1) DEFAULT 0,
            PRIMARY KEY  (id),
            UNIQUE KEY username (username),
            UNIQUE KEY email (email)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_users);
        
        if ($wpdb->last_error) {
            throw new Exception('Error creating users table: ' . $wpdb->last_error);
        }
        
        // Create waybills table with DECIMAL precision for numeric columns
        $sql_waybills = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}waybills (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            waybill_number varchar(50) NOT NULL,
            Date_Created date NOT NULL,
            Allocated_to varchar(100) NOT NULL,
            Movement_Type varchar(100) NOT NULL,
            Client_Reference varchar(100) NOT NULL,
            Action_Date date NOT NULL,
            Sender_Name varchar(100) NOT NULL,
            Sender_Customer_Name varchar(100) NOT NULL,
            Sender_Address text NOT NULL,
            Sender_Telephone varchar(20) NOT NULL,
            Sender_Country_State_City text NOT NULL,
            Reciver_Name varchar(100) NOT NULL,
            Reciver_Customer_Name varchar(100) NOT NULL,
            Reciver_Address text NOT NULL,
            Reciver_Telephone varchar(20) NOT NULL,
            Reciver_Country_State_City text NOT NULL,
            Type_Of_Service varchar(100) NOT NULL,
            Special_Instructions text NOT NULL,
            Total_Pieces int NOT NULL,
            total_cartins int(255) NOT NULL,
            Volume_Mass DECIMAL(10,2) NOT NULL,
            Actual_Mass DECIMAL(10,2) NOT NULL,
            pod_image varchar(255) DEFAULT NULL,
            pod_upload_date datetime DEFAULT NULL,
            description_of_goods varchar(255) NOT NULL,
            Delivery varchar(255) DEFAULT NULL,
            Invoice_No varchar(255) DEFAULT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        
        dbDelta($sql_waybills);
        
        if ($wpdb->last_error) {
            throw new Exception('Error creating waybills table: ' . $wpdb->last_error);
        }
        
        // Create upload directory if it doesn't exist
        $upload_dir = wp_upload_dir();
        $pod_dir = $upload_dir['basedir'] . '/pod-images';
        if (!file_exists($pod_dir)) {
            wp_mkdir_p($pod_dir);
        }
        
        error_log('Waybill tables created successfully');
        return true;
        
    } catch (Exception $e) {
        error_log('Waybill table creation error: ' . $e->getMessage());
        return false;
    }
}

// Create or update waybill pages on plugin activation
function waybill_manager_create_pages() {
    // Create default admin user first
    waybill_manager_create_default_user();
    
    // Check and create/update the login page
    $login_page = get_page_by_path('waybill-login');
    $login_page_id = wp_insert_post(array(
        'post_title'    => 'Waybill Login',
        'post_content'  => '[waybill_login]',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_name'     => 'waybill-login',
        'page_template' => 'template-waybill-login.php'
    ));
    
    // Check and create/update the dashboard page
    $dashboard_page = get_page_by_path('waybill-dashboard');
    $dashboard_page_id = wp_insert_post(array(
        'post_title'    => 'Waybill Dashboard',
        'post_content'  => '[waybill_dashboard]',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_name'     => 'waybill-dashboard',
        'page_template' => 'template-waybill-dashboard.php'
    ));
    
    // Set the templates
    update_post_meta($login_page_id, '_wp_page_template', 'template-waybill-login.php');
    update_post_meta($dashboard_page_id, '_wp_page_template', 'template-waybill-dashboard.php');
    
    // Delete any duplicate pages
    $duplicate_pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => array('template-waybill-login.php', 'template-waybill-dashboard.php')
    ));
    
    foreach ($duplicate_pages as $page) {
        if ($page->ID != $login_page_id && $page->ID != $dashboard_page_id) {
            wp_delete_post($page->ID, true);
        }
    }
    
    
}

// Handle login page display
function waybill_display_login() {
    if (!session_id() && !headers_sent()) {
        session_start();
    }
    
    if (isset($_SESSION['waybill_user'])) {
        wp_redirect(home_url('/waybill-dashboard/'));
        exit;
    }
    
    status_header(200);
    get_header();
    echo render_login_form();
    get_footer();
    exit;
}

// Add rewrite rules for the login page
function waybill_add_rewrite_rules() {
    add_rewrite_rule(
        '^waybill-login/?$',
        'index.php?waybill_login=1',
        'top'
    );
}
add_action('init', 'waybill_add_rewrite_rules');

// Add query vars
function waybill_add_query_vars($vars) {
    $vars[] = 'waybill_login';
    return $vars;
}
add_filter('query_vars', 'waybill_add_query_vars');

// Handle template redirect
function waybill_handle_template() {
    if (get_query_var('waybill_login') || is_page('waybill-login')) {
        if (!session_id() && !headers_sent()) {
            session_start();
        }
        
        // If user is already logged in, redirect to dashboard
        if (isset($_SESSION['waybill_user'])) {
            wp_redirect(home_url('/waybill-dashboard/'));
            exit;
        }
    }
}
add_action('template_redirect', 'waybill_handle_template', 10);

// Register activation hook
register_activation_hook(__FILE__, function() {
    waybill_add_rewrite_rules();
    flush_rewrite_rules();
});

// Register deactivation hook
register_deactivation_hook(__FILE__, 'flush_rewrite_rules');

// Login form renderer with integrated styling
function render_login_form() {
    // No need to start session here as it's handled by waybill_manager_init
    
    if (isset($_SESSION['waybill_user']) && isset($_SESSION['waybill_logged_in'])) {
        return render_waybill_dashboard();
    }

    $nonce = wp_create_nonce('waybill_login_nonce');
    
    ob_start();
    ?>
    <div class="wrap">
        <style type="text/css">
            #waybill-login-container {
                max-width: 400px;
                margin: 50px auto;
                padding: 20px;
                font-family: Arial, sans-serif;
            }
            #waybill-login-wrapper {
                background: #ffffff;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            }
            #waybill-form h2 {
                text-align: center;
                margin: 0 0 30px 0;
                color: #333333;
                font-size: 24px;
                font-weight: bold;
            }
            #waybill-form form div {
                margin-bottom: 20px;
            }
            #waybill-form input[type="text"],
            #waybill-form input[type="password"] {
                width: 100%;
                padding: 12px;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-size: 14px;
                color: #333333;
                box-sizing: border-box;
                margin-bottom: 15px;
                background-color: #ffffff;
            }
            #waybill-form input[type="text"]:focus,
            #waybill-form input[type="password"]:focus {
                outline: none;
                border-color: #4a90e2;
                box-shadow: 0 0 5px rgba(74, 144, 226, 0.2);
            }
            #waybill-form input[type="submit"] {
                width: 100%;
                padding: 12px;
                background: #4a90e2;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                font-weight: bold;
                margin-top: 10px;
            }
            #waybill-form input[type="submit"]:hover {
                background: #357abd;
            }
            @media (max-width: 480px) {
                #waybill-login-container {
                    margin: 20px auto;
                    padding: 15px;
                }
                #waybill-login-wrapper {
                    padding: 20px;
                }
            }
        </style>
        <div id="waybill-login-container">
            <div id="waybill-login-wrapper">
                <div id="waybill-form">
                    <h2>Login to Waybill System</h2>
                    <form id="waybill-login-form" method="post">
                        <div>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   placeholder="Username" 
                                   required>
                        </div>
                        <div>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Password" 
                                   required>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: flex; align-items: center; font-size: 14px; color: #666;">
                                <input type="checkbox" 
                                       id="remember_me" 
                                       name="remember_me" 
                                       style="margin-right: 8px;">
                                Remember Me
                            </label>
                        </div>
                        <div>
                            <input type="hidden" 
                                   name="waybill_login_nonce" 
                                   value="<?php echo wp_create_nonce('waybill_login_nonce'); ?>">
                            <input type="submit" 
                                   value="Login" 
                                   class="button button-primary">
                        </div>
                    </form>
                    <div id="login-message"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check for stored credentials
        const storedUsername = localStorage.getItem('waybill_username');
        const storedPassword = localStorage.getItem('waybill_password');
        const rememberMe = localStorage.getItem('waybill_remember_me');

        if (storedUsername && storedPassword && rememberMe === 'true') {
            document.getElementById('username').value = storedUsername;
            document.getElementById('password').value = atob(storedPassword); // decode password
            document.getElementById('remember_me').checked = true;
        }

        // Add form submit handler
        document.getElementById('waybill-login-form').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const rememberMe = document.getElementById('remember_me').checked;

            if (rememberMe) {
                localStorage.setItem('waybill_username', username);
                localStorage.setItem('waybill_password', btoa(password)); // encode password
                localStorage.setItem('waybill_remember_me', 'true');
            } else {
                localStorage.removeItem('waybill_username');
                localStorage.removeItem('waybill_password');
                localStorage.setItem('waybill_remember_me', 'false');
            }
        });
    });
    </script>
    <?php
    return ob_get_clean();
}

// Improve AJAX login handler
function handle_waybill_login_ajax() {
    try {
        check_ajax_referer('waybill_login_nonce', 'nonce');
        
        $username = sanitize_text_field($_POST['username']);
        $password = $_POST['password'];
        
        if (empty($username) || empty($password)) {
            wp_send_json_error('Please enter both username and password');
            return;
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'waybill_users';
        
        $user = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE username = %s",
            $username
        ));
        
        if (!$user) {
            wp_send_json_error('Invalid username');
            return;
        }
        
        if (!wp_check_password($password, $user->password)) {
            wp_send_json_error('Invalid password');
            return;
        }
        
        $_SESSION['waybill_user'] = $user;
        $_SESSION['waybill_user_name'] = $user->username;
        $_SESSION['waybill_logged_in'] = true;
        $_SESSION['waybill_is_admin'] = (strpos($user->email, '@outrite.africa') !== false) || $user->is_admin == 1;
        
        wp_send_json_success(array(
            'message' => 'Login successful',
            'redirect' => home_url('/waybill-dashboard/')
        ));
        
    } catch (Exception $e) {
        error_log('Login error: ' . $e->getMessage());
        wp_send_json_error('An error occurred during login');
    }
}

// Add logout handler
add_action('wp_ajax_waybill_logout', 'handle_waybill_logout_ajax');
add_action('wp_ajax_nopriv_waybill_logout', 'handle_waybill_logout_ajax');

function handle_waybill_logout_ajax() {
    try {
        check_ajax_referer('waybill_nonce', 'nonce');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Get the return path from the request
        $return_path = isset($_POST['return_path']) ? sanitize_text_field($_POST['return_path']) : 'waybill-login';
        
        // Store the current site URL
        $site_url = get_site_url();
        
        // Clear all session variables
        $_SESSION = array();
        
        // Destroy the session
        session_destroy();
        
        // Build the redirect URL using the base site URL and return path
        $redirect_url = trailingslashit($site_url) . $return_path;
        
        wp_send_json_success(array(
            'message' => 'Logged out successfully',
            'redirect' => $redirect_url,
            'clearCredentials' => true // Add this flag
        ));
        
    } catch (Exception $e) {
        wp_send_json_error('Logout failed: ' . $e->getMessage());
    }
}

function render_waybill_page($type) {
    if (!session_id()) {
        session_start();
    }

    ob_start();
    get_header();
    
    echo '<div class="waybill-container" style="max-width: 1200px; margin: 40px auto; padding: 20px;">';
    
    if ($type === 'login') {
        if (isset($_SESSION['waybill_user'])) {
            // If logged in, show dashboard instead of redirecting
            echo render_waybill_dashboard();
        } else {
            echo render_login_form();
        }
    } else if ($type === 'dashboard') {
        if (!isset($_SESSION['waybill_user'])) {
            echo render_login_form();
        } else {
            echo render_waybill_dashboard();
        }
    }
    
    echo '</div>';
    
    get_footer();
    
    return ob_get_clean();
}

// Handle template redirect
function waybill_template_include($template) {
    if (is_page('waybill-login')) {
        if (!session_id() && !headers_sent()) {
            session_start();
        }
        
        // If user is already logged in, show dashboard content directly
        if (isset($_SESSION['waybill_user'])) {
            echo render_waybill_dashboard();
            exit;
        }
    }
    return $template;
}
add_action('template_include', 'waybill_template_include');

// Add required scripts and styles
add_action('wp_enqueue_scripts', 'waybill_manager_enqueue_scripts');
add_action('admin_enqueue_scripts', 'waybill_manager_enqueue_scripts');

function waybill_manager_enqueue_scripts() {
    $plugin_url = plugins_url('', __FILE__);
    $google_maps_api_key = get_option('waybill_google_maps_api_key', '');

    // Only enqueue Google Maps scripts if we have an API key
    if (!empty($google_maps_api_key)) {
        // Enqueue the address autocomplete script first
        wp_enqueue_script(
            'waybill-address-autocomplete',
            $plugin_url . '/js/address-autocomplete.js',
            array('jquery'),
            '1.0',
            true
        );

        // Pass the API key to JavaScript
        wp_localize_script(
            'waybill-address-autocomplete',
            'waybillData',
            array(
                'apiKey' => $google_maps_api_key,
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('waybill_nonce')
            )
        );
    } else {
        // Add admin notice if API key is missing
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>Google Maps API key is required for address autocomplete functionality. Please configure it in the <a href="' . admin_url('options-general.php?page=waybill-manager-settings') . '">Waybill Manager Settings</a>.</p></div>';
        });
    }

    // Enqueue jsPDF and its dependencies
    wp_enqueue_script(
        'jspdf',
        'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js',
        array(),
        '2.5.1',
        true
    );

    wp_enqueue_script(
        'jspdf-autotable',
        'https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js',
        array('jspdf'),
        '3.5.28',
        true
    );

    // Enqueue our custom PDF script
    wp_enqueue_script(
        'waybill-pdf',
        $plugin_url . '/js/waybill-pdf.js',
        array('jquery', 'jspdf', 'jspdf-autotable'),
        '1.0',
        true
    );

    // Properly enqueue jQuery first
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-migrate');
    
    // Register and enqueue our custom script with jQuery dependency
    wp_register_script(
        'waybill-manager', 
        $plugin_url . '/js/waybill-custom.js',
        array('jquery', 'jquery-migrate'),
        '1.0',
        true
    );
    wp_enqueue_script('waybill-manager');
    
    // Localize script with our AJAX data
    wp_localize_script(
        'waybill-manager',
        'waybill_ajax',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('waybill_nonce'),
            'plugin_url' => $plugin_url
        )
    );
    
    // Enqueue styles
    wp_enqueue_style(
        'waybill-manager-style', 
        $plugin_url . '/css/style.css',
        array(),
        '1.0'
    );

    // Add the logout script
    wp_enqueue_script(
        'waybill-logout',
        plugins_url('js/waybill-logout.js', __FILE__),
        array('jquery', 'waybill-manager'),
        '1.0',
        true
    );

    // Remove the old Google Places API script enqueue
    remove_action('wp_enqueue_scripts', 'google_maps_api_script');
}

// Remove the duplicate localization
remove_action('wp_enqueue_scripts', function() {
    wp_localize_script('jquery', 'waybill_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('waybill_nonce')
    ));
});

// Add admin menu
add_action('admin_menu', 'waybill_manager_menu');

function waybill_manager_menu() {
    add_menu_page(
        'Waybill Manager',
        'Waybill Manager',
        'manage_options',
        'waybill-manager',
        'render_waybill_admin_page',
        'dashicons-clipboard'
    );

    add_submenu_page(
        'waybill-manager',
        'Manage Users',
        'Manage Users',
        'manage_options',
        'waybill-users',
        'render_user_management_page'
    );
}

// Render admin page
function render_waybill_admin_page() {
    ?>
    <div class="wrap">
        <h1>Waybill Manager Settings</h1>
        <p>Use these shortcodes on your pages:</p>
        <ul>
            <li><code>[waybill_login]</code> - Displays the login form</li>
            <li><code>[capture_waybill_form]</code> - Displays the waybill capture page</li>
        </ul>
    </div>
    <?php
}

// Render User Management Page
function render_user_management_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'waybill_users';

    $message = '';

    // Add email field to users table if it doesn't exist
    $email_column_exists = $wpdb->get_results("SHOW COLUMNS FROM $table_name LIKE 'email'");
    if (empty($email_column_exists)) {
        $wpdb->query("ALTER TABLE $table_name ADD COLUMN email varchar(100)");
    }

    // Handle user creation
    if (isset($_POST['create_user'])) {
        $username = sanitize_user($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $generated_password = wp_generate_password(12, true); // Generate a strong password

        // Check if email is valid
        if (!is_email($email)) {
            $message = '<div class="error"><p>Please enter a valid email address.</p></div>';
        } else {
            // Check if user/email already exists
            $existing_user = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $table_name WHERE username = %s OR email = %s",
                $username,
                $email
            ));

            if ($existing_user) {
                $message = '<div class="error"><p>Username or email already exists.</p></div>';
            } else {
                $wpdb->insert(
                    $table_name,
                    array(
                        'username' => $username,
                        'email' => $email,
                        'password' => wp_hash_password($generated_password)
                    ),
                    array('%s', '%s', '%s')
                );
                $message = '<div class="updated">
                    <p>User created successfully.</p>
                    <p><strong>Generated Password:</strong> <span style="background: #fff; padding: 5px; border: 1px solid #ccc;">' . 
                    esc_html($generated_password) . '</span></p>
                    <p><em>Important: Save this password now! It will not be shown again.</em></p>
                </div>';
            }
        }
    }

    // Handle password reset
    if (isset($_POST['reset_password']) && isset($_POST['user_id'])) {
        $user_id = intval($_POST['user_id']);
        $new_password = wp_generate_password(12, true);
        
        $wpdb->update(
            $table_name,
            array('password' => wp_hash_password($new_password)),
            array('id' => $user_id),
            array('%s'),
            array('%d')
        );

        $message = '<div class="updated">
            <p>Password reset successfully.</p>
            <p><strong>New Password:</strong> <span style="background: #fff; padding: 5px; border: 1px solid #ccc;">' . 
            esc_html($new_password) . '</span></p>
            <p><em>Important: Save this password now! It will not be shown again.</em></p>
        </div>';
    }

    // Handle user deletion
    if (isset($_POST['delete_user']) && isset($_POST['user_id'])) {
        $user_id = intval($_POST['user_id']);
        $wpdb->delete($table_name, array('id' => $user_id), array('%d'));
        $message = '<div class="updated"><p>User deleted successfully.</p></div>';
    }

    ?>
    <div class="wrap">
        <h1>Manage Users</h1>
        
        <?php echo $message; ?>

        <!-- Add User Form -->
        <div class="card" style="max-width: 600px; margin: 20px 0;">
            <h2>Add New User</h2>
            <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <th><label for="username">Username</th>
                        <td><input type="text" name="username" id="username" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th><label for="email">Email</th>
                        <td><input type="email" name="email" id="email" class="regular-text" required></td>
                    </tr>
                </table>
                <p class="description">A secure password will be automatically generated when the user is created.</p>
                <p class="submit">
                    <input type="submit" name="create_user" class="button button-primary" value="Add User">
                </p>
            </form>
        </div>

        <!-- Users List -->
        <h2>Existing Users</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    
<th>Delivery</th>
<th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $users = $wpdb->get_results("SELECT * FROM $table_name");
                foreach ($users as $user): 
                ?>
                <tr>
                    <td><?php echo esc_html($user->username); ?></td>
                    <td><?php echo esc_html($user->email); ?></td>
                    <td>
                        <form method="post" action="" style="display: inline-block;">
                            <input type="hidden" name="user_id" value="<?php echo esc_attr($user->id); ?>">
                            <input type="submit" name="reset_password" class="button button-small" value="Reset Password" 
                                   onclick="return confirm('Are you sure you want to reset this user\'s password?');">
                        </form>
                        <form method="post" action="" style="display: inline-block; margin-left: 5px;">
                            <input type="hidden" name="user_id" value="<?php echo esc_attr($user->id); ?>">
                            <input type="submit" name="delete_user" class="button button-small" value="Delete" 
                                   onclick="return confirm('Are you sure you want to delete this user?');">
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

// Render waybill dashboard
function render_waybill_dashboard() {
    if (!session_id()) {
        session_start();
    }

    // Check if user is logged in
    if (!isset($_SESSION['waybill_user']) || !isset($_SESSION['waybill_logged_in'])) {
        return render_login_form();
    }

    ob_start();
    ?>
    <style>
        .waybill-dashboard {
            margin-top: 80px;
            padding: 20px;
        }
        .waybill-header {
            background: #fff;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }
        .logo img {
            height: 60px;
            width: auto;
        }
        .nav-buttons {
            position: relative;
            display: inline-block;
        }
        .waybill-btn {
            background: #0073aa;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            margin-left: 10px;
        }
        .waybill-btn:hover {
            background: #005177;
        }
        .user-info {
            margin-right: 20px;
            color: #666;
        }
    </style>

    <div class="waybill-dashboard">
        <!-- Header with Logo and Navigation -->
        <div class="waybill-header">
            <div class="logo">
                <img src="<?php echo plugins_url('Outrite-Africa-Logo-2020.jpg', __FILE__); ?>" 
                     alt="Company Logo" 
                     style="max-height: 60px;"
                     onerror="this.style.display='none'">
            </div>
            
            <div style="display: flex; align-items: center;">
                <span class="user-info">Welcome, <?php echo esc_html($_SESSION['waybill_user_name']); ?></span>
                <div class="nav-buttons">
                    <button onclick="showView('dashboard-view')" class="waybill-btn">Dashboard</button>
                    <button onclick="showView('capture-view')" class="waybill-btn">New Waybill</button>
                    <button onclick="showView('view-waybills')" class="waybill-btn">View Waybills</button>
                    <button id="waybill-logout-btn" class="waybill-btn" style="background: #dc3545;">Logout</button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="waybill-content">
            <div id="dashboard-view" style="display: block;">
                <!-- Quick Search Box -->
                <div class="quick-search-container" style="margin: 20px 0; padding: 15px; background: #fff; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="text" 
                            id="dashboard-waybill-search" 
                            placeholder="Search waybill number..." 
                            style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                        <button onclick="clearDashboardSearch()" 
                                class="button" 
                                style="padding: 8px 15px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            Clear
                        </button>
                    </div>
                </div>

                <!-- Add this JavaScript right after the search box -->
                <script>
                document.getElementById('dashboard-waybill-search').addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase().trim();
                    const rows = document.querySelectorAll('#overdue-waybills-table tbody tr');
                    
                    rows.forEach(row => {
                        const waybillNumber = row.querySelector('td:nth-child(3)'); // Adjust column index if needed
                        if (waybillNumber) {
                            const text = waybillNumber.textContent.toLowerCase();
                            row.style.display = text.includes(searchTerm) ? '' : 'none';
                        }
                    });
                });

                function clearDashboardSearch() {
                    const searchInput = document.getElementById('dashboard-waybill-search');
                    searchInput.value = '';
                    // Trigger the input event to show all rows
                    searchInput.dispatchEvent(new Event('input'));
                }
                </script>
                <!-- Overdue Waybills Table -->
                <div class="overdue-waybills-section" style="margin: 20px; padding: 20px; background: white; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 1100px; align-self: center;">
                    <h3 style="margin-bottom: 15px; color: #d32f2f;">Overdue Waybills / Pending POD Upload</h3>
                    <table id="overdue-waybills-table" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                        <thead>
                            <tr>
                           
                            <th style="border: 1px solid #ddd; padding: 12px; background: #f5f5f5;"> Date Created</th>
                                <th style="border: 1px solid #ddd; padding: 12px; background: #f5f5f5;">Action Date</th>
                                <th style="border: 1px solid #ddd; padding: 12px; background: #f5f5f5;">Waybill No</th>
                                <th style="border: 1px solid #ddd; padding: 12px; background: #f5f5f5;">Client Reference</th>
                                <th style="border: 1px solid #ddd; padding: 12px; background: #f5f5f5;">Service Type</th>
                                <th style="border: 1px solid #ddd; padding: 12px; background: #f5f5f5;">Sender Name</th>
                                <th style="border: 1px solid #ddd; padding: 12px; background: #f5f5f5;">Receiver Location</th>
                                <th style="border: 1px solid #ddd; padding: 12px; background: #f5f5f5;">Days Overdue</th>
                                <th style="border: 1px solid #ddd; padding: 12px;  background: #f5f5f5;">Status</th>
                                <th style="border: 1px solid #ddd; padding: 12px;  background: #f5f5f5;">Invoice No.</th>
                                <th style="border: 1px solid #ddd; padding: 12px; background: #f5f5f5;">Upload POD</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include_overdue_waybills_table(); ?>
                        </tbody>
                    </table>
                    
                    <!-- Refresh button for overdue waybills -->
                    <div style="text-align: right; margin: 20px;">
                        <button onclick="refreshOverdueWaybills()" 
                                style="padding: 8px 16px; background-color: #2196F3; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            Refresh Table
                        </button>
                    </div>
                </div>
            </div>
            <div id="capture-view" style="display: none;">
                <?php echo do_shortcode('[capture_waybill_form]'); ?>
            </div>
            <div id="view-waybills" style="display: none;">
                <?php echo do_shortcode('[view_waybills]'); ?>
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Initial load of overdue waybills
        refreshOverdueWaybills();
        
        // Refresh every 5 minutes
        setInterval(refreshOverdueWaybills, 300000);
    });

    function showView(viewId) {
        // Hide all views
        document.getElementById('dashboard-view').style.display = 'none';
        document.getElementById('capture-view').style.display = 'none';
        document.getElementById('view-waybills').style.display = 'none';
        
        // Show selected view
        document.getElementById(viewId).style.display = 'block';

        // Only refresh overdue waybills table when showing dashboard
        if (viewId === 'dashboard-view') {
            refreshOverdueWaybills();
        }
    }

    // Add refresh function for overdue waybills
    function refreshOverdueWaybills() {
        jQuery.ajax({
            url: waybill_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'refresh_overdue_waybills',
                nonce: waybill_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    jQuery('#overdue-waybills-table tbody').html(response.data);
                } else {
                    alert('Error refreshing table');
                }
            },
            error: function() {
                alert('Error connecting to server');
            }
        });
    }

    </script>
    <!-- Add this modal markup right after the overdue waybills table -->
    <!-- Waybill Details Modal -->
    <div id="dashboardWaybillModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); overflow: auto;">
        <div class="modal-content" style="background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 90%; max-width: 1000px; max-height: 80vh; position: relative; overflow: auto;">
            <span class="close" style="position: sticky; top: 0; float: right; font-size: 28px; font-weight: bold; cursor: pointer; background: white; padding: 0 10px; z-index: 1;">&times;</span>
            <div id="dashboardWaybillDetails" style="overflow: auto;"></div>
        </div>
    </div>

    <script>
    function viewWaybillDetails(waybillNumber) {
        var modal = document.getElementById('dashboardWaybillModal');
        var span = modal.getElementsByClassName('close')[0];
        var detailsDiv = document.getElementById('dashboardWaybillDetails');

        // Show loading state
        detailsDiv.innerHTML = 'Loading...';
        modal.style.display = 'block';

        // Fetch waybill details via AJAX
        jQuery.ajax({
            url: waybill_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_waybill_details',
                waybill_number: waybillNumber,
                nonce: waybill_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    detailsDiv.innerHTML = response.data;
                    initializePodUpload();
                } else {
                    detailsDiv.innerHTML = 'Error loading waybill details.';
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                detailsDiv.innerHTML = 'Error loading waybill details.';
            }
        });

        // Close modal when clicking X
        span.onclick = function() {
            modal.style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    }

    function initializePodUpload() {
        jQuery('.pod-upload').on('change', function(e) {
            e.preventDefault(); // Prevent any default behavior
            
            const file = e.target.files[0];
            const form = jQuery(this).closest('form');
            const waybillNumber = form.find('input[name="waybill_number"]').val();
            const uploadStatus = form.find('.upload-status');
            const formData = new FormData();
            
            // Prevent form from submitting
            form.on('submit', function(e) {
                e.preventDefault();
                return false;
            });
            
            formData.append('action', 'upload_pod_image');
            formData.append('nonce', form.find('input[name="nonce"]').val());
            formData.append('waybill_number', waybillNumber);
            formData.append('pod_image', file);
            
            uploadStatus.html('Uploading...');
            
            jQuery.ajax({
                url: waybill_ajax.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        uploadStatus.html('Upload successful!');
                        // Close the modal after successful upload
                        setTimeout(function() {
                            const modal = document.getElementById('waybillModal');
                            if (modal) {
                                modal.style.display = 'none';
                            }
                            // Clear the file input
                            jQuery(e.target).val('');
                        }, 1500);
                    } else {
                        uploadStatus.html('Upload failed: ' + response.data);
                    }
                },
                error: function() {
                    uploadStatus.html('Upload failed. Please try again.');
                }
            });
        });
    }
    </script>
    <?php
    return ob_get_clean();
}



// Function to generate waybill number
function generate_waybill_number() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'waybills';
    
    // Get the highest waybill number from the database
    $query = "SELECT MAX(CAST(SUBSTRING(waybill_number, 3) AS UNSIGNED)) FROM $table_name WHERE waybill_number REGEXP '^OA[0-9]{6}$'";
    $last_number = $wpdb->get_var($query);
    
    // If no existing waybill numbers, start from 1
    // Otherwise, increment by 1
    $next_number = ($last_number === null) ? 1 : intval($last_number) + 1;
    
    // Format the number with leading zeros (6 digits)
    $formatted_number = 'OA' . sprintf('%06d', $next_number);
    
    // Verify uniqueness to be safe
    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE waybill_number = %s",
        $formatted_number
    ));
    
    if ($exists) {
        // Log this occurrence as it should not happen with sequential numbers
        error_log("Warning: Generated waybill number {$formatted_number} already exists despite using sequential generation.");
        // Recursively try the next number
        return generate_waybill_number();
    }
    
    return $formatted_number;
}

// Render capture waybill form
function render_capture_waybill_form() {
    if (!isset($_SESSION['waybill_user'])) {
        return 'Please log in to access this feature.';
    }

    $is_admin = isset($_SESSION['waybill_is_admin']) && $_SESSION['waybill_is_admin'];
    $logged_in_username = $_SESSION['waybill_user_name'];

    ob_start();
    ?>
    <!-- Add hidden fields before the form starts -->
    <input type="hidden" id="sender_country_state_city" name="sender_country_state_city" value="">
    <input type="hidden" id="receiver_country_state_city" name="receiver_country_state_city" value="">
    
    <!-- Rest of the form HTML -->
    <form id="waybill-form" method="post">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 20px; border-bottom: 2px solid #000;">
            <!-- Logo and Company Information Section -->
            <div style="flex: 1;">
                <img src="<?php echo plugins_url('Outrite-Africa-Logo-2020.jpg', __FILE__); ?>" 
                     alt="Company Logo" 
                     style="height: 70px; display: block; margin-bottom: 10px;">
                <div style="font-size: 12px; line-height: 1.5;">
                    <div>Outrite Africa (Pty) Ltd</div>
                    <div>Co.Reg: 2017/470572/07 VAT: 4010281444</div>
                    <div>Unit 6, Coventry Park</div>
                    <div>657 Old Pretoria Main Road</div>
                    <div>Halfway House, Midrand</div>
                    <div><span style="color: blue;">South Africa</div>
                    <div style="margin-top: 10px;">
                        Email: <a href="mailto:sales@outrite.africa">sales@outrite.africa</a><br>
                        Phone: +27 84 500 4645<br>
                        Website: <a href="http://www.outrite.africa" target="_blank">www.outrite.africa</a><br>
                        Switchboard: +27 10 200 9950<br>
                        Date: <?php echo date('Y-m-d'); ?>
                    </div>
                </div>
            </div>

            <!-- Waybill Details Section -->
            <div style="flex: 1; text-align: center;">

                <!-- Allocated To field -->
                <div style="margin-left: 10px; text-align: right;">
                    <label for="allocated_to" style="display: block; font-size: 15px; margin-bottom: 5px;">Allocated To:</label>
                    <?php if (isset($_SESSION['waybill_is_admin']) && $_SESSION['waybill_is_admin']): ?>
                        <select id="allocated_to" name="allocated_to" style="padding: 5px; border: 1px solid #ccc; width: 150px;" required>
                            <option value="" selected disabled>Allocated to</option>  
                            <option value="Areo Africa">Areo Africa</option>
                            <option value="Contra">Contra</option>
                            <option value="Emlink">Emlink</option>
                            <option value="Elphas">Elphas</option>
                            <option value="Eveshwin">Eveshwin</option>
                            <option value="FP Du Toit">FP Du Toit</option>
                            <option value="ITT">ITT</option>
                            <option value="Katlego">Katlego</option>
                            <option value="Moffat">Moffat</option>
                            <option value="Prime Space">Prime Space</option>
                            <option value="RWS">RWS</option>
                            <option value="Sbusiso">Sbusiso</option>
                            <option value="Sunil">Sunil</option>
                            <option value="Triton">Triton</option>
                            <option value="Thulani">Thulani</option>
                            <option value="Tashlin">Tashlin</option>
                            <option value="Tshego">Tshego</option>
                            <option value="Tankiso">Tankiso</option>
                            <option value="Treydan">Treydan</option>
                            <option value="Walter">Walter</option>
                        </select>
                    <?php else: ?>
                        <input type="text" 
                               id="allocated_to" 
                               name="allocated_to" 
                               value="Outrite Africa" 
                               readonly 
                               style="padding: 5px; border: 1px solid #ccc; width: 150px; background-color: #f0f0f0;" />
                    <?php endif; ?>
                </div>

                <!-- Movement Type -->
                <div style="margin-left: 10px; text-align: right; margin-bottom: 10px;">
                    <div style="margin-bottom: 5px;">Movement Type:</div>
                    <div style="display: inline-flex; gap: 15px; justify-content: flex-end;">
                        <label style="display: flex; align-items: center;">
                            <input type="radio" name="movement_type" value="Import" required style="margin: 0 5px 0 0;">
                            Import
                        </label>
                        <label style="display: flex; align-items: center;">
                            <input type="radio" name="movement_type" value="Export" style="margin: 0 5px 0 0;">
                            Export
                        </label>
                        <label style="display: flex; align-items: center;">
                            <input type="radio" name="movement_type" value="Local" style="margin: 0 5px 0 0;">
                            Local
                        </label>
                    </div>
                </div>
                <div style="margin-left: 10px; text-align: right;">
                    <label for="waybill_number">Waybill No:</label>
                    <input type="text" id="waybill_number" name="waybill_number" 
                           readonly 
                           value="<?php echo generate_waybill_number(); ?>" 
                           style="font-size: 1.2em; text-align: center; border: 1px solid #ccc; background-color: #f5f5f5; width: 200px; margin-bottom: 10px;" />
                </div>
                <div style="margin-left: 10px; text-align: right;">
                    <label for="client_reference">Client Reference:</label>
                    <input type="text" id="client_reference" name="client_reference" 
                           style="width: 200px; padding: 5px; border: 1px solid #ccc; margin-bottom: 10px;" required>
                </div>
                <div style="margin-left: 10px; text-align: right;">
                    <label for="action_date">Action Date:</label>
                    <input type="date" id="action_date" name="action_date" 
                           style="width: 200px; padding: 5px; border: 1px solid #ccc;" required>
                </div>
                <div style="margin-left: 10px; text-align: right;">
                    <label for="service_type">Type of Service:</label>
                    <select name="service_type" id="service_type" required>
                        <option value="">Select Service</option>
                        <option value="Airfreight">Airfreight</option>
                        <option value="Overnight">Overnight</option>
                        <option value="Economy">Economy</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Road Freight - CB">Road Freight - CB</option>
                        <option value="Road Freight - local">Road Freight - local</option>
                        <option value="Sea Freight">Sea Freight</option>
                        <option value="Budget">Budget</option>
                    </select>
                </div>
            </div>
        </div>


        <hr />

        <!-- Sender and Receiver Details Section -->
        <div style="display: flex; justify-content: space-between;">
 <!-- Sender Details -->
 <div style="flex: 1; margin-right: 10px;">
        <h4>Sender</h4>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <label for="sender_name" style="flex: 1;">Sender Name:</label>
            <?php if (!$is_admin): ?>
                <input type="text" id="sender_name" name="sender_name" 
                       value="<?php echo esc_attr($logged_in_username); ?>" 
                       readonly 
                       style="flex: 2; padding: 5px; background-color: #f0f0f0;" />
            <?php else: ?>
                <input type="text" id="sender_name" name="sender_name" 
                       required style="flex: 2; padding: 5px;" />
            <?php endif; ?>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <label for="sender_customer_name" style="flex: 1;">Customer Name:</label>
            <input type="text" id="sender_customer_name" name="sender_customer_name" required style="flex: 2; padding: 5px;" />
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <label for="sender_address_1" style="flex: 1;">Street Address:</label>
            <input type="text" id="sender_address_1" name="sender_address_1" required style="flex: 2; padding: 5px;" />
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <label for="sender_telephone" style="flex: 1;">Telephone:</label>
            <input type="text" id="sender_telephone" name="sender_telephone" required style="flex: 2; padding: 5px;" />
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <label for="sender_country" style="flex: 1;">Country:</label>
            <input type="text" id="sender_country" name="sender_country" required style="flex: 2; padding: 5px;" />
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <label for="sender_state" style="flex: 1;">State/Province:</label>
            <input type="text" id="sender_state" name="sender_state" required style="flex: 2; padding: 5px;" />
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <label for="sender_city" style="flex: 1;">City:</label>
            <input type="text" id="sender_city" name="sender_city" required style="flex: 2; padding: 5px;" />
        </div>
        <input type="hidden" class="location-field" data-target="sender_country_state_city"
                   data-fields="sender_country,sender_state,sender_city">
</div>



    <!-- Receiver Details -->
    <div style="flex: 1; margin-left: 10px;">
        <h4>Receiver</h4>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <label for="receiver_name" style="flex: 1;">Receiver Name:</label>
            <input type="text" id="receiver_name" name="receiver_name" required style="flex: 2; padding: 5px;" />
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <label for="receiver_customer_name" style="flex: 1;">Customer Name:</label>
            <input type="text" id="receiver_customer_name" name="receiver_customer_name" required style="flex: 2; padding: 5px;" />
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <label for="receiver_address_1" style="flex: 1;">Street Address:</label>
            <input type="text" id="receiver_address_1" name="receiver_address_1" required style="flex: 2; padding: 5px;" />
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <label for="receiver_telephone" style="flex: 1;">Telephone:</label>
            <input type="text" id="receiver_telephone" name="receiver_telephone" required style="flex: 2; padding: 5px;" />
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <label for="receiver_country" style="flex: 1;">Country:</label>
            <input type="text" id="receiver_country" name="receiver_country" required style="flex: 2; padding: 5px;" />
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <label for="receiver_state" style="flex: 1;">State/Province:</label>
            <input type="text" id="receiver_state" name="receiver_state" required style="flex: 2; padding: 5px;" />
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <label for="receiver_city" style="flex: 1;">City:</label>
            <input type="text" id="receiver_city" name="receiver_city" required style="flex: 2; padding: 5px;" />
        </div>
        <input type="hidden" class="location-field" data-target="receiver_country_state_city"
                   data-fields="receiver_country,receiver_state,receiver_city">
    </div>

   
    
</div>


<!-- Black Line Divider -->
<div style="height: 2px; background-color: black; margin: 20px 0;"></div>

        <hr />
        <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 20px;">
            
           
            
        </div>

        <!-- Goods Details Table -->
        <div class="goods-details-section" style="margin: 20px 0;">
            <table id="pieces-table" class="goods-table" style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #ddd; padding: 8px;">No. of Pallets</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">No. of Cartons</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Description</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Dimensions (cm)</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Kg's</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Select</th>
                    </tr>
                </thead>
                <tbody id="goodsTableBody">
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            <input type="number" class="pieces" style="width: 100px;" min="1" name="pieces[]">
                        </td>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            <input type="number" class="cartons" style="width: 100px;" min="1" name="cartons[]">
                        </td>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            <input type="text" id="description_of_goods" class="description" style="width: 200px;" name="description[]">
                        </td>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            <div style="display: flex; gap: 5px;">
                                <input type="number" class="length" placeholder="L" style="width: 60px;" min="0" step="0.01" name="dimensions[]">
                                <input type="number" class="width" placeholder="W" style="width: 60px;" min="0" step="0.01" name="dimensions[]">
                                <input type="number" class="height" placeholder="H" style="width: 60px;" min="0" step="0.01" name="dimensions[]">
                            </div>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            <input type="number" class="weight" style="width: 80px;" min="0" step="0.01" name="kg[]">
                        </td>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            <input type="checkbox" class="row-select">
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <div style="margin: 10px 0;">
                <button type="button" id="addRow" class="button">Add Row</button>
                <button type="button" id="deleteRows" class="button" disabled style="margin-left: 10px; background-color: #dc3545; color: white;">Delete Selected</button>
            </div>

            <!-- Totals Section -->
            <div class="totals-section" style="margin-top: 10px;">
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                    <tr>
                        <th style="border: 1px solid #ddd; padding: 8px; background-color: #f5f5f5;">Total Pallets</th>
                        <th style="border: 1px solid #ddd; padding: 8px; background-color: #f5f5f5;">Total Cartons</th>
                        <th style="border: 1px solid #ddd; padding: 8px; background-color: #f5f5f5;">Volume Mass (cm³)</th>
                        <th style="border: 1px solid #ddd; padding: 8px; background-color: #f5f5f5;">Actual Mass (kg)</th>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;"><span id="totalPieces">0</span></td>
                        <td style="border: 1px solid #ddd; padding: 8px;"><span id="totalCartins">0</span></td>
                        <td style="border: 1px solid #ddd; padding: 8px;"><span id="volumeMass">0</span> cm³</td>
                        <td style="border: 1px solid #ddd; padding: 8px;"><span id="actualMass">0</span> kg</td>
                    </tr>
                </table>
                <!-- Hidden inputs for calculated values -->
                <input type="hidden" name="total_pieces" id="hiddenTotalPieces" value="0">
                <input type="hidden" name="total_cartins" id="hiddenTotalCartins" value="0">
                <input type="hidden" name="volume_mass" id="hiddenVolumeMass" value="0">
                <input type="hidden" name="actual_mass" id="hiddenActualMass" value="0">
            </div>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="special_instructions">Special Instructions:</label>
            <textarea id="special_instructions" name="special_instructions" style="width: 100%; padding: 5px;"></textarea>
        </div>

<!-- New Rows Next to Each Other -->
<div style="display: flex; justify-content: space-between; margin-top: 10px;">
    <div style="flex: 1; padding-right: 10px;">
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
            <tr>
                <td style="border: 1px solid #000; padding: 5px;">Received by Outrite:</td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="text" placeholder="Signature" name="received_by_outright" style="width: 100%;">
                </td>
                <td style="border: 1px solid #000; padding: 5px;">Print Name:</td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="text" name="received_by_outright_time" style="width: 100%;">
                </td>
                <td style="border: 1px solid #000; padding: 5px;">Date:</td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="date" name="received_by_outright_date" style="width: 100%;">
                </td>
                
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 5px;">Receiver 3PL:</td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="text" placeholder="Signature" name="receiver_3pl" style="width: 100%;">
                </td>
                <td style="border: 1px solid #000; padding: 5px;">Print Name:</td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="text" name="receiver_3pl_time" style="width: 100%;">
                </td>
                <td style="border: 1px solid #000; padding: 5px;">Date:</td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="date" name="receiver_3pl_date" style="width: 100%;">
                </td>
                
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 5px;">Final Receiver:</td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="text" placeholder="Signature" name="final_receiver" style="width: 100%;">
                </td>
                <td style="border: 1px solid #000; padding: 5px;">Print name:</td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="text" name="final_receiver_time" style="width: 100%;">
                </td>
                <td style="border: 1px solid #000; padding: 5px;">Date:</td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="date" name="final_receiver_date" style="width: 100%;">
                </td>
                
            </tr>
        </table>
    </div>
</div>

<!-- Signature Row -->
<div style="display: flex; justify-content: space-between; margin-top: 10px;">
    <div style="flex: 1; padding-right: 10px;">
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
            <tr>
                <td style="border: 1px solid #000; padding: 5px;">Sender's Signature:</td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="text" name="sender_signature" style="width: 100%;">
                </td>
                <td style="border: 1px solid #000; padding: 5px;">Print Name:</td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="text" name="sender_print_name" style="width: 100%;">
                </td>
                <td style="border: 1px solid #000; padding: 5px;">Date:</td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="date" name="sender_sign_date" style="width: 100%;">
                </td>
                <td style="border: 1px solid #000; padding: 5px;">Time:</td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="time" name="sender_sign_time" style="width: 100%;">
                </td>
            </tr>
        </table>
    </div>
</div>
<!-- Hidden Fields for Totals -->
<input type="hidden" id="total_pieces" name="total_pieces" value="0">
<input type="hidden" id="volume_mass" name="volume_mass" value="0">
<input type="hidden" id="actual_mass" name="actual_mass" value="0">


<div style="margin-top: 20px; display: flex; justify-content: flex-start; gap: 10px;">
    <button type="button" id="download-pdf" class="button" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Download PDF</button>
    <input type="submit" name="submit_waybill" value="Save Waybill" class="button" style="padding: 10px 20px; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;" />
    <button type="button" id="fresh-waybill-btn" class="button" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; display: none;">Refresh</button>
</div>

<div id="save-success-message" style="display: none; margin-top: 15px; padding: 10px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px;">
    Waybill saved successfully! You can now download the PDF if needed.
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('waybill-form');
    const submitBtn = form.querySelector('input[name="submit_waybill"]');
    const downloadBtn = document.getElementById('download-pdf');
    const freshBtn = document.getElementById('fresh-waybill-btn');
    const successMsg = document.getElementById('save-success-message');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show saving state
        submitBtn.value = 'Saving...';
        submitBtn.disabled = true;
        
        const formData = new FormData(form);
        formData.append('action', 'save_waybill');
        formData.append('nonce', waybill_ajax.nonce);

        fetch(waybill_ajax.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success state
                submitBtn.value = 'Saved!';
                downloadBtn.disabled = false;
                freshBtn.style.display = 'inline-block';
                successMsg.style.display = 'block';
                
                // Enable PDF download
                downloadBtn.classList.remove('disabled');
                
                // Show fresh waybill button
                freshBtn.style.display = 'inline-block';
            } else {
                // Show error state
                submitBtn.value = 'Save Failed';
                alert('Error saving waybill: ' + (data.message || 'Unknown error'));
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            submitBtn.value = 'Save Failed';
            alert('Error saving waybill');
            submitBtn.disabled = false;
        })
        .finally(() => {
            // Reset button text after 2 seconds
            setTimeout(() => {
                submitBtn.value = 'Save Waybill';
            }, 2000);
        });
    });
    
    // Add fresh waybill button handler
    if (freshBtn) {
        freshBtn.addEventListener('click', function() {
            location.reload();
        });
    }
});
</script>

    <script>
  document.addEventListener('DOMContentLoaded', function() {
            doc.text('Date:', 120, y += 8);
            doc.text(new Date().toLocaleDateString(), 160, y);
            
            doc.text('Type of Service:', 120, y += 8);
            doc.text(document.getElementById('service_type').value || '-', 160, y);

            // Continue with the rest of the PDF generation
            // Black divider line
            y = 80;
            doc.setDrawColor(0);
            doc.setLineWidth(0.5);
            doc.line(margin, y, pageWidth - margin, y);

            // Sender/Receiver section - Adjusted positioning
            y += 5;
            doc.setFontSize(8);
            
            // Sender details (Left side)
            doc.setFont(undefined, 'bold'); // Set font to bold
            doc.text('SENDER', margin, y);
            doc.setFont(undefined, 'normal'); // Reset font to normal
            doc.text('Sender Name:', margin, y + 5);
            doc.text(document.getElementById('sender_name').value || '', margin + 40, y + 5);
            doc.text('Customer Name:', margin, y + 10);
            doc.text(document.getElementById('sender_customer_name').value || '', margin + 40, y + 10);
            doc.text('Street Address:', margin, y + 15);
            doc.text(document.getElementById('sender_address_1').value || '', margin + 40, y + 15);
            doc.text('Tel:', margin, y + 20);
            doc.text(document.getElementById('sender_telephone').value || '', margin + 40, y + 20);
            doc.text('Location:', margin, y + 25);
            doc.text([
                document.getElementById('sender_city').value,
                document.getElementById('sender_state').value,
                document.getElementById('sender_country').value
            ].filter(Boolean).join(', '), margin + 40, y + 25);

            // Receiver details (Right side) - Using same y-coordinate as sender
            const rightMargin = pageWidth/2;
            doc.setFont(undefined, 'bold'); // Set font to bold
            doc.text('RECEIVER', rightMargin, y);
            doc.setFont(undefined, 'normal'); // Reset font to normal
            doc.text('Receiver Name:', rightMargin, y + 5);
            doc.text(document.getElementById('receiver_name').value || '', rightMargin + 40, y + 5);
            doc.text('Customer Name:', rightMargin, y + 10);
            doc.text(document.getElementById('receiver_customer_name').value || '', rightMargin + 40, y + 10);
            doc.text('Street Address:', rightMargin, y + 15);
            doc.text(document.getElementById('receiver_address_1').value || '', rightMargin + 40, y + 15);
            doc.text('Tel:', rightMargin, y + 20);
            doc.text(document.getElementById('receiver_telephone').value || '', rightMargin + 40, y + 20);
            doc.text('Location:', rightMargin, y + 25);
            doc.text([
                document.getElementById('receiver_city').value,
                document.getElementById('receiver_state').value,
                document.getElementById('receiver_country').value
            ].filter(Boolean).join(', '), rightMargin + 40, y + 25);

            // Update y position for goods table
            y += 32;

            // Continue with goods table
            doc.autoTable({
                startY: y,
                head: [['No. of Pallets', 'Description of Goods', 'Dimensions (cm)', 'Kg\'s']],
                body: Array.from(document.querySelectorAll('#pieces-table tbody tr')).map(row => [
                    row.querySelector('.pieces').value || '',
                    row.querySelector('.description').value || '',
                    `${row.querySelector('.length').value || ''}x${row.querySelector('.width').value || ''}x${row.querySelector('.height').value || ''}`,
                    row.querySelector('.weight').value || ''
                ]),
                margin: { left: margin, right: margin },
                theme: 'grid'
            });

            // Totals Table
            y = doc.lastAutoTable.finalY + 5;
            doc.autoTable({
                startY: y,
                head: [['Total Pallets', 'Volume Mass (cm³)', 'Actual Mass (kg)']],
                body: [[
                    document.getElementById('totalPieces').textContent,
                    document.getElementById('volumeMass').textContent,
                    document.getElementById('actualMass').textContent
                ]],
                margin: { left: margin, right: margin },
                theme: 'grid'
            });

            // Special Instructions
            y = doc.lastAutoTable.finalY + 10;
            doc.setFontSize(10);
            doc.text('Special Instructions:', margin, y);
            doc.setFontSize(9);
            doc.text(document.getElementById('special_instructions').value || '-', margin, y + 5);

            // Signatures Table
            y = doc.lastAutoTable.finalY + 20;
            doc.autoTable({
                startY: y,
                head: [[ '','Signature', 'Print Name', 'Date']],
                body: [
                    ['RECEIVED BY OUTRITE', '', '', ''],
                    ['RECEIVER-3PL', '', '', ''],
                    ['FINAL RECEIVER', '', '', '']
                ],
                margin: { left: margin, right: margin },
                theme: 'grid'
            });

            // Sender's signature at bottom
            const bottomMargin = 20;
            y = doc.internal.pageSize.height - bottomMargin;
            doc.autoTable({
                startY: y - 15,
                head: [['Sender\'s Signature', 'Print Name', 'Date', 'Time']],
                body: [['', '', '', '']],
                margin: { left: margin, right: margin },
                theme: 'grid'
            });

            // Save the PDF
            doc.save(document.getElementById('waybill_number').value + '.pdf');

        } catch (error) {
            console.error('PDF Generation Error:', error);
            alert('Error generating PDF. Please try again.');
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to update location fields
    function updateLocationFields() {
        document.querySelectorAll('.location-field').forEach(field => {
            const targetId = field.dataset.target;
            const fieldIds = field.dataset.fields.split(',');
            const values = fieldIds.map(id => document.getElementById(id).value.trim());
            document.getElementById(targetId).value = values.join(', ');
        });
    }

    // Add event listeners to all location input fields
    ['sender', 'receiver'].forEach(prefix => {
        ['country', 'state', 'city'].forEach(field => {
            const input = document.getElementById(`${prefix}_${field}`);
            if (input) {
                input.addEventListener('change', updateLocationFields);
                input.addEventListener('blur', updateLocationFields);
            }
        });
    });

    // Function to aggregate description of goods
    function updateDescriptionOfGoods() {
        const descriptions = [];
        document.querySelectorAll('#goodsTableBody tr').forEach(row => {
            const description = row.querySelector('.description')?.value?.trim();
            if (description) {
                descriptions.push(description);
            }
        });
        document.getElementById('description_of_goods').value = descriptions.trim();
    }

    // Add event listeners to goods table
    const goodsTable = document.getElementById('goodsTableBody');
    if (goodsTable) {
        goodsTable.addEventListener('change', function(e) {
            if (e.target.classList.contains('description')) {
                updateDescriptionOfGoods();
            }
        });
    }

    // Add form submission handler
    const form = document.getElementById('waybill-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            updateLocationFields();
            updateDescriptionOfGoods();
            
            // Get the form data
            const formData = new FormData(form);
            formData.append('action', 'save_waybill');
            formData.append('nonce', waybill_ajax.nonce);

            // Submit the form via AJAX
            
        });
    }
});
</script>

    </form>
   

   
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const addRowBtn = document.getElementById('addRow');
        const deleteRowsBtn = document.getElementById('deleteRows');
        const tbody = document.getElementById('goodsTableBody');

        // Function to calculate totals
        function calculateTotals() {
            let totalPieces = 0;
            let totalCartins = 0;
            let totalVolumeMass = 0;
            let totalActualMass = 0;

            document.querySelectorAll('#pieces-table tbody tr').forEach(row => {
                // Parse values as floats to handle decimals
                const pieces = parseFloat(row.querySelector('.pieces').value) || 0;
                const cartons = parseFloat(row.querySelector('.cartons').value) || 0;
                const length = parseFloat(row.querySelector('.length').value) || 0;
                const width = parseFloat(row.querySelector('.width').value) || 0;
                const height = parseFloat(row.querySelector('.height').value) || 0;
                const kg = parseFloat(row.querySelector('.weight').value) || 0;

                totalPieces += pieces;
                totalCartins += cartons;
                
                // Calculate volume mass with decimal precision
                const rowVolumeMass = (length && width && height) ? 
                    ((length * width * height) / 5000) * pieces : 0;
                
                totalVolumeMass += rowVolumeMass;
                totalActualMass += kg * pieces;
            });

            // Update displays with proper decimal formatting
            document.getElementById('totalPieces').textContent = totalPieces;
            document.getElementById('totalCartins').textContent = totalCartins;
            document.getElementById('volumeMass').textContent = totalVolumeMass.toFixed(2);
            document.getElementById('actualMass').textContent = totalActualMass.toFixed(2);
            
            // Update hidden fields with proper decimal values
            document.getElementById('hiddenTotalPieces').value = totalPieces;
            document.getElementById('hiddenTotalCartins').value = totalCartins;
            document.getElementById('hiddenVolumeMass').value = totalVolumeMass.toFixed(2);
            document.getElementById('hiddenActualMass').value = totalActualMass.toFixed(2);
        }

        // Add new row
        addRowBtn.addEventListener('click', () => {
            const newRow = tbody.rows[0].cloneNode(true);
            // Clear input values
            newRow.querySelectorAll('input').forEach(input => {
                input.value = '';
                input.checked = false;
            });
            tbody.appendChild(newRow);
            attachRowListeners(newRow);
        });

        // Delete selected rows
        deleteRowsBtn.addEventListener('click', () => {
            const rows = tbody.querySelectorAll('tr');
            if (rows.length > 1) {
                rows.forEach(row => {
                    if (row.querySelector('.row-select').checked) {
                        row.remove();
                    }
                });
            }
            updateDeleteButton();
            calculateTotals();
        });

        // Function to update delete button state
        function updateDeleteButton() {
            const hasChecked = [...document.querySelectorAll('.row-select')].some(cb => cb.checked);
            deleteRowsBtn.disabled = !hasChecked;
        }

        // Function to attach listeners to row inputs
        function attachRowListeners(row) {
            row.querySelectorAll('input').forEach(input => {
                if (input.type === 'checkbox') {
                    input.addEventListener('change', updateDeleteButton);
                } else {
                    input.addEventListener('input', calculateTotals);
                }
            });
        }

        // Attach listeners to initial row
        attachRowListeners(tbody.rows[0]);
    });
    </script>
    <?php
    return ob_get_clean();
}

// Remove the regular form submission handler
// DELETE or COMMENT OUT the following function:
// function handle_waybill_form_submission() { ... }
// remove_action('init', 'handle_waybill_form_submission');

// Add AJAX handlers for waybill filtering
add_action('wp_ajax_fetch_waybills', 'handle_fetch_waybills');
add_action('wp_ajax_nopriv_fetch_waybills', 'handle_fetch_waybills');

function handle_fetch_waybills() {
    check_ajax_referer('waybill_nonce', 'nonce');

    global $wpdb;
    $table_name = $wpdb->prefix . 'waybills';

    // Get filter parameters
    $from_date = isset($_POST['from_date']) ? sanitize_text_field($_POST['from_date']) : '';
    $to_date = isset($_POST['to_date']) ? sanitize_text_field($_POST['to_date']) : '';
    $service = isset($_POST['service']) ? sanitize_text_field($_POST['service']) : '';
    $allocated_to = isset($_POST['allocated_to']) ? sanitize_text_field($_POST['allocated_to']) : '';
    $movement_type = isset($_POST['movement_type']) ? sanitize_text_field($_POST['movement_type']) : '';

    // Build query
    $query = "SELECT * FROM $table_name WHERE 1=1";
    $params = array();

    if (!empty($from_date)) {
        $query .= " AND Date_Created >= %s";
        $params[] = $from_date;
    }
    if (!empty($to_date)) {
        $query .= " AND Date_Created <= %s";
        $params[] = $to_date;
    }
    if (!empty($service)) {
        $query .= " AND Type_Of_Service = %s";
        $params[] = $service;
    }
    if (!empty($allocated_to)) {
        $query .= " AND Allocated_to = %s";
        $params[] = $allocated_to;
    }
    if (!empty($movement_type)) {
        $query .= " AND Movement_Type = %s";
        $params[] = $movement_type;
    }

    // Add ORDER BY clause
    $query .= " ORDER BY Date_Created DESC, id DESC";

    // Prepare and execute query
    if (!empty($params)) {
        error_log('Preparing query with parameters: ' . print_r($params, true));
        $query = $wpdb->prepare($query, $params);
        error_log('Prepared query: ' . $query);
    }
    
    error_log('Executing query: ' . $query);
    $results = $wpdb->get_results($query);
    error_log('Query results: ' . print_r($results, true));
    
    wp_send_json_success($results);
}

// Add AJAX handler for image upload
add_action('wp_ajax_upload_pod_image', 'handle_pod_image_upload');
add_action('wp_ajax_nopriv_upload_pod_image', 'handle_pod_image_upload');

function handle_pod_image_upload() {
    global $wpdb;
    $wpdb->show_errors();
    error_log('POD upload started - ' . date('Y-m-d H:i:s'));
    
    try {
        // Verify nonce
        if (!isset($_POST['nonce'])) {
            error_log('Nonce not provided');
            wp_send_json_error('Security token missing');
            return;
        }

        if (!wp_verify_nonce($_POST['nonce'], 'upload_pod_image')) {
            error_log('Nonce verification failed. Provided nonce: ' . $_POST['nonce']);
            wp_send_json_error('Invalid security token');
            return;
        }

        // Verify waybill number exists and is valid
        if (!isset($_POST['waybill_number']) || empty($_POST['waybill_number'])) {
            error_log('No waybill number provided');
            wp_send_json_error('Waybill number is required');
            return;
        }

        $waybill_number = sanitize_text_field($_POST['waybill_number']);
        error_log('Processing upload for waybill: ' . $waybill_number);
        
        // Validate waybill number format
        if (!preg_match('/^(WB-\d{8}-\d{4}|OA\d{6})$/', $waybill_number)) {
            error_log('Invalid waybill number format: ' . $waybill_number);
            wp_send_json_error('Invalid waybill number format');
            return;
        }

        // Verify waybill exists in database
        $table_name = $wpdb->prefix . 'waybills';
        error_log('Checking waybill in database: ' . $waybill_number);
        
        $waybill_exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE waybill_number = %s",
            $waybill_number
        ));
        
        if ($wpdb->last_error) {
            error_log('Database error checking waybill: ' . $wpdb->last_error);
            wp_send_json_error('Database error occurred. Please try again.');
            return;
        }

        error_log('Waybill exists check result: ' . var_export($waybill_exists, true));

        if (!$waybill_exists) {
            error_log('Waybill not found in database: ' . $waybill_number);
            wp_send_json_error('Waybill number not found');
            return;
        }

        // Verify file was uploaded
        if (!isset($_FILES['pod_image'])) {
            error_log('No file data received');
            wp_send_json_error('No file was uploaded');
            return;
        }

        if (!isset($_FILES['pod_image']['tmp_name']) || empty($_FILES['pod_image']['tmp_name'])) {
            error_log('No temporary file found');
            wp_send_json_error('File upload failed - no data received');
            return;
        }

        $file = $_FILES['pod_image'];
        error_log('File details: ' . print_r($file, true));
        
        // Additional file validation
        $allowed_types = array('image/jpeg', 'image/jpg', 'image/png', 'application/pdf');
        $file_type = wp_check_filetype($file['name']);
        
        if (!in_array($file['type'], $allowed_types)) {
            error_log('Invalid file type: ' . $file['type']);
            wp_send_json_error('Invalid file type. Please upload JPG, JPEG, PNG, or PDF files only');
            return;
        }

        if ($file['size'] > 10 * 1024 * 1024) { // 10MB limit
            error_log('File too large: ' . $file['size']);
            wp_send_json_error('File size must be less than 10MB');
            return;
        }

        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $upload_errors = array(
                UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
            );
            $error_message = isset($upload_errors[$file['error']]) ? $upload_errors[$file['error']] : 'Unknown upload error';
            error_log('File upload error: ' . $error_message);
            wp_send_json_error('Upload error: ' . $error_message);
            return;
        }

        // Get WordPress upload directory
        $upload_dir = wp_upload_dir();
        if ($upload_dir['error']) {
            error_log('WordPress upload directory error: ' . $upload_dir['error']);
            wp_send_json_error('Server configuration error');
            return;
        }
        
        $pod_dir = $upload_dir['basedir'] . '/pod-images';
        error_log('Upload directory: ' . $pod_dir);
        
        // Create pod-images directory if it doesn't exist
        if (!file_exists($pod_dir)) {
            if (!wp_mkdir_p($pod_dir)) {
                error_log('Failed to create directory: ' . $pod_dir);
                wp_send_json_error('Failed to create upload directory');
                return;
            }
            
            // Create an .htaccess file to protect directory
            $htaccess_content = "Options -Indexes\n";
            $htaccess_content .= "<IfModule mod_rewrite.c>\n";
            $htaccess_content .= "RewriteEngine On\n";
            $htaccess_content .= "RewriteCond %{REQUEST_FILENAME} -f\n";
            $htaccess_content .= "RewriteRule . - [L]\n";
            $htaccess_content .= "</IfModule>\n";
            
            if (!file_put_contents($pod_dir . '/.htaccess', $htaccess_content)) {
                error_log('Failed to create .htaccess file in: ' . $pod_dir);
            }
        }

        // Start transaction
        $wpdb->query('START TRANSACTION');

        // Remove any existing POD image for this waybill
        $existing_pod = $wpdb->get_var($wpdb->prepare(
            "SELECT pod_image FROM $table_name WHERE waybill_number = %s",
            $waybill_number
        ));
        
        if ($wpdb->last_error) {
            error_log('Error fetching existing POD: ' . $wpdb->last_error);
            $wpdb->query('ROLLBACK');
            wp_send_json_error('Database error occurred. Please try again.');
            return;
        }
        
        if ($existing_pod) {
            $existing_file = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $existing_pod);
            if (file_exists($existing_file)) {
                if (!unlink($existing_file)) {
                    error_log('Failed to delete existing file: ' . $existing_file);
                    $wpdb->query('ROLLBACK');
                    wp_send_json_error('Failed to remove existing file. Please try again.');
                    return;
                }
                error_log('Deleted existing POD file: ' . $existing_file);
            }
        }

        // Generate unique filename
        $filename = 'pod-' . $waybill_number . '-' . time() . '.' . $file_type['ext'];
        $file_path = $pod_dir . '/' . $filename;
        
        error_log('Attempting to move file to: ' . $file_path);

        // Check if destination is writable
        if (!is_writable(dirname($file_path))) {
            error_log('Destination directory is not writable: ' . dirname($file_path));
            $wpdb->query('ROLLBACK');
            wp_send_json_error('Server configuration error: Upload directory is not writable');
            return;
        }

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $file_path)) {
            $upload_error = error_get_last();
            error_log('Failed to move uploaded file: ' . ($upload_error ? $upload_error['message'] : 'Unknown error'));
            $wpdb->query('ROLLBACK');
            wp_send_json_error('Failed to save uploaded file. Please try again.');
            return;
        }

        error_log('File moved successfully');

        // Verify file was actually created
        if (!file_exists($file_path)) {
            error_log('File not found after move: ' . $file_path);
            $wpdb->query('ROLLBACK');
            wp_send_json_error('File upload failed - please try again');
            return;
        }

        // Update database with new POD image
        $image_url = $upload_dir['baseurl'] . '/pod-images/' . $filename;
        error_log('Updating database with image URL: ' . $image_url);
        
        $update_result = $wpdb->update(
            $table_name,
            array(
                'pod_image' => $image_url,
                'pod_upload_date' => current_time('mysql')
            ),
            array('waybill_number' => $waybill_number),
            array('%s', '%s'),
            array('%s')
        );

        if ($update_result === false) {
            error_log('Database update failed. Error: ' . $wpdb->last_error);
            error_log('Database update details: ' . print_r([
                'table' => $table_name,
                'waybill' => $waybill_number,
                'image_url' => $image_url,
                'upload_date' => current_time('mysql'),
                'wpdb_error' => $wpdb->last_error,
                'wpdb_last_query' => $wpdb->last_query
            ], true));
            
            // Clean up the uploaded file since database update failed
            unlink($file_path);
            $wpdb->query('ROLLBACK');
            wp_send_json_error('Failed to update database. Please try again.');
            return;
        }

        // Commit the transaction
        $wpdb->query('COMMIT');

        error_log('Database updated successfully');
        error_log('POD upload completed successfully - ' . date('Y-m-d H:i:s'));

        // Return success with image URL
        wp_send_json_success(array(
            'message' => 'POD image uploaded successfully',
            'image_url' => $image_url,
            'file_type' => $file['type']
        ));
        
    } catch (Exception $e) {
        error_log('Unexpected error in POD upload: ' . $e->getMessage());
        error_log('Stack trace: ' . $e->getTraceAsString());
        $wpdb->query('ROLLBACK');
        wp_send_json_error('An unexpected error occurred. Please try again.');
    }
}

// Get POD image
add_action('wp_ajax_get_pod_image', 'handle_get_pod_image');
add_action('wp_ajax_nopriv_get_pod_image', 'handle_get_pod_image');

function handle_get_pod_image() {
    try {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'get_pod_image')) {
            wp_send_json_error('Invalid security token');
            return;
        }

        // Check required data
        if (!isset($_POST['waybill_number'])) {
            wp_send_json_error('Missing waybill number');
            return;
        }

        global $wpdb;
        $table = $wpdb->prefix . 'waybills';
        $waybill_number = sanitize_text_field($_POST['waybill_number']);

        $pod_image = $wpdb->get_var($wpdb->prepare(
            "SELECT pod_image FROM $table WHERE waybill_number = %s",
            $waybill_number
        ));

        if ($pod_image) {
            wp_send_json_success(array('image_url' => $pod_image));
        } else {
            wp_send_json_error('No POD image found');
        }
    } catch (Exception $e) {
        wp_send_json_error('Error: ' . $e->getMessage());
    }
}

// Add necessary hooks for AJAX in WordPress
add_action('wp_enqueue_scripts', function() {
    wp_localize_script('jquery', 'waybill_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('waybill_image_upload')
    ));
});

// Render view report
function render_view_report() {
    if (!session_id()) {
        session_start();
    }

    // Check if user is logged in
    if (!isset($_SESSION['waybill_user']) || !isset($_SESSION['waybill_logged_in'])) {
        return 'Please log in to view waybills.';
    }

    ob_start();
    ?>
    <div class="waybill-container" style="display: flex; gap: 20px;">
        <!-- Left Filter Panel -->
        <div class="filter-panel" style="width: 250px; background: #f5f5f5; padding: 20px; border-radius: 4px;">
            <!-- Search Box - Modified version -->
            <div class="search-box" style="margin-bottom: 20px;">
                <h4>Quick Search</h4>
                <input type="text" 
                       id="waybill-search" 
                       placeholder="Search by waybill number..." 
                       style="width: 100%; 
                              padding: 8px; 
                              border: 1px solid #ddd; 
                              border-radius: 4px; 
                              margin-bottom: 10px;">
                <small style="color: #666;">Type to search waybill number</small>
            </div>

            <!-- Add this script right after the search box -->
            <script>
            jQuery(document).ready(function($) {
                // Cache the search input and table rows
                const searchInput = $('#waybill-search');
                
                // Add event listener for search input
                searchInput.on('input', function() {
                    const searchTerm = $(this).val().toLowerCase().trim();
                    
                    // Get all table rows except header
                    const rows = $('#waybills-list tbody tr');
                    
                    if (searchTerm === '') {
                        // Show all rows if search is empty
                        rows.show();
                        return;
                    }
                    
                    // Filter rows
                    rows.each(function() {
                        // Get waybill number from third column (index 2)
                        const waybillNumber = $(this).find('td:eq(2)').text().toLowerCase();
                        
                        // Show/hide row based on match
                        if (waybillNumber.includes(searchTerm)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });
                
                // Clear search when filters are updated
                $('#update-filters').on('click', function() {
                    searchInput.val('');
                    $('#waybills-list tbody tr').show();
                });
            });
            </script>

            <!-- Existing filter content -->
            <h3>Filters</h3>
            
            <!-- Date Range Selector -->
            <div class="date-range" style="margin-bottom: 20px;">
                <div class="form-group" style="margin-bottom: 10px;">
                    <label for="date-from">From:</label>
                    <input type="date" id="date-from" class="form-control" style="width: 100%; padding: 5px;">
                </div>
                <div class="form-group" style="margin-bottom: 10px;">
                    <label for="date-to">To:</label>
                    <input type="date" id="date-to" class="form-control" style="width: 100%; padding: 5px;">
                </div>
            </div>

            <!-- Quick Filters -->
            <div class="quick-filters" style="margin-bottom: 20px;">
                <h4>Quick Filters</h4>
                <button onclick="setQuickFilter('today')" class="quick-filter-btn" style="width: 100%; margin-bottom: 5px; padding: 8px; background: #fff; border: 1px solid #ddd; cursor: pointer;">Today</button>
                <button onclick="setQuickFilter('this-month')" class="quick-filter-btn" style="width: 100%; margin-bottom: 5px; padding: 8px; background: #fff; border: 1px solid #ddd; cursor: pointer;">This Month</button>
                <button onclick="setQuickFilter('last-month')" class="quick-filter-btn" style="width: 100%; padding: 8px; background: #fff; border: 1px solid #ddd; cursor: pointer;">Last Month</button>
            </div>

            <script>
                
                function setQuickFilter(filter) {
    const today = new Date();
    let fromDate, toDate;

    switch(filter) {
        case 'today':
            fromDate = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 0, 0, 0); // Today at 00:00:00
            toDate = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 23, 59, 0); // Today at 23:59:00
            break;
        case 'this-month':
            fromDate = new Date(today.getFullYear(), today.getMonth(), 1, 0, 0, 0); // 1st of this month
            toDate = new Date(today.getFullYear(), today.getMonth() + 1, 0, 23, 59, 0); // Last day of this month
            break;
        case 'last-month':
            fromDate = new Date(today.getFullYear(), today.getMonth() - 1, 1, 0, 0, 0); // 1st of last month
            toDate = new Date(today.getFullYear(), today.getMonth(), 0, 23, 59, 0); // Last day of last month
            break;
    }

    // Format dates in YYYY-MM-DD without time zone shifts
    document.getElementById('date-from').value = formatDate(fromDate);
    document.getElementById('date-to').value = formatDate(toDate);

    updateWaybillsList();
}

// Format date correctly in YYYY-MM-DD format
function formatDate(date) {
    return date.toLocaleDateString('en-CA'); // en-CA ensures YYYY-MM-DD format in local time
}

            </script>
            
            <!-- Service Filter -->
            <div class="service-filter" style="margin-bottom: 20px;">
                <h4>Service</h4>
                <select id="service-filter" style="width: 100%; padding: 5px;">
                    <option value="">All Services</option>
                    <option value="Airfreight">Airfreight</option>
                    <option value="Overnight">Overnight</option>
                    <option value="Economy">Economy</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Road Freight - CB">Road Freight - CB</option>
                    <option value="Road Freight - local">Road Freight - local</option>
                    <option value="Sea Freight">Sea Freight</option>
                    <option value="Budget">Budget</option>
                </select>
            </div>

            <!-- Movement Type Filter -->
            <div class="movement-filter" style="margin-bottom: 20px;">
                <h4>Movement Type</h4>
                <div class="radio-group" style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="display: flex; align-items: center;">
                        <input type="radio" name="movement-filter" value="" checked style="margin-right: 5px;">
                        All Types
                    </label>
                    <label style="display: flex; align-items: center;">
                        <input type="radio" name="movement-filter" value="Import" style="margin-right: 5px;">
                        Import
                    </label>
                    <label style="display: flex; align-items: center;">
                        <input type="radio" name="movement-filter" value="Export" style="margin-right: 5px;">
                        Export
                    </label>
                    <label style="display: flex; align-items: center;">
                        <input type="radio" name="movement-filter" value="Local" style="margin-right: 5px;">
                        Local
                    </label>
                </div>
            </div>

            <!-- POD Status Filter -->
            <div class="pod-status-filter" style="margin-bottom: 20px;">
                <h4>POD Status</h4>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <button onclick="filterByPODStatus('all')" class="pod-filter-btn" style="width: 100%; padding: 8px; background: #fff; border: 1px solid #ddd; cursor: pointer; text-align: left;">
                        All Waybills
                    </button>
                    <button onclick="filterByPODStatus('with-pod')" class="pod-filter-btn" style="width: 100%; padding: 8px; background: #fff; border: 1px solid #ddd; cursor: pointer; text-align: left;">
                        With POD
                    </button>
                    <button onclick="filterByPODStatus('without-pod')" class="pod-filter-btn" style="width: 100%; padding: 8px; background: #fff; border: 1px solid #ddd; cursor: pointer; text-align: left;">
                        Without POD
                    </button>
                </div>
            </div>

            <!-- Update Button -->
            <button id="update-filters" onclick="updateWaybillsList()" class="button button-primary" style="width: 100%; padding: 10px; margin-bottom: 20px;">Update</button>

            <!-- Actions -->
            <div class="actions">
                <h4>Actions</h4>
                <button onclick="exportToExcel()" class="action-btn" style="width: 100%; padding: 8px; background: #fff; border: 1px solid #ddd; cursor: pointer;">Export to Excel</button>
            </div>
        </div>

        <!-- Right Content Area -->
        <div class="content-area" style="flex-grow: 1; display: flex; flex-direction: column;">
            <div class="table-container" style="position: relative; margin-top: 20px; max-height: calc(100vh - 200px); overflow: auto;">
                <table id="waybills-list" style="width: 100%; border-collapse: collapse; background: white;">
                    <thead>
                        <tr>
                        <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 2; min-width: 80px; border: 1px solid #ccc; padding: 12px; text-align: center;">View</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 120px; border: 1px solid #ccc; padding: 12px; text-align: left;">Date</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 120px; border: 1px solid #ccc; padding: 12px; text-align: left;">Waybill No</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 120px; border: 1px solid #ccc; padding: 12px; text-align: left;">Allocated To</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 120px; border: 1px solid #ccc; padding: 12px; text-align: left;">Movement Type</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 120px; border: 1px solid #ccc; padding: 12px; text-align: left;">Client Reference</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 100px; border: 1px solid #ccc; padding: 12px; text-align: left;">Action Date</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 100px; border: 1px solid #ccc; padding: 12px; text-align: left;">Type of Service</th>

                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 100px; border: 1px solid #ccc; padding: 12px; text-align: left;">Sender Name</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 100px; border: 1px solid #ccc; padding: 12px; text-align: left;">Sender Customer Name</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 200px; border: 1px solid #ccc; padding: 12px; text-align: left;">Sender Address</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 120px; border: 1px solid #ccc; padding: 12px; text-align: left;">Sender Tel</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 100px; border: 1px solid #ccc; padding: 12px; text-align: left;">Sender Country</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 100px; border: 1px solid #ccc; padding: 12px; text-align: left;">Sender State</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 100px; border: 1px solid #ccc; padding: 12px; text-align: left;">Sender City</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 150px; border: 1px solid #ccc; padding: 12px; text-align: left;">Receiver Name</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 150px; border: 1px solid #ccc; padding: 12px; text-align: left;">Receiver Customer Name</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 200px; border: 1px solid #ccc; padding: 12px; text-align: left;">Receiver Address</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 120px; border: 1px solid #ccc; padding: 12px; text-align: left;">Receiver Tel</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 100px; border: 1px solid #ccc; padding: 12px; text-align: left;">Receiver Country</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 100px; border: 1px solid #ccc; padding: 12px; text-align: left;">Receiver State</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 100px; border: 1px solid #ccc; padding: 12px; text-align: left;">Receiver City</th>
                           
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 100px; border: 1px solid #ccc; padding: 12px; text-align: left;">Total Pallets</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 100px; border: 1px solid #ccc; padding: 12px; text-align: left;">Volume Mass</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 100px; border: 1px solid #ccc; padding: 12px; text-align: left;">Actual Mass</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 200px; border: 1px solid #ccc; padding: 12px; text-align: left;">Special Instructions</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 100px; border: 1px solid #ccc; padding: 12px; text-align: left;">Status</th>
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 1; min-width: 100px; border: 1px solid #ccc; padding: 12px; text-align: left;">Invoice No.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        global $wpdb;
                        $table = $wpdb->prefix . 'waybills';
                        
                        // Modify the initial query to filter based on user type
                        $query = "SELECT * FROM $table";
                        $where = array();
                        $where_values = array();

                        // If not admin, only show waybills where sender name matches username
                        if (!isset($_SESSION['waybill_is_admin']) || !$_SESSION['waybill_is_admin']) {
                            $where[] = "Sender_Name = %s";
                            $where_values[] = $_SESSION['waybill_user_name'];
                        }

                        if (!empty($where)) {
                            $query .= " WHERE " . implode(" AND ", $where);
                            $query = $wpdb->prepare($query, $where_values);
                        }

                        $query .= " ORDER BY Date_Created DESC, id DESC";
                        $waybills = $wpdb->get_results($query);

                        foreach ($waybills as $waybill) {
                            // Parse sender location
                            $sender_location = explode(', ', $waybill->Sender_Country_State_City);
                            $sender_country = isset($sender_location[0]) ? $sender_location[0] : '';
                            $sender_state = isset($sender_location[1]) ? $sender_location[1] : '';
                            $sender_city = isset($sender_location[2]) ? $sender_location[2] : '';

                            // Parse receiver location
                            $receiver_location = explode(', ', $waybill->Reciver_Country_State_City);
                            $receiver_country = isset($receiver_location[0]) ? $receiver_location[0] : '';
                            $receiver_state = isset($receiver_location[1]) ? $receiver_location[1] : '';
                            $receiver_city = isset($receiver_location[2]) ? $receiver_location[2] : '';

                            echo '<tr>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px; text-align: center;"><button onclick="viewWaybillDetails(\'' . esc_js($waybill->waybill_number) . '\')" class="button button-small" style="background-color: #007bff; color: white; padding: 4px 8px; font-size: 12px; border: none; border-radius: 3px; cursor: pointer; line-height: 1;">View</button></td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px; white-space: nowrap;">' . esc_html($waybill->Date_Created) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->waybill_number) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Allocated_to) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Movement_Type) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Client_Reference) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px; white-space: nowrap;">' . esc_html($waybill->Action_Date) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Type_Of_Service) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Sender_Name) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Sender_Customer_Name) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Sender_Address) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Sender_Telephone) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($sender_country) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($sender_state) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($sender_city) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Reciver_Name) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Reciver_Customer_Name) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Reciver_Address) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Reciver_Telephone) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($receiver_country) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($receiver_state) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($receiver_city) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Total_Pieces) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Volume_Mass) . ' kg</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Actual_Mass) . ' kg</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Special_Instructions) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px; min-width: 150px;">' . 
                                 render_delivery_status_field($waybill, isset($_SESSION['waybill_is_admin']) && $_SESSION['waybill_is_admin']) . 
                                 '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">';
                            echo '<input type="text" name="invoice_no_' . esc_attr($waybill->id) . '" value="' . esc_attr($waybill->Invoice_No) . '" style="width: 100%; padding: 5px; border: 1px solid #ddd; border-radius: 4px;" />';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Waybill Details Modal -->
    <div id="waybillModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); overflow: auto;">
        <div class="modal-content" style="background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 90%; max-width: 1000px; max-height: 80vh; position: relative; overflow: auto;">
            <span class="close" style="position: sticky; top: 0; float: right; font-size: 28px; font-weight: bold; cursor: pointer; background: white; padding: 0 10px; z-index: 1;">&times;</span>
            <div id="waybillDetails" style="overflow: auto;"></div>
        </div>
    </div>



    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
            overflow: hidden;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 1000px;
            max-height: 80vh;
            position: relative;
            overflow: auto;
        }

        .close {
            position: sticky;
            top: 0;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            background: white;
            padding: 0 10px;
            z-index: 1;
        }

        .close:hover {
            color: #666;
        }

        #waybillDetails {
            overflow: auto;
            padding-right: 10px;
        }

        .waybill-table {
            min-width: 800px;
            margin-bottom: 20px;
        }

        .modal-content::-webkit-scrollbar,
        #waybillDetails::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .modal-content::-webkit-scrollbar-track,
        #waybillDetails::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .modal-content::-webkit-scrollbar-thumb,
        #waybillDetails::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .modal-content::-webkit-scrollbar-thumb:hover,
        #waybillDetails::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if jQuery and waybill_ajax are available
        if (typeof jQuery !== 'undefined' && typeof waybill_ajax !== 'undefined') {
            console.log('jQuery and waybill_ajax are loaded');
        } else {
            console.error('jQuery or waybill_ajax is not loaded');
        }
    });

    function viewWaybillDetails(waybillNumber) {
        var modal = document.getElementById('waybillModal');
        var span = document.getElementsByClassName('close')[0];
        var detailsDiv = document.getElementById('waybillDetails');

        // Show loading state
        detailsDiv.innerHTML = 'Loading...';
        modal.style.display = 'block';

        // Fetch waybill details via AJAX
        jQuery.ajax({
            url: waybill_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_waybill_details',
                waybill_number: waybillNumber,
                nonce: waybill_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    detailsDiv.innerHTML = response.data;
                } else {
                    detailsDiv.innerHTML = 'Error loading waybill details.';
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                detailsDiv.innerHTML = 'Error loading waybill details.';
            }
        });

        // Close modal when clicking X
        span.onclick = function() {
            modal.style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    }



    function updateWaybillsList() {
        // Get filter values
        const fromDate = document.getElementById('date-from').value;
        const toDate = document.getElementById('date-to').value;
        const service = document.getElementById('service-filter').value;
        const movementType = document.querySelector('input[name="movement-filter"]:checked')?.value;

        // Show loading state
        document.querySelector('#waybills-list tbody').innerHTML = '<tr><td colspan="15" style="text-align: center;">Loading...</td></tr>';

        // Make AJAX request
        jQuery.ajax({
            url: waybill_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'fetch_filtered_waybills',
                from_date: fromDate,
                to_date: toDate,
                service: service,
                movement_type: movementType,
                nonce: waybill_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    document.querySelector('#waybills-list tbody').innerHTML = response.data;
                } else {
                    document.querySelector('#waybills-list tbody').innerHTML = '<tr><td colspan="15" style="text-align: center;">Error loading data</td></tr>';
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                document.querySelector('#waybills-list tbody').innerHTML = '<tr><td colspan="15" style="text-align: center;">Error loading data</td></tr>';
            }
        });
    }

    function filterByPODStatus(status) {
        // Get other filter values
        const fromDate = document.getElementById('date-from').value;
        const toDate = document.getElementById('date-to').value;
        const service = document.getElementById('service-filter').value;
        const movementType = document.querySelector('input[name="movement-filter"]:checked')?.value;

        // Show loading state
        document.querySelector('#waybills-list tbody').innerHTML = '<tr><td colspan="15" style="text-align: center;">Loading...</td></tr>';

        // Make AJAX request
        jQuery.ajax({
            url: waybill_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'fetch_filtered_waybills',
                from_date: fromDate,
                to_date: toDate,
                service: service,
                movement_type: movementType,
                pod_status: status,
                nonce: waybill_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    document.querySelector('#waybills-list tbody').innerHTML = response.data;
                } else {
                    document.querySelector('#waybills-list tbody').innerHTML = '<tr><td colspan="15" style="text-align: center;">Error loading data</td></tr>';
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                document.querySelector('#waybills-list tbody').innerHTML = '<tr><td colspan="15" style="text-align: center;">Error loading data</td></tr>';
            }
        });
    }
    
    </script>
    <?php
    return ob_get_clean();
}

// AJAX handler for waybill details
add_action('wp_ajax_get_waybill_details', 'handle_get_waybill_details');
add_action('wp_ajax_nopriv_get_waybill_details', 'handle_get_waybill_details');

function handle_get_waybill_details() {
    try {
        check_ajax_referer('waybill_nonce', 'nonce');
        
        if (!isset($_POST['waybill_number'])) {
            throw new Exception('No waybill number provided');
        }

        global $wpdb;
        $table = $wpdb->prefix . 'waybills';
        $waybill_number = sanitize_text_field($_POST['waybill_number']);
        
        $waybill = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE waybill_number = %s",
            $waybill_number
        ));

        if (!$waybill) {
            throw new Exception('Waybill not found');
        }

        ob_start();
        ?>
        <div class="waybill-details" style="padding: 20px;">
            <!-- Success Message Container -->
            <div id="modal-success-message" class="modal-notification success" style="display: none; margin-bottom: 20px; padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="message-text">POD uploaded successfully!</span>
                    <button onclick="this.parentElement.parentElement.style.display='none'" style="background: none; border: none; color: #155724; cursor: pointer; font-size: 20px;">&times;</button>
                </div>
            </div>

            <h3 style="margin-bottom: 20px;">Waybill Details - <?php echo esc_html($waybill->waybill_number); ?></h3>
            
            <!-- Header Information -->
            <div class="details-section" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px; background: #f9f9f9; padding: 15px; border-radius: 4px;">
                <div>
                    <strong>Date Created:</strong> <?php echo esc_html($waybill->Date_Created); ?><br>
                    <strong>Action Date:</strong> <?php echo esc_html($waybill->Action_Date); ?>
                </div>
                <div>
                    <strong>Movement Type:</strong> <?php echo esc_html($waybill->Movement_Type); ?><br>
                    <strong>Service Type:</strong> <?php echo esc_html($waybill->Type_Of_Service); ?>
                </div>
                <div>
                    <strong>Client Reference:</strong> <?php echo esc_html($waybill->Client_Reference); ?><br>
                    <strong>Allocated To:</strong> <?php echo esc_html($waybill->Allocated_to); ?>
                </div>
            </div>

            <!-- Sender/Receiver Information -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <!-- Sender Details -->
                <div class="sender-details" style="background: #fff; padding: 15px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h4 style="margin-bottom: 10px; color: #333;">Sender Details</h4>
                    <div style="line-height: 1.6;">
                        <strong>Name:</strong> <?php echo esc_html($waybill->Sender_Name); ?><br>
                        <strong>Customer Name:</strong> <?php echo esc_html($waybill->Sender_Customer_Name); ?><br>
                        <strong>Address:</strong> <?php echo esc_html($waybill->Sender_Address); ?><br>
                        <strong>Telephone:</strong> <?php echo esc_html($waybill->Sender_Telephone); ?><br>
                        <strong>Location:</strong> <?php echo esc_html($waybill->Sender_Country_State_City); ?>
                    </div>
                </div>

                <!-- Receiver Details -->
                <div class="receiver-details" style="background: #fff; padding: 15px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h4 style="margin-bottom: 10px; color: #333;">Receiver Details</h4>
                    <div style="line-height: 1.6;">
                        <strong>Name:</strong> <?php echo esc_html($waybill->Reciver_Name); ?><br>
                        <strong>Customer Name:</strong> <?php echo esc_html($waybill->Reciver_Customer_Name); ?><br>
                        <strong>Address:</strong> <?php echo esc_html($waybill->Reciver_Address); ?><br>
                        <strong>Telephone:</strong> <?php echo esc_html($waybill->Reciver_Telephone); ?><br>
                        <strong>Location:</strong> <?php echo esc_html($waybill->Reciver_Country_State_City); ?>
                    </div>
                </div>
            </div>

            <!-- Shipment Details -->
            <div class="shipment-details" style="background: #fff; padding: 15px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <h4 style="margin-bottom: 10px; color: #333;">Shipment Details</h4>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                    <div>
                        <strong>Total Pallets:</strong> <?php echo esc_html($waybill->Total_Pieces); ?>
                    </div>
                    <div>
                        <strong>Volume Mass:</strong> <?php echo esc_html($waybill->Volume_Mass); ?> kg
                    </div>
                    <div>
                        <strong>Actual Mass:</strong> <?php echo esc_html($waybill->Actual_Mass); ?> kg
                    </div>
                </div>
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
                    <strong>Description of Goods:</strong>
                    <div style="margin-top: 8px; padding: 10px; background: #f9f9f9; border-radius: 4px; line-height: 1.4;">
                        <?php 
                        $descriptions = explode('; ', $waybill->description_of_goods);
                        foreach ($descriptions as $index => $desc) {
                            echo esc_html(trim($desc));
                            if ($index < count($descriptions) - 1) {
                                echo '<br>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <div style="margin-top: 15px;">
                    <strong>Special Instructions:</strong><br>
                    <div style="margin-top: 5px; padding: 10px; background: #f9f9f9; border-radius: 4px;">
                        <?php echo nl2br(esc_html($waybill->Special_Instructions)); ?>
                    </div>
                </div>
            </div>

            <!-- POD Upload Section -->
            <div class="pod-section" style="background: #f9f9f9; padding: 20px; border-radius: 4px;">
                <?php if ($waybill->pod_image): ?>
                    <div class="current-pod" style="margin-bottom: 20px;">
                        <h4 style="margin-bottom: 10px;">Current POD:</h4>
                        <?php if (strpos($waybill->pod_image, '.pdf') !== false): ?>
                            <a href="<?php echo esc_url($waybill->pod_image); ?>" target="_blank" class="button">View PDF</a>
                        <?php else: ?>
                            <img src="<?php echo esc_url($waybill->pod_image); ?>" alt="POD" style="max-width: 300px; border: 1px solid #ddd; border-radius: 4px;">
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <form class="pod-upload-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="waybill_number" value="<?php echo esc_attr($waybill->waybill_number); ?>">
                    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('upload_pod_image'); ?>">
                    
                    <div style="margin-bottom: 15px;">
                        <h4>Upload POD</h4>
                        <input type="file" class="pod-upload" name="pod_image" accept="image/jpeg,image/png,application/pdf" style="display: none;">
                        <button type="button" class="upload-pod-btn button" style="background-color: #0073aa; color: white;">Select File</button>
                    </div>
                    
                    <div class="upload-status" style="display: none;"></div>
                    <div class="current-pod" style="margin-top: 15px;"></div>
                </form>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        wp_send_json_success($html);
        
    } catch (Exception $e) {
        wp_send_json_error('Error: ' . $e->getMessage());
    }
}

// Add AJAX handler for filtered waybills
add_action('wp_ajax_fetch_filtered_waybills', 'handle_fetch_filtered_waybills');
add_action('wp_ajax_nopriv_fetch_filtered_waybills', 'handle_fetch_filtered_waybills');

function handle_fetch_filtered_waybills() {
    if (!session_id()) {
        session_start();
    }
    
    check_ajax_referer('waybill_nonce', 'nonce');

    global $wpdb;
    $table = $wpdb->prefix . 'waybills';

    // Build the WHERE clause
    $where_clauses = array();
    $where_values = array();

    // Always add user-based filtering for non-admin users first
    if (!isset($_SESSION['waybill_is_admin']) || !$_SESSION['waybill_is_admin']) {
        $where_clauses[] = "Sender_Name = %s";
        $where_values[] = $_SESSION['waybill_user_name'];
    }

    // Add other filters
    if (!empty($_POST['from_date'])) {
        $where_clauses[] = "Date_Created >= %s";
        $where_values[] = sanitize_text_field($_POST['from_date']);
    }

    if (!empty($_POST['to_date'])) {
        $where_clauses[] = "Date_Created <= %s";
        $where_values[] = sanitize_text_field($_POST['to_date']);
    }

    if (!empty($_POST['service'])) {
        $where_clauses[] = "Type_Of_Service = %s";
        $where_values[] = sanitize_text_field($_POST['service']);
    }

    if (!empty($_POST['movement_type'])) {
        $where_clauses[] = "Movement_Type = %s";
        $where_values[] = sanitize_text_field($_POST['movement_type']);
    }

    // Add POD status to WHERE clause
    if (!empty($_POST['pod_status'])) {
        if ($_POST['pod_status'] === 'with-pod') {
            $where_clauses[] = "(pod_image IS NOT NULL AND pod_image != '')";
        } else if ($_POST['pod_status'] === 'without-pod') {
            $where_clauses[] = "(pod_image IS NULL OR pod_image = '')";
        }
    }

    // Construct the query
    $query = "SELECT * FROM $table";
    if (!empty($where_clauses)) {
        $query .= " WHERE " . implode(" AND ", $where_clauses);
    }
    $query .= " ORDER BY Date_Created DESC, id DESC";

    // Prepare and execute query
    if (!empty($where_values)) {
        error_log('Preparing query with parameters: ' . print_r($where_values, true));
        $query = $wpdb->prepare($query, $where_values);
        error_log('Prepared query: ' . $query);
    }
    
    error_log('Executing query: ' . $query);
    $results = $wpdb->get_results($query);
    error_log('Query results: ' . print_r($results, true));
    
    ob_start();
    if ($results) {
        foreach ($results as $waybill) {
            // Parse sender location
            $sender_location = explode(', ', $waybill->Sender_Country_State_City);
            $sender_country = isset($sender_location[0]) ? $sender_location[0] : '';
            $sender_state = isset($sender_location[1]) ? $sender_location[1] : '';
            $sender_city = isset($sender_location[2]) ? $sender_location[2] : '';

            // Parse receiver location
            $receiver_location = explode(', ', $waybill->Reciver_Country_State_City);
            $receiver_country = isset($receiver_location[0]) ? $receiver_location[0] : '';
            $receiver_state = isset($receiver_location[1]) ? $receiver_location[1] : '';
            $receiver_city = isset($receiver_location[2]) ? $receiver_location[2] : '';

            echo '<tr>';
            echo '<td style="border: 1px solid #ddd; padding: 8px; text-align: center;"><button onclick="viewWaybillDetails(\'' . esc_js($waybill->waybill_number) . '\')" class="button button-small" style="background-color: #007bff; color: white; padding: 4px 8px; font-size: 12px; border: none; border-radius: 3px; cursor: pointer; line-height: 1;">View</button></td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px; white-space: nowrap;">' . esc_html($waybill->Date_Created) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->waybill_number) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Allocated_to) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Movement_Type) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Client_Reference) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px; white-space: nowrap;">' . esc_html($waybill->Action_Date) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Type_Of_Service) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Sender_Name) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Sender_Customer_Name) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Sender_Address) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Sender_Telephone) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($sender_country) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($sender_state) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($sender_city) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Reciver_Name) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Reciver_Customer_Name) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Reciver_Address) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Reciver_Telephone) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($receiver_country) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($receiver_state) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($receiver_city) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Total_Pieces) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Volume_Mass) . ' kg</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Actual_Mass) . ' kg</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($waybill->Special_Instructions) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px; min-width: 150px;">' . 
                 render_delivery_status_field($waybill, isset($_SESSION['waybill_is_admin']) && $_SESSION['waybill_is_admin']) . 
                 '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">';
            echo '<input type="text" name="invoice_no_' . esc_attr($waybill->id) . '" value="' . esc_attr($waybill->Invoice_No) . '" style="width: 100%; padding: 5px; border: 1px solid #ddd; border-radius: 4px;" />';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="15" style="text-align: center;">No waybills found</td></tr>';
    }
    $html = ob_get_clean();
    wp_send_json_success($html);
}

// AJAX handler for Excel export
add_action('wp_ajax_export_waybills_to_excel', 'handle_export_waybills_to_excel');
add_action('wp_ajax_nopriv_export_waybills_to_excel', 'handle_export_waybills_to_excel');

function handle_export_waybills_to_excel() {
    if (!session_id()) {
        session_start();
    }
    
    check_ajax_referer('waybill_nonce', 'nonce');

    global $wpdb;
    $table = $wpdb->prefix . 'waybills';

    // Build the WHERE clause
    $where_clauses = array();
    $where_values = array();

    // Add user-based filtering for non-admin users
    if (!isset($_SESSION['waybill_is_admin']) || !$_SESSION['waybill_is_admin']) {
        $where_clauses[] = "Sender_Name = %s";
        $where_values[] = $_SESSION['waybill_user_name'];
    }

    // Add other filters
    if (!empty($_POST['from_date'])) {
        $where_clauses[] = "Date_Created >= %s";
        $where_values[] = sanitize_text_field($_POST['from_date']);
    }

    if (!empty($_POST['to_date'])) {
        $where_clauses[] = "Date_Created <= %s";
        $where_values[] = sanitize_text_field($_POST['to_date']);
    }

    if (!empty($_POST['service'])) {
        $where_clauses[] = "Type_Of_Service = %s";
        $where_values[] = sanitize_text_field($_POST['service']);
    }

    if (!empty($_POST['movement_type'])) {
        $where_clauses[] = "Movement_Type = %s";
        $where_values[] = sanitize_text_field($_POST['movement_type']);
    }

    // Construct the query
    $query = "SELECT * FROM $table";
    if (!empty($where_clauses)) {
        $query .= " WHERE " . implode(" AND ", $where_clauses);
    }
    // Change the ORDER BY clause to sort from oldest to newest
    $query .= " ORDER BY Date_Created ASC, id ASC";

    // Get the waybills
    if (!empty($where_values)) {
        $waybills = $wpdb->get_results($wpdb->prepare($query, $where_values));
    } else {
        $waybills = $wpdb->get_results($query);
    }

    // Set headers for Excel download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="waybills_export_' . date('Y-m-d') . '.csv"');

    // Create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // Add the UTF-8 BOM to the file
    fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // Output header row
    fputcsv($output, array(
        'Date',
        'Waybill No',
        'Allocated To',
        'Movement Type',
        'Client Reference',
        'Action Date',
        'Type of Service',
        'Sender Name',
        'Sender Customer Name',
        'Sender Address',
        'Sender Tel',
        'Sender Country',
        'Sender State/Province',
        'Sender City',
        'Receiver Name',
        'Receiver Customer Name',
        'Receiver Address',
        'Receiver Tel',
        'Receiver Country',
        'Receiver State/Province',
        'Receiver City',
        'Total Pieces',
        'Volume Mass',
        'Actual Mass',
        'Special Instructions',
        'Invoice No'
    ));

    // Output each row
    foreach ($waybills as $waybill) {
        // Parse sender location
        $sender_location = explode(', ', $waybill->Sender_Country_State_City);
        $sender_country = isset($sender_location[0]) ? $sender_location[0] : '';
        $sender_state = isset($sender_location[1]) ? $sender_location[1] : '';
        $sender_city = isset($sender_location[2]) ? $sender_location[2] : '';

        // Parse receiver location
        $receiver_location = explode(', ', $waybill->Reciver_Country_State_City);
        $receiver_country = isset($receiver_location[0]) ? $receiver_location[0] : '';
        $receiver_state = isset($receiver_location[1]) ? $receiver_location[1] : '';
        $receiver_city = isset($receiver_location[2]) ? $receiver_location[2] : '';

        fputcsv($output, array(
            $waybill->Date_Created,
            $waybill->waybill_number,
            $waybill->Allocated_to,
            $waybill->Movement_Type,
            $waybill->Client_Reference,
            $waybill->Action_Date,
            $waybill->Type_Of_Service,
            $waybill->Sender_Name,
            $waybill->Sender_Customer_Name,
            $waybill->Sender_Address,
            $waybill->Sender_Telephone,
            $sender_country,
            $sender_state,
            $sender_city,
            $waybill->Reciver_Name,
            $waybill->Reciver_Customer_Name,
            $waybill->Reciver_Address,
            $waybill->Reciver_Telephone,
            $receiver_country,
            $receiver_state,
            $receiver_city,
            $waybill->Total_Pieces,
            $waybill->Volume_Mass,
            $waybill->Actual_Mass,
            $waybill->Special_Instructions,
            $waybill->Invoice_No
        ));
    }

    fclose($output);
    exit;
}

// Register Shortcodes
function register_waybill_shortcodes() {
    add_shortcode('waybill_login', 'render_login_form');
    add_shortcode('waybill_dashboard', 'render_waybill_dashboard');
    add_shortcode('capture_waybill_form', 'render_capture_waybill_form');
    add_shortcode('view_waybills', 'render_view_report');
}
add_action('init', 'register_waybill_shortcodes');

// Add AJAX handler for updating database structure
add_action('wp_ajax_update_waybill_table', 'handle_update_waybill_table');
add_action('wp_ajax_nopriv_update_waybill_table', 'handle_update_waybill_table');

function handle_update_waybill_table() {
    global $wpdb;
    $wpdb->show_errors();
    
    try {
        $table_name = $wpdb->prefix . 'waybills';
        
        // Check if required columns exist
        $columns_to_check = array(
            'pod_upload_date' => "ALTER TABLE {$table_name} ADD COLUMN pod_upload_date datetime DEFAULT NULL",
            'Delivery' => "ALTER TABLE {$table_name} ADD COLUMN Delivery varchar(255) DEFAULT NULL",
            'Invoice_No' => "ALTER TABLE {$table_name} ADD COLUMN Invoice_No varchar(255) DEFAULT NULL"
        );
        
        foreach ($columns_to_check as $column => $alter_query) {
            $column_exists = $wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE '{$column}'");
            if (empty($column_exists)) {
                $wpdb->query($alter_query);
                error_log("Added {$column} column to waybills table");
            }
        }
        
        wp_send_json_success('Database structure updated successfully');
    } catch (Exception $e) {
        error_log('Error updating database structure: ' . $e->getMessage());
        wp_send_json_error('Failed to update database structure');
    }
}

// Add JavaScript to update database structure
add_action('wp_footer', function() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $.ajax({
            url: waybill_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'update_waybill_table'
            },
            success: function(response) {
                console.log('Database structure update:', response);
            },
            error: function(xhr, status, error) {
                console.error('Database structure update failed:', error);
            }
        });
    });
    </script>
    <?php
});

// JavaScript to handle the export to Excel functionality
add_action('wp_footer', function() {
    ?>
    <script type="text/javascript">
    function exportToExcel() {
        const fromDate = document.getElementById('date-from').value;
        const toDate = document.getElementById('date-to').value;
        const service = document.getElementById('service-filter').value;
        const movementType = document.querySelector('input[name="movement-filter"]:checked')?.value;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = waybill_ajax.ajax_url;

        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'export_waybills_to_excel';
        form.appendChild(actionInput);

        const nonceInput = document.createElement('input');
        nonceInput.type = 'hidden';
        nonceInput.name = 'nonce';
        nonceInput.value = waybill_ajax.nonce;
        form.appendChild(nonceInput);

        const fromDateInput = document.createElement('input');
        fromDateInput.type = 'hidden';
        fromDateInput.name = 'from_date';
        fromDateInput.value = fromDate;
        form.appendChild(fromDateInput);

        const toDateInput = document.createElement('input');
        toDateInput.type = 'hidden';
        toDateInput.name = 'to_date';
        toDateInput.value = toDate;
        form.appendChild(toDateInput);

        const serviceInput = document.createElement('input');
        serviceInput.type = 'hidden';
        serviceInput.name = 'service';
        serviceInput.value = service;
        form.appendChild(serviceInput);

        const movementTypeInput = document.createElement('input');
        movementTypeInput.type = 'hidden';
        movementTypeInput.name = 'movement_type';
        movementTypeInput.value = movementType;
        form.appendChild(movementTypeInput);

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
    </script>
    <?php
});

// Add AJAX handler for refreshing overdue waybills table
add_action('wp_ajax_refresh_overdue_waybills', 'handle_refresh_overdue_waybills');
add_action('wp_ajax_nopriv_refresh_overdue_waybills', 'handle_refresh_overdue_waybills');

function handle_refresh_overdue_waybills() {
    if (!session_id()) {
        session_start();
    }
    
    check_ajax_referer('waybill_nonce', 'nonce');

    global $wpdb;
    $table = $wpdb->prefix . 'waybills';
    
    // Modified to get all waybills without PODs
    $where_clauses = array(
        "(pod_image IS NULL OR pod_image = '')"
    );
    $where_values = array();

    // Add user-based filtering for non-admin users
    if (!isset($_SESSION['waybill_is_admin']) || !$_SESSION['waybill_is_admin']) {
        $where_clauses[] = "Sender_Name = %s";
        $where_values[] = $_SESSION['waybill_user_name'];
    }

    $query = "SELECT *, 
              CASE 
                WHEN Action_Date < CURDATE() THEN DATEDIFF(CURDATE(), Action_Date)
                ELSE 0
              END as days_overdue 
              FROM $table 
              WHERE " . implode(" AND ", $where_clauses);

    if (!empty($where_values)) {
        $query = $wpdb->prepare($query, $where_values);
    }

    // Changed ORDER BY from Action_Date to Date_Created
    $query .= " ORDER BY Date_Created DESC, id DESC";
    
    $waybills = $wpdb->get_results($query);
    
    ob_start();
    if ($waybills) {
        foreach ($waybills as $waybill) {
            $days_overdue = intval($waybill->days_overdue);
            $status_text = $days_overdue > 0 ? 
                          $days_overdue . " days overdue" : 
                          "Required POD";
            $status_color = $days_overdue > 0 ? '#d32f2f' : '#ff6d00';
            $row_color = $days_overdue > 4 ? '#ffebee' : ($days_overdue > 0 ? '#fff3e0' : '');
            ?>
            <tr id="waybill-row-<?php echo esc_attr($waybill->waybill_number); ?>" style="background-color: <?php echo $row_color; ?>">
                <td style="border: 1px solid #ddd; padding: 8px; white-space: nowrap;"><?php echo esc_html($waybill->Date_Created); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px; white-space: nowrap;"><?php echo esc_html($waybill->Action_Date); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?php echo esc_html($waybill->waybill_number); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?php echo esc_html($waybill->Client_Reference); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?php echo esc_html($waybill->Type_Of_Service); ?></td>
               <td style="border: 1px solid #ddd; padding: 8px;"><?php echo esc_html($waybill->Sender_Name ); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?php echo esc_html($waybill->Reciver_Country_State_City); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px; color: <?php echo $status_color; ?>; font-weight: bold;"><?php echo $status_text; ?></td>
                <td style="border: 1px solid #ddd; padding: 8px; min-width: 150px;"><?php echo render_delivery_status_field($waybill, isset($_SESSION['waybill_is_admin']) && $_SESSION['waybill_is_admin']); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 80px;">
                    <input type="text" name="invoice_no_<?php echo esc_attr($waybill->id); ?>" value="<?php echo esc_attr($waybill->Invoice_No); ?>" style="width: 100%; padding: 5px; border: 1px solid #ddd; border-radius: 4px;" />
                </td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 80px;">
                    <form class="pod-upload-form" method="post" enctype="multipart/form-data" style="margin: 0;">
                        <input type="hidden" name="waybill_number" value="<?php echo esc_attr($waybill->waybill_number); ?>">
                        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('upload_pod_image'); ?>">
                        <input type="file" class="pod-upload" style="display: none;" accept="image/jpeg,image/png,application/pdf">
                        <button type="button" class="upload-pod-btn" style="padding: 4px 8px; font-size: 12px; min-width: 60px;">Upload POD</button>
                        <div class="upload-status" style="display: none;"></div>
                    </form>
                </td>
            </tr>
            <?php
        }
    } else {
        echo '<tr><td colspan="8" style="text-align: center; padding: 20px;">No waybills requiring POD</td></tr>';
    }
    
    $html = ob_get_clean();
    wp_send_json_success($html);
}

// Add helper function to generate overdue waybills table content
function include_overdue_waybills_table() {
    global $wpdb;
    $table = $wpdb->prefix . 'waybills';
    
    // Updated query to order by Date_Created instead of Action_Date
    $overdue_waybills = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT *, 
            DATEDIFF(CURDATE(), Action_Date) as days_overdue 
            FROM $table 
            WHERE Action_Date < CURDATE() 
            AND (pod_image IS NULL OR pod_image = '')
            ORDER BY Date_Created DESC, id DESC"  // Changed from Action_Date to Date_Created
        )
    );

    if ($overdue_waybills) {
        foreach ($overdue_waybills as $waybill) {
            $days_overdue = intval($waybill->days_overdue);
            $row_color = $days_overdue > 4 ? '#ffebee' : ($days_overdue > 1 ? '#fff3e0' : '');
            ?>
            <tr id="waybill-row-<?php echo esc_attr($waybill->waybill_number); ?>" style="background-color: <?php echo $row_color; ?>">
            <td style="border: 1px solid #ddd; padding: 8px; white-space: nowrap;"><?php echo esc_html($waybill->Date_Created); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px; white-space: nowrap;"><?php echo esc_html($waybill->Action_Date); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?php echo esc_html($waybill->waybill_number); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?php echo esc_html($waybill->Client_Reference); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?php echo esc_html($waybill->Type_Of_Service); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?php echo esc_html($waybill->Sender_Name); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?php echo esc_html($waybill->Reciver_Name); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px; color: <?php echo $days_overdue > 4 ? '#d32f2f' : '#ff6d00'; ?>">
                    <?php echo $days_overdue; ?> days
                </td>
                <td style="border: 1px solid #ddd; padding: 8px; min-width: 150px;"><?php echo render_delivery_status_field($waybill, isset($_SESSION['waybill_is_admin']) && $_SESSION['waybill_is_admin']); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px; min-width: 150px;">
                    <input type="text" name="invoice_no_<?php echo esc_attr($waybill->id); ?>" value="<?php echo esc_attr($waybill->Invoice_No); ?>" style="width: 100%; padding: 5px; border: 1px solid #ddd; border-radius: 4px;" />
                </td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                    <div class="pod-upload-section">
                        <!-- POD Preview Area -->
                        <div id="pod-preview-<?php echo esc_attr($waybill->waybill_number); ?>" class="pod-preview" style="display: none; margin-bottom: 10px;">
                            <img src="" alt="POD Preview" style="max-width: 200px;">
                        </div>
                        
                        <!-- Upload Form -->
                        <form class="pod-upload-form" style="display: inline-block;">
                            <input type="hidden" name="waybill_number" value="<?php echo esc_attr($waybill->waybill_number); ?>">
                            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('upload_pod_image'); ?>">
                            <input type="file" class="pod-upload" accept="image/jpeg,image/jpg,image/png,application/pdf" style="display: none;">
                            <button type="button" onclick="triggerFileSelect(this)" class="upload-pod-btn" style="background-color: #4CAF50; color: white; width: 120px; padding: 6px 0; border: none; border-radius: 4px; cursor: pointer; line-height: 1.5; font-size: 14px;">
                                Upload POD
                            </button>
                        </form>

                    </div>
                </td>
            </tr>
            <?php
        }
    } else {
        echo '<tr><td colspan="7" style="text-align: center; padding: 20px;">No overdue waybills found</td></tr>';
    }
}

// Add AJAX handler for generating new waybill number
add_action('wp_ajax_generate_new_waybill_number', 'handle_generate_new_waybill_number');
add_action('wp_ajax_nopriv_generate_new_waybill_number', 'handle_generate_new_waybill_number');

function handle_generate_new_waybill_number() {
    check_ajax_referer('waybill_nonce', 'nonce');
    $new_number = generate_waybill_number();
    wp_send_json_success($new_number);
}

// Add AJAX handler for saving waybill
function handle_save_waybill_ajax() {
    try {
        check_ajax_referer('waybill_nonce', 'nonce');

        global $wpdb;
        $table = $wpdb->prefix . 'waybills';

        // Check if waybill already exists
        $waybill_number = sanitize_text_field($_POST['waybill_number']);
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE waybill_number = %s",
            $waybill_number
        ));
        
        if ($exists) {
            // If it exists, still return success but with existing=true flag
            wp_send_json_success(array(
                'waybill_number' => $waybill_number,
                'existing' => true,
                'message' => 'Waybill saved successfully'
            ));
            return;
        }

        // Prepare location data
        $sender_location = implode(', ', array_filter([
            sanitize_text_field($_POST['sender_country'] ?? ''),
            sanitize_text_field($_POST['sender_state'] ?? ''),
            sanitize_text_field($_POST['sender_city'] ?? '')
        ]));

        $receiver_location = implode(', ', array_filter([
            sanitize_text_field($_POST['receiver_country'] ?? ''),
            sanitize_text_field($_POST['receiver_state'] ?? ''),
            sanitize_text_field($_POST['receiver_city'] ?? '')
        ]));

        // Fix typo in sanitize_text_field
        $waybill_data = array(
            'waybill_number' => $waybill_number,
            'Date_Created' => current_time('Y-m-d'),
            'Allocated_to' => sanitize_text_field($_POST['allocated_to']),
            'Movement_Type' => sanitize_text_field($_POST['movement_type']),
            'Client_Reference' => sanitize_text_field($_POST['client_reference']),
            'Action_Date' => sanitize_text_field($_POST['action_date']),
            'Sender_Name' => sanitize_text_field($_POST['sender_name']),
            'Sender_Customer_Name' => sanitize_text_field($_POST['sender_customer_name']),
            'Sender_Address' => sanitize_text_field($_POST['sender_address_1']),
            'Sender_Telephone' => sanitize_text_field($_POST['sender_telephone']), // Fixed typo here
            'Sender_Country_State_City' => $sender_location,
            'Reciver_Name' => sanitize_text_field($_POST['receiver_name']),
            'Reciver_Customer_Name' => sanitize_text_field($_POST['receiver_customer_name']),
            'Reciver_Address' => sanitize_text_field($_POST['receiver_address_1']),
            'Reciver_Telephone' => sanitize_text_field($_POST['receiver_telephone']),
            'Reciver_Country_State_City' => $receiver_location,
            'Type_Of_Service' => sanitize_text_field($_POST['service_type']),
            'Special_Instructions' => sanitize_text_field($_POST['special_instructions']),
            'Total_Pieces' => intval($_POST['total_pieces']),
            'total_cartins' => intval($_POST['total_cartins']),
            'Volume_Mass' => floatval($_POST['volume_mass']),
            'Actual_Mass' => floatval($_POST['actual_mass']),
            'description_of_goods' => implode('; ', array_filter(array_map('sanitize_text_field', $_POST['description'] ?? []))),
            'Delivery' => sanitize_text_field($_POST['delivery'] ?? ''),
            'Invoice_No' => sanitize_text_field($_POST['invoice_no'] ?? '')
        );

        // Start transaction
        $wpdb->query('START TRANSACTION');

        // Insert waybill data
        $result = $wpdb->insert($table, $waybill_data);

        if ($result === false) {
            $wpdb->query('ROLLBACK');
            error_log('Waybill Save Error: ' . $wpdb->last_error);
            wp_send_json_error('Database error: ' . $wpdb->last_error);
            return;
        }

        $wpdb->query('COMMIT');
        wp_send_json_success(array(
            'waybill_number' => $waybill_number,
            'message' => 'Waybill saved successfully'
        ));

    } catch (Exception $e) {
        if (isset($wpdb)) {
            $wpdb->query('ROLLBACK');
        }
        error_log('Waybill Save Error: ' . $e->getMessage());
        wp_send_json_error($e->getMessage());
    }
}

// Update the localization of the AJAX data
function waybill_enqueue_scripts() {
    // ...existing code...
    
    wp_localize_script('waybill-manager', 'waybill_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('waybill_nonce'),
        'plugin_url' => plugins_url('', __FILE__)
    ));
}

// Add AJAX handler for saving waybill
add_action('wp_ajax_save_waybill', 'handle_save_waybill_ajax');
add_action('wp_ajax_nopriv_save_waybill', 'handle_save_waybill_ajax');

// Add this new function to handle API key settings
function waybill_manager_add_settings_page() {
    add_options_page(
        'Waybill Manager Settings',
        'Waybill Manager',
        'manage_options',
        'waybill-manager-settings',
        'waybill_manager_settings_page'
    );
}
add_action('admin_menu', 'waybill_manager_add_settings_page');

function waybill_manager_settings_page() {
    if (isset($_POST['google_maps_api_key'])) {
        update_option('waybill_google_maps_api_key', sanitize_text_field($_POST['google_maps_api_key']));
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }
    
    $api_key = get_option('waybill_google_maps_api_key', '');
    ?>
    <div class="wrap">
        <h2>Waybill Manager Settings</h2>
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th scope="row">Google Maps API Key</th>
                    <td>
                        <input type="text" name="google_maps_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text">
                        <p class="description">Enter your Google Maps API key. <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">Get an API key</a></p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Add this new function at the end of the file
function get_delivery_status_options() {
    return array(
        '' => 'Select Status',
        'Collected' => 'Collected',
        'In-Transit-Linehaul' => 'In-Transit-Linehaul',
        'On Delivery-Last Mile' => 'On Delivery-Last Mile',
        'Delivered' => 'Delivered',
        'Duplicate' => 'Duplicate'
    );
}

function render_delivery_status_field($waybill, $is_admin = false) {
    $delivery_options = get_delivery_status_options();
    
    if ($is_admin) {
        return sprintf(
            '<select class="delivery-status-select" data-waybill="%s" style="width: 100%%; padding: 5px; border: 1px solid #ddd; border-radius: 3px;">%s</select>',
            esc_attr($waybill->waybill_number),
            implode('', array_map(function($value, $label) use ($waybill) {
                return sprintf(
                    '<option value="%s" %s>%s</option>',
                    esc_attr($value),
                    selected($waybill->Delivery, $value, false),
                    esc_html($label)
                );
            }, array_keys($delivery_options), $delivery_options))
        );
    } else {
        $current_status = isset($delivery_options[$waybill->Delivery]) ? $delivery_options[$waybill->Delivery] : 'Not Set';
        return sprintf(
            '<div class="delivery-status-readonly" style="padding: 5px;">%s</div>',
            esc_html($current_status)
        );
    }
}

// Add new AJAX handler for updating delivery status
add_action('wp_ajax_update_delivery_status', 'handle_update_delivery_status');
add_action('wp_ajax_nopriv_update_delivery_status', 'handle_update_delivery_status');

function handle_update_delivery_status() {
    check_ajax_referer('waybill_nonce', 'nonce');
    
    // Check if user is admin
    if (!isset($_SESSION['waybill_is_admin']) || !$_SESSION['waybill_is_admin']) {
        wp_send_json_error('Unauthorized: Only administrators can update delivery status');
        return;
    }
    
    global $wpdb;
    $table = $wpdb->prefix . 'waybills';
    
    $waybill_number = sanitize_text_field($_POST['waybill_number']);
    $delivery_status = sanitize_text_field($_POST['delivery_status']);
    
    $result = $wpdb->update(
        $table,
        array('Delivery' => $delivery_status),
        array('waybill_number' => $waybill_number),
        array('%s'),
        array('%s')
    );
    
    if ($result !== false) {
        wp_send_json_success(array(
            'message' => 'Delivery status updated successfully',
            'status' => $delivery_status,
            'status_text' => get_delivery_status_options()[$delivery_status] ?? 'Not Set'
        ));
    } else {
        wp_send_json_error('Failed to update delivery status');
    }
}

// Add this JavaScript to the footer
add_action('wp_footer', function() {
    if (!isset($_SESSION['waybill_is_admin']) || !$_SESSION['waybill_is_admin']) {
        return; // Don't add JS for non-admin users
    }
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Handle delivery status changes
        $(document).on('change', '.delivery-status-select', function() {
            const select = $(this);
            const waybillNumber = select.data('waybill');
            const newStatus = select.val();
            
            // Show loading state
            select.prop('disabled', true);
            
            // Send AJAX request
            $.ajax({
                url: waybill_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'update_delivery_status',
                    nonce: waybill_ajax.nonce,
                    waybill_number: waybillNumber,
                    delivery_status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        // Flash green background briefly
                        select.css('background-color', '#d4edda')
                              .delay(1000)
                              .queue(function(next) {
                                  $(this).css('background-color', '');
                                  next();
                              });
                    } else {
                        // Flash red background briefly
                        select.css('background-color', '#f8d7da')
                              .delay(1000)
                              .queue(function(next) {
                                  $(this).css('background-color', '');
                                  next();
                              });
                        // Revert to previous value
                        select.val(select.find('option[selected]').val());
                        alert(response.data || 'Failed to update delivery status');
                    }
                },
                error: function() {
                    // Flash red background briefly
                    select.css('background-color', '#f8d7da')
                          .delay(1000)
                          .queue(function(next) {
                              $(this).css('background-color', '');
                              next();
                          });
                    // Revert to previous value
                    select.val(select.find('option[selected]').val());
                    alert('Error connecting to server');
                },
                complete: function() {
                    select.prop('disabled', false);
                }
            });
        });
    });
    </script>
    <?php
});


