import { useRef } from 'react';
import { motion, useScroll, useTransform } from 'motion/react';

const categories = [
  {
    id: '1',
    title: 'UX',
    subtitle: 'DESIGN',
    image: 'https://images.unsplash.com/photo-1521391406205-4a6af174a4c2?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxVWCUyMGRlc2lnbmVyJTIwd29ya3NwYWNlfGVufDF8fHx8MTc1OTkwMjQ5Mnww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    index: '01',
    route: 'ux-work',
    accentColor: '#D32F2F',
  },
  {
    id: '2',
    title: 'INTERACTIVE',
    subtitle: 'DEVELOPMENT',
    image: 'https://images.unsplash.com/photo-1593086784152-b060f8109e0c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxkZXZlbG9wZXIlMjBjb2RpbmclMjBzY3JlZW58ZW58MXx8fHwxNzU5OTAyNDkyfDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',

    index: '02',
    route: 'interactive-dev',
    accentColor: '#FFB300',
  },
  {
    id: '3',
    title: 'CLIENT',
    subtitle: 'WORK',
    image: 'https://images.unsplash.com/photo-1688818228656-cccc0a933b30?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxhcnRpc3RpYyUyMGNyZWF0aXZlJTIwd29ya3xlbnwxfHx8fDE3NTk5MDI0OTN8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
  
    index: '03',
    route: 'personal-work',
    accentColor: '#00897B',
  },
];

