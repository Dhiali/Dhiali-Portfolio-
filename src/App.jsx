import { useState } from 'react';
import { Header } from './components/Header';
import { Hero } from './components/Hero';
import { WordCarousel } from './components/WordCarousel';
import { PromiseLand } from './components/PromiseLand';
import { Work } from './components/Work';
import { Contact } from './components/Contact';
import { Footer } from './components/Footer';
import { SplashScreen } from './components/SplashScreen';
import { UXWork } from './components/UXWork';
import { InteractiveDev } from './components/InteractiveDev';
import { PersonalWork } from './components/PersonalWork';

export default function App() {
  const [currentPage, setCurrentPage] = useState('home');
  const [showSplash, setShowSplash] = useState(true);
  const carouselWords1 = ['UX DESIGN', 'FRONTEND DEVELOPMENT', 'CREATIVE CODING', 'USER RESEARCH'];
  const carouselWords2 = ['CULTURAL DESIGN', 'INNOVATION', 'STORYTELLING', 'DIGITAL EXPERIENCES'];

  const handleNavigate = (route) => {
    setCurrentPage(route);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const handleSplashComplete = () => {
    setShowSplash(false);
  };

  // Show splash screen first
  if (showSplash) {
    return <SplashScreen onComplete={handleSplashComplete} />;
  }

  // Render different pages based on route
  if (currentPage === 'ux-work') {
    return <UXWork onNavigate={handleNavigate} />;
  }

  if (currentPage === 'interactive-dev') {
    return <InteractiveDev onNavigate={handleNavigate} />;
  }

  if (currentPage === 'personal-work') {
    return <PersonalWork onNavigate={handleNavigate} />;
  }

  // Home page
  return (
    <div className="min-h-screen overflow-x-hidden bg-background">
      <Header />
      <main>
        <Hero />
        <WordCarousel words={carouselWords1} direction="left" speed={45} />
        <PromiseLand />
        <WordCarousel words={carouselWords2} direction="right" speed={45} />
        <Work onNavigate={handleNavigate} />
        <Contact />
      </main>
      <Footer />
    </div>
  );
}


