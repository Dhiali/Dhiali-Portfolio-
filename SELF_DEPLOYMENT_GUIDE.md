# How to Deploy This Project by Yourself

This guide will help you deploy this portfolio project for your own use. Whether you want to use it as a template for your portfolio or contribute to the project, follow these steps.

## Prerequisites

Before you start, make sure you have:

- **Git** installed on your computer ([Download Git](https://git-scm.com/downloads))
- **Node.js** (version 18 or higher) installed ([Download Node.js](https://nodejs.org/))
- A **GitHub account** ([Sign up here](https://github.com/join))
- Basic command line knowledge

To verify your installations:
```bash
git --version
node --version
npm --version
```

## Option 1: Deploy Your Own Version (Recommended for Personal Use)

Follow these steps to create your own portfolio using this project as a template:

### Step 1: Fork the Repository

1. Go to https://github.com/Dhiali/Dhiali-Portfolio-
2. Click the **Fork** button in the top-right corner
3. Select your GitHub account as the destination
4. Wait for GitHub to create your fork

### Step 2: Clone Your Fork

```bash
# Replace YOUR-USERNAME with your GitHub username
git clone https://github.com/YOUR-USERNAME/Dhiali-Portfolio-.git
cd Dhiali-Portfolio-
```

### Step 3: Install Dependencies

```bash
npm install
```

This will install all required packages (React, Vite, Tailwind CSS, etc.)

### Step 4: Customize for Your Repository

1. **Update `package.json`:**
   - Change the `homepage` field to match your repository:
   ```json
   "homepage": "https://YOUR-USERNAME.github.io/YOUR-REPO-NAME"
   ```

2. **Update `vite.config.js`:**
   - Change the `base` field to match your repository name:
   ```javascript
   base: '/YOUR-REPO-NAME',
   ```

3. **Customize the content:**
   - Edit files in `src/` to add your personal information
   - Replace images in `public/` with your own
   - Update portfolio items, skills, and contact information

### Step 5: Test Locally

Before deploying, test the site on your local machine:

```bash
npm run dev
```

Open your browser to `http://localhost:5173` to preview the site.

### Step 6: Configure GitHub Pages

1. Go to your forked repository on GitHub
2. Click **Settings** (top navigation bar)
3. Scroll down and click **Pages** (left sidebar)
4. Under "Build and deployment":
   - **Source**: Select **GitHub Actions**
5. Save the settings

### Step 7: Deploy

```bash
# Commit your changes
git add .
git commit -m "Customize portfolio for my use"

# Push to GitHub
git push origin main
```

GitHub Actions will automatically:
- Build your site
- Deploy to GitHub Pages
- Your site will be live at `https://YOUR-USERNAME.github.io/YOUR-REPO-NAME/` in 2-3 minutes

### Step 8: Verify Deployment

1. Go to your repository on GitHub
2. Click the **Actions** tab
3. Watch the deployment workflow complete (green checkmark = success)
4. Visit your live site: `https://YOUR-USERNAME.github.io/YOUR-REPO-NAME/`

## Option 2: Deploy to Other Platforms

### Deploy to Netlify

1. Sign up at [Netlify](https://www.netlify.com/)
2. Click "New site from Git"
3. Connect your GitHub account and select your repository
4. Configure build settings:
   - **Build command**: `npm run build`
   - **Publish directory**: `dist`
5. Click "Deploy site"

Your site will be live at a Netlify URL (e.g., `your-site-name.netlify.app`)

### Deploy to Vercel

1. Sign up at [Vercel](https://vercel.com/)
2. Click "New Project"
3. Import your GitHub repository
4. Vercel will auto-detect Vite settings
5. Click "Deploy"

Your site will be live at a Vercel URL (e.g., `your-project.vercel.app`)

### Deploy to Custom Domain

After deploying to GitHub Pages, Netlify, or Vercel, you can connect a custom domain:

**For GitHub Pages:**
1. Go to Settings → Pages
2. Under "Custom domain", enter your domain
3. Add DNS records as instructed by GitHub

**For Netlify/Vercel:**
- Follow their custom domain setup guides in your project settings

## Option 3: Contributing to the Original Project

If you want to contribute improvements to this project:

### Step 1: Fork and Clone

```bash
# Fork on GitHub first, then:
git clone https://github.com/YOUR-USERNAME/Dhiali-Portfolio-.git
cd Dhiali-Portfolio-
```

### Step 2: Create a Branch

```bash
git checkout -b feature/your-feature-name
```

### Step 3: Make Changes and Test

```bash
npm install
npm run dev

# Make your changes
# Test thoroughly
npm run build
```

### Step 4: Submit a Pull Request

```bash
git add .
git commit -m "Description of your changes"
git push origin feature/your-feature-name
```

Then go to GitHub and create a pull request from your branch.

## Development Workflow

### Local Development

```bash
# Start development server with hot reload
npm run dev
```

Access at `http://localhost:5173`

### Build for Production

```bash
# Create optimized production build
npm run build
```

Output will be in the `dist/` directory

### Preview Production Build

```bash
# After building, preview the production version
npm run preview
```

## Troubleshooting

### Issue: Site Shows 404 or Blank Page

**Solution:**
- Verify `vite.config.js` has the correct `base` path matching your repository name
- Check that GitHub Pages is set to "GitHub Actions" in Settings → Pages
- Clear browser cache (Ctrl+Shift+R or Cmd+Shift+R)

### Issue: "Could not resolve host: github.com"

**Solution:**
- This happens with `npm run deploy` command in restricted networks
- Use the GitHub Actions method instead (push to main branch)
- The workflow doesn't require direct GitHub access from your machine

### Issue: GitHub Actions Workflow Fails

**Solution:**
1. Go to the Actions tab in your repository
2. Click on the failed workflow to see error details
3. Common fixes:
   - Ensure `package-lock.json` is committed
   - Verify all dependencies are in `package.json`
   - Check Node.js version compatibility

### Issue: Changes Not Appearing on Live Site

**Solution:**
- Wait 2-3 minutes after deployment completes
- Check Actions tab for deployment status
- Clear browser cache
- Verify you're visiting the correct URL

### Issue: Build Fails Locally

**Solution:**
```bash
# Clean installation
rm -rf node_modules package-lock.json
npm install

# Try building again
npm run build
```

### Issue: Permission Denied When Pushing

**Solution:**
- Ensure you have write access to the repository
- Set up SSH keys or use personal access token
- Check your Git credentials: `git config user.name` and `git config user.email`

## Additional Resources

- **React Documentation**: https://react.dev/
- **Vite Documentation**: https://vite.dev/
- **Tailwind CSS**: https://tailwindcss.com/docs
- **GitHub Pages**: https://docs.github.com/en/pages
- **GitHub Actions**: https://docs.github.com/en/actions

## Project Structure

```
Dhiali-Portfolio-/
├── .github/
│   └── workflows/
│       └── deploy.yml          # GitHub Actions deployment workflow
├── public/                     # Static assets (images, etc.)
├── src/
│   ├── components/            # React components
│   ├── App.jsx               # Main application component
│   └── main.jsx              # Application entry point
├── package.json              # Dependencies and scripts
├── vite.config.js           # Vite configuration
├── tailwind.config.js       # Tailwind CSS configuration
└── index.html               # HTML template

```

## Need Help?

If you encounter issues not covered in this guide:

1. Check the [original repository](https://github.com/Dhiali/Dhiali-Portfolio-) for updates
2. Review existing issues and discussions
3. Create a new issue with details about your problem

## License

Make sure to check the repository's LICENSE file before using this project for your own purposes.

---

**Quick Start Summary:**
1. Fork the repository on GitHub
2. Clone your fork: `git clone https://github.com/YOUR-USERNAME/Dhiali-Portfolio-.git`
3. Install: `npm install`
4. Customize: Update `package.json` and `vite.config.js` with your info
5. Configure: Set GitHub Pages to "GitHub Actions" in repository settings
6. Deploy: `git push origin main`
7. Visit: `https://YOUR-USERNAME.github.io/YOUR-REPO-NAME/`

🎉 Your portfolio is now live!
