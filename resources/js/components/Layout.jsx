import React, { useContext, useState, useEffect } from 'react';
import { Link, useNavigate, useLocation, useSearchParams } from 'react-router-dom';
import { Menu, X } from 'lucide-react';
import LoginPopup from './LoginPopup';
import RegisterPopup from './RegisterPopup';
import { logout } from '../api';
import { AuthContext } from '../contexts/AuthContext';
import toast from 'react-hot-toast';
import LoadingScreen from './LoadingScreen';
import RegistrationStatusPopup from './RegistrationStatusPopup';
import '../../css/layout.css';

const Layout = ({ children }) => {
  const { isAuthenticated, user, logout: contextLogout } = useContext(AuthContext);
  const [isLoginOpen, setIsLoginOpen] = useState(false);
  const [isRegisterOpen, setIsRegisterOpen] = useState(false);
  const [isLoggingOut, setIsLoggingOut] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [activationEmail, setActivationEmail] = useState(null);
  const [showActivationStatus, setShowActivationStatus] = useState(false);
  const [isScrolled, setIsScrolled] = useState(false);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const [openDropdowns, setOpenDropdowns] = useState({});
  const [searchParams] = useSearchParams();
  const location = useLocation();
  const navigate = useNavigate();

  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 50);
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  useEffect(() => {
    const email = searchParams.get('email');
    const token = searchParams.get('token');
    const isActivation = location.pathname.includes('/activate-account');

    if (email && isActivation) {
      setActivationEmail(decodeURIComponent(email));
      setShowActivationStatus(true);
      navigate(location.pathname, { replace: true });
    }
  }, [location, searchParams, navigate]);

  useEffect(() => {
    const isActivationRoute = location.pathname.startsWith('/activate-account');
    const publicRoutes = ['/', '/tin-tuc', '/huong-dan', '/cam-nang-tan-thu'];

    if (!isAuthenticated && !isActivationRoute && !publicRoutes.includes(location.pathname)) {
      setIsLoginOpen(true);
    }
  }, [isAuthenticated, location.pathname]);

  const handleLogout = async () => {
    if (isLoggingOut) return;
    setIsLoggingOut(true);
    try {
      await logout();
      localStorage.removeItem('token');
      await contextLogout();
      toast.success('Đăng xuất thành công!', { duration: 3000, position: 'top-right' });
      navigate('/');
    } catch (error) {
      console.error('Logout error:', error);
      localStorage.removeItem('token');
      await contextLogout();
      toast.success('Đã đăng xuất khỏi hệ thống', { duration: 3000, position: 'top-right' });
      navigate('/');
    } finally {
      setIsLoggingOut(false);
      closeMobileMenu();
    }
  };

  const toggleDropdown = (key) => {
    setOpenDropdowns(prev => ({
      ...prev,
      [key]: !prev[key]
    }));
  };

  const closeMobileMenu = () => {
    setIsMobileMenuOpen(false);
    setOpenDropdowns({});
  };

  const renderAuthButton = () => {
    if (isAuthenticated && user) {
      return (
        <div className="flex items-center space-x-4">
          <a href="/thong-tin-tai-khoan">
            <div className="ly-user-profile">
              <span className="ly-user-name">{user.userid}</span>
            </div>
          </a>
          <button
            className="ly-logout-button"
            onClick={handleLogout}
            disabled={isLoggingOut}
          >
            {isLoggingOut ? 'Đang đăng xuất...' : 'Đăng xuất'}
          </button>
        </div>
      );
    }
    return (
      <button
        className="ly-login-button"
        onClick={() => setIsLoginOpen(true)}
      >
        Đăng nhập/đăng ký
      </button>
    );
  };

  return (
    <div className="ly-container">
      {isLoading && <LoadingScreen />}

      <header className={`ly-header ${isScrolled ? 'header-scrolled' : ''}`}>
        <div className="ly-header-content">
          <nav className="ly-nav">
            {/* Logo */}
            <div className="ly-logo-container">
              <Link to="/" className="ly-logo-link">
                <div className="ly-logo-wrapper">
                  <img src="/frontend/images/z9dlogo.png" alt="logo" className="ly-logo-image" />
                </div>
              </Link>
            </div>
            
            {/* Desktop Menu */}
            <div className="ly-nav-menu">
              <ul className="ly-nav-list">
                <li className="ly-nav-item">
                  <Link to="/" className="ly-nav-link">
                    <img src="/frontend/images/header-home.png" alt="trang chủ" className="ly-nav-icon" />
                    Trang chủ
                  </Link>
                </li>
                <li className="ly-nav-item">
                  <Link to="/tin-tuc" className="ly-nav-link">
                    Tin tức
                  </Link>
                </li>
                <li className="ly-nav-item">
                  <button className="ly-nav-link">
                    Hướng dẫn
                  </button>
                  <ul className="ly-dropdown-menu">
                    <li><Link to="/huong-dan-dang-ky-tai-khoan">Đăng ký tài khoản</Link></li>
                    <li><Link to="/huong-dan-tinh-nang">Tính năng</Link></li>
                    <li><Link to="/huong-dan-nap">Donates</Link></li>
                  </ul>
                </li>
                <li className="ly-nav-item">
                  <Link to="/cam-nang-tan-thu" className="ly-nav-link">
                    Cẩm nang tân thủ
                  </Link>
                </li>
                <li className="ly-nav-item">
                  <button className="ly-nav-link">
                    Hỗ trợ
                  </button>
                  <ul className="ly-dropdown-menu">
                    <li><Link to="/cau-hoi-thuong-gap">FAQ</Link></li>
                    <li><Link to="/chinh-sach-chung">Chính sách chung</Link></li>
                  </ul>
                </li>
              </ul>
            </div>

            {/* Mobile Menu Button */}
            <button
              className="ly-mobile-menu-button"
              onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
            >
              {isMobileMenuOpen ? <X size={24} /> : <Menu size={24} />}
            </button>

            {/* Mobile Menu */}
            <div className={`ly-mobile-menu ${isMobileMenuOpen ? 'open' : ''}`}>
              <nav className="ly-mobile-nav">
                <ul className="ly-mobile-nav-list">
                  <li className="ly-mobile-nav-item">
                    <Link to="/" className="ly-mobile-nav-link" onClick={closeMobileMenu}>
                      Trang chủ
                    </Link>
                  </li>
                  <li className="ly-mobile-nav-item">
                    <Link to="/tin-tuc" className="ly-mobile-nav-link" onClick={closeMobileMenu}>
                      Tin tức
                    </Link>
                  </li>
                  <li className="ly-mobile-nav-item">
                    <button 
                      className="ly-mobile-nav-link w-full flex justify-between items-center"
                      onClick={() => toggleDropdown('guide')}
                    >
                      Hướng dẫn
                      <span>{openDropdowns.guide ? '▼' : '▶'}</span>
                    </button>
                    <div className={`ly-mobile-dropdown ${openDropdowns.guide ? 'open' : ''}`}>
                      <Link to="/huong-dan-dang-ky-tai-khoan" onClick={closeMobileMenu}>
                        Đăng ký tài khoản
                      </Link>
                      <Link to="/huong-dan-tinh-nang" onClick={closeMobileMenu}>
                        Tính năng
                      </Link>
                      <Link to="/huong-dan-nap-silk" onClick={closeMobileMenu}>
                        Donates
                      </Link>
                    </div>
                  </li>
                  <li className="ly-mobile-nav-item">
                    <Link to="/cam-nang-tan-thu" className="ly-mobile-nav-link" onClick={closeMobileMenu}>
                      Cẩm nang tân thủ
                    </Link>
                  </li>
                  <li className="ly-mobile-nav-item">
                    <button 
                      className="ly-mobile-nav-link w-full flex justify-between items-center"
                      onClick={() => toggleDropdown('support')}
                    >
                      Hỗ trợ
                      <span>{openDropdowns.support ? '▼' : '▶'}</span>
                    </button>
                    <div className={`ly-mobile-dropdown ${openDropdowns.support ? 'open' : ''}`}>
                      <Link to="/cau-hoi-thuong-gap" onClick={closeMobileMenu}>FAQ</Link>
                      <Link to="/chinh-sach-chung" onClick={closeMobileMenu}>Chính sách chung</Link>
                    </div>
                  </li>
                </ul>

                {/* Mobile Auth */}
                <div className="ly-mobile-auth">
                  {isAuthenticated ? (
                    <div className="ly-mobile-profile">
                      <div className="ly-mobile-username">{user.userid}</div>
                      <button 
                        className="ly-logout-button mt-4"
                        onClick={handleLogout}
                      >
                        Đăng xuất
                      </button>
                    </div>
                  ) : (
                    <button
                      className="ly-login-button w-full"
                      onClick={() => {
                        setIsLoginOpen(true);
                        closeMobileMenu();
                      }}
                    >
                      Đăng nhập/đăng ký
                    </button>
                  )}
                </div>
              </nav>
            </div>

            {/* Desktop Auth */}
            <div className="ly-auth-container hidden md:block">
              {renderAuthButton()}
            </div>
          </nav>
        </div>
      </header>

      <main className="ly-content">
        <div className="ly-content-container">
          {children}
        </div>
      </main>

      <footer className="ly-footer">
        <div className="ly-footer-content">
          <div className="ly-footer-brand">TEAM Z</div>
          <div className="ly-footer-links">
            <Link to="/about">Giới thiệu</Link>
            <Link to="/privacy">Chính sách bảo mật</Link>
            <Link to="/terms">Điều khoản dịch vụ</Link>
            <Link to="/contact">Liên hệ</Link>
          </div>
          <div className="ly-footer-copyright">
            <p>&copy; 2025 Cửu Long Tranh Bá. Bản quyền bởi TEAM Z.</p>
          </div>
        </div>
      </footer>

      {/* Popups */}
      <RegistrationStatusPopup
        isOpen={showActivationStatus}
        email={activationEmail}
        onClose={() => {
          setShowActivationStatus(false);
          setActivationEmail(null);
          navigate('/', { replace: true });
        }}
        isActivationFlow={true}
      />
      
      {!isAuthenticated && (
        <>
          <LoginPopup
            isOpen={isLoginOpen}
            onClose={() => setIsLoginOpen(false)}
            onSwitchToRegister={() => {
              setIsLoginOpen(false);
              setIsRegisterOpen(true);
            }}
          />
          <RegisterPopup
            isOpen={isRegisterOpen}
            onClose={() => setIsRegisterOpen(false)}
            onSwitchToLogin={() => {
              setIsRegisterOpen(false);
              setIsLoginOpen(true);
            }}
          />
        </>
      )}
    </div>
  );
};

export default Layout;