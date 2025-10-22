import { motion, useScroll, useTransform } from 'motion/react';
import { useRef } from 'react';
import { Mail, Instagram, Linkedin, FileText, Palette, Facebook } from 'lucide-react';

export function Contact() {
  const containerRef = useRef(null);
  const { scrollYProgress } = useScroll({
    target: containerRef,
    offset: ["start end", "end start"]
  });

  const scale = useTransform(scrollYProgress, [0, 0.5, 1], [0.8, 1, 1]);

  return (<section
      id="contact"
      ref={containerRef}
      className="relative min-h-screen flex items-center justify-center overflow-hidden bg-background"
    >
      {/* Decorative background */}
      <div className="absolute inset-0 opacity-5">
        <div
          className="absolute inset-0 bg-repeat"
          style={{
            backgroundImage: `radial-gradient(circle, var(--primary) 2px, transparent 2px)`,
            backgroundSize: '60px 60px',
          }}
        />
      </div>

      <motion.div 
        className="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8 text-center"
        style={{ scale }}
      >
        <motion.div
          initial={{ opacity: 0 }}
          whileInView={{ opacity: 1 }}
          viewport={{ once: true, amount: 0.3 }}
          transition={{ duration: 1 }}
        >
          <motion.h2
            className="text-[15vw] xs:text-[12vw] sm:text-[10vw] md:text-[8vw] lg:text-[6vw] tracking-tighter leading-[0.85] mb-8 sm:mb-12"
            style={{
              fontWeight: 700,
            }}
            initial={{ opacity: 0, y: 80 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, amount: 0.3 }}
            transition={{ duration: 1, delay: 0.2, ease: [0.6, 0.01, 0.05, 0.95] }}
          >
            LET'S
            <br />
            <span className="text-primary">CONNECT</span>
          </motion.h2>

          <motion.p
            className="text-base sm:text-lg md:text-xl lg:text-2xl text-muted-foreground mb-12 sm:mb-16 tracking-wide max-w-3xl mx-auto px-4"
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, amount: 0.3 }}
            transition={{ duration: 1, delay: 0.4 }}
            style={{ fontWeight: 400 }}
          >
            Ready to create something meaningful together?
            <br />
            Get in touch and let's make it happen.
          </motion.p>

          <motion.div
            className="flex flex-col sm:flex-row gap-8 justify-center items-center mb-16"
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, amount: 0.3 }}
            transition={{ duration: 1, delay: 0.6 }}
          >
            <motion.a
              href="mailto:hello@dhaanya.com"
              className="group relative px-12 py-6 bg-primary text-primary-foreground overflow-hidden"
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
            >
              <span className="relative z-10 tracking-wider text-xl" style={{ fontWeight: 500 }}>
                EMAIL ME
              </span>
              <motion.div
                className="absolute inset-0 bg-secondary"
                initial={{ x: '-100%' }}
                whileHover={{ x: 0 }}
                transition={{ duration: 0.3 }}
              />
              <span className="absolute inset-0 flex items-center justify-center z-20 opacity-0 group-hover:opacity-100 transition-opacity text-secondary-foreground tracking-wider text-xl" style={{ fontWeight: 500 }}>
                EMAIL ME
              </span>
            </motion.a>
          </motion.div>

          <motion.div
            className="flex flex-wrap gap-6 sm:gap-8 justify-center"
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, amount: 0.3 }}
            transition={{ duration: 1, delay: 0.8 }}
          >
            <motion.a
              href="https://instagram.com/dhaanyaaaaaa"
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center gap-3 text-muted-foreground hover:text-primary transition-colors group"
              whileHover={{ scale: 1.1 }}
              whileTap={{ scale: 0.95 }}
            >
              <Instagram className="w-8 h-8" />
              <span className="tracking-wider" style={{ fontWeight: 500 }}>INSTAGRAM</span>
            </motion.a>

            <motion.a
              href="https://linkedin.com/in/dhaanya"
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center gap-3 text-muted-foreground hover:text-primary transition-colors group"
              whileHover={{ scale: 1.1 }}
              whileTap={{ scale: 0.95 }}
            >
              <Linkedin className="w-8 h-8" />
              <span className="tracking-wider" style={{ fontWeight: 500 }}>LINKEDIN</span>
            </motion.a>

            <motion.a
              href="/path-to-cv.pdf"
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center gap-3 text-muted-foreground hover:text-primary transition-colors group"
              whileHover={{ scale: 1.1 }}
              whileTap={{ scale: 0.95 }}
            >
              <FileText className="w-8 h-8" />
              <span className="tracking-wider" style={{ fontWeight: 500 }}>CV</span>
            </motion.a>

            <motion.a
              href="https://behance.net/dhaanya"
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center gap-3 text-muted-foreground hover:text-primary transition-colors group"
              whileHover={{ scale: 1.1 }}
              whileTap={{ scale: 0.95 }}
            >
              <Palette className="w-8 h-8" />
              <span className="tracking-wider" style={{ fontWeight: 500 }}>BEHANCE</span>
            </motion.a>

            <motion.a
              href="https://facebook.com/dhaanya"
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center gap-3 text-muted-foreground hover:text-primary transition-colors group"
              whileHover={{ scale: 1.1 }}
              whileTap={{ scale: 0.95 }}
            >
              <Facebook className="w-8 h-8" />
              <span className="tracking-wider" style={{ fontWeight: 500 }}>FACEBOOK</span>
            </motion.a>
          </motion.div>
        </motion.div>
      </motion.div>
    </section>
  );
}


