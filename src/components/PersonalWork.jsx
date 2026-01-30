import React from 'react';
import { motion} from 'motion/react';
import { ArrowLeft } from 'lucide-react';

// Case Studies Data - Add your case studies here
const caseStudies = [
  {
    title: "Outrite Africa: Brand Advertising & Visual Design",
    description: "Designed a promotional advertising poster for Outrite Africa, created for both digital and print use. The objective was to clearly communicate services, contact details and brand credibility through a visually engaging and professional design. The poster uses Outrite Africa’s established blue and green brand colour palette to reinforce trust, professionalism and growth within the logistics sector. Custom vector illustrations were developed to represent road, sea and warehousing services with strong information hierarchy applied to prioritise services and ensure clear, readable contact details. My role included concept development, visual direction, layout design, brand-aligned colour and typography selection and the creation of scalable vector illustrations.",
    tags: ["Graphic design", "Advertising Design", "Brand Communication", "Logistics", "Visual Hierarchy", "Figma", "Adobe Illustrator"],
    pdfUrl: "./case-studies/Outrite-Africa-Brand-Advertising.pdf",
    filename: "Outrite-Africa-Brand-Advertising.pdf",
    image: "./case-studies/Outrite-Africa-Brand Advertising.png",
    buttonLabel: "VIEW PDF"
  },
  {
    title: "NSIAWIN Electrical: Company Profile Design",
    description: "NSIAWIN Electrical provided a text-heavy word document which I designed into a polished, client-ready company profile. The focus was on improving visual hierarchy, readability and brand presence while maintaining a professional, industrial tone suited to the electrical and construction sector. The final PDF is designed for use in proposals, presentations and business development. As the sole designer, I was responsible for the layout design, information hierarchy, typography and colour usage, AI-assisted visuals and print-ready PDF export. Brand content and copy provided by the client. Design and layout by Dhiali Chetty.",
    tags: ["Brand Presentation", "Visual Design", "Client Communication", "Figma", "AI image generation"],
    pdfUrl: "./case-studies/NSIAWIN-ELECTRICAL-PROFILE.pdf",
    filename: "NSIAWIN-ELECTRICAL-PROFILE.pdf",
    image: "./case-studies/NSIAWIN-cover.png",
    buttonLabel: "VIEW PDF"
  },
  {
    title: "La Way Travel Agency: Brand Identity & Logo Design",
    description: "La Way Travel Agency is a startup focused on attracting international tourists to South Africa. I was approached to design a logo from scratch using an open-ended brief, allowing creative freedom while ensuring that the brand strongly represented South Africa as a destination. The concept draws inspiration from safari culture featuring the Big Five and a warm sunset to evoke adventure and African landscapes with an aeroplane symbolising international travel. All visual elements were custom-created in Adobe Illustrator resulting in a scalable vector logo suitable for digital and print use. This project demonstrates my ability to interpret open-ended briefs, translate brand values into a cohesive visual identity and apply storytelling within logo design.",
    tags: ["Logo Design", "Branding", " South African Design", "Adobe Illustrator", "Visual Identity"],
    pdfUrl: "./case-studies/la-way-logo.pdf",
    filename: "la-way-logo.pdf",
    image: "./case-studies/La-way-cover.png",
    buttonLabel: "VIEW PDF"
  },
  {
    title: "Motion Graphics Showcase",
    description: "A motion design project created to demonstrate my capabilities in animation and video advertising. This piece showcases vector-based motion graphics, clean transitions, strong visual timing and cohesive motion storytelling, taking assets from initial concept and illustration through to final animation. I was responsible for the full process using custom vector artwork designed in Adobe Illustrator and animated in Adobe After Effects. The project reflects my ability to create engaging motion content suited for digital advertising, social media, brand visuals, promotional campaigns and website motion.",
    tags: ["Motion Design", "After Effects", "Adobe Illustrator", "Video Animation", "Content Creation", "Promotional Video", "Digital Marketing"],
    pdfUrl: "./case-studies/Motion-Graphics-Showcase.mp4",
    filename: "Motion-Graphics-Showcase.mp4",
    image: "./case-studies/Motion-Graphics-Showcase-cover.png",
    buttonLabel: "VIEW VIDEO"
  },
  {
    title: "RecoverTogether: An Addiction Recovery Companion App",
    description: "Designed to empower individuals in recovery from drug addiction, this app provides a secure, all-in-one platform for managing the journey to sobriety. It combines personalized goal-setting, an on-demand toolkit of coping mechanisms and a supportive community to help users build resilience, track progress and maintain motivation. The design prioritizes empathy & accessibility creating a trusted digital space that fosters a lasting positive change.",
    tags: ["Product Strategy", "Digital Health", "Behavioral Design", "Addiction Recovery", "Community Platform", "Social Impact"],
    pdfUrl: "./case-studies/RecoverTogether.pdf",
    filename: "RecoverTogether-Case-Study.pdf",
    image: "./case-studies/Recover.png"
  },
  
  
];



