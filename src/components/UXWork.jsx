import { motion } from 'motion/react';
import { ArrowLeft } from 'lucide-react';

// Case Studies Data - Add your case studies here
const caseStudies = [
  {
    title: "Blooming Engagement: Designing a Gamified Treasure Hunt for Rocking the Daisies",
    description: "Rocking the Daisies needed to sustain off-season engagement. As Lead UX Designer, I created a gamified Online Treasure Hunt where users unlocked real-world rewards. This end-to-end project extended the festival's brand and fostered a year-round community.",
    tags: ["Mobile Design", "Design & Prototyping", "User Research"],
    pdfUrl: "https://drive.google.com/file/d/1I8Z203DPlbyH00lOYNgC_OOZl-nSN2qD/view?usp=sharing" // Your PDF file
  },
  {
    title: "Visualizing Resilience: A Data-Driven Journey Through Grief",
    description: "As the sole UX designer and data storyteller, I transformed the intangible journey of grief into a tangible data narrative. I designed and built an interactive dashboard that visualizes personal metrics across mind, body, and soul, revealing patterns of resilience and healing. The final prototype, built in Figma, serves as an empathetic tool for self-reflection, telling a silent story of recovery.",
    tags: ["UX/UI Design & Prototyping", "Research & Synthesis", "Data Visualization"],
    pdfUrl: "https://drive.google.com/file/d/15-64HNDgEktB681e1aGxAXkEkchhErwb/view?usp=sharing"
  }
  // Add more case studies as needed
];

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

      {/* 2-Column Editorial Magazine Layout */}
      

      {/* Case Studies Section */}
      <section className="min-h-screen py-20 bg-background">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          {/* Section Header */}
          <motion.div
            className="text-center mb-16"
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.8 }}
          >
            <h2 
              className="text-[8vw] sm:text-[6vw] md:text-[4vw] lg:text-[3vw] xl:text-[2.5vw] mb-6"
              style={{ fontFamily: "'Bebas Neue', sans-serif" }}
            >
              CASE STUDIES
            </h2>
            <p className="text-muted-foreground max-w-2xl mx-auto">
              Explore my UX design process through detailed case studies
            </p>
          </motion.div>

          {/* Case Studies Grid */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {caseStudies.map((study, index) => (
              <motion.div
                key={index}
                className="group border border-border hover:border-primary transition-all duration-300"
                initial={{ opacity: 0, y: 50 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.6, delay: index * 0.1 }}
                whileHover={{ y: -5 }}
              >
                {/* Content */}
                <div className="p-6">
                  <h3 
                    className="text-xl mb-2 group-hover:text-primary transition-colors"
                    style={{ fontFamily: "'Bebas Neue', sans-serif" }}
                  >
                    {study.title}
                  </h3>
                  <p className="text-muted-foreground text-sm mb-4 line-clamp-2">
                    {study.description}
                  </p>
                  
                  {/* Tags */}
                  <div className="flex flex-wrap gap-2 mb-4">
                    {study.tags.map((tag, tagIndex) => (
                      <span 
                        key={tagIndex}
                        className="px-2 py-1 text-xs bg-muted text-muted-foreground rounded"
                      >
                        {tag}
                      </span>
                    ))}
                  </div>

                  {/* View PDF Button */}
                  <motion.a
                    href={study.pdfUrl}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="inline-flex items-center gap-2 px-4 py-2 border border-primary text-primary hover:bg-primary hover:text-primary-foreground transition-all duration-300"
                    whileHover={{ scale: 1.05 }}
                    whileTap={{ scale: 0.95 }}
                  >
                    <span className="text-sm">VIEW CASE STUDY</span>
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                  </motion.a>
                </div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}


