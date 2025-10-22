import { motion, useScroll, useTransform } from 'motion/react';
import { useRef } from 'react';

export function About() {
  const containerRef = useRef(null);
  const { scrollYProgress } = useScroll({
    target);

  const y = useTransform(scrollYProgress, [0, 1], [100, -100]);
  const scale = useTransform(scrollYProgress, [0, 0.5, 1], [0.8, 1, 0.8]);

  return (<section
      id="about"
      ref={containerRef}
      className="relative min-h-screen flex items-center justify-center overflow-hidden bg-accent"
    >
      {/* Decorative elements */}
      <div className="absolute inset-0 opacity-10">
        <div
          className="absolute inset-0 bg-repeat"
          style={{
            backgroundImage)`,
            backgroundSize: '40px 40px',
          }}
        />
      </div>

      <motion.div 
        className="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8"
        style={{ y }}
      >
        <motion.div
          style={{ scale }}
          className="max-w-5xl mx-auto"
        >
          <motion.h2
            className="text-[12vw] sm:text-[10vw] tracking-tighter leading-[0.85] text-accent-foreground mb-12"
            style={{
              fontWeight: 700,
            }}
            initial={{ opacity: 0, y: 80 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, amount: 0.3 }}
            transition={{ duration: 1, ease: [0.6, 0.01, 0.05, 0.95] }}
          >
            ABOUT
            <br />
            <span className="text-secondary">ME</span>
          </motion.h2>

          <motion.div
            className="space-y-8"
            initial={{ opacity: 0, y: 50 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, amount: 0.3 }}
            transition={{ duration: 1, delay: 0.2 }}
          >
            <p 
              className="text-[4vw] sm:text-[2vw] lg:text-[1.5vw] text-accent-foreground/90 tracking-wide leading-relaxed"
              style={{ fontWeight: 400 }}
            >
              I'm a UX designer and developer who believes that the best digital experiences
              are rooted in cultural authenticity and human connection.
            </p>

            <p 
              className="text-[4vw] sm:text-[2vw] lg:text-[1.5vw] text-accent-foreground/90 tracking-wide leading-relaxed"
              style={{ fontWeight: 400 }}
            >
              My work celebrates my Indian heritage through bold colors, spiritual motifs,
              and design that tells stories. From UX research to interactive development,
              I create digital spaces that honor tradition while embracing innovation.
            </p>

            <motion.div
              className="pt-8 flex flex-wrap gap-4"
              initial={{ opacity: 0 }}
              whileInView={{ opacity: 1 }}
              viewport={{ once: true, amount: 0.3 }}
              transition={{ duration: 1, delay: 0.4 }}
            >
              {['UX Design', 'Frontend Development', 'Design Systems', 'Creative Coding'].map((skill) => (<span 
                  key={skill}
                  className="px-6 py-3 bg-secondary text-secondary-foreground tracking-wider"
                  style={{ fontWeight))}
            </motion.div>
          </motion.div>
        </motion.div>
      </motion.div>
    </section>
  );
}


