import { motion, AnimatePresence } from 'motion/react';
import { useState, useEffect } from 'react';
import { Monitor } from 'lucide-react';

export function SplashScreen({ onComplete }) {
  const [dismissed, setDismissed] = useState(false);
  const [progress, setProgress] = useState(0);

  useEffect(() => {
    // Simulate loading progress
    const progressInterval = setInterval(() => {
      setProgress(prev => {
        if (prev >= 100) {
          clearInterval(progressInterval);
          return 100;
        }
        return prev + 2;
      });
    }, 60);

    // Auto-dismiss after 3.5 seconds
    const autoTimer = setTimeout(() => {
      handleDismiss();
    }, 3500);

    return () => {
      clearInterval(progressInterval);
      clearTimeout(autoTimer);
    };
  }, []);

  const handleDismiss = () => {
    setDismissed(true);
    setTimeout(() => {
      if (onComplete) onComplete();
    }, 600);
  };

  return (
    <AnimatePresence>
      {!dismissed && (
        <motion.div
          className="fixed inset-0 z-[9999] flex items-center justify-center bg-background overflow-hidden"
          initial={{ opacity: 1 }}
          exit={{ opacity: 0 }}
          transition={{ duration: 0.6, ease: [0.6, 0.01, 0.05, 0.95] }}
        >
          {/* Background pattern */}
          <motion.div
            className="absolute inset-0 opacity-5"
            initial={{ scale: 0.8, opacity: 0 }}
            animate={{ scale: 1, opacity: 0.05 }}
            transition={{ duration: 1.5, ease: 'easeOut' }}
          >
            <div
              className="absolute inset-0 bg-repeat"
              style={{
                backgroundImage: `radial-gradient(circle, var(--primary) 2px, transparent 2px)`,
                backgroundSize: '60px 60px',
              }}
            />
          </motion.div>

          {/* Main content */}
          <div className="relative z-10 max-w-2xl mx-auto px-6 text-center">
            {/* Icon */}
            <motion.div
              className="flex items-center justify-center mb-8"
              initial={{ scale: 0, rotate: -180 }}
              animate={{ scale: 1, rotate: 0 }}
              transition={{ 
                duration: 1, 
                delay: 0.5, 
                ease: [0.6, 0.01, 0.05, 0.95],
                type: "spring",
                stiffness: 200
              }}
            >
              <div className="relative">
                <div className="relative bg-primary/10 p-8 rounded-full border-2 border-primary">
                  <Monitor className="w-16 h-16 text-primary" strokeWidth={1.5} />
                </div>
              </div>
            </motion.div>

            {/* Main heading */}
            <motion.div
              className="mb-6 overflow-hidden"
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              transition={{ duration: 0.8, delay: 0.8 }}
            >
              <motion.h1
                className="text-2xl xs:text-3xl sm:text-4xl md:text-5xl lg:text-6xl tracking-tighter leading-tight"
                style={{ fontWeight: 700 }}
                initial={{ y: 100 }}
                animate={{ y: 0 }}
                transition={{ duration: 1, delay: 0.8, ease: [0.6, 0.01, 0.05, 0.95] }}
              >
                BEST VIEWED ON LAPTOP OR DESKTOP
              </motion.h1>
            </motion.div>

            {/* Description */}
            <motion.p
              className="text-base sm:text-lg text-muted-foreground mb-12 max-w-md mx-auto"
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8, delay: 1.5 }}
            >
              This portfolio features advanced animations and interactions optimized for larger screens
            </motion.p>

            {/* Loading Progress Bar */}
            <motion.div
              className="w-full max-w-xs mx-auto mb-8"
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              transition={{ duration: 0.5, delay: 2 }}
            >
              <div className="h-1 bg-muted rounded-full overflow-hidden">
                <motion.div
                  className="h-full bg-primary"
                  style={{ width: `${progress}%` }}
                  transition={{ duration: 0.1 }}
                />
              </div>
              <p className="text-xs text-muted-foreground mt-2 text-center">
                {Math.round(progress)}%
              </p>
            </motion.div>

            {/* CTA Button */}
            <motion.button
              onClick={handleDismiss}
              className="group relative px-8 py-4 bg-primary text-primary-foreground tracking-wider overflow-hidden rounded-lg"
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8, delay: 1.7 }}
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
            >
              <span className="relative z-10">SKIP INTRO</span>
            </motion.button>
          </div>
        </motion.div>
      )}
    </AnimatePresence>
  );
}


