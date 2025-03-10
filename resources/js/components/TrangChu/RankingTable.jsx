import React, { useState, useEffect, useCallback, useMemo } from 'react';
import { Star, AlertTriangle, ArrowLeft, ArrowRight, Gift } from 'lucide-react';
import axios from 'axios';
import dayjs from 'dayjs';
import RankingEventRewards from './RankingEventRewards';
import ReactDOM from 'react-dom';
import '../../../css/RankingTable.css';

const RankingTable = () => {
  const [rankType, setRankType] = useState('level');
  const [rankingData, setRankingData] = useState({});
  const [currentPage, setCurrentPage] = useState(1);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [metadata, setMetadata] = useState({});
  const [isRefreshing, setIsRefreshing] = useState(false);
  const [eventActive, setEventActive] = useState(false);
  const [eventTime, setEventTime] = useState(null);
  const [countdown, setCountdown] = useState(null);
  const [showRewards, setShowRewards] = useState(false);

  const tabs = useMemo(
    () => [
      { key: 'level', label: 'Level', shortLabel: 'Level' },
      { key: 'honor', label: 'Top Danh Tiếng', shortLabel: 'DT', icon: Star },
      { key: 'gong', label: 'Top Ác Danh', shortLabel: 'AD', icon: Star },
      { key: 'top-recharge', label: 'Top Nạp', shortLabel: 'Nạp', icon: Star },
      { key: 'top-spend', label: 'Top Tiêu', shortLabel: 'Tiêu', icon: Star },
    ],
    []
  );

  const getRankColor = useCallback((rank) => {
    const colors = {
      1: 'text-yellow-400',
      2: 'text-gray-400',
      3: 'text-amber-600',
      4: 'text-purple-400',
      5: 'text-pink-400',
      6: 'text-blue-400',
      7: 'text-green-400',
      8: 'text-indigo-400',
      9: 'text-red-400',
    };
    return colors[rank] || 'text-slate-300';
  }, []);

const formatTimestamp = useCallback((timestamp) => {
  if (!timestamp) return { relative: 'N/A', absolute: 'N/A' };

  // Sử dụng mốc 2003-01-01 00:00:00 ở múi giờ Việt Nam (+7)
  // Đây là điểm khác biệt quan trọng so với các phương pháp trước
  const SQL_SERVER_START_DATE = new Date('2003-01-01T00:00:00+07:00').getTime();
  const milliseconds = SQL_SERVER_START_DATE + timestamp * 1000;
  const date = new Date(milliseconds);
  
  // Lấy thời gian hiện tại
  const now = new Date();
  
  // Hiển thị thời gian tuyệt đối
  const absolute = date.toLocaleString('vi-VN', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
    timeZone: 'Asia/Ho_Chi_Minh' // Bảo đảm sử dụng múi giờ VN khi hiển thị
  });

  // Xử lý trường hợp thời gian tương lai
  if (date > now) {
    console.warn('Phát hiện thời gian tương lai:', {
      timestamp,
      calculatedTime: date.toString(),
      now: now.toString(),
      timeDiff: (date.getTime() - now.getTime()) / 1000 / 60 + ' phút'
    });
    
    return {
      relative: 'Vừa xong', // Luôn hiển thị "Vừa xong" cho thời gian tương lai
      absolute: absolute
    };
  }
  
  // Tính chênh lệch thời gian cho thời gian quá khứ
  const diffInSeconds = Math.floor((now.getTime() - date.getTime()) / 1000);
  
  let relative;
  if (diffInSeconds < 60) relative = 'Vừa xong';
  else if (diffInSeconds < 3600) {
    const mins = Math.floor(diffInSeconds / 60);
    relative = `${mins} phút trước`;
  }
  else if (diffInSeconds < 86400) {
    const hours = Math.floor(diffInSeconds / 3600);
    relative = `${hours} giờ trước`;
  }
  else {
    const days = Math.floor(diffInSeconds / 86400);
    relative = `${days} ngày trước`;
  }

  return { relative, absolute };
}, []);



