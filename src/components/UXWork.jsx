import React, { useState } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { ArrowLeft, X } from 'lucide-react';

// Case Studies Data - Add your case studies here
const caseStudies = [
  {
    title: "EziFix: Handyman Accessibility App Research & Planning",
    description: "EziFix's design process began with deep empathy and user-centered research, immersing ourselves in the daily realities of both handymen and homeowners. We conducted extensive qualitative and quantitative studies to understand the handyman's struggle while also uncovering the homeowner anxieties. This empathic understanding revealed critical insights that shaped our entire solution framework. The research ensured every design decision addressed real user needs rather than assumptions.",
    tags: ["Mobile app design", "UI/UX", "Figma", "Handyman", "Houseowner", "Accessibility", "empathy", "problem solving"],
    pdfUrl: "/case-studies/EziFixResearch&Planning.pdf",
    filename: "EziFixResearch&Planning-Case-Study.pdf",
    image: "/case-studies/term-1-cover.png"
  },
  {
    title: "EziFix: Handyman Accessibility App Visual Execution",
    description: "The visual execution of EziFix translates our empathy-driven research into user-friendly interfaces that solve real problems for both handymen and homeowners. For handymen, we created intuitive tools while homeowners enjoy transparent trust-building interfaces. Our design system even extends to health protection through a smartwatch companion for noise safety monitoring. Every visual element from the verification badges to the live tracking was crafted to build confidence, reduce anxiety and create a seamless experience that addresses the specific pain points uncovered during our research phase.",
    tags: ["Handyman", "UI/UX", "Prototype", "Accessibility Guidelines", "Visual Design"],
    pdfUrl: "/case-studies/HandymanVisualExecution.pdf",
    filename: "Handyman-Visual-Execution-Case-Study.pdf",
    image: "/case-studies/VisualE mockup.png"
  },
  {
    title: "MediLink : Service Design App",
    description: "MediLink is a centralized healthcare application that transforms the hospital experience for patients with and without medical aid. By integrating NFC technology, MediLink automates every step of a hospital visit  from booking and check-in to payments, prescriptions and results.",
    tags: ["User Research", "Service Design", "Hospital", "Mobile App", "South Africa"],
    pdfUrl: "/case-studies/MediLinkCaseStudy.pdf",
    filename: "MediLink-Case-Study.pdf",
    image: "/case-studies/MediLink mockup.png"
  },
  {
    title: "Shaya Ecommerce Website: Technology For Social Good",
    description: "The Shaya e-commerce site embraces a warm, modern and accessible design that reflects South Africa’s vibrant informal market culture. Its visual style combines earthy tones, such as soft browns and copper accents, with clean white space to create a sense of trust and approachability.",
    tags: ["Collaboration", "Research", "Local", "Strategy & Planning", "Mobile App", "Responsive Design", "E-Commerce"],
    pdfUrl: "/case-studies/CaseStudySHAYA.pdf",
    filename: "CaseStudySHAYA.pdf",
    image: "/case-studies/Shaya mockup.png"
  },
  {
    title: "RecoverTogether: An Addiction Recovery Companion App",
    description: "Designed to empower individuals in recovery from drug addiction, this app provides a secure, all-in-one platform for managing the journey to sobriety. It combines personalized goal-setting, an on-demand toolkit of coping mechanisms and a supportive community to help users build resilience, track progress and maintain motivation. The design prioritizes empathy & accessibility creating a trusted digital space that fosters a lasting positive change.",
    tags: ["Product Strategy", "Digital Health", "Behavioral Design", "Addiction Recovery", "Community Platform", "Social Impact"],
    pdfUrl: "/case-studies/RecoverTogether.pdf",
    filename: "RecoverTogether-Case-Study.pdf",
    image: "/case-studies/Recover.png"
  },
  {
    title: "Blooming Engagement: Gamified Treasure Hunt",
    description: "Rocking the Daisies needed to sustain off-season engagement. As Lead UX Designer, I created a gamified Online Treasure Hunt where users unlocked real-world rewards.",
    tags: ["Mobile Design", "Gamification", "Interaction Design", "Festival Experience"],
    pdfUrl: "/case-studies/Rockingthedaisies.pdf",
    filename: "Rocking-the-Daisies-Case-Study.pdf",
    image: "/case-studies/RTD.png" // Add your image path
  },
  // ...existing code...
    {
      title: "Visualizing Resilience: Grief Data Journey",
      description: "In the aftermath of loss, it's difficult to perceive progress. Emotions and recovery feel chaotic, making it hard to see the gradual return to stability or identify the activities that genuinely aid healing. This personal reflection is a designed personal data dashboard that transforms daily metrics into a visual narrative of resilience. By tracking interconnected aspects of well-being, the dashboard illustrates the ups and downs of the healing journey, highlighting the subtle correlations between emotional, physical and mental recovery to foster self-awareness and hope.",
      tags: ["Data Visualization", "UX Research", "Grief", "Mental Health", "Charts", "Recovery"],
      pdfUrl: "/case-studies/Grief.pdf",
      filename: "Grief-Data-Visualization-Case-Study.pdf",
      image: "/case-studies/Grief.png" // Add your image path
    },
  
];