function CategoryCover({ category, onNavigate }) {
  const containerRef = useRef(null);
  const { scrollYProgress } = useScroll({
    target: containerRef,
    offset: ["start end", "end start"]
  });

  const scale = useTransform(scrollYProgress, [0, 0.5, 1], [0.95, 1, 0.95]);
  const opacity = useTransform(scrollYProgress, [0, 0.2, 0.8, 1], [0, 1, 1, 0]);

  return (
    <motion.div
      ref={containerRef}
      className="relative h-screen w-full cursor-pointer overflow-hidden"
      style={{ opacity }}
      onClick={() => onNavigate(category.route)}
    >
      {/* Background Image */}
      <motion.div
        className="absolute inset-0"
        style={{ scale }}
      >
        <div className="absolute inset-0 bg-black/60 z-10" />
        <img
          src={category.image}
          alt={category.title}
          className="w-full h-full object-cover grayscale"
        />
      </motion.div>

      {/* Corner Labels */}
     

      <div className="absolute top-2 right-2 z-30 sm:top-6 sm:right-6">
        <motion.div
          className="text-foreground/80 tracking-[0.3em] text-[9px] sm:text-xs"
          initial={{ opacity: 0, x: 20 }}
          whileInView={{ opacity: 1, x: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.8, delay: 0.2 }}
        >
          {category.label}
        </motion.div>
      </div>

    

      {/* Rotating Circular Badge - Top Right */}
      <div className="absolute top-8 right-8 z-30 hidden sm:block">
        <motion.div
          className="relative w-20 h-20 sm:w-28 sm:h-28 lg:w-32 lg:h-32"
          animate={{ rotate: 360 }}
          transition={{ duration: 20, repeat: Infinity, ease: "linear" }}
        >
          <svg viewBox="0 0 100 100" className="w-full h-full">
            <defs>
              <path
                id={`circlePath-${category.id}`}
                d="M 50, 50 m -37, 0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0"
              />
            </defs>
            <text className="text-[7px] sm:text-[8px] fill-foreground tracking-[0.3em]">
              <textPath href={`#circlePath-${category.id}`}>
                VIEW PROJECTS • EXPLORE • VIEW PROJECTS • EXPLORE •
              </textPath>
            </text>
          </svg>
          <div
            className="absolute inset-0 flex items-center justify-center border-2 border-foreground/30 rounded-full"
            style={{ borderColor: category.accentColor }}
          >
            <span className="tracking-wider" style={{ color: category.accentColor }}>
              {category.index}
            </span>
          </div>
        </motion.div>
      </div>

      {/* Rotating Circular Badge - Bottom Right */}
      <div className="absolute bottom-8 right-8 z-30 hidden lg:block">
        <motion.div
          className="relative w-16 h-16 lg:w-24 lg:h-24"
          animate={{ rotate: -360 }}
          transition={{ duration: 15, repeat: Infinity, ease: "linear" }}
        >
          <svg viewBox="0 0 100 100" className="w-full h-full">
            <defs>
              <path
                id={`circlePath2-${category.id}`}
                d="M 50, 50 m -37, 0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0"
              />
            </defs>
            <text className="text-[6px] sm:text-[7px] fill-foreground tracking-[0.3em]">
              <textPath href={`#circlePath2-${category.id}`}>
                CLICK TO EXPLORE • CLICK TO EXPLORE •
              </textPath>
            </text>
          </svg>
        </motion.div>
      </div>

      {/* Main Content */}
      <div className="absolute inset-0 z-20 flex items-center justify-center px-2 sm:px-4">
        <div className="relative w-full max-w-7xl mx-auto flex flex-col items-center justify-center">
          {/* Large Title */}
          <motion.div
            className="relative w-full"
            initial={{ opacity: 0, y: 50 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 1, ease: [0.6, 0.01, 0.05, 0.95] }}
          >
            <h2
              className="text-[13vw] sm:text-[10vw] lg:text-[7vw] tracking-tighter leading-[0.85] text-center break-words"
              style={{
                fontWeight: 900,
                WebkitTextStroke: '2px rgba(245, 230, 211, 0.8)',
                WebkitTextFillColor: 'transparent',
                textShadow: `0 0 40px ${category.accentColor}40`,
                wordBreak: 'break-word',
                overflowWrap: 'break-word',
              }}
            >
              {category.title}
            </h2>
            <h3
              className="text-[8vw] sm:text-[6vw] lg:text-[4vw] tracking-tighter leading-[0.85] text-center mt-2 break-words"
              style={{
                fontWeight: 900,
                color: category.accentColor,
                wordBreak: 'break-word',
                overflowWrap: 'break-word',
              }}
            >
              {category.subtitle}
            </h3>
          </motion.div>

          {/* Description Text Overlay */}
          <motion.div
            className="relative w-full max-w-xs sm:max-w-md lg:max-w-lg mx-auto mt-4"
            initial={{ opacity: 0 }}
            whileInView={{ opacity: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 1, delay: 0.3 }}
          >
            <p
              className="text-[10px] sm:text-xs lg:text-sm leading-tight tracking-wide text-foreground/70 text-justify break-words"
              style={{ wordBreak: 'break-word', overflowWrap: 'break-word' }}
            >
              {category.description}
            </p>
          </motion.div>
        </div>
      </div>

      {/* Decorative Colored Block */}
      <motion.div
        className="absolute z-20 w-24 h-32 sm:w-40 sm:h-56 lg:w-48 lg:h-64"
        style={{ 
          backgroundColor: category.accentColor,
          opacity: 0.85,
        }}
        initial={{ x: -100, y: -100, opacity: 0 }}
        whileInView={{ x: 0, y: 0, opacity: 0.85 }}
        viewport={{ once: true }}
        transition={{ duration: 1.2, delay: 0.5, ease: [0.6, 0.01, 0.05, 0.95] }}
        animate={{
          x: [0, 10, 0],
          y: [0, -10, 0],
        }}
        whileHover={{
          scale: 1.05,
          rotate: 2,
        }}
      />

      {/* Hover Overlay */}
      <motion.div
        className="absolute inset-0 z-[25] bg-black/0 hover:bg-black/20 transition-all duration-500"
        whileHover={{ backgroundColor: 'rgba(0, 0, 0, 0.2)' }}
      />
    </motion.div>
  );
}

export function Work({ onNavigate }) {
  return (
    <section
      id="work"
      className="relative bg-background pt-24 sm:pt-32 md:pt-40 lg:pt-48"
    >
      {categories.map((category) => (
        <CategoryCover 
          key={category.id} 
          category={category}
          onNavigate={onNavigate}
        />
      ))}
    </section>
  );
}


