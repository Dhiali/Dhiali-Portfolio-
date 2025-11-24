import { motion, AnimatePresence } from 'motion/react';
import { ArrowLeft, X } from 'lucide-react';
import { useState, useEffect } from 'react';

// Development Projects Data - Add your GitHub projects here
const developmentProjects = [
	{
		title: 'FaceOff - Superhero Power Comparison API',
		description:
			'FaceOff is an interactive React application that transforms data from the SuperHero API into a dynamic exploration of comic book characters. The platform features a dashboard of powerful heroes and villains, a detailed comparison tool with radar charts, and historical timelines, providing fans and data enthusiasts a unique way to settle debates and explore superhero statistics.',
		tags: ['Axios', 'Bootstrap', 'Chart.js', 'CSS3', 'Node.js', 'React', 'Javascript'],
		githubUrl: 'https://github.com/Dhiali/super-dashboard.git',
		image: '/case-studies/faceoff mockup.png',
	},
	{
		title: 'The Drunken Giraffe',
		description:
			'A comprehensive MERN stack e-commerce web application that revolutionizes user authentication through an innovative bottle-sorting game while delivering a complete online liquor store experience. This full-stack web platform combines traditional e-commerce functionality with creative gamified authentication, featuring product management, shopping cart capabilities, user reviews and accessibility-first design principles.',
		tags: [
			'React',
			'JavaScript',
			'HTML5/CSS3',
			'Bootstrap',
			'Node.js',
			'Express.js',
			'MongoDB',
			'JWT Authentication',
		],
		githubUrl: 'https://github.com/AngievR05/mern_liquor',
		image: '/case-studies/drunken mock up.png',
	},
	{
		title: 'Housemate - Smart Household Management System',
		description:
			'HouseMate is a full-stack PWA that streamlines shared living by managing tasks, bills, events and communication with role-based access control. Built with React, Node.js/Express and MySQL it features real-time updates, secure authentication and household analytics. Previously deployed on Google Cloud Run and Azure Static Web Apps it now runs locally for demonstrations.',
		tags: ['React', 'Tailwind CSS', 'Vite', 'Javascript', 'HTML5', 'CSS3', 'Node/Express.js', 'MySQL', 'JWT', 'Google Cloud', 'Microsoft Azure'],
		githubUrl: 'https://github.com/Dhiali/housemate',
		image: '/case-studies/HM mockup.png',
	},
	// Add more projects as needed
];

