import { motion } from 'motion/react';

export function Footer() {
  const currentYear = new Date().getFullYear();

  return (
    <footer className="relative bg-background border-t border-border/30 py-8 sm:py-12">
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8">
          {/* Brand Section */}
          <motion.div
            className="space-y-4"
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
          >
            <h3 className="text-xl sm:text-2xl font-bold tracking-wider">DHIALI CHETTY</h3>
            <p className="text-muted-foreground text-xs sm:text-sm leading-relaxed">
              UX Designer & Frontend Developer specializing in cultural design and digital innovation.
            </p>
          </motion.div>

          {/* Quick Links */}
          <motion.div
            className="space-y-4"
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, delay: 0.1 }}
          >
            <h4 className="font-semibold tracking-wide">EXPLORE</h4>
            <ul className="space-y-2 text-muted-foreground text-sm">
              <li><a href="#work" className="hover:text-primary transition-colors">Work</a></li>
              <li><a href="#about" className="hover:text-primary transition-colors">About</a></li>
              <li><a href="#contact" className="hover:text-primary transition-colors">Contact</a></li>
            </ul>
          </motion.div>

          {/* Contact Info */}
          <motion.div
            className="space-y-4"
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, delay: 0.2 }}
          >
            <h4 className="font-semibold tracking-wide">CONNECT</h4>
            <ul className="space-y-2 text-muted-foreground text-sm">
              <li>
                <a 
                  href="mailto:hello@dhiali.com" 
                  className="hover:text-primary transition-colors"
                >
                  hello@dhiali.com
                </a>
              </li>
              <li>
                <a 
                  href="https://instagram.com/dhaanyaaaaaa" 
                  target="_blank" 
                  rel="noopener noreferrer"
                  className="hover:text-primary transition-colors"
                >
                  @dhiali
                </a>
              </li>
            </ul>
          </motion.div>
        </div>

        {/* Bottom Bar */}
        <motion.div
          className="mt-12 pt-8 border-t border-border/30 flex flex-col sm:flex-row justify-between items-center gap-4"
          initial={{ opacity: 0 }}
          whileInView={{ opacity: 1 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6, delay: 0.3 }}
        >
          <p className="text-muted-foreground text-xs">
            © {currentYear} Dhiali Chetty. All rights reserved.
          </p>
          <p className="text-muted-foreground text-xs tracking-wider">
            DESIGNED & DEVELOPED WITH ❤️
          </p>
        </motion.div>
      </div>
    </footer>
  );
}


