import { motion, useScroll, useTransform } from 'motion/react';
import { useRef } from 'react';

// Import all collage images
import clothsImg from "../assets/cloths .jpg";
import cultureImg from "../assets/culture.jpg";
import familyImg from "../assets/family .jpg";
import family2Img from "../assets/family2.jpg";
import filmImg from "../assets/film .jpg";
import jortsImg from "../assets/jorts .jpg";
import makingClothsImg from "../assets/making cloths .jpg";
import outdoorsImg from "../assets/outdoors.jpg";
import photoImg from "../assets/photo.jpg";
import photoshootImg from "../assets/photoshoot.jpg";
import streetware4Img from "../assets/steetware4.jpg";
import streetware2Img from "../assets/streetware 2.jpg";
import streetwareImg from "../assets/streetware.jpg";
import streetware3Img from "../assets/streetware3.jpg";
import teamworkImg from "../assets/teamwork.jpg";

// Collage images array with positioning and animation data
const collageImages = [
  { src: clothsImg, x: 15, y: 5, rotation: -8, scale: 0.7, delay: 0.1, zIndex: 1 },
  { src: cultureImg, x: 70, y: 10, rotation: 12, scale: 0.8, delay: 0.3, zIndex: 2 },
  { src: familyImg, x: 25, y: 25, rotation: -15, scale: 0.6, delay: 0.5, zIndex: 3 },
  { src: family2Img, x: 57, y: 30, rotation: 10, scale: 0.9, delay: 0.7, zIndex: 1 },
  { src: filmImg, x: 10, y: 45, rotation: 18, scale: 0.5, delay: 0.9, zIndex: 2 },
  { src: jortsImg, x: 85, y: 50, rotation: -12, scale: 0.7, delay: 1.1, zIndex: 3 },
  { src: makingClothsImg, x: 35, y: 55, rotation: 8, scale: 0.6, delay: 1.3, zIndex: 1 },
  { src: outdoorsImg, x: 5, y: 20, rotation: -20, scale: 0.8, delay: 1.5, zIndex: 2 },
  { src: photoImg, x: 60, y: 60, rotation: 15, scale: 0.5, delay: 1.7, zIndex: 3 },
  { src: photoshootImg, x: 90, y: 2, rotation: -10, scale: 0.6, delay: 1.9, zIndex: 1 },
  { src: streetware4Img, x: 20, y: 65, rotation: 25, scale: 0.7, delay: 2.1, zIndex: 2 },
  { src: streetware2Img, x: 75, y: 58, rotation: -5, scale: 0.6, delay: 2.3, zIndex: 3 },
  { src: streetwareImg, x: 50, y: 6, rotation: 12, scale: 1, delay: 2.5, zIndex: 1 },
  { src: streetware3Img, x: 95, y: 18, rotation: -18, scale: 0.7, delay: 2.7, zIndex: 2 },
  { src: teamworkImg, x: 90, y: 78, rotation: 8, scale: 0.6, delay: 2.9, zIndex: 3 },
];

