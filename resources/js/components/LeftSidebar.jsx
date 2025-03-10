import React, { useContext, useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { createPortal } from 'react-dom';
import { 
  Download, Gift, CreditCard, HelpCircle, User, 
  LogOut, Signal, Castle, Timer, Map, Gauge, 
  ArrowUpRight, Swords, AlertCircle, Crown 
} from 'lucide-react';
import { AuthContext } from '../contexts/AuthContext';
import axios from 'axios';
import RegistrationStatusPopup from './RegistrationStatusPopup';
import '../../css/LeftSidebar.css';

const LeftSidebar = () => {
  const { isAuthenticated, user, logout, loading } = useContext(AuthContext);
  const [isActivated, setIsActivated] = useState(true);
  const [showActivationPopup, setShowActivationPopup] = useState(false);

  useEffect(() => {
    const checkActivationStatus = async () => {
      if (isAuthenticated && user?.email) {
        try {
          const response = await axios.get(`/api/check-activation-status/${user.email}`);
          setIsActivated(response.data.isActivated);
        } catch (error) {
          console.error('Lỗi kiểm tra trạng thái kích hoạt:', error);
        }
      }
    };
    checkActivationStatus();
  }, [isAuthenticated, user]);

  const handleActivationClick = () => {
    if (!isActivated && user?.email) {
      setShowActivationPopup(true);
    }
  };

  const serverStatus = "online";

  return (
    <div className="ls-container">
      {/* Nút Tải Game/Kích hoạt */}
      <div className="ls-card ls-download-section">
        {isAuthenticated && !isActivated ? (
          <button onClick={handleActivationClick} className="ls-activation-button">
            <AlertCircle className="icon" />
            <span>KÍCH HOẠT TÀI KHOẢN</span>
          </button>
        ) : (
		<a href="https://zplay9d.net/tin-tuc/tai-ve-va-cai-dat-game" className="ls-download-button" target="_blank" rel="noopener noreferrer">
		  <Download className="icon" />
		  <span>TẢI GAME</span>
		</a>
        )}
      </div>

      {/* Thông tin người dùng */}
      {!loading && isAuthenticated && user && (
        <div className="ls-card ls-user-info">
          <Link to="/thong-tin-tai-khoan" className="ls-user-profile">
            <div className="ls-avatar">
              <User className="ls-avatar-icon" />
            </div>
            <div className="ls-user-details">
              <h3>{user.userid}</h3>
              <span>Thành viên</span>
            </div>
            <button onClick={(e) => {
              e.preventDefault();
              logout();
            }} className="ls-logout-btn">
              <LogOut />
            </button>
          </Link>
          
          <div className="ls-user-stats">
            <div className="ls-stat-item">
              <span className="ls-stat-label">Xu:</span>
              <div className="ls-coin-display">
                <span>{Number(user.coin || 0).toLocaleString()}</span>
                <img src="/frontend/images/gold.png" alt="xu" />
              </div>
            </div>
            <Link to="/ky-tran-cac" className="ls-stat-item clickable">
              <span className="ls-stat-label">Kỳ Trân Các</span>
              <ArrowUpRight />
            </Link>
            <Link to="/thong-tin-tai-khoan" className="ls-stat-item clickable">
              <span className="ls-stat-label">Nạp Xu Tự Động</span>
              <CreditCard />
            </Link>
          </div>
        </div>
      )}

      {/* Thông tin Server */}
      <div className="ls-card ls-server-info">
        <div className="ls-card-header">
          <Crown className="ls-header-icon" />
          <h3>Thông Tin Server</h3>
        </div>

        <div className={`ls-server-status ${serverStatus === 'online' ? 'online' : ''}`}>
          <div className="ls-status-indicator">
            <Signal />
            <span>Trạng Thái:</span>
          </div>
          <span className="ls-status-text">
            {serverStatus === 'online' ? 'ĐANG HOẠT ĐỘNG' : 'BẢO TRÌ'}
          </span>
        </div>

        <div className="ls-daily-events">
          <div className="ls-events-header">
            <span>Lịch Sự Kiện Hàng Ngày</span>
          </div>
          <div className="ls-events-list">
            <div className="ls-event-item">
              <div className="ls-event-name">
                <Swords className="ls-event-icon" />
                <span>Hắc Bạch Đại Chiến:</span>
              </div>
              <span className="ls-event-time">20:00 - 21:00</span>
            </div>
            <div className="ls-event-item">
              <div className="ls-event-name">
                <Castle className="ls-event-icon" />
                <span>Trang Viên Chiến:</span>
              </div>
              <span className="ls-event-time">21:00 - 22:00</span>
            </div>
          </div>
        </div>

        <div className="ls-server-stats">
          <div className="ls-stat-group">
            <div className="ls-stat-row">
              <div className="ls-stat-label">
                <Gauge className="ls-stat-icon" />
                <span>Tỉ Lệ Kinh Nghiệm:</span>
              </div>
              <span className="ls-stat-value">x5</span>
            </div>
            <div className="ls-stat-row">
              <div className="ls-stat-label">
                <ArrowUpRight className="ls-stat-icon" />
                <span>Giai Đoạn:</span>
              </div>
              <span className="ls-stat-value">Giai đoạn 1</span>
            </div>
          </div>

          <div className="ls-stat-group">
            <div className="ls-stat-row">
              <div className="ls-stat-label">
                <Timer className="ls-stat-icon" />
                <span>Level Tối Đa:</span>
              </div>
              <span className="ls-stat-value">300</span>
            </div>
            <div className="ls-stat-row">
              <div className="ls-stat-label">
                <Map className="ls-stat-icon" />
                <span>Map Giới Hạn:</span>
              </div>
              <span className="ls-stat-value">Tô Châu</span>
            </div>
          </div>
        </div>
      </div>

      {showActivationPopup && createPortal(
        <RegistrationStatusPopup
          isOpen={showActivationPopup}
          email={user?.email}
          onClose={() => setShowActivationPopup(false)}
        />,
        document.getElementById('popup-root')
      )}
    </div>
  );
};

export default LeftSidebar;