export function InteractiveDev({ onNavigate }) {
	const [showSkillsModal, setShowSkillsModal] = useState(false);

	// Prevent background scrolling when modal is open
	useEffect(() => {
		if (showSkillsModal) {
			document.body.style.overflow = 'hidden';
		} else {
			document.body.style.overflow = 'unset';
		}

		// Cleanup on unmount
		return () => {
			document.body.style.overflow = 'unset';
		};
	}, [showSkillsModal]);

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
							className="text-[8vw] sm:text-[6vw] md:text-[4vw] lg:text-[3vw] xl:text-[2.5vw] mb-12"
							style={{ fontFamily: "'Bebas Neue', sans-serif" }}
						>
							DEVELOPMENT PROJECTS
						</h2>

						{/* Professional Summary */}
						<div className="max-w-4xl mx-auto text-left mb-16">
							<p className="text-muted-foreground leading-relaxed text-base mb-6">
								I am a junior Interactive Developer with a passion for creating 
                intuitive and impactful digital experiences. I’m highly motivated 
                with a strong foundation in full-stack JavaScript development, cloud 
                deployment and user-centric design. Proficient in building dynamic, 
                data-driven web applications using the MERN stack (MongoDB, Express.js, 
                React, Node.js) and relational databases with SQL.
							</p>
							<p className="text-muted-foreground leading-relaxed text-base mb-8">
								I'm experienced in applying software development lifecycle (SDLC) principles, from design and database normalisation to secure deployment on cloud platforms like AWS and Azure. A collaborative team player adept at using Git/GitHub for version control and passionate about creating intuitive, secure and scalable digital experiences. Through my studio, Dhiali Digital Designs, I specialize in UX/UI design, branding and full-stack web development. I’m driven to build digital solutions that are both innovative and user-focused.
							</p>

							{/* Call-to-Action Section */}
							<div className="bg-white/5 backdrop-blur-sm rounded-lg p-8 text-center">
								<p className="text-muted-foreground leading-relaxed text-base mb-6">
									Explore my coding projects and technical implementations below.
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
									{/* DEVELOPMENT CV button removed as requested */}
									<motion.a
										href="https://github.com/Dhiali"
										target="_blank"
										rel="noopener noreferrer"
										className="technical-skills-button"
										whileHover={{ scale: 1.05 }}
										whileTap={{ scale: 0.95 }}
									>
										GITHUB PROFILE
									</motion.a>
								</div>
							</div>
						</div>
					</motion.div>

					{/* Projects Grid */}
					<div className="flex justify-center">
						<div className="flex flex-col sm:flex-row gap-6 sm:gap-3 lg:gap-4 w-full max-w-7xl px-4 sm:overflow-x-auto">
							{developmentProjects.map((project, index) => (
								<motion.div
									key={index}
									className="group border border-border hover:border-primary transition-all duration-300 w-full sm:flex-1 sm:min-w-0"
									initial={{ opacity: 0, y: 50 }}
									whileInView={{ opacity: 1, y: 0 }}
									viewport={{ once: true }}
									transition={{ duration: 0.6, delay: index * 0.1 }}
									whileHover={{ y: -5 }}
								>
									<div className="retreat-card">
										<div className="card-image">
											{project.image ? (
												<img
													src={project.image}
													alt={project.title + ' Cover'}
													className="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
													style={{ maxHeight: '90%', maxWidth: '95%' }}
												/>
											) : (
												<div className="w-full h-auto min-h-[200px] flex items-center justify-center bg-gray-100">
													<svg
														className="w-20 h-20 text-gray-400"
														fill="none"
														stroke="currentColor"
														viewBox="0 0 24 24"
													>
														<path
															strokeLinecap="round"
															strokeLinejoin="round"
															strokeWidth={2}
															d="M16 18l6-6-6-6M8 6l-6 6 6 6"
														/>
													</svg>
												</div>
											)}
										</div>
										<div className="card-content">
											<h2 className="card-title">{project.title}</h2>
											<p className="card-description">{project.description}</p>
											<div className="card-features">
												{project.tags.map((tag, tagIndex) => (
													<span key={tagIndex} className="feature-tag">
														{tag}
													</span>
												))}
											</div>
											<div className="flex gap-2 mt-2">
												<motion.a
													href={project.githubUrl}
													target="_blank"
													rel="noopener noreferrer"
													className="reserve-button"
													whileHover={{ scale: 1.05 }}
													whileTap={{ scale: 0.95 }}
												>
													CODE
												</motion.a>
												{project.liveUrl && (
													<motion.a
														href={project.liveUrl}
														target="_blank"
														rel="noopener noreferrer"
														className="reserve-button"
														whileHover={{ scale: 1.05 }}
														whileTap={{ scale: 0.95 }}
													>
														DEMO
													</motion.a>
												)}
											</div>
										</div>
									</div>
								</motion.div>
							))}
						</div>
					</div>
					<style jsx>{`
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
					`}</style>
				</div>
			</section>

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
									<svg
										className="w-10 h-10 text-white"
										fill="none"
										stroke="currentColor"
										viewBox="0 0 24 24"
									>
										<path
											strokeLinecap="round"
											strokeLinejoin="round"
											strokeWidth={2}
											d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"
										/>
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
									{/* Programming Languages & Markup */}
									<div className="bg-gray-900 rounded-lg p-3">
										<h4
											className="text-sm font-bold mb-2"
											style={{ color: '#dc2626' }}
										>
											Programming Languages & Markup
										</h4>
										<div className="text-xs text-gray-300 leading-relaxed">
											JavaScript (ES6+), TypeScript, PHP, SQL, HTML5, CSS3
										</div>
									</div>

									{/* Frontend Development */}
									<div className="bg-gray-900 rounded-lg p-3">
										<h4
											className="text-sm font-bold mb-2"
											style={{ color: '#dc2626' }}
										>
											Frontend Development
										</h4>
										<div className="text-xs text-gray-300 leading-relaxed">
											React, Vue.js, Angular, React Router, Component-Based Architecture, Dynamic
											Data Visualisation, Responsive Web Design
										</div>
									</div>

									{/* Backend Development */}
									<div className="bg-gray-900 rounded-lg p-3">
										<h4
											className="text-sm font-bold mb-2"
											style={{ color: '#dc2626' }}
										>
											Backend Development
										</h4>
										<div className="text-xs text-gray-300 leading-relaxed">
											Node.js, Express.js, RESTful API Development, Middleware, Session Management,
											Authentication & Authorization
										</div>
									</div>

									{/* Databases */}
									<div className="bg-gray-900 rounded-lg p-3">
										<h4
											className="text-sm font-bold mb-2"
											style={{ color: '#dc2626' }}
										>
											Databases
										</h4>
										<div className="text-xs text-gray-300 leading-relaxed">
											MySQL, PostgreSQL, MongoDB, Data Modelling, Advanced SQL, Performance
											Optimisation
										</div>
									</div>

									{/* Cloud & Deployment */}
									<div className="bg-gray-900 rounded-lg p-3">
										<h4
											className="text-sm font-bold mb-2"
											style={{ color: '#dc2626' }}
										>
											Cloud & Deployment
										</h4>
										<div className="text-xs text-gray-300 leading-relaxed">
											AWS, Azure, Google Cloud Platform, Heroku, Application Deployment, Auto-Scaling,
											Performance Monitoring
										</div>
									</div>

									{/* Development Tools & Methodologies */}
									<div className="bg-gray-900 rounded-lg p-3">
										<h4
											className="text-sm font-bold mb-2"
											style={{ color: '#dc2626' }}
										>
											Development Tools & Methodologies
										</h4>
										<div className="text-xs text-gray-300 leading-relaxed">
											Git, GitHub, npm, Agile/Scrum, Software Development Lifecycle, User-Centric
											Design, Algorithmic Problem-Solving, ERD Creation
										</div>
									</div>

									{/* Security & Networking */}
									<div className="bg-gray-900 rounded-lg p-3">
										<h4
											className="text-sm font-bold mb-2"
											style={{ color: '#dc2626' }}
										>
											Security & Networking
										</h4>
										<div className="text-xs text-gray-300 leading-relaxed">
											Encryption, Secure Authentication, SQL Injection Prevention, Prepared Statements,
											Secure Session Management, CORS
										</div>
									</div>

									{/* Other */}
									<div className="bg-gray-900 rounded-lg p-3">
										<h4
											className="text-sm font-bold mb-2"
											style={{ color: '#dc2626' }}
										>
											Other
										</h4>
										<div className="text-xs text-gray-300 leading-relaxed">
											SEO Principles & Implementation, UX/UI Design Principles, Asynchronous
											Programming, CRUD Operations, Technical Documentation
										</div>
									</div>
								</div>
							</div>
						</motion.div>
					</motion.div>
				)}
			</AnimatePresence>

			<style jsx>{`
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


