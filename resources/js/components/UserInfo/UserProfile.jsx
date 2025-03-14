import React, { useState, useContext, Suspense, useEffect } from 'react';
import { AuthContext } from '../../contexts/AuthContext';
import {
  User, Shield, History, CreditCard, Gift, Crown, Coins, TrendingUp, Swords
} from 'lucide-react';
import { useNavigate } from 'react-router-dom';
import { dailyGiftApi } from '../../api';
import '../../../css/UserProfile.css';

// Lazy load components
const UserInfoProfile = React.lazy(() => import('./UserInfoProfile'));
const Security = React.lazy(() => import('./Security'));
const TransactionHistory = React.lazy(() => import('./TransactionHistory'));
const Recharge = React.lazy(() => import('./Recharge'));
const DailyGift = React.lazy(() => import('./DailyGift'));
const GiftCode = React.lazy(() => import('./GiftCode'));
const ChangeWeapon = React.lazy(() => import('./ChangeWeapon'));

const UserProfile = () => {
  const { user } = useContext(AuthContext);
  const [activeTab, setActiveTab] = useState('profile');
  const [vipInfo, setVipInfo] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    const fetchVipInfo = async () => {
      try {
        const response = await dailyGiftApi.getVipInfo();
        setVipInfo(response.data);
      } catch (error) {
        console.error('Error fetching VIP info:', error);
      }
    };

    if (user) {
      fetchVipInfo();
    }
  }, [user]);

  const tabs = [
    { id: 'profile', label: 'Thông tin cá nhân', icon: User },
    { id: 'security', label: 'Bảo mật', icon: Shield },
    { id: 'transactionHistory', label: 'Lịch sử giao dịch', icon: History },
    { id: 'recharge', label: 'Nạp Xu', icon: CreditCard },
    { id: 'giftcode', label: 'Nhận Gift Code', icon: Gift },
    { id: 'changeWeapon', label: 'Thay Đổi Vũ Khí', icon: Swords },
    { id: 'kyTranCac', label: 'Kỳ Trân Các', icon: Crown }
  ];

  return (
    <div className="up-container">
      <div className="up-background">
        <div className="up-content">
          <div className="up-card">
            {/* Header Profile */}
            <div className="up-header">
              <div className="up-header__content">
                {/* Profile Info */}
                <div className="up-profile">
                  <div className="up-profile__avatar-wrapper">
                    <div className={`up-profile__avatar up-profile__avatar--vip-${vipInfo?.currentVip || 0}`}>
                      {vipInfo?.currentVip > 0 ? (
                        <Crown size={64} className="up-profile__crown" />
                      ) : (
                        <User size={64} className="up-profile__user" />
                      )}
                    </div>
                  </div>
                  
                  <div className="up-profile__info">
                    <h1 className="up-profile__name">
                      {user?.userid}
                      {vipInfo?.currentVip > 0 && (
                        <span className="up-profile__vip-badge">
                          <Crown size={16} />
                          VIP {vipInfo.currentVip}
                        </span>
                      )}
                    </h1>
                    <div className="up-profile__status">
                      <p className="up-profile__member-status">Thành viên</p>
                      {vipInfo?.currentVip < 7 && (
                        <p className="up-profile__vip-progress">
                          Cần {Number(vipInfo?.xuNeeded || 0).toLocaleString()} Xu → VIP {vipInfo?.nextVip}
                        </p>
                      )}
                    </div>
                  </div>
                </div>

                {/* Stats */}
                <div className="up-stats">
                  <div className="up-stats__card">
                    <div className="up-stats__header up-stats__header--coin">
                      <Coins className="up-stats__icon" />
                      <span>Số dư Xu</span>
                    </div>
                    <p className="up-stats__value">
                      {Number(vipInfo?.totalCoins || 0).toLocaleString()}
                    </p>
                  </div>

                  <div className="up-stats__card">
                    <div className="up-stats__header up-stats__header--deposit">
                      <TrendingUp className="up-stats__icon" />
                      <span>Tổng nạp</span>
                    </div>
                    <p className="up-stats__value">
                      {Number(vipInfo?.totalDeposits || 0).toLocaleString()}
                    </p>
                  </div>
                </div>
              </div>
              
              {/* VIP Progress Bar */}
              {vipInfo?.currentVip < 7 && (
                <div className="up-vip-progress">
                  <div className="up-vip-progress__header">
                    <span>Tiến trình VIP {vipInfo?.nextVip}</span>
                    <span>{vipInfo?.progress?.toFixed(1)}%</span>
                  </div>
                  <div className="up-vip-progress__bar">
                    <div 
                      className="up-vip-progress__fill"
                      style={{ width: `${vipInfo?.progress || 0}%` }}
                    />
                  </div>
                </div>
              )}
            </div>

            {/* Main Content */}
            <div className="up-main">
              <div className="up-layout">
                {/* Tabs */}
                <div className="up-tabs">
                  <div className="up-tabs__list">
                    {tabs.map((tab) => (
                      <button
                        key={tab.id}
                        onClick={() => tab.id === 'kyTranCac' ? navigate('/ky-tran-cac') : setActiveTab(tab.id)}
                        className={`up-tabs__button ${activeTab === tab.id ? 'up-tabs__button--active' : ''}`}
                      >
                        <tab.icon className="up-tabs__icon" />
                        {tab.label}
                      </button>
                    ))}
                  </div>
                </div>

                {/* Content */}
                <div className="up-content">
                  <div className="up-content__inner">
                    <Suspense fallback={<div className="up-loading">Đang tải...</div>}>
                      {activeTab === 'profile' && <UserInfoProfile user={user} />}
                      {activeTab === 'security' && <Security user={user} />}
                      {activeTab === 'transactionHistory' && <TransactionHistory />}
                      {activeTab === 'recharge' && <Recharge setActiveTab={setActiveTab} />}
                      {activeTab === 'dailyGift' && <DailyGift user={user} />}
                      {activeTab === 'giftcode' && <GiftCode user={user} />}
                      {activeTab === 'changeWeapon' && <ChangeWeapon />}
                    </Suspense>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  );
};

export default UserProfile;