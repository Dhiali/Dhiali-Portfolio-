import { motion, useScroll, useTransform } from 'motion/react';
import { useRef, useState } from 'react';

// Import all collage images
import clothsImg from "../assets/cloths .jpg";
import cultureImg from "../assets/culture.jpg";
import familyImg from "../assets/family .jpg";
import teamworkImg from "../assets/teamwork.jpg";
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
import family2Img from "../assets/family2.jpg";



// Images array for horizontal scrollable container
const images = [
  { src: clothsImg, alt: "Cloths" },
  { src: cultureImg, alt: "Culture" },
  { src: familyImg, alt: "Family" },
  { src: teamworkImg, alt: "Teamwork" },
  { src: filmImg, alt: "Film" },
  { src: jortsImg, alt: "Jorts" },
  { src: makingClothsImg, alt: "Making Cloths" },
  { src: outdoorsImg, alt: "Outdoors" },
  { src: photoImg, alt: "Photo" },
  { src: photoshootImg, alt: "Photoshoot" },
  { src: streetware4Img, alt: "Streetware 4" },
  { src: streetware2Img, alt: "Streetware 2" },
  { src: streetwareImg, alt: "Streetware" },
  { src: streetware3Img, alt: "Streetware 3" },
  { src: family2Img, alt: "Family 2"},
];

export function PromiseLand() {
  const containerRef = useRef(null);
  const scrollContainerRef = useRef(null);
  const [isDragging, setIsDragging] = useState(false);
  const [startX, setStartX] = useState(0);
  const [scrollLeft, setScrollLeft] = useState(0);
  
  const { scrollYProgress } = useScroll({
    target: containerRef,
    offset: ["start end", "end start"],
  });

  // Drag handlers for horizontal scroll
  const handleMouseDown = (e) => {
    setIsDragging(true);
    setStartX(e.pageX - scrollContainerRef.current.offsetLeft);
    setScrollLeft(scrollContainerRef.current.scrollLeft);
    scrollContainerRef.current.style.cursor = 'grabbing';
  };

  const handleMouseLeave = () => {
    setIsDragging(false);
    if (scrollContainerRef.current) {
      scrollContainerRef.current.style.cursor = 'grab';
    }
  };

  const handleMouseUp = () => {
    setIsDragging(false);
    if (scrollContainerRef.current) {
      scrollContainerRef.current.style.cursor = 'grab';
    }
  };

  const handleMouseMove = (e) => {
    if (!isDragging || !scrollContainerRef.current) return;
    e.preventDefault();
    const x = e.pageX - scrollContainerRef.current.offsetLeft;
    const walk = (x - startX) * 2; // Scroll speed multiplier
    scrollContainerRef.current.scrollLeft = scrollLeft - walk;
  };



  return (
    <section ref={containerRef} className="relative bg-background overflow-hidden py-20 lg:py-32">
      {/* Full width intro section */}
      <div className="relative pb-20 lg:pb-32">

        
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

        {/* Content container - positioned to the right */}
        <div className="relative flex flex-col items-center sm:items-end justify-start p-6 sm:p-12 lg:p-20 pt-16 sm:pt-20 lg:pt-24 z-10 min-h-screen">
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
            <div className="w-full mb-8 lg:mb-16">
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

            {/* Horizontal Scrollable Image Container */}
            <motion.div
              className="w-full mt-12 lg:mt-20"
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.8, delay: 0.4 }}
            >
              <div 
                ref={scrollContainerRef}
                className="flex gap-4 overflow-x-auto overflow-y-hidden scrollbar-hide cursor-grab active:cursor-grabbing pb-4"
                style={{
                  scrollbarWidth: 'none',
                  msOverflowStyle: 'none',
                  WebkitScrollbar: { display: 'none' },
                  overflowX: 'auto',
                  overflowY: 'hidden'
                }}
                onMouseDown={handleMouseDown}
                onMouseLeave={handleMouseLeave}
                onMouseUp={handleMouseUp}
                onMouseMove={handleMouseMove}
              >
                {images.map((image, index) => (
                  <motion.div
                    key={index}
                    className="flex-shrink-0 relative group cursor-pointer"
                    style={{ width: '256px', height: '220px' }}
                    whileHover={{ 
                      scale: 1.1,
                      zIndex: 10,
                      transition: { duration: 0.3 }
                    }}
                    whileTap={{ 
                      scale: 1.15,
                      transition: { duration: 0.2 }
                    }}
                    initial={{ opacity: 0, x: 50 }}
                    whileInView={{ opacity: 1, x: 0 }}
                    viewport={{ once: true }}
                    transition={{ 
                      duration: 0.6, 
                      delay: index * 0.1,
                      ease: "easeOut"
                    }}
                  >
                    <div 
                      className="overflow-hidden rounded-lg border-2 border-primary/20 shadow-lg group-hover:border-primary/40 transition-all duration-300"
                      style={{ width: '256px', height: '820px' }}
                    >
                      <img
                        src={image.src}
                        alt={image.alt}
                        className="transition-all duration-500 grayscale-[70%] group-hover:grayscale-0 group-hover:scale-110"
                        draggable="false"
                        style={{
                          filter: "contrast(1.2) brightness(0.9)",
                          width: '256px',
                          height: '220px',
                          objectFit: 'cover',
                          objectPosition: 'center'
                        }}
                      />
                      {/* Overlay for better visual effect */}
                      <div className="absolute inset-0 bg-gradient-to-t from-background/20 to-transparent opacity-60 group-hover:opacity-30 transition-opacity duration-300" />
                    </div>
                    
                    {/* Optional label */}
                    <div className="absolute bottom-2 left-2 right-2">
                      <p className="text-primary-foreground text-xs font-medium bg-background/80 backdrop-blur-sm px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        {image.alt}
                      </p>
                    </div>
                  </motion.div>
                ))}
              </div>
              
              {/* Scroll indicator */}
              <div className="flex justify-center mt-4">
                <p className="text-muted-foreground text-xs tracking-wider">
                  ← DRAG TO SCROLL →
                </p>
              </div>
            </motion.div>

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


