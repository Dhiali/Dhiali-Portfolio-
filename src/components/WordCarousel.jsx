import { motion } from 'motion/react';

export function WordCarousel({ words, direction = 'left', speed = 50 }) {
  const text = words.join(' • ');
  const repeatedText = `${text} • ${text} • ${text} • ${text} • ${text} • ${text}`;
  
  // Calculate animation values based on direction and speed
  const animationDirection = direction === 'left' ? -1 : 1;
  const duration = (100 / speed) * 8; // Adjusted for smoother animation

  return (
    <div className="relative overflow-hidden py-8 border-y border-border/30">
      <motion.div
        className="flex whitespace-nowrap"
        animate={{
          x: [0, animationDirection * -50 + '%']
        }}
        transition={{
          duration: duration,
          repeat: Infinity,
          ease: 'linear',
          repeatType: 'loop'
        }}
      >
        <span className="text-4xl xs:text-5xl sm:text-6xl md:text-7xl lg:text-8xl xl:text-9xl font-bold tracking-tighter text-muted-foreground/20 select-none">
          {repeatedText}
        </span>
      </motion.div>
      
      {/* Gradient overlays for smooth edges */}
      <div className="absolute left-0 top-0 w-32 h-full bg-gradient-to-r from-background to-transparent z-10 pointer-events-none" />
      <div className="absolute right-0 top-0 w-32 h-full bg-gradient-to-l from-background to-transparent z-10 pointer-events-none" />
    </div>
  );
}