export function PersonalWork({ onNavigate }) {

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
              Client WORK
            </h2>
        
          </motion.div>

          {/* Case Studies Grid - 3 cards per row on desktop, stacked on mobile */}
          <div className="flex flex-col gap-6 w-full max-w-7xl px-4">
            {/* First row: first 3 case studies */}
            <div className="flex flex-col sm:flex-row gap-6 sm:gap-3 lg:gap-4 w-full">
              {caseStudies.slice(0,3).map((study, index) => (
                // ...existing code for card rendering...
                <motion.div
                  key={index}
                  className="group border border-border hover:border-primary transition-all duration-300 w-full sm:flex-1 sm:min-w-0"
                  initial={{ opacity: 0, y: 50 }}
                  whileInView={{ opacity: 1, y: 0 }}
                  viewport={{ once: true }}
                  transition={{ duration: 0.6, delay: index * 0.1 }}
                  whileHover={{ y: -5 }}
                >
                  {/* Swiss Alps Retreat Style Card for all projects */}
                  <div className="retreat-card">
                    <div className="card-image">
                      {study.title === "EziFix: Handyman Accessibility App Research & Planning" ? (
                        <img 
                          src={study.image} 
                          alt={`${study.title} Cover`}
                          className="object-contain group-hover:scale-105 transition-transform duration-300"
                          style={{ maxHeight: '90%', maxWidth: '70%', width: 'auto', height: '100%' }}
                        />
                      ) : study.title === "EziFix: Handyman Accessibility App Visual Execution" ? (
                        <img 
                          src={study.image} 
                          alt={`${study.title} Cover`}
                          className="object-contain group-hover:scale-105 transition-transform duration-300"
                          style={{ maxHeight: '90%', maxWidth: '80%', width: 'auto', height: '100%' }}
                        />
                      ) : study.title === "MediLink : Service Design App"||
     study.title === "NSIAWIN Electrical: Company Profile Design" ||
     study.title === "La Way Travel Agency: Brand Identity & Logo Design" ? (
                        <img 
                          src={study.image} 
                          alt={`${study.title} Cover`}
                          className="object-contain group-hover:scale-105 transition-transform duration-300"
                          style={{ maxHeight: '90%', maxWidth: '75%', width: 'auto', height: '100%' }}
                        />
                      ) : (
                        <img 
                          src={study.image} 
                          alt={`${study.title} Cover`}
                          className="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                          style={{ maxHeight: '90%', maxWidth: '95%' }}
                        />
                      )}
                    </div>
                    <div className="card-content">
                      <h2 className="card-title">{study.title}</h2>
                      <p className="card-description">{study.description}</p>
                      <div className="card-features">
                        {study.tags.map((tag, tagIndex) => (
                          <span key={tagIndex} className="feature-tag">{tag}</span>
                        ))}
                      </div>
                      {study.pdfUrl && (
  <motion.a
    href={study.pdfUrl}
    target="_blank"
    rel="noopener noreferrer"
    className="reserve-button"
    whileHover={{ scale: 1.05 }}
    whileTap={{ scale: 0.95 }}
  >
    {study.buttonLabel || "VIEW CASE STUDY"}
  </motion.a>
)}
                    </div>
                  </div>
                </motion.div>
              ))}
            </div>
            {/* Second row: next 3 case studies */}
            {caseStudies.length > 3 && (
              <div className="flex flex-col sm:flex-row gap-6 sm:gap-3 lg:gap-4 w-full">
                {caseStudies.slice(3,6).map((study, index) => (
                  // ...existing code for card rendering...
                  <motion.div
                    key={index + 3}
                    className="group border border-border hover:border-primary transition-all duration-300 w-full sm:flex-1 sm:min-w-0"
                    initial={{ opacity: 0, y: 50 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true }}
                    transition={{ duration: 0.6, delay: index * 0.1 }}
                    whileHover={{ y: -5 }}
                  >
                    <div className="retreat-card">
                      <div className="card-image">
                        {study.title === "Motion Graphics Showcase" ? (
                          <img 
                            src={study.image} 
                            alt={`${study.title} Cover`}
                            className="object-contain group-hover:scale-105 transition-transform duration-300"
                            style={{ maxHeight: '90%', maxWidth: '75%', width: 'auto', height: '100%' }}
                          />
                        ) : (
                          <img 
                            src={study.image} 
                            alt={`${study.title} Cover`}
                            className="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                            style={{ maxHeight: '90%', maxWidth: '95%' }}
                          />
                        )}
                      </div>
                      <div className="card-content">
                        <h2 className="card-title">{study.title}</h2>
                        <p className="card-description">{study.description}</p>
                        <div className="card-features">
                          {study.tags.map((tag, tagIndex) => (
                            <span key={tagIndex} className="feature-tag">{tag}</span>
                          ))}
                        </div>
                        {study.pdfUrl && (
  <motion.a
    href={study.pdfUrl}
    target="_blank"
    rel="noopener noreferrer"
    className="reserve-button"
    whileHover={{ scale: 1.05 }}
    whileTap={{ scale: 0.95 }}
  >
    {study.buttonLabel || "VIEW CASE STUDY"}
  </motion.a>
)}
                      </div>
                    </div>
                  </motion.div>
                ))}
              </div>
            )}
            {/* Third row: last card if any */}
            {caseStudies.length > 6 && (
              <div className="flex flex-col sm:flex-row gap-6 sm:gap-3 lg:gap-4 w-full">
                {caseStudies.slice(6).map((study, index) => (
                  // ...existing code for card rendering...
                  <motion.div
                    key={index + 6}
                    className="group border border-border hover:border-primary transition-all duration-300 w-full sm:flex-1 sm:min-w-0"
                    initial={{ opacity: 0, y: 50 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true }}
                    transition={{ duration: 0.6, delay: index * 0.1 }}
                    whileHover={{ y: -5 }}
                  >
                    <div className="retreat-card">
                      <div className="card-image">
                        {study.title === "Visualizing Resilience: Grief Data Journey" ? (
                          <img 
                            src={study.image} 
                            alt={`${study.title} Cover`}
                            className="object-contain group-hover:scale-105 transition-transform duration-300"
                            style={{ maxHeight: '90%', maxWidth: '70%', width: 'auto', height: '100%' }}
                          />
                        ) : (
                          <img 
                            src={study.image} 
                            alt={`${study.title} Cover`}
                            className="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                            style={{ maxHeight: '90%', maxWidth: '95%' }}
                          />
                        )}
                      </div>
                      <div className="card-content">
                        <h2 className="card-title">{study.title}</h2>
                        <p className="card-description">{study.description}</p>
                        <div className="card-features">
                          {study.tags.map((tag, tagIndex) => (
                            <span key={tagIndex} className="feature-tag">{tag}</span>
                          ))}
                        </div>
                        {study.pdfUrl && (
  <motion.a
    href={study.pdfUrl}
    target="_blank"
    rel="noopener noreferrer"
    className="reserve-button"
    whileHover={{ scale: 1.05 }}
    whileTap={{ scale: 0.95 }}
  >
    {study.buttonLabel || "VIEW CASE STUDY"}
  </motion.a>
)}
                      </div>
                    </div>
                  </motion.div>
                ))}
              </div>
            )}
          </div>

        
        </div>
      </section>

      <style jsx>{`
        .retreat-card-style {
          width: 100%;
          max-width: 380px;
          border-radius: 12px;
          overflow: hidden;
          box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
          background: white;
          font-family: 'Arial', sans-serif;
          margin: 0 auto;
        }

        @media (min-width: 768px) {
          .retreat-card-style {
            max-width: 350px;
          }
        }

        @media (min-width: 1024px) {
          .retreat-card-style {
            max-width: 380px;
          }
        }

        @media (min-width: 1280px) {
          .retreat-card-style {
            max-width: 400px;
          }
        }

        .retreat-card {
          display: flex;
          flex-direction: column;
          height: 100%;
        }

        .card-image {
          width: 100%;
          height: 260px;
          min-height: 220px;
          max-height: 320px;
          overflow: hidden;
          display: flex;
          align-items: center;
          justify-content: center;
        }

        .card-content {
          padding: 24px;
          flex: 1;
          display: flex;
          flex-direction: column;
        }

        .card-title {
          font-size: 1.4rem;
          font-weight: bold;
          margin: 0 0 12px 0;
          color: #ffffff;
          line-height: 1.3;
        }

        .card-description {
          color: #878787ff;
          line-height: 1.5;
          margin: 0 0 20px 0;
          font-size: 0.95rem;
          flex: 1;
        }

        .card-features {
          display: flex;
          gap: 10px;
          margin-bottom: 20px;
          flex-wrap: wrap;
        }

        .feature-tag {
          background-color: #f0f7f0;
          color: #2c5530;
          padding: 6px 12px;
          border-radius: 20px;
          font-size: 0.85rem;
          font-weight: 500;
          white-space: nowrap;
        }

        .reserve-button {
          width: 100%;
          background-color: #b91f1fff;
          color: white;
          border: none;
          padding: 12px 0;
          border-radius: 6px;
          font-size: 1rem;
          font-weight: bold;
          cursor: pointer;
          transition: background-color 0.2s;
          text-align: center;
          text-decoration: none;
          display: block;
        }

        .reserve-button:hover {
          background-color: #b91c1c;
        }

        .technical-skills-button {
          width: auto;
          min-width: 200px;
          background-color: #b91f1fff;
          color: white;
          border: none;
          padding: 12px 24px;
          border-radius: 6px;
          font-size: 1rem;
          font-weight: bold;
          cursor: pointer;
          transition: background-color 0.2s;
          text-align: center;
          text-decoration: none;
          display: inline-block;
        }

        .technical-skills-button:hover {
          background-color: #b91c1c;
        }
      `}</style>
    </div>
  );
}