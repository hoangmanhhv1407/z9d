import React, { useState, useEffect } from 'react';
import { Swords, ChevronDown, ChevronUp, RefreshCw, Shield, AlertCircle, Check, Coins, LogOut, Info } from 'lucide-react';
import { toast } from 'react-hot-toast';
import api from '../../api';
import { useAuth } from '../../contexts/AuthContext';
import '../../../css/ChangeWeapon.css';

const ChangeWeapon = () => {
  const { user, fetchUser, updateUserData } = useAuth();
  const [characters, setCharacters] = useState([]);
  const [selectedCharacter, setSelectedCharacter] = useState('');
  const [currentWeapon, setCurrentWeapon] = useState(null);
  const [availableWeapons, setAvailableWeapons] = useState([]);
  const [selectedWeaponId, setSelectedWeaponId] = useState(null);
  const [loading, setLoading] = useState(false);
  const [isProcessing, setIsProcessing] = useState(false);
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const [step, setStep] = useState(1); // 1: Chọn nhân vật, 2: Xem vũ khí hiện tại, 3: Chọn vũ khí mới
  const [changeFee, setChangeFee] = useState(0);
  const [config, setConfig] = useState({ min_level_required: 0, change_fee: 0 });
  const [weaponType, setWeaponType] = useState(null);
  const [showGuide, setShowGuide] = useState(false);
  const [isAccountOnline, setIsAccountOnline] = useState(false);
  const [accountStatusMessage, setAccountStatusMessage] = useState('');
  const [unlockTime, setUnlockTime] = useState(null);
  const [remainingTime, setRemainingTime] = useState(null);

  useEffect(() => {
    fetchCharacters();
  }, []);

  useEffect(() => {
    if (selectedCharacter) {
      fetchCharacterWeapons(selectedCharacter);
    }
  }, [selectedCharacter]);

  // Thêm useEffect này sau các useEffect hiện tại
useEffect(() => {
  let interval;
  if (unlockTime) {
    interval = setInterval(() => {
      const now = Math.floor(Date.now() / 1000); // Thời gian hiện tại tính bằng giây
      const remaining = unlockTime - now;
      
      if (remaining <= 0) {
        clearInterval(interval);
        setRemainingTime(null);
        setUnlockTime(null);
        toast.success('Tài khoản đã được mở khóa!');
      } else {
        const minutes = Math.floor(remaining / 60);
        const seconds = remaining % 60;
        setRemainingTime(`${minutes}:${seconds < 10 ? '0' + seconds : seconds}`);
      }
    }, 1000);
  }
  
  return () => {
    if (interval) clearInterval(interval);
  };
}, [unlockTime]);

  const fetchCharacters = async () => {
    try {
      setLoading(true);
      const response = await api.get('/user-characters');
      if (response.data.success) {
        setCharacters(response.data.data);
        setConfig(response.data.config || { min_level_required: 0, change_fee: 0 });
        if (response.data.data.length > 0) {
          setSelectedCharacter(response.data.data[0].unique_id);
        }
      } else {
        toast.error(response.data.message || 'Không thể tải danh sách nhân vật');
      }
    } catch (error) {
      console.error('Error fetching characters:', error);
      toast.error(error.response?.data?.message || 'Không thể tải danh sách nhân vật');
    } finally {
      setLoading(false);
    }
  };

  const fetchCharacterWeapons = async (characterId) => {
    try {
      setLoading(true);
      setCurrentWeapon(null);
      setAvailableWeapons([]);
      setSelectedWeaponId(null);
      setStep(1);
      
      const response = await api.get(`/character-weapons/${characterId}`);
      if (response.data.success) {
        const { current_weapon, weapons, change_fee, weapon_type } = response.data.data;
        setCurrentWeapon(current_weapon);
        setAvailableWeapons(weapons || []);
        setChangeFee(change_fee || 0);
        setWeaponType(weapon_type || null);
        
        // Lưu trạng thái đăng nhập từ response
        if (response.data.account_status) {
          setIsAccountOnline(response.data.account_status.is_online);
          setAccountStatusMessage(response.data.account_status.message);
        }
        
        // Tự động chọn vũ khí đang trang bị
        const equippedWeapon = weapons.find(w => w.is_equipped);
        if (equippedWeapon) {
          setSelectedWeaponId(equippedWeapon.id);
        }
        
        // Chuyển đến bước 2 vì đã có thông tin vũ khí
        setStep(2);
      } else {
        toast.error(response.data.message || 'Không thể tải thông tin vũ khí');
      }
    } catch (error) {
      console.error('Error fetching weapons:', error);
      toast.error(error.response?.data?.message || 'Không thể tải thông tin vũ khí');
    } finally {
      setLoading(false);
    }
  };

  const handleWeaponSelect = (weaponId) => {
    setSelectedWeaponId(weaponId);
  };

  const handleCharacterChange = (e) => {
    setSelectedCharacter(e.target.value);
    setCurrentWeapon(null);
    setAvailableWeapons([]);
    setSelectedWeaponId(null);
    setStep(1);
  };

  const handleChangeWeapon = async () => {
    if (!selectedCharacter || !selectedWeaponId) {
      toast.error('Vui lòng chọn nhân vật và vũ khí');
      return;
    }
  
    // Nếu chọn vũ khí giống với vũ khí đang trang bị
    if (currentWeapon && currentWeapon.id === selectedWeaponId) {
      toast.error('Bạn đang chọn vũ khí hiện tại đang trang bị');
      return;
    }
  
    // Kiểm tra số dư xu
    if (user.coin < changeFee) {
      toast.error(`Không đủ xu để thay đổi vũ khí. Cần ${changeFee} xu.`);
      return;
    }
  
    try {
      setIsProcessing(true);
      
      // Tiến hành thay đổi vũ khí
      const response = await api.post('/change-weapon', {
        character_id: selectedCharacter,
        weapon_id: selectedWeaponId
      });
  
      if (response.data.success) {
        toast.success('Thay đổi vũ khí thành công!');
        
        // Cập nhật số xu người dùng
        if (response.data.data && response.data.data.remaining_coin !== undefined) {
          updateUserData({ coin: response.data.data.remaining_coin });
        } else {
          fetchUser();
        }
        
        // Lưu thời gian mở khóa - THÊM ĐOẠN CODE NÀY
        if (response.data.data && response.data.data.unlock_timestamp) {
          setUnlockTime(response.data.data.unlock_timestamp);
        }
        
        // Fetch lại thông tin vũ khí
        fetchCharacterWeapons(selectedCharacter);
        
        // Quay lại bước 2
        setStep(2);
      } else {
        // Nếu có lỗi từ server
        toast.error(response.data.message || 'Thay đổi vũ khí thất bại');
      }
    } catch (error) {
      console.error('Error changing weapon:', error);
      toast.error(error.response?.data?.message || 'Có lỗi xảy ra khi thay đổi vũ khí');
    } finally {
      setIsProcessing(false);
    }
  };
  
  const sortWeapons = (type) => {
    if (!availableWeapons || availableWeapons.length === 0) return;
    
    let sortedWeapons = [...availableWeapons];
    
    switch(type) {
      case 'level':
        sortedWeapons.sort((a, b) => b.level - a.level);
        break;
      case 'name':
        sortedWeapons.sort((a, b) => a.name.localeCompare(b.name));
        break;
      default:
        break;
    }
    
    setAvailableWeapons(sortedWeapons);
    setDropdownOpen(false);
  };

  const goToChooseWeapon = () => {
    // Chuyển đến bước 3 (Chọn vũ khí mới)
    setStep(3);
  };

  const goBack = () => {
    setStep(2);
  };

  const getWeaponTypeLabel = (weaponId) => {
    return weaponType ? weaponType.name : "Không xác định";
  };

  const toggleGuide = () => {
    setShowGuide(!showGuide);
  };

  const renderGuide = () => {
    if (!showGuide) return null;
    
    return (
      <div className="cw-guide">
        <div className="cw-guide-header">
          <h4 className="cw-guide-title">Hướng dẫn thay đổi vũ khí</h4>
          <button className="cw-guide-close" onClick={toggleGuide}>×</button>
        </div>
        <div className="cw-guide-content">
          <div className="cw-guide-section">
            <h5 className="cw-guide-section-title">Điều kiện</h5>
            <ul className="cw-guide-list">
              <li>Nhân vật phải đạt cấp {config.min_level_required} trở lên</li>
              <li>Phí thay đổi vũ khí: <strong>{changeFee.toLocaleString()} Xu</strong></li>
              <li>Chỉ có thể thay đổi giữa các vũ khí cùng loại (ví dụ: Kỳ Lân PvE, Quét Tan PvP)</li>
              <li>Tài khoản phải <strong>không đang trực tuyến</strong> trong game khi thực hiện thay đổi</li>
            </ul>
          </div>
          <div className="cw-guide-section">
            <h5 className="cw-guide-section-title">Lưu ý quan trọng</h5>
            <ul className="cw-guide-list">
              <li>Bạn cần <strong>thoát game hoàn toàn</strong> trước khi thực hiện thay đổi vũ khí</li>
              <li>Sau khi thay đổi vũ khí, tài khoản sẽ bị khóa tạm thời trong <strong>15 phút</strong></li>
              <li>Trong thời gian khóa tạm thời, bạn không thể đăng nhập vào game</li>
              <li>Thao tác thay đổi vũ khí không thể hoàn tác sau khi thực hiện</li>
              <li>Vũ khí mới sẽ giữ nguyên các thuộc tính như: độ bền, số lượng socket, độ cường hóa</li>
            </ul>
          </div>
          <div className="cw-guide-section">
            <h5 className="cw-guide-section-title">Các bước thực hiện</h5>
            <ol className="cw-guide-steps">
              <li>Thoát game hoàn toàn</li>
              <li>Chọn nhân vật cần thay đổi vũ khí</li>
              <li>Xem thông tin vũ khí hiện tại và nhấn "Đổi Vũ Khí"</li>
              <li>Chọn vũ khí mới từ danh sách (cùng loại với vũ khí hiện tại)</li>
              <li>Nhấn "Xác nhận thay đổi" để hoàn tất</li>
            </ol>
          </div>
        </div>
      </div>
    );
  };

  const renderAccountLockStatus = () => {
    if (!remainingTime) return null;
    
    return (
      <div className="cw-lock-status">
        <div className="cw-lock-status-header">
          <h4 className="cw-lock-status-title">Trạng thái tài khoản</h4>
        </div>
        <div className="cw-lock-status-content">
          <div className="cw-lock-status-icon">
            <LogOut size={32} />
          </div>
          <div className="cw-lock-status-info">
            <p className="cw-lock-status-message">Tài khoản đang bị khóa tạm thời</p>
            <div className="cw-lock-status-timer">
              <p className="cw-lock-status-label">Thời gian còn lại:</p>
              <p className="cw-lock-status-value">{remainingTime}</p>
            </div>
            <p className="cw-lock-status-note">
              Tài khoản sẽ tự động được mở khóa khi hết thời gian.
              Vui lòng không đăng nhập game trong thời gian này.
            </p>
          </div>
        </div>
      </div>
    );
  };

  const renderStepContent = () => {
    if (loading) {
      return (
        <div className="cw-loading">
          <RefreshCw className="cw-loading-icon" />
          <span>Đang tải dữ liệu...</span>
        </div>
      );
    }

    switch (step) {
      case 1: // Chọn nhân vật
        return (
          <div className="cw-empty-state">
            <Shield className="cw-empty-state-icon" />
            <p className="cw-empty-state-text">Vui lòng chọn nhân vật để xem thông tin vũ khí</p>
            <p className="cw-empty-state-info">Nhân vật phải đạt cấp {config.min_level_required} trở lên</p>
            <button className="cw-info-button" onClick={toggleGuide}>
              <Info size={16} />
              <span>Xem hướng dẫn</span>
            </button>
          </div>
        );
        
      case 2: // Hiển thị vũ khí hiện tại
      return currentWeapon ? (
        <div className="cw-current-weapon">
          <div className="cw-detail-header">
            <h4 className="cw-detail-title">Vũ khí đang trang bị</h4>
            <button className="cw-info-button" onClick={toggleGuide}>
              <Info size={16} />
              <span>Xem hướng dẫn</span>
            </button>
          </div>
          <div className="cw-detail-content">
            <div className="cw-detail-info">
              <h5 className="cw-weapon-name">{currentWeapon.name}</h5>
              
              <div className="cw-detail-basic">
                <div className="cw-detail-type">
                  <span className="cw-detail-label">Loại vũ khí:</span>
                  <span className="cw-detail-value">{getWeaponTypeLabel(currentWeapon.id)}</span>
                </div>
              </div>
              
              <div className="cw-detail-attributes">
                <div className="cw-attribute-item">
                  <span className="cw-attribute-label">Độ bền:</span>
                  <span className="cw-attribute-value">{currentWeapon.durability}</span>
                </div>
                <div className="cw-attribute-item">
                  <span className="cw-attribute-label">Số lượng socket:</span>
                  <span className="cw-attribute-value">{currentWeapon.socket_count}</span>
                </div>
                <div className="cw-attribute-item">
                  <span className="cw-attribute-label">Độ cường hóa:</span>
                  <span className="cw-attribute-value">+{currentWeapon.inchant}</span>
                </div>
              </div>
            </div>
          </div>
          <div className="cw-fee-info">
            <div className="cw-fee-box">
              <Coins className="cw-fee-icon" />
              <div className="cw-fee-text">
                <div className="cw-fee-label">Phí thay đổi vũ khí:</div>
                <div className="cw-fee-value">{changeFee.toLocaleString()} Xu</div>
              </div>
            </div>
          </div>
          
    {/* Hiển thị cảnh báo dựa trên trạng thái tài khoản */}
    {isAccountOnline ? (
      <div className="cw-lock-warning">
        <AlertCircle className="cw-warning-icon" />
        <p className="cw-warning-text">
          <strong>Tài khoản đang trực tuyến!</strong> {accountStatusMessage || 'Bạn cần thoát game hoàn toàn trước khi thay đổi vũ khí.'}
        </p>
      </div>
    ) : (
<div className="cw-logout-warning">
  <LogOut className="cw-warning-icon" />
  <p className="cw-warning-text">
    <strong>Lưu ý:</strong> Sau khi thay đổi vũ khí, tài khoản sẽ bị khóa tạm thời trong 15 phút
    {remainingTime && (
      <span className="cw-countdown">
        <br />Tài khoản sẽ được mở khóa sau: <strong>{remainingTime}</strong>
      </span>
    )}
  </p>
</div>
    )}
    
    <div className="cw-detail-actions">
      <button
        className="cw-action-button"
        onClick={goToChooseWeapon}
        disabled={isProcessing || isAccountOnline} // Disable nút khi đang online
      >
        <Swords className="cw-button-icon" />
        <span>Đổi Vũ Khí</span>
      </button>
    </div>
  </div>
) : (
        <div className="cw-empty-state">
          <AlertCircle className="cw-empty-state-icon" />
          <p className="cw-empty-state-text">Nhân vật này chưa trang bị vũ khí hoặc vũ khí không thuộc loại có thể thay đổi</p>
          <button className="cw-info-button" onClick={toggleGuide}>
            <Info size={16} />
            <span>Xem hướng dẫn</span>
          </button>
        </div>
      );
        
      case 3: // Chọn vũ khí mới
        return (
          <div className="cw-weapon-selection">
            <div className="cw-selection-header">
              <button className="cw-back-button" onClick={goBack}>
                <ChevronDown className="cw-back-icon" />
                <span>Quay lại</span>
              </button>
              <h4 className="cw-selection-title">Chọn vũ khí mới</h4>
              <div className="cw-weapons-filter" onClick={() => setDropdownOpen(!dropdownOpen)}>
                <span>Sắp xếp</span>
                {dropdownOpen ? (
                  <ChevronUp className="cw-dropdown-icon" />
                ) : (
                  <ChevronDown className="cw-dropdown-icon" />
                )}
                {dropdownOpen && (
                  <div className="cw-dropdown-menu">
                    <button className="cw-dropdown-item" onClick={() => sortWeapons('level')}>Theo cấp độ</button>
                    <button className="cw-dropdown-item" onClick={() => sortWeapons('name')}>Theo tên</button>
                  </div>
                )}
              </div>
            </div>
            
            <div className="cw-fee-banner">
              <Coins className="cw-fee-banner-icon" />
              <span className="cw-fee-banner-text">
                Phí thay đổi vũ khí: <strong>{changeFee.toLocaleString()} Xu</strong>
              </span>
              <span className="cw-fee-banner-balance">
                Số dư: <strong>{user.coin.toLocaleString()} Xu</strong>
              </span>
            </div>
            
            <div className="cw-lock-warning">
              <AlertCircle className="cw-warning-icon" />
              <p className="cw-warning-text">
                Sau khi thay đổi vũ khí, tài khoản sẽ bị khóa tạm thời trong <strong>15 phút</strong>. 
                Trong thời gian này, bạn không thể đăng nhập vào game.
              </p>
            </div>
            
            <div className="cw-weapons-list">
              {availableWeapons.length === 0 ? (
                <div className="cw-no-weapons">
                  <AlertCircle className="cw-no-weapons-icon" />
                  <span>Không có vũ khí nào có thể đổi</span>
                </div>
              ) : (
                <div className="cw-weapons-grid">
                  {availableWeapons.map((weapon) => (
                    <div
                      key={weapon.id}
                      className={`cw-weapon-item ${selectedWeaponId === weapon.id ? 'cw-weapon-selected' : ''} ${weapon.is_equipped ? 'cw-weapon-equipped' : ''}`}
                      onClick={() => handleWeaponSelect(weapon.id)}
                    >
                      <div className="cw-weapon-info">
                        <h5 className="cw-weapon-name">{weapon.name}</h5>
                        {weapon.is_equipped && (
                          <div className="cw-equipped-badge">
                            <Check className="cw-equipped-icon" />
                            <span>Đang dùng</span>
                          </div>
                        )}
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
            <div className="cw-selection-actions">
              <button
                className="cw-action-button"
                onClick={handleChangeWeapon}
                disabled={isProcessing || !selectedWeaponId || (currentWeapon && currentWeapon.id === selectedWeaponId) || user.coin < changeFee}
              >
                {isProcessing ? (
                  <>
                    <RefreshCw className="cw-button-icon cw-spinning" />
                    <span>Đang xử lý...</span>
                  </>
                ) : (
                  <>
                    <Swords className="cw-button-icon" />
                    <span>Xác nhận thay đổi</span>
                  </>
                )}
              </button>
            </div>
          </div>
        );
        
      default:
        return null;
    }
  };

  return (
    <div className="cw-container">
      <div className="cw-header">
        <Swords className="cw-header__icon" />
        <h4 className="cw-header__title">Thay Đổi Vũ Khí</h4>
      </div>

      <div className="cw-content">
        {/* Character Selection */}
        <div className="cw-character-select">
          <label htmlFor="character" className="cw-label">Chọn nhân vật:</label>
          <div className="cw-select-wrapper">
            {loading && step === 1 ? (
              <div className="cw-loading-select">
                <RefreshCw className="cw-loading-icon" />
                <span>Đang tải...</span>
              </div>
            ) : (
              <select
                id="character"
                value={selectedCharacter}
                onChange={handleCharacterChange}
                className="cw-select"
                disabled={isProcessing || loading}
              >
                {characters.length === 0 ? (
                  <option value="">Không có nhân vật</option>
                ) : (
                  characters.map((char) => (
                    <option key={char.unique_id} value={char.unique_id}>
                      {char.chr_name} (Cấp {char.level}) {!char.can_change_weapon ? '- Chưa đủ cấp' : ''}
                    </option>
                  ))
                )}
              </select>
            )}
          </div>
        </div>

        {/* Main Content */}
        <div className="cw-main-content">
          {renderStepContent()}
        </div>
      </div>

      {/* Hướng dẫn */}
      {renderGuide()}

          {/* Hiển thị trạng thái khóa tài khoản - THÊM DÒNG NÀY */}
    {renderAccountLockStatus()}
    </div>
  );
};

export default ChangeWeapon;