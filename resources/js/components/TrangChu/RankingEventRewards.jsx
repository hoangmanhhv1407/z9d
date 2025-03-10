import React, { useState, useEffect, useMemo } from 'react';
import { 
  Trophy, X, AlertCircle, Gift, Check, Loader, Timer, 
  Medal, Crown, Users, Target, Award, Star, Clock
} from 'lucide-react';
import { toast } from 'react-hot-toast';
import api from '../../api';
import '../../../css/RankingEventRewards.css';

const RANKING_TYPES = [
  { id: 'level', label: 'Level', icon: Target },
  { id: 'honor', label: 'Danh Tiếng', icon: Crown },
  { id: 'gong', label: 'Ác Danh', icon: Award },
  { id: 'top-recharge', label: 'Top Nạp', icon: Gift },
  { id: 'top-spend', label: 'Top Tiêu', icon: Star }
];

// Tách ButtonStatus thành component riêng để dễ quản lý
const ButtonStatus = ({ status, claiming, onClaim }) => {
  const getButtonContent = () => {
    if (claiming) {
      return (
        <span className="rer-button-content">
          <Loader className="rer-button-icon rer-spin" />
          Đang nhận...
        </span>
      );
    }

    const icons = {
      claimed: <Check className="rer-button-icon" />,
      waiting: <Timer className="rer-button-icon" />,
      not_eligible: <AlertCircle className="rer-button-icon" />,
      can_claim: <Gift className="rer-button-icon" />
    };

    const texts = {
      claimed: "Đã nhận",
      waiting: "Chờ kết thúc event",
      not_eligible: "Chưa đủ điều kiện",
      can_claim: "Nhận quà"
    };

    const buttonStatus = status.status || status;
    return (
      <span className="rer-button-content">
        {icons[buttonStatus]}
        {texts[buttonStatus]}
      </span>
    );
  };

  return (
    <button
      onClick={status === 'can_claim' ? onClaim : undefined}
      disabled={status !== 'can_claim' || claiming}
      className={`rer-button rer-button-${status}`}
    >
      {getButtonContent()}
    </button>
  );
};

