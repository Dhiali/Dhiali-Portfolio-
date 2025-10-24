import { useState, useEffect } from 'react';
import { motion, useScroll, useTransform, AnimatePresence } from 'motion/react';

export function Header() {
  const [menuOpen, setMenuOpen] = useState(false);
  const { scrollY } = useScroll();
  
  const backgroundColor = useTransform(
    scrollY,
    [0, 100],
    ['rgba(10, 10, 10, 0)', 'rgba(10, 10, 10, 0.95)']
  );

  // Prevent scroll when menu is open
  useEffect(() => {
    if (menuOpen) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = 'unset';
    }
    return () => {
      document.body.style.overflow = 'unset';
    };
  }, [menuOpen]);

  const scrollToSection = (id) => {
    const element = document.getElementById(id);
    if (element) {
      element.scrollIntoView({ behavior: 'smooth' });
      setMenuOpen(false);
    }
  };

  const menuItems = [
    { id: 'work', label: 'WORK' },
    { id: 'about', label: 'ABOUT' },
    { id: 'contact', label: 'CONTACT' },
  ];

  return (
    <>
      <motion.header
        className="fixed top-0 left-0 right-0 z-50 backdrop-blur-md border-b border-border/20"
        style={{ backgroundColor }}
      >
        <nav className="container mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between h-20">
            <motion.button 
              onClick={() => scrollToSection('hero')}
              className="hover:opacity-70 transition-opacity tracking-wider text-xl sm:text-2xl"
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
              style={{ 
                fontFamily: "'Bebas Neue', sans-serif",
                fontWeight: 400,
                letterSpacing: '0.05em'
              }}
            >
              DHIALI CHETTY
            </motion.button>

            {/* Creative Hamburger Button */}
            <motion.button
              onClick={() => setMenuOpen(!menuOpen)}
              className="relative w-12 h-12 flex items-center justify-center group"
              whileHover={{ scale: 1.1 }}
              whileTap={{ scale: 0.9 }}
              aria-label="Toggle menu"
            >
              <div className="w-8 h-6 flex flex-col justify-between">
                <motion.span
                  className="w-full h-0.5 bg-foreground origin-center"
                  animate={{
                    rotate: menuOpen ? 45 : 0,
                    y: menuOpen ? 10 : 0,
                    backgroundColor: menuOpen ? '#D32F2F' : '#f5e6d3',
                  }}
                  transition={{ duration: 0.3, ease: [0.6, 0.01, 0.05, 0.95] }}
                />
                <motion.span
                  className="w-full h-0.5 bg-foreground"
                  animate={{
                    opacity: menuOpen ? 0 : 1,
                    x: menuOpen ? -20 : 0,
                  }}
                  transition={{ duration: 0.2 }}
                />
                <motion.span
                  className="w-full h-0.5 bg-foreground origin-center"
                  animate={{
                    rotate: menuOpen ? -45 : 0,
                    y: menuOpen ? -10 : 0,
                    backgroundColor: menuOpen ? '#D32F2F' : '#f5e6d3',
                  }}
                  transition={{ duration: 0.3, ease: [0.6, 0.01, 0.05, 0.95] }}
                />
              </div>
            </motion.button>
          </div>
        </nav>
      </motion.header>

      {/* Full-screen Menu Overlay */}
      <AnimatePresence>
        {menuOpen && (
          <motion.div
            className="fixed inset-0 z-40 bg-background"
            initial={{ clipPath: 'circle(0% at 100% 0%)' }}
            animate={{ clipPath: 'circle(150% at 100% 0%)' }}
            exit={{ clipPath: 'circle(0% at 100% 0%)' }}
            transition={{ duration: 0.8, ease: [0.6, 0.01, 0.05, 0.95] }}
          >
            {/* Decorative background pattern */}
            <div className="absolute inset-0 opacity-5">
              <div
                className="absolute inset-0 bg-repeat"
                style={{
                  backgroundImage: `radial-gradient(circle, var(--primary) 2px, transparent 2px)`,
                  backgroundSize: '60px 60px',
                }}
              />
            </div>

            {/* Menu content */}
            <div className="relative h-full flex flex-col items-center justify-center px-4">
              <nav className="space-y-8 sm:space-y-12">
                {menuItems.map((item, index) => (
                  <motion.div
                    key={item.id}
                    initial={{ opacity: 0, y: 50 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0, y: 50 }}
                    transition={{ 
                      duration: 0.5, 
                      delay: 0.1 + index * 0.1,
                      ease: [0.6, 0.01, 0.05, 0.95]
                    }}
                  >
                    <motion.button
                      onClick={() => scrollToSection(item.id)}
                      className="block text-[15vw] sm:text-[12vw] md:text-[10vw] tracking-tighter leading-none hover:text-primary transition-colors duration-300"
                      style={{ 
                        fontWeight: 700,
                      }}
                      whileHover={{ 
                        x: 20,
                        transition: { duration: 0.3 }
                      }}
                    >
                      {item.label}
                    </motion.button>
                  </motion.div>
                ))}
              </nav>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </>
  );
}


