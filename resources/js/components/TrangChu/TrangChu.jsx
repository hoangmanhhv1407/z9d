import React, { useState, useEffect, useRef, Suspense, lazy } from 'react';
import '../../../css/trangchu.css';
import FloatingSocialMedia from '../FloatingSocialMedia';
import GameSocialMedia from '../GameSocialMedia';

const BackgroundSlideshow = lazy(() => import('./BackgroundSlideshow'));
const MainContent = lazy(() => import('./MainContent'));
const RankingTable = lazy(() => import('./RankingTable'));
const LeftSidebar = lazy(() => import('../LeftSidebar'));
const EventSchedule = lazy(() => import('./EventSchedule'));


const SidebarSkeleton = () => (
  <div className="skeleton-pulse">
    <div className="skeleton-sidebar h-12 mb-4"></div>
    <div className="space-y-3">
      <div className="skeleton-sidebar h-32"></div>
      <div className="space-y-2">
        {[1, 2, 3, 4].map((item) => (
          <div key={item} className="skeleton-sidebar h-10"></div>
        ))}
      </div>
    </div>
  </div>
);

const MainContentSkeleton = () => (
  <div className="skeleton-pulse space-y-4">
    <div className="skeleton-content h-[300px]"></div>
    <div className="space-y-4">
      {[1, 2, 3].map((item) => (
        <div key={item} className="flex gap-4">
          <div className="skeleton-content w-32 h-24"></div>
          <div className="flex-1 space-y-2">
            <div className="skeleton-content h-6 w-3/4"></div>
            <div className="skeleton-content h-4 w-1/4"></div>
          </div>
        </div>
      ))}
    </div>
  </div>
);

const LoadingWithBackground = () => (
  <div className="loading-overlay">
    <div className="loading-spinner-container">
      <div className="loading-spinner">
        <div className="spinner-ring spinner-ring-static"></div>
        <div className="spinner-ring spinner-ring-dynamic"></div>
      </div>
      <div className="loading-text">
        <div className="loading-dots">
          <span className="loading-dot">Đang</span>
          <span className="loading-dot" style={{ animationDelay: '0.1s' }}>tải</span>
          <span className="loading-dot" style={{ animationDelay: '0.2s' }}>.</span>
          <span className="loading-dot" style={{ animationDelay: '0.3s' }}>.</span>
          <span className="loading-dot" style={{ animationDelay: '0.4s' }}>.</span>
        </div>
      </div>
    </div>
  </div>
);

const TrangChu = () => {
  const [blogs] = useState([
    { id: 1, b_name: 'BẢN CẬP NHẬT MỚI GAME', b_thunbar: '/frontend/images/update1.jpg', created_at: '2024-03-20' },
    { id: 2, b_name: 'SỰ KIỆN HOÀNG GIA', b_thunbar: '/frontend/images/update2.jpg', created_at: '2024-03-21' },
  ]);

  const [isSidebarOpen, setIsSidebarOpen] = useState(false);
  const [isLoading, setIsLoading] = useState(true);
  const isMobile = useMediaQuery('(max-width: 768px)');

  useEffect(() => {
    const loadingTimer = setTimeout(() => {
      setIsLoading(false);
    }, 1500);

    return () => clearTimeout(loadingTimer);
  }, []);

  // Media query hook
  function useMediaQuery(query) {
    const [matches, setMatches] = useState(false);

    useEffect(() => {
      const media = window.matchMedia(query);
      if (media.matches !== matches) {
        setMatches(media.matches);
      }
      const listener = () => setMatches(media.matches);
      window.addEventListener('resize', listener);
      return () => window.removeEventListener('resize', listener);
    }, [matches, query]);

    return matches;
  }

  return (
    <>
      {isLoading && <LoadingWithBackground />}

      <div className="flex flex-col min-h-screen">
        {/* Hero Section - Chỉ hiển thị trên desktop */}
        {!isMobile && (
          <section className="tc-hero-section">
            <Suspense fallback={<div className="w-full h-full bg-gray-900 animate-pulse"></div>}>
              <BackgroundSlideshow />
            </Suspense>
            <div 
              className="tc-hero-decor"
              style={{ backgroundImage: 'url(/frontend/images/decor.png)' }}
            />
          </section>
        )}

        {/* Main Content Section */}
        <section className={`tc-main-section ${isMobile ? 'mobile' : ''}`}>
          <div 
            className="tc-main-background"
            style={{ backgroundImage: 'url(/frontend/images/bg-ulti.jpg)' }}
          />

          <div className="tc-content-wrapper">
            {/* Sidebar Toggle - Chỉ hiển thị trên mobile */}
            {isMobile && (
              <div className="fixed bottom-4 right-4 z-50">
                <button 
                  onClick={() => setIsSidebarOpen(!isSidebarOpen)}
                  className="tc-sidebar-toggle"
                  aria-label="Toggle Sidebar"
                >
                  <span className={`tc-toggle-line ${isSidebarOpen ? 'tc-active' : ''}`}></span>
                  <span className={`tc-toggle-line tc-middle ${isSidebarOpen ? 'tc-active' : ''}`}></span>
                  <span className={`tc-toggle-line tc-bottom ${isSidebarOpen ? 'tc-active' : ''}`}></span>
                </button>
              </div>
            )}

            <div className="tc-content-grid">
              {/* Sidebar */}
              <div className={`tc-sidebar-column ${isSidebarOpen ? 'tc-active' : ''}`}>
                <div className="tc-sidebar-sticky">
                  <Suspense fallback={<SidebarSkeleton />}>
                    <LeftSidebar />
                  </Suspense>
                </div>
              </div>

              {/* Main Content */}
              <div className="tc-main-column">
                <Suspense fallback={<MainContentSkeleton />}>
                  <MainContent />
                </Suspense>
              </div>

              {/* Character Decoration - Chỉ hiển thị trên desktop */}

            </div>

            {/* Ranking Section */}
            <div className="mt-12 relative">
              <Suspense fallback={<div className="animate-pulse bg-gray-700/50 h-96 rounded-xl"></div>}>
                <RankingTable />
              </Suspense>

            </div>
            
            <GameSocialMedia />
          </div>
        </section>
      </div>
    </>
  );
};

export default TrangChu;