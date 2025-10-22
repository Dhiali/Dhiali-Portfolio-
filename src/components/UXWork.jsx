import { motion } from 'motion/react';
import { ArrowLeft } from 'lucide-react';

export function UXWork({ onNavigate }) {
  return (
    <div className="min-h-screen bg-background">
      {/* Back button */}
      <div className="fixed top-8 left-8 z-50">
        <motion.button
          onClick={() => onNavigate('home')}
          className="group flex items-center gap-2 px-4 py-2 border border-primary/50 hover:border-primary hover:bg-primary/10 transition-all duration-300"
          whileHover={{ scale: 1.05 }}
          whileTap={{ scale: 0.95 }}
        >
          <ArrowLeft className="w-4 h-4" />
          <span className="text-sm tracking-wider">BACK</span>
        </motion.button>
      </div>

      {/* 3-Column Editorial Magazine Layout */}
      <section className="min-h-screen">
        <div className="grid grid-cols-1 lg:grid-cols-[2fr_1.5fr_2fr] min-h-screen">
          {/* LEFT Column - Background Image */}
          <motion.div 
            className="relative overflow-hidden min-h-[40vh] lg:min-h-screen"
            initial={{ opacity: 0, x: -50 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 1, ease: [0.6, 0.01, 0.05, 0.95] }}
          >
            <div 
              className="absolute inset-0 bg-cover bg-center"
              style={{ 
                backgroundImage: `url('https)`,
                filter: 'grayscale(100%)',
              }}
            />
            <div className="absolute inset-0 bg-black/30" />
          </motion.div>

          {/* MIDDLE Column - Date & Name Block */}
          <motion.div 
            className="relative bg-[#f5e6d3] flex flex-col justify-between p-8 sm:p-12 lg:p-16 min-h-[50vh] lg:min-h-screen"
            initial={{ opacity: 0, y: 50 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 1, delay: 0.2, ease: [0.6, 0.01, 0.05, 0.95] }}
          >
            {/* Date Range */}
            <div>
              <motion.h2 
                className="text-[14vw] sm:text-[10vw] lg:text-[6vw] xl:text-[5vw] tracking-tight text-[#0a0a0a] leading-[0.9]"
                style={{ fontFamily: "'Bebas Neue', sans-serif" }}
                initial={{ opacity: 0, y: 30 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 1, delay: 0.4 }}
              >
                2024-
                <br />
                PRESENT
              </motion.h2>
              
              <motion.p 
                className="mt-6 text-[5vw] sm:text-[3vw] lg:text-[2vw] xl:text-[1.5vw] tracking-wider text-[#0a0a0a]"
                style={{ fontFamily: "'Bebas Neue', sans-serif" }}
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 1, delay: 0.6 }}
              >
                DHIALI CHETTY
              </motion.p>
            </div>

            {/* Rotating Circle Badge */}
            <motion.div 
              className="relative w-32 h-32 sm:w-40 sm:h-40 lg:w-48 lg:h-48 xl:w-56 xl:h-56 mt-auto"
              initial={{ opacity: 0, scale: 0.8 }}
              animate={{ opacity: 1, scale: 1 }}
              transition={{ duration: 1, delay: 0.8 }}
            >
              {/* Rotating text circle */}
              <motion.div
                className="absolute inset-0"
                animate={{ rotate: 360 }}
                transition={{ duration: 20, repeat: Infinity, ease: "linear" }}
              >
                <svg viewBox="0 0 200 200" className="w-full h-full">
                  <defs>
                    <path
                      id="circlePath"
                      d="M 100, 100 m -80, 0 a 80,80 0 1,1 160,0 a 80,80 0 1,1 -160,0"
                    />
                  </defs>
                  <text className="text-[14px] tracking-[0.3em] fill-[#0a0a0a]" style={{ fontFamily: "'Bebas Neue', sans-serif" }}>
                    <textPath href="#circlePath" startOffset="0%">
                      EXPLORE MORE • EXPLORE MORE • 
                    </textPath>
                  </text>
                </svg>
              </motion.div>
              
              {/* Center number */}
              <div className="absolute inset-0 flex items-center justify-center">
                <span className="text-[3.5rem] sm:text-[4.5rem] lg:text-[5rem] text-[#0a0a0a]" style={{ fontFamily: "'Bebas Neue', sans-serif" }}>
                  01
                </span>
              </div>
            </motion.div>
          </motion.div>

          {/* RIGHT Column - Image with Content Overlay */}
          <motion.div 
            className="relative overflow-hidden min-h-[50vh] lg:min-h-screen"
            initial={{ opacity: 0, x: 50 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 1, delay: 0.4, ease: [0.6, 0.01, 0.05, 0.95] }}
          >
            {/* Background Image */}
            <div 
              className="absolute inset-0 bg-cover bg-center"
              style={{ 
                backgroundImage: `url('https)`,
                filter: 'grayscale(100%)',
              }}
            />
            
            {/* Dark Overlay */}
            <div className="absolute inset-0 bg-black/50" />

            {/* Content Overlay */}
            <div className="relative z-10 h-full flex flex-col justify-between p-8 sm:p-12 lg:p-16">
              {/* Section Title - Top */}
              <motion.div 
                className="ml-auto text-right max-w-xl"
                initial={{ opacity: 0, y: -30 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 1, delay: 0.6 }}
              >
                <h1 
                  className="text-[14vw] sm:text-[10vw] lg:text-[8vw] xl:text-[6vw] leading-[0.85] tracking-tight"
                  style={{ 
                    fontFamily: "'Bebas Neue', sans-serif",
                    WebkitTextStroke: '2px #f5e6d3',
                    WebkitTextFillColor: 'transparent',
                    textShadow: '0 0 40px rgba(0,0,0,0.5)',
                  }}
                >
                  UX<br/>DESIGN
                </h1>
              </motion.div>

              {/* Description - Bottom */}
              <motion.div
                className="bg-[#f5e6d3] p-6 sm:p-8 lg:p-10 max-w-md ml-auto"
                initial={{ opacity: 0, y: 30 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 1, delay: 0.8 }}
              >
                <p className="text-[#0a0a0a] text-base sm:text-lg leading-relaxed">
                  User-centered design solutions blending traditional Indian aesthetics with modern digital experiences. Creating intuitive interfaces that honor cultural heritage while pushing boundaries.
                </p>
              </motion.div>

              {/* Corner Label - Bottom Right */}
              <motion.div 
                className="absolute bottom-8 right-8 sm:bottom-12 sm:right-12 lg:bottom-16 lg:right-16"
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                transition={{ duration: 1, delay: 1 }}
              >
                <p 
                  className="text-[#f5e6d3] text-sm tracking-[0.3em]"
                  style={{ fontFamily: "'Bebas Neue', sans-serif" }}
                >
                  UX DESIGN
                </p>
              </motion.div>
            </div>
          </motion.div>
        </div>
      </section>

      {/* Projects Section */}
      <section className="min-h-screen flex items-center justify-center">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <motion.div
            className="text-center border border-border/50 p-16"
            initial={{ opacity: 0 }}
            whileInView={{ opacity: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 1 }}
          >
            <p className="text-muted-foreground tracking-wide">
              UX Design projects coming soon...
            </p>
          </motion.div>
        </div>
      </section>
    </div>
  );
}