export function PromiseLand() {
  const containerRef = useRef(null);
  const { scrollYProgress } = useScroll({
    target: containerRef,
    offset: ["start end", "end start"],
  });

  // Parallax effects for different image layers
  const parallax1 = useTransform(scrollYProgress, [0, 1], [0, -100]);
  const parallax2 = useTransform(scrollYProgress, [0, 1], [0, 50]);
  const parallax3 = useTransform(scrollYProgress, [0, 1], [0, -150]);
  const parallax4 = useTransform(scrollYProgress, [0, 1], [0, 75]);

  return (
    <section ref={containerRef} className="relative min-h-screen bg-background overflow-hidden">
      {/* Full width intro section */}
      <div className="relative min-h-screen">
        {/* Dynamic Collage Background */}
        <div className="absolute inset-0 z-0">
          {collageImages.map((img, index) => {
            // Apply different parallax effects based on zIndex for depth
            const parallaxY = img.zIndex === 1 ? parallax1 : 
                             img.zIndex === 2 ? parallax2 : 
                             img.zIndex === 3 ? parallax3 : parallax4;
            
            return (
              <motion.div
                key={index}
                className="absolute w-40 h-40 sm:w-48 sm:h-48 md:w-56 md:h-56 lg:w-64 lg:h-64 xl:w-72 xl:h-72 overflow-hidden"
                style={{
                  left: `${img.x}%`,
                  top: `${img.y}%`,
                  transform: `translate(-50%, -50%) rotate(${img.rotation}deg)`,
                  y: parallaxY,
                  zIndex: -img.zIndex,
                }}
                initial={{ 
                  opacity: 0, 
                  scale: 0,
                  rotate: img.rotation + 360
                }}
                whileInView={{ 
                  opacity: 0.8, 
                  scale: img.scale,
                  rotate: img.rotation
                }}
                viewport={{ once: true }}
                transition={{
                  duration: 1.5,
                  delay: img.delay,
                  ease: [0.6, 0.01, 0.05, 0.95],
                }}
                whileHover={{ 
                  scale: img.scale * 1.15, 
                  rotate: img.rotation + 10,
                  opacity: 1,
                  zIndex: -1,
                  transition: { duration: 0.4 }
                }}
              >
                <motion.img
                  src={img.src}
                  alt=""
                  className="w-full h-full object-cover transition-all duration-500 border-2 border-primary/20 shadow-lg"
                  style={{
                    filter: "grayscale(70%) contrast(1.2) brightness(0.9)",
                  }}
                  whileHover={{ 
                    filter: "grayscale(0%) contrast(1.3) brightness(1.1)",
                    transition: { duration: 0.4 }
                  }}
                />
                {/* Artistic overlay for depth and cohesion */}
                <div 
                  className="absolute inset-0 pointer-events-none transition-opacity duration-500"
                  style={{
                    background: `linear-gradient(${45 + img.rotation}deg, 
                      rgba(245, 230, 211, 0.1) 0%, 
                      transparent 50%, 
                      rgba(139, 69, 19, 0.1) 100%)`,
                    mixBlendMode: "overlay"
                  }}
                />
              </motion.div>
            );
          })}
        </div>
        
        {/* Enhanced Overlay gradient for better text readability */}
        <div className="absolute inset-0 bg-gradient-to-br from-background/30 via-background/10 to-background/50" />
        <div className="absolute inset-0 bg-gradient-to-r from-transparent via-background/20 to-background/60" />

        {/* Animated background pattern overlay */}
        <div className="absolute inset-0 opacity-10">
          <motion.div
            className="absolute inset-0 bg-repeat"
            style={{
              backgroundImage: `radial-gradient(circle, var(--primary) 1px, transparent 1px)`,
              backgroundSize: "40px 40px",
            }}
            animate={{
              backgroundPosition: ["0px 0px", "40px 40px"],
            }}
            transition={{
              duration: 20,
              repeat: Infinity,
              ease: "linear",
            }}
          />
        </div>

        {/* Content overlay - positioned to the right */}
        <div className="absolute inset-0 flex items-center justify-center sm:justify-end p-4 sm:p-8 lg:p-16 z-10">
          <motion.div
            className="max-w-lg lg:max-w-xl text-center sm:text-right w-full sm:w-auto"
            initial={{ opacity: 0, x: 30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.8, delay: 0.2 }}
          >
            {/* Section title with outlined text effect */}
            <h2 
              className="text-[8vw] xs:text-[7vw] sm:text-[6vw] md:text-[5vw] lg:text-[4vw] xl:text-[3.5vw] leading-tight mb-4 lg:mb-8"
              style={{ 
                fontFamily: "'Bebas Neue', sans-serif",
                WebkitTextStroke: '1px #f5e6d3',
                WebkitTextFillColor: 'transparent',
                textStroke: '1px #f5e6d3',
                color: 'transparent',
              }}
            >
              I CRAFT VISUAL EXPERIENCES BECAUSE THAT'S WHERE CULTURE LIVES. I BELIEVE IN THE UNIQUE POWER OF FRESH PERSPECTIVES TO CREATE WORK THAT TRULY CONNECTS.
            </h2>

            {/* Three quote blocks - Left, Center, Right */}
            <div className="w-full mb-6 lg:mb-12">
              <div className=" flex flex-row flex items-center justify-center gap-2 sm:gap-3 lg:gap-4 w-full">
                {/* Left Block */}
                <div className="flex-1 bg-primary-foreground/95 backdrop-blur-sm p-4 sm:p-6 lg:p-8 aspect-square flex items-center justify-center">
                  <div className="text-background text-xs sm:text-sm lg:text-base leading-relaxed text-center w-full">
                    Digital Designer Working for Culture,<br/> Not Brands.
                  </div>
                </div>
                
                {/* Center Block */}
                <div className="flex-1 bg-primary-foreground/95 backdrop-blur-sm p-4 sm:p-6 lg:p-8 aspect-square flex items-center justify-center">
                  <div className="text-background text-xs sm:text-sm lg:text-base leading-relaxed text-center w-full">
                    Human-Focused Creative: Connecting Culture,<br/> Design and Events.
                  </div>
                </div>
                
                {/* Right Block */}
                <div className="flex-1 bg-primary-foreground/95 backdrop-blur-sm p-4 sm:p-6 lg:p-8 aspect-square flex items-center justify-center">
                  <div className="text-background text-xs sm:text-sm lg:text-base leading-relaxed text-center w-full">
                    Bringing Cultural Stories to Life <br /> Through Digital Design & Photography.
                  </div>
                </div>
              </div>
            </div>

            

            {/* Corner label */}
            <div className="mt-4 text-primary-foreground text-xs lg:text-sm tracking-widest" style={{ fontFamily: "'Bebas Neue', sans-serif" }}>
              
            </div>
          </motion.div>
        </div>
      </div>

      {/* Personal About Section */}
      <div id="about" className="relative bg-background py-16 sm:py-20 lg:py-24">
        <div className="max-w-4xl mx-auto px-4 sm:px-8 lg:px-16">
          <motion.div
            className="text-center"
            initial={{ opacity: 0, y: 50 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.8, delay: 0.2 }}
          >
            {/* Section Header */}
            <motion.h3 
              className="text-2xl sm:text-3xl lg:text-4xl xl:text-5xl mb-8 lg:mb-12 text-primary-foreground"
              style={{ fontFamily: "'Bebas Neue', sans-serif" }}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.6, delay: 0.1 }}
            >
              ⟡ ABOUT ME
            </motion.h3>

            {/* Personal Paragraph */}
            <motion.div
              className="prose prose-lg lg:prose-xl mx-auto"
              initial={{ opacity: 0, y: 40 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.8, delay: 0.3 }}
            >
              <div className="text-primary-foreground/90 leading-relaxed text-base sm:text-lg lg:text-xl xl:text-2xl max-w-3xl mx-auto space-y-6">
                {/* Replace this placeholder text with your personal story */}
                <p>
                  My journey as a creator started with my hands, sewing and designing my own clothes and has evolved into a passion for crafting digital experiences. At 19, I channelled this energy into founding <span className="font-bold text-xl sm:text-2xl lg:text-3xl text-primary-foreground" style={{ fontFamily: "'Bebas Neue', sans-serif" }}>dhialidigitaldesigns</span>, a testament to my belief in building things that matter. This hands-on background, from designing streetwear for friends and family to directing a photoshoot to creating systems for companies, has taught me a fundamental truth: <span className="font-bold text-xl sm:text-2xl lg:text-3xl text-primary-foreground" style={{ fontFamily: "'Bebas Neue', sans-serif" }}>I can solve any problem, anywhere.</span> 
                  The medium changes but the creative process remains the same.
                </p>

                <p>
                  I am electrified by human-focused work. The pulse of a festival, the story of an event, the soul of music. I want to see new faces every week and draw inspiration from the world around me, especially from the rich tapestry of my own culture and others. This is my greatest strength. I don't work for brands; I work for culture.
                  I also believe that <span className="font-bold text-xl sm:text-2xl lg:text-3xl text-primary-foreground" style={{ fontFamily: "'Bebas Neue', sans-serif" }}>a fresh perspective is a superpower.</span> There are certain traits and values that a lack of experience can bring, the fearless innovation and untainted curiosity that experience itself sometimes can't. I see it not as a shortcoming but as my greatest opportunity to create work that is truly unique.
                </p>
              </div>
            </motion.div>

            {/* Optional decorative element */}
            <motion.div
              className="mt-12 flex justify-center"
              initial={{ opacity: 0, scale: 0.8 }}
              whileInView={{ opacity: 1, scale: 1 }}
              viewport={{ once: true }}
              transition={{ duration: 0.6, delay: 0.5 }}
            >
              <div className="w-16 h-px bg-primary-foreground/30"></div>
            </motion.div>
          </motion.div>
        </div>
      </div>
    </section>
  );
}