export function UXWork({ onNavigate }) {
  const [showSkillsModal, setShowSkillsModal] = useState(false);

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
              CASE STUDIES
            </h2>
           
            {/* Professional Summary & Call-to-Action Section */}
            <div className="max-w-4xl mx-auto text-left mt-10 mb-0">
              <p className="text-muted-foreground leading-relaxed text-base mb-6">
                I am a junior User Experience (UX/Ui) with a passion for creating intuitive and impactful digital experiences. I’m a strategic and empathetic UI/UX Designer with 3 years of academic training and a human-centered design approach. I Specialize in transforming complex user problems into intuitive, accessible and visually refined digital solutions.
              </p>
              <p className="text-muted-foreground leading-relaxed text-base mb-8">
                I have a proven ability to own the design process end-to-end, from initial user research and problem framing to prototyping, testing and designing for multi-channel ecosystems including the integration of wearable technology. Driven by data and user feedback to create engaging experiences that align with business objectives. Through my studio, Dhiali Digital Designs, I specialize in UX/UI design, branding and full-stack web development. I’m driven to build digital solutions that are both innovative and user-focused.
              </p>
              {/* Call-to-Action Section */}
              <div className="bg-white/5 backdrop-blur-sm rounded-lg p-8 text-center">
                <p className="text-muted-foreground leading-relaxed text-base mb-6">
                  Explore my UX case studies and technical skills below.
                </p>
                {/* Technical Skills Button */}
                <div className="flex flex-row gap-4 justify-center">
                  <motion.button
                    onClick={() => setShowSkillsModal(true)}
                    className="technical-skills-button"
                    whileHover={{ scale: 1.05 }}
                    whileTap={{ scale: 0.95 }}
                  >
                    VIEW TECHNICAL SKILLS
                  </motion.button>
                  
                </div>
              </div>
            </div>
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
                      ) : study.title === "MediLink : Service Design App" ? (
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
                      <motion.a
                        href={study.pdfUrl}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="reserve-button"
                        whileHover={{ scale: 1.05 }}
                        whileTap={{ scale: 0.95 }}
                      >
                        VIEW CASE STUDY
                      </motion.a>
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
                        <img 
                          src={study.image} 
                          alt={`${study.title} Cover`}
                          className="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                          style={{ maxHeight: '90%', maxWidth: '95%' }}
                        />
                      </div>
                      <div className="card-content">
                        <h2 className="card-title">{study.title}</h2>
                        <p className="card-description">{study.description}</p>
                        <div className="card-features">
                          {study.tags.map((tag, tagIndex) => (
                            <span key={tagIndex} className="feature-tag">{tag}</span>
                          ))}
                        </div>
                        <motion.a
                          href={study.pdfUrl}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="reserve-button"
                          whileHover={{ scale: 1.05 }}
                          whileTap={{ scale: 0.95 }}
                        >
                          VIEW CASE STUDY
                        </motion.a>
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
                        <motion.a
                          href={study.pdfUrl}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="reserve-button"
                          whileHover={{ scale: 1.05 }}
                          whileTap={{ scale: 0.95 }}
                        >
                          VIEW CASE STUDY
                        </motion.a>
                      </div>
                    </div>
                  </motion.div>
                ))}
              </div>
            )}
          </div>

          {/* Technical Skills Modal */}
          <AnimatePresence>
            {showSkillsModal && (
              <motion.div
                className="fixed inset-0 z-50 flex items-center justify-center p-8 py-12 bg-black"
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                exit={{ opacity: 0 }}
                onClick={() => setShowSkillsModal(false)}
              >
                {/* Modal Card */}
                <motion.div
                  className="relative text-white rounded-[8rem] w-full max-w-md shadow-2xl flex flex-col overflow-hidden p-8"
                  style={{ backgroundColor: '#000000' }}
                  initial={{ scale: 0.8, opacity: 0, y: 20 }}
                  animate={{ scale: 1, opacity: 1, y: 0 }}
                  exit={{ scale: 0.8, opacity: 0, y: 20 }}
                  onClick={(e) => e.stopPropagation()}
                >
                  {/* Close Button */}
                  <button
                    onClick={() => setShowSkillsModal(false)}
                    className="absolute top-4 right-4 p-2 hover:bg-gray-800 rounded-full transition-colors z-10"
                    style={{ boxSizing: 'content-box' }}
                  >
                    <X className="w-5 h-5 text-gray-400" />
                  </button>
                  {/* Illustration/Icon Section */}
                  <div className="pt-8 pb-4 text-center">
                    <div className="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center">
                      {/* Changed icon to a user/ux related icon */}
                      <svg className="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4" strokeWidth="2" />
                        <path strokeWidth="2" d="M4 20c0-4 4-7 8-7s8 3 8 7" />
                      </svg>
                    </div>
                    <h2 className="text-2xl font-bold text-white mb-2">
                      Technical Skills
                    </h2>
                    <p className="text-gray-300 text-sm leading-relaxed mb-6">
                      Here's an overview of my technical expertise and capabilities.
                    </p>
                  </div>
                  {/* Scrollable Content */}
                  <div className="flex-1 overflow-y-auto pb-4 max-h-60">
                    <div className="space-y-3">
                      {/* User Research & Strategy */}
                      <div className="bg-gray-900 rounded-lg p-3">
                        <h4 className="text-sm font-bold mb-2" style={{ color: '#dc2626' }}>
                          User Research & Strategy
                        </h4>
                        <div className="text-xs text-gray-300 leading-relaxed">
                          User Personas & Empathy Mapping, Problem Framing & Journey Mapping, Qualitative & Quantitative Research, Stakeholder Needs Analysis, UX Strategy & Service Design, Data Sorting & Interpretation
                        </div>
                      </div>
                      {/* UI/UX Design & Prototyping */}
                      <div className="bg-gray-900 rounded-lg p-3">
                        <h4 className="text-sm font-bold mb-2" style={{ color: '#dc2626' }}>
                          UI/UX Design & Prototyping
                        </h4>
                        <div className="text-xs text-gray-300 leading-relaxed">
                          Mobile-First & Responsive Web Design, Interaction Design & Micro-interactions, Native iOS/Android Application Design, Wireframing & High-Fidelity Prototyping, Accessibility-First Design (WCAG), Data Visualization & Storytelling
                        </div>
                      </div>
                      {/* Visual & Brand Design */}
                      <div className="bg-gray-900 rounded-lg p-3">
                        <h4 className="text-sm font-bold mb-2" style={{ color: '#dc2626' }}>
                          Visual & Brand Design
                        </h4>
                        <div className="text-xs text-gray-300 leading-relaxed">
                          Visual Aesthetic & Art Direction, Brand Identity & Systems, Design Systems & UI Components, Visual Language & Conceptual Thinking
                        </div>
                      </div>
                      {/* Technical & Tools */}
                      <div className="bg-gray-900 rounded-lg p-3">
                        <h4 className="text-sm font-bold mb-2" style={{ color: '#dc2626' }}>
                          Technical & Tools
                        </h4>
                        <div className="text-xs text-gray-300 leading-relaxed">
                          Design & Prototyping: Figma, Adobe Creative Suite<br/>
                          Animation & Interaction: Figma, After Effects<br/>
                          Channels & Tech: E-commerce Journeys, Wearable Tech Integration, Multi-channel Service Delivery
                        </div>
                      </div>
                    </div>
                  </div>
                </motion.div>
              </motion.div>
            )}
          </AnimatePresence>
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