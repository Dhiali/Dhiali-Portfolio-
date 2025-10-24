import { motion } from 'motion/react';
import { ArrowLeft } from 'lucide-react';

// Development Projects Data - Add your GitHub projects here
const developmentProjects = [
  {
    title: "FaceOff - Superhero Power Comparison API",
    description: "FaceOff is an interactive React application that transforms data from the SuperHero API into a dynamic exploration of comic book characters. The platform features a dashboard of powerful heroes and villains, a detailed comparison tool with radar charts, and historical timelines, providing fans and data enthusiasts a unique way to settle debates and explore superhero statistics.",
    tags: ["Axios", "Bootstrap", "Chart.js", "CSS3", "Node.js", "React", "Javascript" ],
    githubUrl: "https://github.com/Dhiali/super-dashboard.git",
  },
  {
    title: "The Drunken Giraffe",
    description: "A comprehensive MERN stack e-commerce platform that revolutionizes user authentication through gamification while delivering a full-featured online liquor store experience.",
    tags: ["React", "JavaScript", "HTML5/CSS3", "Node.js", "Express.js", "MongoDB","JWT Authentication"],
    githubUrl: "https://github.com/Dhiali/mern_liquor.git",
  
  },
 
  // Add more projects as needed
];

export function InteractiveDev({ onNavigate }) {
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

    

      {/* Development Projects Section */}
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
              DEVELOPMENT PROJECTS
            </h2>
            <p className="text-muted-foreground max-w-2xl mx-auto">
              Explore my coding projects and technical implementations
            </p>
          </motion.div>

          {/* Projects Grid */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {developmentProjects.map((project, index) => (
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
                    {project.title}
                  </h3>
                  <p className="text-muted-foreground text-sm mb-4 line-clamp-2">
                    {project.description}
                  </p>
                  
                  {/* Tags */}
                  <div className="flex flex-wrap gap-2 mb-4">
                    {project.tags.map((tag, tagIndex) => (
                      <span 
                        key={tagIndex}
                        className="px-2 py-1 text-xs bg-muted text-muted-foreground rounded"
                      >
                        {tag}
                      </span>
                    ))}
                  </div>

                  {/* Project Links */}
                  <div className="flex gap-3">
                    {/* GitHub Link */}
                    <motion.a
                      href={project.githubUrl}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="inline-flex items-center gap-2 px-3 py-2 border border-primary text-primary hover:bg-primary hover:text-primary-foreground transition-all duration-300 text-sm"
                      whileHover={{ scale: 1.05 }}
                      whileTap={{ scale: 0.95 }}
                    >
                      <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0C5.374 0 0 5.373 0 12 0 17.302 3.438 21.8 8.207 23.387c.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/>
                      </svg>
                      <span>CODE</span>
                    </motion.a>

                    {/* Live Demo Link */}
                    {project.liveUrl && (
                      <motion.a
                        href={project.liveUrl}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="inline-flex items-center gap-2 px-3 py-2 bg-primary text-primary-foreground hover:bg-primary/80 transition-all duration-300 text-sm"
                        whileHover={{ scale: 1.05 }}
                        whileTap={{ scale: 0.95 }}
                      >
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        <span>DEMO</span>
                      </motion.a>
                    )}
                  </div>
                </div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}


