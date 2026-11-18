import { useLayoutEffect, useRef, useState } from 'react';
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
  const homeScrollPosition = useRef(0);
  const previousPage = useRef(currentPage);
  const carouselWords = ['UX DESIGN', 'FRONTEND DEVELOPMENT', 'CREATIVE CODING', 'USER RESEARCH', 'CULTURAL DESIGN', 'INNOVATION', 'STORYTELLING', 'DIGITAL EXPERIENCES'];

  const handleNavigate = (route) => {
    if (currentPage === 'home' && route !== 'home') {
      homeScrollPosition.current = window.scrollY;
    }

    setCurrentPage(route);
  };

  useLayoutEffect(() => {
    if (currentPage === 'home' && previousPage.current !== 'home') {
      // Restore where the user was on the home page after leaving a work section.
      window.scrollTo(0, homeScrollPosition.current);
    } else if (currentPage !== 'home') {
      // Start each work section at its top without animating away from the saved home position.
      window.scrollTo(0, 0);
    }

    previousPage.current = currentPage;
  }, [currentPage]);

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
        <PromiseLand />
        <Work onNavigate={handleNavigate} />
        <Contact />
        <WordCarousel words={carouselWords} direction="right" speed={45} />
      </main>
      <Footer />
    </div>
  );
}