const maskAccount = (account) => {
  if (!account) return '***';
  
  if (account.length <= 5) {
    // Với tài khoản ngắn, chỉ hiển thị ký tự đầu
    return account.charAt(0) + '****';
  } else {
    // Với tài khoản dài hơn, hiển thị 2 ký tự đầu và 1 ký tự cuối
    const visibleChars = Math.min(2, Math.floor(account.length / 3));
    return account.substring(0, visibleChars) + 
           '*'.repeat(account.length - visibleChars - 1) + 
           account.charAt(account.length - 1);
  }
};

  useEffect(() => {
    if (countdown > 0) {
      const interval = setInterval(() => {
        setCountdown((prevCountdown) => prevCountdown - 1000);
      }, 1000);

      return () => clearInterval(interval);
    }
  }, [countdown]);

  const formatCountdown = (milliseconds) => {
    if (milliseconds <= 0) return 'Đã kết thúc';

    const totalSeconds = Math.floor(milliseconds / 1000);
    const days = Math.floor(totalSeconds / (60 * 60 * 24));
    const hours = Math.floor((totalSeconds % (60 * 60 * 24)) / (60 * 60));
    const minutes = Math.floor((totalSeconds % (60 * 60)) / 60);
    const seconds = totalSeconds % 60;

    return `${days} ngày ${hours} giờ ${minutes} phút ${seconds} giây`;
  };

  const fetchRankings = useCallback(
    async (isRefresh = false) => {
      const cacheKey = `${rankType}_${currentPage}`;

      if (!isRefresh && rankingData[cacheKey]) {
        setLoading(false);
        return;
      }

      try {
        isRefresh ? setIsRefreshing(true) : setLoading(true);
        setError(null);

        const response = await axios.get(`/api/rankings/${rankType}`, {
          params: {
            page: currentPage,
            per_page: 10,
          },
        });

        if (response.data.success) {
          setRankingData((prev) => ({
            ...prev,
            [cacheKey]: response.data.data,
          }));

          setMetadata((prev) => ({
            ...prev,
            [rankType]: response.data.meta,
          }));
        } else {
          throw new Error(response.data.message);
        }
      } catch (err) {
        setError(err.message || 'Có lỗi xảy ra khi tải bảng xếp hạng');
      } finally {
        setLoading(false);
        setIsRefreshing(false);
      }
    },
    [rankType, currentPage, rankingData]
  );

  useEffect(() => {
    fetchRankings();
  }, [rankType, currentPage, fetchRankings]);

  const renderPagination = useCallback(() => {
    const totalPages = metadata[rankType]?.last_page || 1;
    const pages = [];

    let pageNumbers = new Set([
      1,
      totalPages,
      currentPage,
      currentPage - 1,
      currentPage + 1,
    ]);
    pageNumbers = Array.from(pageNumbers)
      .filter((num) => num >= 1 && num <= totalPages)
      .sort((a, b) => a - b);

    for (let i = 0; i < pageNumbers.length; i++) {
      if (i > 0 && pageNumbers[i] - pageNumbers[i - 1] > 1) {
        pages.push(
          <span key={`ellipsis-${i}`} className="ranking-ellipsis">
            ...
          </span>
        );
      }
      pages.push(
        <button
          key={pageNumbers[i]}
          onClick={() => setCurrentPage(pageNumbers[i])}
          className={`ranking-page-button ${
            currentPage === pageNumbers[i] ? 'ranking-page-button-active' : ''
          }`}
        >
          {pageNumbers[i]}
        </button>
      );
    }

    return (
      <div className="ranking-pagination">
        <button
          onClick={() => setCurrentPage((prev) => Math.max(prev - 1, 1))}
          disabled={currentPage === 1}
          className={`ranking-page-button ${
            currentPage === 1 ? 'ranking-page-button-disabled' : ''
          }`}
        >
          <ArrowLeft className="w-4 h-4" />
        </button>
        {pages}
        <button
          onClick={() => setCurrentPage((prev) => Math.min(prev + 1, totalPages))}
          disabled={currentPage === totalPages}
          className={`ranking-page-button ${
            currentPage === totalPages ? 'ranking-page-button-disabled' : ''
          }`}
        >
          <ArrowRight className="w-4 h-4" />
        </button>
      </div>
    );
  }, [currentPage, metadata, rankType]);

  const renderRewardsModal = () => {
    if (!showRewards) return null;

    return ReactDOM.createPortal(
      <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4">
        <RankingEventRewards
          rankType={rankType}
          closeModal={() => setShowRewards(false)}
        />
      </div>,
      document.getElementById('popup-root')
    );
  };

  const currentData = useMemo(
    () => rankingData[`${rankType}_${currentPage}`] || [],
    [rankingData, rankType, currentPage]
  );

  return (
    <div className="ranking-section">
      <div className="ranking-container">
        <div className="ranking-card">
          <div className="ranking-header">
            <h2 className="ranking-title">Bảng Xếp Hạng</h2>
            <hr className="ranking-divider" />

            <div className="ranking-tabs">
              <button
                onClick={() => setShowRewards(true)}
                className="ranking-reward-button"
              >
                <Gift className="w-4 h-4 md:w-5 md:h-5 mr-1 md:mr-2" />
                <span>Phần thưởng</span>
              </button>

              {tabs.map(({ key, label, shortLabel, icon: Icon }) => (
  <div key={key} className="relative">
    <button
      onClick={() => {
        setRankType(key);
        setCurrentPage(1);
        setError(null);
      }}
      className={`ranking-tab ${
        rankType === key ? 'ranking-tab-active' : 'ranking-tab-inactive'
      }`}
    >
      {Icon && <Icon className="w-4 h-4 md:w-5 md:h-5 mr-1 md:mr-2" />}
      <span className="hidden md:inline">{label}</span>
      <span className="md:hidden">{shortLabel}</span>
    </button>

    {['top-recharge', 'top-spend'].includes(key) && eventActive && (
      <span className="ranking-event-badge">Sự kiện</span>
    )}
  </div>
))}
            </div>

            {eventActive && countdown !== null && (
              <p className="ranking-countdown">
                Thời gian xếp hạng Nạp, Tiêu còn lại: {formatCountdown(countdown)}
              </p>
            )}
          </div>

          <div className="ranking-content">
            {loading ? (
              <div className="ranking-loading">
                <div className="ranking-spinner"></div>
                <p>Đang tải dữ liệu...</p>
              </div>
            ) : error ? (
              <div className="ranking-error">
                <AlertTriangle className="w-8 h-8 mx-auto mb-2" />
                <p>{error}</p>
              </div>
            ) : currentData.length === 0 ? (
              <div className="ranking-empty">
                <p>Không có dữ liệu để hiển thị</p>
              </div>
            ) : (
              <>
                {/* Mobile View */}
                <div className="md:hidden">
                  {currentData.map((player, index) => (
                    <div key={index} className="ranking-mobile-card">
                      <div className="ranking-mobile-header">
                        <span
                          className={`ranking-position ${getRankColor(
                            (currentPage - 1) * 10 + index + 1
                          )}`}
                        >
                          #{(currentPage - 1) * 10 + index + 1}
                        </span>
                        <span className="ranking-player-name">
                          {['top-recharge', 'top-spend'].includes(rankType)
                            ? maskAccount(player.userid)
                            : player.userid}
                        </span>
                      </div>
                      <div className="ranking-stats-grid">
                        {rankType === 'level' && (
                          <>
                            <div className="ranking-stat-box">
                              <span className="ranking-stat-label">Level:</span>
                              <span className="ranking-stat-value">
                                Lv.{player.inner_level}
                              </span>
                            </div>
                            <div className="ranking-stat-box">
                              <span className="ranking-stat-label">Tỷ lệ cấp:</span>
                              <span className="ranking-stat-value">
                                {(player.level_rate * 100).toFixed(2)}%
                              </span>
                            </div>
                          </>
                        )}
                        {rankType !== 'level' &&
                          rankType !== 'top-recharge' &&
                          rankType !== 'top-spend' && (
                            <div className="ranking-stat-box col-span-2">
                              <span className="ranking-stat-label">
                                {rankType === 'honor' ? 'Danh Tiếng:' : 'Ác Danh:'}
                              </span>
                              <span className="ranking-stat-value">
                                {player[rankType]?.toLocaleString()}
                              </span>
                            </div>
                          )}
                        {rankType === 'top-recharge' && (
                          <div className="ranking-stat-box col-span-2">
                            <span className="ranking-stat-label">Điểm Nạp:</span>
                            <span className="ranking-stat-value">
                              {player.char_event?.toLocaleString()}
                            </span>
                          </div>
                        )}
                        {rankType === 'top-spend' && (
                          <div className="ranking-stat-box col-span-2">
                            <span className="ranking-stat-label">Số Xu Đã Tiêu:</span>
                            <span className="ranking-stat-value">
                              {player.spent_coin?.toLocaleString()} xu
                            </span>
                          </div>
                        )}
                        {rankType !== 'top-recharge' && rankType !== 'top-spend' && (
                          <>
                            <div className="ranking-stat-box">
                              <span className="ranking-stat-label">Môn Phái:</span>
                              <span className="ranking-stat-value">
                                {player.party_name}
                              </span>
                            </div>
                            <div className="ranking-stat-box">
                              <span className="ranking-stat-label">Chức Trách:</span>
                              <span className="ranking-stat-value">
                                {player.class_name}
                              </span>
                            </div>
                          </>
                        )}
                        {rankType === 'level' && (
                          <div className="ranking-stat-box col-span-2">
                            <span className="ranking-stat-label">Thời gian lên cấp:</span>
                            <span className="ranking-stat-value">
                              {formatTimestamp(player.levelup_time).relative}
                            </span>
                            <span className="ranking-stat-time-absolute">
                              {formatTimestamp(player.levelup_time).absolute}
                            </span>
                          </div>
                        )}
                      </div>
                    </div>
                  ))}
                </div>

                {/* Desktop View */}
                <div className="hidden md:block">
                  <table className="ranking-table">
                    <thead>
                      <tr>
                        <th className="ranking-table-header">Xếp Hạng</th>
                        <th className="ranking-table-header">
                          {rankType === 'top-recharge' || rankType === 'top-spend'
                            ? 'Người dùng'
                            : 'Nhân vật'}
                        </th>
                        {rankType !== 'top-recharge' && rankType !== 'top-spend' && (
                          <>
                            <th className="ranking-table-header">Môn Phái</th>
                            <th className="ranking-table-header">Chức Trách</th>
                          </>
                        )}
                        <th className="ranking-table-header">
                          {rankType === 'level'
                            ? 'Level'
                            : rankType === 'honor'
                            ? 'Danh Tiếng'
                            : rankType === 'gong'
                            ? 'Ác Danh'
                            : rankType === 'top-recharge'
                            ? 'Điểm Nạp'
                            : 'Số Xu Đã Tiêu'}
                        </th>
                        {rankType === 'level' && (
                          <>
                            <th className="ranking-table-header">Tỷ lệ cấp</th>
                            <th className="ranking-table-header">Thời gian lên cấp</th>
                          </>
                        )}
                      </tr>
                    </thead>
                    <tbody>
                      {currentData.map((player, index) => (
                        <tr key={index} className="ranking-table-row">
                          <td className="ranking-table-cell">
                            <span
                              className={`ranking-position ${getRankColor(
                                (currentPage - 1) * 10 + index + 1
                              )}`}
                            >
                              #{(currentPage - 1) * 10 + index + 1}
                            </span>
                          </td>
                          <td className="ranking-table-cell">
                            <span className="ranking-player-name">
                              {['top-recharge', 'top-spend'].includes(rankType)
                                ? maskAccount(player.userid)
                                : player.chr_name}
                            </span>
                          </td>
                          {rankType !== 'top-recharge' && rankType !== 'top-spend' && (
                            <>
                              <td className="ranking-table-cell">
                                <span className="ranking-party-name">
                                  {player.party_name}
                                </span>
                              </td>
                              <td className="ranking-table-cell">
                                <span className="ranking-class-name">
                                  {player.class_name}
                                </span>
                              </td>
                            </>
                          )}
                          <td className="ranking-table-cell">
                            <span className="ranking-stat-value">
                              {rankType === 'level'
                                ? `Lv.${player.inner_level}`
                                : rankType === 'honor'
                                ? player.honor?.toLocaleString()
                                : rankType === 'gong'
                                ? player.gong?.toLocaleString()
                                : rankType === 'top-recharge'
                                ? player.char_event?.toLocaleString()
                                : `${player.spent_coin?.toLocaleString()} xu`}
                            </span>
                          </td>
                          {rankType === 'level' && (
                            <>
                              <td className="ranking-table-cell">
                                <span className="ranking-level-rate">
                                  {(player.level_rate * 100).toFixed(2)}%
                                </span>
                              </td>
                              <td className="ranking-table-cell">
                                <span className="ranking-levelup-time">
                                  {formatTimestamp(player.levelup_time).relative}
                                </span>
                                <span className="ranking-levelup-time-absolute">
                                  {formatTimestamp(player.levelup_time).absolute}
                                </span>
                              </td>
                            </>
                          )}
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>

                {renderPagination()}
              </>
            )}
          </div>
        </div>
      </div>
      {renderRewardsModal()}
    </div>
  );
};

export default RankingTable;