import React, { useState, useEffect } from 'react';
import { 
  Clock, Gift, Crown, Star, Info, ChevronRight, X, Check, 
  AlertCircle, Loader, Timer, ArrowRight, ChevronUp
} from 'lucide-react';
import { toast } from 'react-hot-toast';
import api from '../../api';
import ReactDOM from 'react-dom';
import '../../../css/RechargeEvents.css';

const RechargeEvents = () => {
  const [eventConfig, setEventConfig] = useState(null);
  const [userStatus, setUserStatus] = useState(null);
  const [timeSettings, setTimeSettings] = useState(null);
  const [loading, setLoading] = useState(true);
  const [selectedEvent, setSelectedEvent] = useState(null);
  const [claimingFirstRecharge, setClaimingFirstRecharge] = useState(false);
  const [claimingMilestone, setClaimingMilestone] = useState(false);
  const [activeTab, setActiveTab] = useState('overview');

  useEffect(() => {
    fetchEventConfig();
    fetchUserStatus();
    fetchTimeSettings();
  }, []);
  
  const fetchTimeSettings = async () => {
    try {
      const response = await api.get('/time-settings');
      if (response.data.success) {
        setTimeSettings(response.data.data);
      }
    } catch (error) {
      console.error('Error fetching time settings:', error);
    }
  };

  const fetchEventConfig = async () => {
    try {
      const response = await api.get('/recharge-events/config');
      if (response.data.success) {
        setEventConfig(response.data.data);
      }
    } catch (error) {
      toast.error('Không thể tải cấu hình sự kiện');
    } finally {
      setLoading(false);
    }
  };

  const fetchUserStatus = async () => {
    try {
      const response = await api.get('/recharge-events/user-status');
      if (response.data.success) {
        setUserStatus(response.data.data);
      }
    } catch (error) {
      console.error('Error:', error);
    }
  };

  const formatDateTime = (dateString) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleString('vi-VN');
  };

  const handleClaimFirstRecharge = async () => {
    if (claimingFirstRecharge) return;
    setClaimingFirstRecharge(true);
    
    try {
      const response = await api.post('/recharge-events/claim-first-recharge');
      if (response.data.success) {
        toast.custom((t) => (
          <div className="bg-white rounded-lg shadow-lg p-4 max-w-md w-full">
            <div className="flex items-start">
              <div className="flex-shrink-0">
                <Gift className="h-6 w-6 text-green-500" />
              </div>
              <div className="ml-3 w-full">
                <p className="text-sm font-medium text-gray-900">
                  Nhận quà thành công!
                </p>
                <div className="mt-2 space-y-1">
                  {response.data.data.gifts.map((gift, idx) => (
                    <div key={idx} className="flex items-center text-sm text-gray-500">
                      <Check className="w-4 h-4 mr-1 text-green-500" />
                      <span>{gift.name}</span>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </div>
        ), { duration: 5000 });
        fetchUserStatus();
      }
    } catch (error) {
      toast.error(error.response?.data?.message || 'Có lỗi xảy ra');
    } finally {
      setClaimingFirstRecharge(false);
    }
  };
  const handleClaimMilestone = async (amount) => {
    if (claimingMilestone) return;
    setClaimingMilestone(true);
    
    try {
      const response = await api.post('/recharge-events/claim-milestone', { amount });
      if (response.data.success) {
        toast.custom((t) => (
          <div className="bg-white rounded-lg shadow-lg p-4 max-w-md w-full">
            <div className="flex items-start">
              <div className="flex-shrink-0">
                <Gift className="h-6 w-6 text-green-500" />
              </div>
              <div className="ml-3 w-full">
                <p className="text-sm font-medium text-gray-900">
                  Nhận quà thành công!
                </p>
                <div className="mt-2 space-y-1">
                  {response.data.data.gifts.map((gift, idx) => (
                    <div key={idx} className="flex items-center text-sm text-gray-500">
                      <Check className="w-4 h-4 mr-1 text-green-500" />
                      <span>{gift.name}</span>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </div>
        ), { duration: 5000 });
        fetchUserStatus();
      }
    } catch (error) {
      toast.error(error.response?.data?.message || 'Có lỗi xảy ra khi nhận quà');
    } finally {
      setClaimingMilestone(false);
    }
  };
  const events = [
    {
      id: 'first_recharge',
      title: "Nạp Lần Đầu",
      icon: Crown,
      color: "blue",
      description: "Ưu đãi đặc biệt cho người chơi mới",
      status: eventConfig?.first_recharge_status,
      timeRange: {
        start: eventConfig?.first_recharge_start_time,
        end: eventConfig?.first_recharge_end_time
      }
    },
    {
      id: 'milestone',
      title: "Nạp Theo Mốc",
      icon: Star,
      color: "amber",
      description: "Phần thưởng hấp dẫn theo từng mốc nạp",
      status: eventConfig?.milestone_status,
      timeRange: {
        start: eventConfig?.milestone_start_time,
        end: eventConfig?.milestone_end_time
      }
    },
    {
      id: 'multiplier_event',
      title: "Sự Kiện Nhân Xu",
      icon: Clock,
      color: "purple",
      description: timeSettings ? `Nhận x${timeSettings.number} giá trị nạp` : "Nhân giá trị nạp",
      status: timeSettings?.status === 1,
      lastUpdated: timeSettings?.updated_at,
      
    }
  ];

  if (loading) {
    return (
      <div className="re-loading">
        <div className="re-loading__spinner" />
      </div>
    );
  }
// Thêm các components này vào cùng file RechargeEvents.jsx hoặc tạo file riêng

const OverviewTab = ({ selectedEvent, timeSettings, formatDateTime }) => {
  return (
    <div className="re-overview">
      <div className="re-overview__grid">
        <div className="re-overview__card">
          <div className="re-overview__label">Trạng thái</div>
          <div className="re-overview__value">
            {selectedEvent.status ? 'Đang diễn ra' : 'Tạm dừng'}
          </div>
        </div>
        {selectedEvent.id !== 'multiplier_event' && (
          <div className="re-overview__card">
            <div className="re-overview__label">Thời gian còn lại</div>
            <div className="re-overview__value">
              {formatDateTime(selectedEvent.timeRange?.end)}
            </div>
          </div>
        )}
      </div>

      {selectedEvent.id === 'multiplier_event' && (
        <div className="re-overview__multiplier">
          <div className="re-overview__multiplier-header">
            <span className="re-overview__multiplier-label">Hệ số nhân hiện tại</span>
            <span className="re-overview__multiplier-value">
              x{timeSettings?.number || 1}
            </span>
          </div>
          <div className="mt-2 text-sm text-purple-600 flex items-center">
            <Clock className="w-4 h-4 mr-1" />
            Cập nhật: {formatDateTime(timeSettings?.created_at)}
          </div>
          <div className="mt-1 text-sm text-purple-600">
            {timeSettings?.status === 1 ? (
              <span className="flex items-center">
                <Check className="w-4 h-4 mr-1" />
                Sự kiện đang diễn ra
              </span>
            ) : (
              <span className="flex items-center">
                <X className="w-4 h-4 mr-1" />
                Sự kiện tạm dừng
              </span>
            )}
          </div>
        </div>
      )}
    </div>
  );
};

const RewardsTab = ({ 
  selectedEvent, 
  eventConfig, 
  userStatus, 
  timeSettings,
  handleClaimFirstRecharge,
  handleClaimMilestone,
  claimingFirstRecharge,
  claimingMilestone 
}) => {
	  // Thêm hàm chuyển đổi VND sang xu
  const vndToXu = (vnd) => {
    return Math.floor(vnd / 1000);
  };
  return (
    <div className="re-rewards">
{selectedEvent.id === 'first_recharge' && eventConfig?.first_recharge_gifts && (
  <div className="re-reward-card">
    <div className="re-reward-header">
      <span className="re-reward-title">Quà nạp lần đầu (tối thiểu 10 xu)</span>
      <button
        onClick={handleClaimFirstRecharge}
        disabled={claimingFirstRecharge || !userStatus?.has_first_recharge || userStatus?.has_claimed_first_recharge}
        className={`re-reward-button ${userStatus?.has_claimed_first_recharge
          ? 're-reward-button--disabled'
          : 're-reward-button--active'}`}
      >
        {claimingFirstRecharge ? (
          <>
            <Loader className="w-4 h-4 animate-spin" />
            <span>Đang xử lý...</span>
          </>
        ) : userStatus?.has_claimed_first_recharge ? (
          'Đã nhận'
        ) : !userStatus?.has_first_recharge ? (
          'Chưa đủ điều kiện'
        ) : (
          'Nhận quà'
        )}
      </button>
    </div>
          <div className="re-reward-content">
            <div className="re-reward-grid">
              {eventConfig.first_recharge_gifts.map((gift, idx) => (
                <div key={idx} className="re-reward-item">
                  <img 
                    src={gift.image}
                    alt={gift.name}
                    className="re-reward-image"
                  />
                  <div className="re-reward-info">
                    <div className="re-reward-name">{gift.name}</div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      )}

{selectedEvent.id === 'milestone' && eventConfig?.milestone_gifts?.map((milestone, idx) => {
  // Mốc VND trong cấu hình
  const milestoneVND = milestone.amount;
  // Chuyển sang xu để so sánh
  const milestoneXu = vndToXu(milestoneVND);
  
  return (
    <div key={idx} className="re-reward-card">
      <div className="re-reward-header">
        <span className="re-reward-title">
          Mốc {milestoneXu.toLocaleString()} xu 
          <span className="text-xs text-gray-500 ml-1">({milestoneVND.toLocaleString()}đ)</span>
        </span>
        <button
          onClick={() => handleClaimMilestone(milestoneVND)}
          disabled={userStatus?.claimed_milestones?.includes(milestoneVND) || 
            claimingMilestone || 
            userStatus?.total_recharge < milestoneXu}
          className={`re-reward-button ${
            userStatus?.claimed_milestones?.includes(milestoneVND) || 
            userStatus?.total_recharge < milestoneXu
              ? 're-reward-button--disabled'
              : 're-reward-button--active'
          }`}
        >
          {claimingMilestone ? (
            <div className="flex items-center">
              <Loader className="w-4 h-4 animate-spin mr-2" />
              <span>Đang xử lý...</span>
            </div>
          ) : userStatus?.claimed_milestones?.includes(milestoneVND) ? (
            <span className="flex items-center">
              <Check className="w-4 h-4 mr-1" />
              Đã nhận
            </span>
          ) : userStatus?.total_recharge < milestoneXu ? (
            'Chưa đủ điều kiện'
          ) : (
            'Nhận quà'
          )}
        </button>
      </div>
      <div className="re-reward-content">
        <div className="re-reward-grid">
          {milestone.products.map((product, pidx) => (
            <div key={pidx} className="re-reward-item">
              <img 
                src={product.image}
                alt={product.name}
                className="re-reward-image"
              />
              <div className="re-reward-info">
                <div className="re-reward-name">{product.name}</div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
})}

      {selectedEvent.id === 'multiplier_event' && (
        <div className={`re-multiplier-reward ${timeSettings?.status === 0 ? 'opacity-50' : ''}`}>
          <div className="text-center space-y-4">
            <div className="text-4xl font-bold text-purple-600">
              x{timeSettings?.number || 1}
            </div>
            <div className="text-purple-700">
              {timeSettings?.status === 0 
                ? "Tất cả giao dịch nạp sẽ được nhân với hệ số này"
                : "Sự kiện đang tạm dừng"}
            </div>
            <div className="text-sm text-purple-600">
              {timeSettings?.status === 1 
                ? "Áp dụng tự động cho mọi giao dịch"
                : "Vui lòng đợi đến khi sự kiện được kích hoạt"}
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

const RulesTab = ({ selectedEvent, timeSettings, formatDateTime }) => {
  return (
    <div className="re-rules">
      {selectedEvent.id === 'first_recharge' && (
        <>
          <div className="re-rules-section re-rules-section--blue">
            <h4 className="re-rules-title re-rules-title--blue">Điều kiện nhận quà</h4>
            <ul className="re-rules-list">
              <li className="re-rules-item">
                <Check className="re-rules-icon" />
                <span className="re-rules-text re-rules-text--blue">
                  Nạp tối thiểu 10.000đ trong lần nạp đầu tiên
                </span>
              </li>
              <li className="re-rules-item">
                <Check className="re-rules-icon" />
                <span className="re-rules-text re-rules-text--blue">
                  Chưa từng nhận quà nạp lần đầu trước đây
                </span>
              </li>
              <li className="re-rules-item">
                <Check className="re-rules-icon" />
                <span className="re-rules-text re-rules-text--blue">
                  Nạp trong thời gian diễn ra sự kiện
                </span>
              </li>
            </ul>
          </div>

          <div className="re-rules-section re-rules-section--amber">
            <h4 className="re-rules-title re-rules-title--amber">Lưu ý quan trọng</h4>
            <ul className="re-rules-list">
              <li className="re-rules-item">
                <AlertCircle className="re-rules-icon" />
                <span className="re-rules-text re-rules-text--amber">
                  Quà chỉ được nhận một lần duy nhất
                </span>
              </li>
              <li className="re-rules-item">
                <AlertCircle className="re-rules-icon" />
                <span className="re-rules-text re-rules-text--amber">
                  Vui lòng kiểm tra kỹ điều kiện trước khi nạp
                </span>
              </li>
            </ul>
          </div>
        </>
      )}

      {selectedEvent.id === 'milestone' && (
        <>
          <div className="re-rules-section re-rules-section--blue">
            <h4 className="re-rules-title re-rules-title--blue">Điều kiện nhận quà</h4>
            <ul className="re-rules-list">
              <li className="re-rules-item">
                <Check className="re-rules-icon" />
                <span className="re-rules-text re-rules-text--blue">
                  Đạt đủ số tiền nạp theo từng mốc
                </span>
              </li>
              <li className="re-rules-item">
                <Check className="re-rules-icon" />
                <span className="re-rules-text re-rules-text--blue">
                  Tổng nạp được tính trong thời gian sự kiện
                </span>
              </li>
              <li className="re-rules-item">
                <Check className="re-rules-icon" />
                <span className="re-rules-text re-rules-text--blue">
                  Có thể nhận nhiều mốc cùng lúc nếu đủ điều kiện
                </span>
              </li>
            </ul>
          </div>

          <div className="re-rules-section re-rules-section--amber">
            <h4 className="re-rules-title re-rules-title--amber">Lưu ý quan trọng</h4>
            <ul className="re-rules-list">
              <li className="re-rules-item">
                <AlertCircle className="re-rules-icon" />
                <span className="re-rules-text re-rules-text--amber">
                  Mỗi mốc chỉ được nhận một lần trong sự kiện
                </span>
              </li>
              <li className="re-rules-item">
                <AlertCircle className="re-rules-icon" />
                <span className="re-rules-text re-rules-text--amber">
                  Hãy nhận quà ngay khi đạt đủ điều kiện
                </span>
              </li>
            </ul>
          </div>
        </>
      )}

      {selectedEvent.id === 'multiplier_event' && (
        <>
          <div className="re-rules-section re-rules-section--blue">
            <h4 className="re-rules-title re-rules-title--blue">Cách thức hoạt động</h4>
            <ul className="re-rules-list">
              <li className="re-rules-item">
                <Check className="re-rules-icon" />
                <span className="re-rules-text re-rules-text--blue">
                  Nạp trong thời gian sự kiện nhận x{timeSettings?.number || 1} giá trị
                </span>
              </li>
              <li className="re-rules-item">
                <Check className="re-rules-icon" />
                <span className="re-rules-text re-rules-text--blue">
                  Áp dụng tự động cho tất cả giao dịch nạp
                </span>
              </li>
            </ul>
          </div>

          <div className="re-rules-section re-rules-section--amber">
            <h4 className="re-rules-title re-rules-title--amber">Thông tin sự kiện</h4>
            <div className="re-rules-list">
              <div className="re-rules-item">
                <Clock className="re-rules-icon" />
                <span className="re-rules-text re-rules-text--amber">
                  Cập nhật lần cuối: {formatDateTime(timeSettings?.updated_at)}
                </span>
              </div>
              <div className="re-rules-item">
                <AlertCircle className="re-rules-icon" />
                <span className="re-rules-text re-rules-text--amber">
                  Trạng thái: {timeSettings?.status === 1 ? 'Đang hoạt động' : 'Tạm dừng'}
                </span>
              </div>
            </div>
          </div>
        </>
      )}
    </div>
  );
};

  return (
    <div className="re-container">
      {/* Header Statistics */}
      <div className="re-stats">
        <div className="re-stats__grid">
          <div className="re-stats__item">
            <span className="re-stats__label">Tổng nạp</span>
            <span className="re-stats__value">
              {userStatus?.total_recharge?.toLocaleString()}đ
            </span>
          </div>
          <div className="re-stats__item">
            <span className="re-stats__label">Sự kiện đang diễn ra</span>
            <span className="re-stats__value">
              {events.filter(e => e.status).length}
            </span>
          </div>
          <div className="re-stats__item">
            <span className="re-stats__label">Quà chưa nhận</span>
            <span className="re-stats__value">
              {events.filter(e => e.status && !userStatus?.claimed_milestones?.includes(e.id)).length}
            </span>
          </div>
        </div>
      </div>

      {/* Event Cards */}
      <div className="re-events">
        {events.map((event) => (
          <div 
            key={event.id}
            className={`re-event ${event.status ? 're-event--active' : 're-event--inactive'}`}
            onClick={() => event.status && setSelectedEvent(event)}
          >
            <div className="re-event__badge">
              <div className={`re-event__status re-event__status--${event.id}`}>
                {event.id === 'multiplier_event' 
                  ? timeSettings?.status === 1 
                    ? 'Đang diễn ra' 
                    : 'Chưa diễn ra'
                  : 'Đang diễn ra'}
              </div>
            </div>

            <div className="re-event__content">
              <div className="re-event__header">
                <event.icon className={`re-event__icon re-event__icon--${event.color}`} />
                <div>
                  <h3 className="re-event__title">{event.title}</h3>
                  <p className="re-event__description">{event.description}</p>
                </div>
              </div>

              {event.id !== 'multiplier_event' && event.timeRange && (
                <div className="re-event__time">
                  <div className="re-event__time-item">
                    <Timer className="re-event__time-icon" />
                    <span>Từ: {formatDateTime(event.timeRange.start)}</span>
                  </div>
                  <div className="re-event__time-item">
                    <Timer className="re-event__time-icon" />
                    <span>Đến: {formatDateTime(event.timeRange.end)}</span>
                  </div>
                </div>
              )}

              {event.id === 'multiplier_event' && (
                <div className="re-event__update">
                  <Clock className="re-event__update-icon" />
                  <span>Cập nhật: {formatDateTime(event.lastUpdated)}</span>
                </div>
              )}

              <button
                className={`re-event__button re-event__button--${event.color}`}
                disabled={!event.status}
              >
                <Info className="re-event__button-icon" />
                <span>Chi tiết</span>
              </button>
            </div>
          </div>
        ))}
      </div>

      {/* Modal */}
      {selectedEvent && ReactDOM.createPortal(
        <div className="re-modal">
          <div className="re-modal__overlay" onClick={() => setSelectedEvent(null)} />
          <div className="re-modal__container">
            {/* Modal Header */}
            <div className="re-modal__header">
              <button 
                onClick={() => setSelectedEvent(null)}
                className="re-modal__close"
              >
                <X />
              </button>
              <div className="re-modal__title">
                <selectedEvent.icon className="re-modal__title-icon" />
                <div>
                  <h2 className="re-modal__title-text">{selectedEvent.title}</h2>
                  <p className="re-modal__title-desc">{selectedEvent.description}</p>
                </div>
              </div>
            </div>

            {/* Modal Content */}
            <div className="re-modal__content">
              {/* Tabs */}
              <div className="re-tabs">
                {['overview', 'rewards', 'rules'].map((tab) => (
                  <button
                    key={tab}
                    onClick={() => setActiveTab(tab)}
                    className={`re-tabs__button ${activeTab === tab ? 're-tabs__button--active' : ''}`}
                  >
                    {tab === 'overview' && 'Tổng quan'}
                    {tab === 'rewards' && 'Phần thưởng'}
                    {tab === 'rules' && 'Điều kiện'}
                  </button>
                ))}
              </div>

              {/* Tab Content */}
              <div className="re-tab-content">
                {/* Content components based on activeTab */}
                {activeTab === 'overview' && (
                  <OverviewTab 
                    selectedEvent={selectedEvent} 
                    timeSettings={timeSettings}
                    formatDateTime={formatDateTime}
                  />
                )}
                {activeTab === 'rewards' && (
                  <RewardsTab 
                    selectedEvent={selectedEvent}
                    eventConfig={eventConfig}
                    userStatus={userStatus}
                    timeSettings={timeSettings}
                    handleClaimFirstRecharge={handleClaimFirstRecharge}
                    handleClaimMilestone={handleClaimMilestone}
                    claimingFirstRecharge={claimingFirstRecharge}
                    claimingMilestone={claimingMilestone}
                  />
                )}
                {activeTab === 'rules' && (
                  <RulesTab
                    selectedEvent={selectedEvent}
                    timeSettings={timeSettings}
                    formatDateTime={formatDateTime}
                  />
                )}
              </div>
            </div>

            {/* Modal Footer */}
            <div className="re-modal__footer">
              <div className="re-modal__footer-info">
                {selectedEvent.id === 'multiplier_event' ? (
                  <>
                    <Clock className="re-modal__footer-icon" />
                    Cập nhật: {formatDateTime(timeSettings?.updated_at)}
                  </>
                ) : (
                  <>
                    <Timer className="re-modal__footer-icon" />
                    Kết thúc: {formatDateTime(selectedEvent.timeRange?.end)}
                  </>
                )}
              </div>
              <button
                onClick={() => setSelectedEvent(null)}
                className="re-modal__footer-button"
              >
                Đóng
              </button>
            </div>
          </div>
        </div>,
        document.getElementById('popup-root')
      )}
    </div>
  );
};

export default RechargeEvents;