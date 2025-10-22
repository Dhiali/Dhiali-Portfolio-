import { motion, useScroll, useTransform } from "motion/react";
import { useRef } from "react";
import portraitImage from "../assets/ff41236483b62c93e9ac9e99cbec7b3f27170eba.png";

export function Hero() {
  const containerRef = useRef(null);
  const { scrollYProgress } = useScroll({
    target: containerRef,
    offset: ["start start", "end start"],
  });

  const opacity = useTransform(
    scrollYProgress,
    [0, 0.5],
    [1, 0],
  );
  const scale = useTransform(
    scrollYProgress,
    [0, 0.5],
    [1, 0.8],
  );
  const imageY = useTransform(
    scrollYProgress,
    [0, 1],
    [0, 100],
  );

  return (<section
      ref={containerRef}
      className="relative h-screen flex items-center justify-center overflow-hidden bg-background"
    >
      {/* Animated background pattern */}
      <div className="absolute inset-0 opacity-5">
        <div
          className="absolute inset-0 bg-repeat"
          style={{
            backgroundImage: `radial-gradient(circle, var(--primary) 2px, transparent 2px)`,
            backgroundSize: "60px 60px",
          }}
        />
      </div>

      {/* Main content */}
      <motion.div
        className="relative z-10 w-full h-full flex items-center justify-center px-4 sm:px-6 lg:px-8"
        style={{ opacity, scale }}
      >
        <div className="relative w-full max-w-7xl mx-auto">
          {/* Container for stacked text and image */}
          <div className="relative flex items-center justify-center">
            {/* Center Image - Behind text */}
            <motion.div
              className="absolute left-[52%] top-[36%] -translate-x-1/2 -translate-y-1/2 z-0"
              initial={{ opacity: 0, scale: 0.8 }}
              animate={{ opacity: 1, scale: 1 }}
              transition={{
                duration: 1,
                delay: 0.5,
                ease: [0.6, 0.01, 0.05, 0.95],
              }}
              style={{ y: imageY }}
            >
              <div className="relative w-[80vw] xs:w-[70vw] sm:w-[60vw] md:w-[50vw] lg:w-[40vw] xl:w-[35vw] max-w-[647px]">
                {/* Image container with aspect ratio (647) */}
                <div className="relative aspect-[647/445] overflow-hidden border-2 border-primary/30">
                  {/* Texture overlay */}
                  <div
                    className="absolute inset-0 z-10 pointer-events-none mix-blend-overlay opacity-20"
                    style={{
                      backgroundImage: `url("data)' /%3E%3C/svg%3E")`,
                    }}
                  />

                  <motion.div
                    className="w-full h-full"
                    whileHover={{ scale: 1.05 }}
                    transition={{ duration: 0.4 }}
                  >
                    <img
                      src={portraitImage}
                      alt="Dhiali Chetty"
                      className="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500"
                      style={{ objectPosition: "40% center" }}
                    />
                  </motion.div>

                  {/* Corner accent */}
                  <div className="absolute top-0 right-0 w-16 h-16 border-t-2 border-r-2 border-primary" />
                </div>
              </div>
            </motion.div>

            {/* PORTFOLIO Text - Overlaid on top */}
            <div className="relative z-10 flex items-center justify-center w-full">
              <motion.h1
                className="text-[25vw] xs:text-[22vw] sm:text-[20vw] md:text-[18vw] lg:text-[16vw] xl:text-[14vw] tracking-tighter leading-none"
                style={{
                  fontWeight: 700,
                  mixBlendMode: "difference",
                }}
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                transition={{
                  duration: 1,
                  delay: 0.3,
                  ease: [0.6, 0.01, 0.05, 0.95],
                }}
              >
                <motion.span
                  className="inline-block"
                  initial={{ x: -100 }}
                  animate={{ x: 0 }}
                  transition={{
                    duration: 1,
                    delay: 0.3,
                    ease: [0.6, 0.01, 0.05, 0.95],
                  }}
                >
                  PORT
                </motion.span>
                <motion.span
                  className="inline-block italic"
                  initial={{ x: 100 }}
                  animate={{ x: 0 }}
                  transition={{
                    duration: 1,
                    delay: 0.3,
                    ease: [0.6, 0.01, 0.05, 0.95],
                  }}
                  style={{
                    marginLeft: "-0.05em",
                  }}
                >
                  FOLIO
                </motion.span>
              </motion.h1>
            </div>
          </div>

          {/* Date Range */}
          <motion.div
            className="absolute -bottom-12 sm:-bottom-16 md:-bottom-20 left-1/2 -translate-x-1/2 whitespace-nowrap"
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 1, delay: 0.8 }}
          >
            <p
              className="text-base tracking-[0.3em] text-muted-foreground"
              style={{ fontWeight: 400 }}
            >
              2024 — PRESENT
            </p>
          </motion.div>
        </div>
      </motion.div>

      {/* Scroll indicator */}
      <motion.div
        className="absolute bottom-8 left-1/2 -translate-x-1/2"
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
        transition={{ duration: 1, delay: 1.5 }}
      >
        <motion.div
          animate={{
            y: [0, 12, 0],
          }}
          transition={{
            duration: 1.5,
            repeat: Infinity,
            ease: "easeInOut",
          }}
          className="text-primary text-sm tracking-widest"
        >
          SCROLL
        </motion.div>
      </motion.div>
    </section>
  );
}