const RankingEventRewards = ({ rankType: initialRankType, closeModal }) => {
  const [activeRankType, setActiveRankType] = useState(initialRankType);
  const [rewardsData, setRewardsData] = useState({});
  const [loading, setLoading] = useState(true);
  const [claiming, setClaiming] = useState(false);

  // Lưu trữ dữ liệu theo từng loại ranking
  useEffect(() => {
    const fetchRewardsAndStatus = async (type) => {
      if (rewardsData[type]) return; // Nếu đã có dữ liệu thì không fetch lại

      try {
        setLoading(true);
        const rewardsRes = await api.get(`/ranking-events/rewards/${type}`);
        if (rewardsRes.data.success) {
          setRewardsData(prev => ({
            ...prev,
            [type]: {
              rewards: rewardsRes.data.data.rewards || [],
              eventTime: rewardsRes.data.data.event_time
            }
          }));
        }
      } catch (error) {
        console.error('Error fetching rewards:', error);
        toast.error('Không thể tải thông tin phần thưởng');
      } finally {
        setLoading(false);
      }
    };

    if (!rewardsData[activeRankType]) {
      fetchRewardsAndStatus(activeRankType);
    }
  }, [activeRankType, rewardsData]);

  const currentData = useMemo(() => 
    rewardsData[activeRankType] || { rewards: [], eventTime: null },
    [rewardsData, activeRankType]
  );

  const handleClaimReward = async (rank) => {
    if (claiming) return;
    
    try {
      setClaiming(true);
      const response = await api.post('/ranking-events/claim', {
        type: activeRankType,
        rank: rank
      });
      
      if (response.data.success) {
        // Cập nhật lại dữ liệu cho ranking type hiện tại
        const updatedRes = await api.get(`/ranking-events/rewards/${activeRankType}`);
        if (updatedRes.data.success) {
          setRewardsData(prev => ({
            ...prev,
            [activeRankType]: {
              rewards: updatedRes.data.data.rewards || [],
              eventTime: updatedRes.data.data.event_time
            }
          }));
        }
        toast.success('Nhận quà thành công!');
      }
    } catch (error) {
      console.error('Error claiming reward:', error);
      toast.error(error.response?.data?.message || 'Có lỗi xảy ra khi nhận quà');
    } finally {
      setClaiming(false);
    }
  };

  // Hàm nhóm phần thưởng theo rank
  const groupedRewards = useMemo(() => {
    if (!currentData.rewards.length) return [];

    // Tạo Map để lưu trữ thông tin theo rank_from
    const rankMap = new Map();

    // Nhóm các phần thưởng theo rank_from
    currentData.rewards.forEach(reward => {
      const rankKey = reward.rank_from;
      
      if (!rankMap.has(rankKey)) {
        // Khởi tạo nhóm mới nếu chưa tồn tại
        rankMap.set(rankKey, {
          rank_from: reward.rank_from,
          rank_to: reward.rank_to,
          button_status: reward.button_status,
          products: [...reward.products] // Sao chép mảng sản phẩm
        });
      } else {
        // Thêm sản phẩm vào nhóm đã tồn tại
        const existing = rankMap.get(rankKey);
        existing.products = [...existing.products, ...reward.products];
      }
    });

    // Chuyển Map thành mảng và sắp xếp theo thứ hạng
    return Array.from(rankMap.values()).sort((a, b) => a.rank_from - b.rank_from);
  }, [currentData.rewards]);

  return (
    <div className="rer-overlay">
      <div className="rer-container">
        <div className="rer-sidebar">
          {/* Trophy và Event Time Section */}
          <div className="rer-sidebar-header">
            <div className="rer-trophy-wrapper">
              <Trophy className="rer-trophy-icon" />
            </div>
            <h3 className="rer-ranking-title">Phần Thưởng Xếp Hạng</h3>
          </div>

          {currentData.eventTime && (
            <div className="rer-event-timer">
              <div className="rer-timer-content">
                <h4 className="rer-timer-title">
                  <Clock className="rer-timer-icon" />
                  Thời Gian Sự Kiện
                </h4>
                <div className="rer-timer-details">
                  <div className="rer-time-box">
                    <span className="rer-time-label">Bắt đầu:</span>
                    <p className="rer-time-value">
                      {new Date(currentData.eventTime.start).toLocaleString('vi-VN')}
                    </p>
                  </div>
                  <div className="rer-time-box">
                    <span className="rer-time-label">Kết thúc:</span>
                    <p className="rer-time-value">
                      {new Date(currentData.eventTime.end).toLocaleString('vi-VN')}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          )}

          {/* Ranking Tabs */}
          <div className="rer-tabs">
            {RANKING_TYPES.map(({ id, label, icon: Icon }) => (
              <button
                key={id}
                onClick={() => setActiveRankType(id)}
                className={`rer-tab ${activeRankType === id ? 'rer-tab-active' : ''}`}
              >
                <Icon className="rer-tab-icon" />
                <span className="rer-tab-label">{label}</span>
              </button>
            ))}
          </div>
        </div>

        <div className="rer-main">
          <div className="rer-header">
            <h2 className="rer-title">
              {RANKING_TYPES.find(type => type.id === activeRankType)?.label}
            </h2>
            <button onClick={closeModal} className="rer-close">
              <X />
            </button>
          </div>

          <div className="rer-rewards">
            {loading && !rewardsData[activeRankType] ? (
              <div className="rer-loading">
                <Loader className="rer-loading-icon rer-spin" />
                <p className="rer-loading-text">Đang tải thông tin phần thưởng...</p>
              </div>
            ) : (
              groupedRewards.map((reward) => (
                <div key={reward.rank_from} className="rer-reward-card">
                  <div className="rer-reward-header">
                    <div className="rer-rank">
                      {reward.rank_from === 1 ? (
                        <Crown className="rer-rank-icon rer-rank-gold" />
                      ) : reward.rank_from === 2 ? (
                        <Medal className="rer-rank-icon rer-rank-silver" />
                      ) : reward.rank_from === 3 ? (
                        <Award className="rer-rank-icon rer-rank-bronze" />
                      ) : (
                        <Star className="rer-rank-icon rer-rank-normal" />
                      )}
                      <div className="rer-rank-text">
                        <h3>Top {reward.rank_from}</h3>
                        {reward.rank_to !== reward.rank_from && (
                          <p>Đến Top {reward.rank_to}</p>
                        )}
                      </div>
                    </div>
                    
                    <ButtonStatus 
                      status={reward.button_status.status || reward.button_status}
                      claiming={claiming}
                      onClaim={() => handleClaimReward(reward.rank_from)}
                    />
                  </div>

                  <div className="rer-items-grid">
                    {reward.products.map((product, idx) => (
                      <div key={idx} className="rer-item">
                        <img src={product.image} alt={product.name} className="rer-item-image" />
                        <div className="rer-item-details">
                          <h4 className="rer-item-name">{product.name}</h4>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              ))
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default RankingEventRewards;