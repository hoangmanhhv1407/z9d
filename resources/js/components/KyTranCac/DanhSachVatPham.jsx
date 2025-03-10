import React, { useState, lazy, Suspense, memo } from 'react';
import { User, Coins, Diamond, ShoppingBag, Gift, Star } from 'lucide-react';
import { useAuth } from '../../contexts/AuthContext';
import '../../../css/DanhSachVatPham.css';

// Lazy load components
const MainShop = lazy(() => import('./MainShop'));
const AccumulateShop = lazy(() => import('./AccumulateShop'));

// Memoized components
const MemoizedMainShop = memo(MainShop);
const MemoizedAccumulateShop = memo(AccumulateShop);

const ShopLoader = () => (
  <div className="dsvp-loader">
    <div className="dsvp-loader__container">
      <div className="dsvp-loader__spinner" />
      <span className="dsvp-loader__text">Đang tải...</span>
    </div>
  </div>
);

const DanhSachVatPham = () => {
  const [activeShop, setActiveShop] = useState('main');
  const [activeTab, setActiveTab] = useState('Điểm');
  const { user, loading } = useAuth();

  const tabs = ['Điểm', 'Ưu Đãi', 'Võ Đạo', 'Guild', 'Rune', 'Bầu Vật Cảm'];

  if (loading) {
    return (
      <div className="dsvp-loading">
        <div className="dsvp-loading__container">
          <div className="dsvp-loading__spinner-container">
            <div className="dsvp-loading__spinner-outer"></div>
            <div className="dsvp-loading__spinner-inner"></div>
          </div>
          <div className="dsvp-loading__text">
            <div className="dsvp-loading__text-animation">
              <span className="dsvp-loading__text-dot">Đang</span>
              <span className="dsvp-loading__text-dot">tải</span>
              <span className="dsvp-loading__text-dot">.</span>
              <span className="dsvp-loading__text-dot">.</span>
              <span className="dsvp-loading__text-dot">.</span>
            </div>
          </div>
        </div>
      </div>
    );
  }

  const renderShopContent = () => {
    return (
      <div className="dsvp-content">
        <div className={`dsvp-content__shop ${activeShop === 'main' ? 'dsvp-content__shop--active' : ''}`}>
          <Suspense fallback={<ShopLoader />}>
            <MemoizedMainShop
              activeTab={activeTab}
              setActiveTab={setActiveTab}
              tabs={tabs}
            />
          </Suspense>
        </div>
        <div className={`dsvp-content__shop ${activeShop === 'accumulate' ? 'dsvp-content__shop--active' : ''}`}>
          <Suspense fallback={<ShopLoader />}>
            <MemoizedAccumulateShop />
          </Suspense>
        </div>
      </div>
    );
  };

  return (
    <div className="dsvp-container">
      <div className="dsvp-background">
        <div className="dsvp-wrapper">
          <div className="dsvp-layout">
            {/* Sidebar */}
            <div className="dsvp-sidebar">
              <div className="dsvp-sidebar__sticky">
                <div className="dsvp-profile">
                  {/* User Profile */}
                  <div className="dsvp-profile__info">
                    <div className="dsvp-profile__avatar">
                      <User size={80} className="dsvp-profile__icon" />
                    </div>
                    <h3 className="dsvp-profile__name">
                      {user?.userid || 'Khách'}
                    </h3>
                  </div>

                  {/* Stats */}
                  <div className="dsvp-stats">
                    <div className="dsvp-stats__item">
                      <span className="dsvp-stats__label">
                        <Coins className="dsvp-stats__icon" />
                        Xu
                      </span>
                      <span className="dsvp-stats__value">
                        {Number(user?.coin || 0).toLocaleString()}
                      </span>
                    </div>

                    <div className="dsvp-stats__item">
                      <span className="dsvp-stats__label">
                        <Star className="dsvp-stats__icon" />
                        Điểm Tích Lũy
                      </span>
                      <span className="dsvp-stats__value">
                        {Number(user?.accumulate || 0).toLocaleString()}
                      </span>
                    </div>

                    <div className="dsvp-stats__item">
                      <span className="dsvp-stats__label">
                        <Diamond className="dsvp-stats__icon" />
                        Tổng tiêu xu
                      </span>
                      <span className="dsvp-stats__value">
                        {Number(user?.tongtieucoin || 0).toLocaleString()}
                      </span>
                    </div>
                  </div>

                  {/* Navigation Buttons */}
                  <div className="dsvp-nav">
                    <button
                      className={`dsvp-nav__button ${activeShop === 'main' ? 'dsvp-nav__button--active' : ''}`}
                      onClick={() => setActiveShop('main')}
                    >
                      <ShoppingBag className="dsvp-nav__icon" />
                      Kỳ Trân Các
                    </button>
                    <button
                      className={`dsvp-nav__button ${activeShop === 'accumulate' ? 'dsvp-nav__button--active' : ''}`}
                      onClick={() => setActiveShop('accumulate')}
                    >
                      <Coins className="dsvp-nav__icon" />
                      Shop Tích Lũy
                    </button>
                  </div>
                </div>
              </div>
            </div>

            {/* Main Content */}
            <div className="dsvp-main">
              <div className="dsvp-main__content">
                {renderShopContent()}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default DanhSachVatPham;