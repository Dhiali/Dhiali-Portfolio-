import { motion } from 'motion/react';

export function PromiseLand() {
  return (
    <section className="relative min-h-screen bg-background overflow-hidden">
      {/* Full width intro section */}
      <div className="relative min-h-screen">
        {/* Background Image with grayscale */}
        <div 
          className="absolute inset-0 bg-cover bg-center"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2340&q=80')`,
            filter: 'grayscale(100%)',
          }}
        />
        
        {/* Overlay gradient */}
        <div className="absolute inset-0 bg-gradient-to-b from-background/20 via-transparent to-background/40" />

        {/* Content overlay - positioned to the right */}
        <div className="absolute inset-0 flex items-center justify-center sm:justify-end p-4 sm:p-8 lg:p-16">
          <motion.div
            className="max-w-md text-center sm:text-right w-full sm:w-auto"
            initial={{ opacity: 0, x: 30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.8, delay: 0.2 }}
          >
            {/* Section title with outlined text effect */}
            <h2 
              className="text-[18vw] xs:text-[15vw] sm:text-[12vw] md:text-[10vw] lg:text-[8vw] xl:text-[7vw] leading-none mb-4 lg:mb-8"
              style={{ 
                fontFamily: "'Bebas Neue', sans-serif",
                WebkitTextStroke: '2px #f5e6d3',
                WebkitTextFillColor: 'transparent',
                textStroke: '2px #f5e6d3',
                color: 'transparent',
              }}
            >
              INTRO<br/>DUCTION
            </h2>

            {/* Paragraph text */}
            <div className="bg-primary-foreground/95 backdrop-blur-sm p-4 sm:p-6 lg:p-8">
              <p className="text-background text-xs sm:text-sm lg:text-base leading-relaxed">
                I'm Dhiali Chetty, a UX designer and creative developer who bridges the gap between traditional Indian heritage and modern digital innovation. My work celebrates bold colors, spiritual motifs, and cultural storytelling through immersive web experiences.
              </p>
            </div>

            {/* Corner label */}
            <div className="mt-4 text-primary-foreground text-xs lg:text-sm tracking-widest" style={{ fontFamily: "'Bebas Neue', sans-serif" }}>
              ⟡ ABOUT ME
            </div>
          </motion.div>
        </div>
      </div>
    </section>
  );
}


