import React, { useState, useEffect, useRef } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import { toast } from 'react-hot-toast';
import api from '../../api';
import RechargeEvents from './RechargeEvents';
import {
  CreditCard, QrCode, BanknoteIcon,
  Timer, History, Gift, Sparkles,
  Coins, TrendingUp, Bell, Star
} from 'lucide-react';
import '../../../css/Recharge.css';

const Recharge = ({ setActiveTab }) => {
  // State Management
  const [multiplier, setMultiplier] = useState(null);
  const [amount, setAmount] = useState('');
  const [qrCode, setQrCode] = useState('');
  const [processing, setProcessing] = useState(false);
  const [countdown, setCountdown] = useState(600);
  const [currentCoins, setCurrentCoins] = useState(0);
  const [showShopPrompt, setShowShopPrompt] = useState(false);
  const [selectedPaymentMethod, setSelectedPaymentMethod] = useState('qr');
  const [timeSettings, setTimeSettings] = useState(null);
  const [bankInfo, setBankInfo] = useState(null);

  // Tỷ lệ quy đổi cố định: 1000 VND = 1 xu (100.000 VND = 100 xu)
  const CONVERSION_RATE = 1000;

  // Refs & Hooks
  const countdownRef = useRef(null);
  const amountInputRef = useRef(null);
  const navigate = useNavigate();
  const { user } = useAuth();

  // Quick amount options
  const quickAmounts = [
    { value: 50000, hot: false },
    { value: 100000, hot: false },
    { value: 200000, hot: false },
    { value: 500000, hot: true },
    { value: 1000000, hot: true },
    { value: 2000000, special: true }
  ];

  // Initial Setup
  useEffect(() => {
    fetchMultiplier();
    if (user?.coin) {
      setCurrentCoins(user.coin);
    }
  }, [user]);

  // Websocket Event Listener
  useEffect(() => {
    if (!user) return;

    const token = localStorage.getItem('token');
    if (!token) {
      toast.error('Vui lòng đăng nhập lại');
      return;
    }

    if (window.Echo) {
      const channel = window.Echo.private(`user.${user.id}`);

      channel.subscribed(() => {
        console.log('Subscribed to channel');
      }).error((error) => {
        if (error.type === 'AuthError') {
          toast.error('Phiên đăng nhập hết hạn, vui lòng đăng nhập lại');
        }
      });

      channel.listen('.NewTransactionEvent', (event) => {
        toast.success(`Nạp thành công: ${event.amount.toLocaleString()}đ!`);
        setCurrentCoins(event.currentCoins);
        resetUI();
        setShowShopPrompt(true);
        setTimeout(() => setShowShopPrompt(false), 10000);
      });
    }

    return () => {
      if (window.Echo) {
        window.Echo.leave(`user.${user.id}`);
      }
    };
  }, [user]);

  // Countdown Timer
  useEffect(() => {
    if (processing) {
      countdownRef.current = setInterval(() => {
        setCountdown(prev => {
          if (prev <= 1) {
            clearInterval(countdownRef.current);
            resetUI();
            toast.error('Hết thời gian xử lý giao dịch! Vui lòng thử lại sau.');
            return 600;
          }
          return prev - 1;
        });
      }, 1000);
    }

    return () => clearInterval(countdownRef.current);
  }, [processing]);

  // API Functions
  const fetchMultiplier = async () => {
    try {
      const response = await api.get('/time-settings');
      if (response.data.success) {
        const settings = response.data.data;
        // Chúng ta vẫn lưu giá trị từ API để làm việc khác nếu cần
        setMultiplier(settings.number);
        setTimeSettings(settings);
      }
    } catch (error) {
      console.error('Error fetching multiplier:', error);
    }
  };

  const handleConfirmAmount = () => {
    if (!amount || amount < 1000) {
      toast.error('Vui lòng nhập số tiền cần nạp (tối thiểu 1,000 VND)');
      return;
    }

    amountInputRef.current.readOnly = true;
    setProcessing(true);
    loadQRCode(amount);
  };

  // Hàm loadQRCode để tạo mã QR
  const loadQRCode = async (amount) => {
    try {
      const response = await api.post('/generate-qr-code', { amount });
      if (response.data.success) {
        setQrCode(response.data.qrCode);
        setBankInfo(response.data.bankInfo);
      } else {
        toast.error('Không thể tạo mã QR. Vui lòng thử lại sau.');
        resetUI();
      }
    } catch (error) {
      console.error('QR code error:', error);
      toast.error('Có lỗi xảy ra. Vui lòng thử lại sau.');
      resetUI();
    }
  };

  const resetUI = () => {
    setAmount('');
    if (amountInputRef.current) {
      amountInputRef.current.readOnly = false;
    }
    setProcessing(false);
    setQrCode('');
    setCountdown(600);
    clearInterval(countdownRef.current);
  };

  // Tính số xu sẽ nhận được dựa trên số tiền với tỷ lệ cố định và hệ số nhân từ sự kiện
  const calculateCoins = (amountVND) => {
    if (!amountVND) return 0;
    
    // Tính xu cơ bản với tỷ lệ cố định (1.000 VND = 1 xu)
    const baseCoins = Math.floor(parseInt(amountVND) / CONVERSION_RATE);
    
    // Áp dụng hệ số nhân từ sự kiện nếu có
    if (timeSettings?.status === 1 && multiplier > 1) {
      return Math.floor(parseInt(amountVND) * multiplier / 1000);
    }
    
    return baseCoins;
  };

  return (
    <div className="recharge-container">
      {/* Header Section */}
      <div className="recharge-header">
        <div className="recharge-header__content">
          <div className="recharge-header__info">
            <h1 className="recharge-header__title">
              <Coins className="recharge-header__icon" />
              Nạp Xu
            </h1>
            <p className="recharge-header__subtitle">Nạp xu nhanh chóng và an toàn</p>
          </div>
          <div className="recharge-header__balance">
            <div className="recharge-header__amount">{parseInt(currentCoins).toLocaleString()}</div>
            <div className="recharge-header__label">Xu hiện có</div>
          </div>
        </div>
      </div>

      {/* Main Content Grid */}
      <div className="recharge-content">
        {/* Payment Methods Column */}
        <div className="recharge-methods">
          <div className="recharge-methods__header">
            <h2 className="recharge-methods__title">
              <CreditCard className="recharge-methods__icon" />
              Chọn Phương Thức Thanh Toán
            </h2>
          </div>

          <div className="recharge-methods__body">
            {/* Payment Method Selection */}
            <div className="recharge-methods__options">
              {['qr', 'bank'].map((method) => (
                <button
                  key={method}
                  onClick={() => setSelectedPaymentMethod(method)}
                  className={`recharge-method ${
                    selectedPaymentMethod === method ? 'recharge-method--active' : ''
                  }`}
                >
                  <div className="recharge-method__content">
                    {method === 'qr' ? (
                      <>
                        <QrCode className="recharge-method__icon" />
                        <span className="recharge-method__label">Quét Mã QR</span>
                      </>
                    ) : (
                      <>
                        <BanknoteIcon className="recharge-method__icon" />
                        <span className="recharge-method__label">Chuyển Khoản</span>
                      </>
                    )}
                  </div>
                </button>
              ))}
            </div>

            {/* Exchange Rate Info */}
            <div className="recharge-rate">
              <div className="recharge-rate__content">
                <span className="recharge-rate__label">Tỷ lệ quy đổi cơ bản:</span>
                <span className="recharge-rate__value">
                  100.000đ = 100 xu (1.000đ = 1 xu)
                </span>
                {timeSettings?.status === 1 && multiplier > 1 && (
                  <div className="recharge-rate__event">
                    <Sparkles className="recharge-rate__event-icon" />
                    <span>Đang có sự kiện nhân x{multiplier} giá trị nạp</span>
                  </div>
                )}
              </div>
            </div>

            {/* Quick Amount Selection */}
            <div className="recharge-amounts">
              {quickAmounts.map((item) => (
                <button
                  key={item.value}
                  onClick={() => setAmount(item.value.toString())}
                  className="recharge-amount"
                >
                  <div className="recharge-amount__value">
                    {item.value.toLocaleString()}đ
                  </div>
                  {item.hot && <div className="recharge-amount__badge">HOT</div>}
                  {item.special && (
                    <div className="recharge-amount__special">
                      <Sparkles className="recharge-amount__special-icon" />
                    </div>
                  )}
                </button>
              ))}
            </div>

            {/* Custom Amount Input */}
            <div className="recharge-custom">
              <label className="recharge-custom__label">
                Hoặc nhập số tiền khác
              </label>
              <div className="recharge-custom__input-wrapper">
                <input
                  type="number"
                  ref={amountInputRef}
                  value={amount}
                  onChange={(e) => setAmount(e.target.value)}
                  className="recharge-custom__input"
                  placeholder="Nhập số tiền muốn nạp"
                  min="10000"
                  step="10000"
                  disabled={processing}
                />
                <div className="recharge-custom__currency">VND</div>
              </div>
              
              {amount && (
                <div className="recharge-custom__preview">
                  <div className="recharge-custom__xu">
                    Bạn sẽ nhận được: {calculateCoins(amount).toLocaleString()} xu
                  </div>
                  {timeSettings?.status === 1 && multiplier > 1 && (
                    <div className="recharge-custom__bonus">
                      <Sparkles className="recharge-custom__bonus-icon" />
                      Đang có sự kiện nhân x{multiplier} giá trị nạp
                    </div>
                  )}
                </div>
              )}
            </div>

            {/* Action Button */}
            {selectedPaymentMethod === 'qr' && (
              <button
                onClick={processing ? resetUI : handleConfirmAmount}
                className={`recharge-action ${processing ? 'recharge-action--cancel' : ''}`}
              >
                {processing ? 'Hủy giao dịch' : 'Tạo mã QR'}
              </button>
            )}
          </div>
        </div>

        {/* QR Code or Bank Info Column */}
        <div className="recharge-details">
          {selectedPaymentMethod === 'qr' ? (
            <div className="recharge-qr">
              {qrCode ? (
                <div className="recharge-qr__content">
                  <img
                    src={qrCode}
                    alt="QR Code Thanh Toán"
                    className="recharge-qr__image"
                  />
                  <div className="recharge-qr__info">
                    <p className="recharge-qr__amount">
                      Số tiền: <strong>{parseInt(amount).toLocaleString()} VND</strong>
                    </p>
                    <p className="recharge-qr__coins">
                      Xu nhận được: <strong>{calculateCoins(amount).toLocaleString()} xu</strong>
                    </p>
                    <p className="recharge-qr__bank">
                      Ngân hàng: <strong>MB Bank</strong>
                    </p>
                    <p className="recharge-qr__account">
                      Số tài khoản: <strong>{bankInfo?.accountNumber || '90511239999'}</strong>
                    </p>
                    <p className="recharge-qr__description">
                      Nội dung CK: <strong>{bankInfo?.description || `CLTB9D${user?.id}`}</strong>
                    </p>
                    <p className="recharge-qr__instruction">
                      Quét mã QR bằng ứng dụng ngân hàng để thanh toán
                    </p>
                  </div>
                  <div className="recharge-qr__timer">
                    <div className="recharge-qr__countdown">
                      <Timer className="recharge-qr__timer-icon" />
                      Thời gian còn lại: {Math.floor(countdown / 60)}:{(countdown % 60).toString().padStart(2, '0')}
                    </div>
                    <div className="recharge-qr__progress">
                      <div 
                        className="recharge-qr__progress-bar"
                        style={{ width: `${(countdown / 600) * 100}%` }}
                      />
                    </div>
                  </div>
                </div>
              ) : (
                <div className="recharge-qr__placeholder">
                  <QrCode className="recharge-qr__placeholder-icon" />
                  <p className="recharge-qr__placeholder-text">
                    Nhập số tiền và nhấn tạo mã QR để hiển thị mã thanh toán
                  </p>
                </div>
              )}
            </div>
          ) : (
            <div className="recharge-bank">
              <div className="recharge-bank__info">
                <h3 className="recharge-bank__title">
                  Thông tin chuyển khoản
                </h3>
                <div className="recharge-bank__details">
                  {[
                    { label: 'Ngân hàng', value: 'MB Bank' },
                    { label: 'Số tài khoản', value: '90511239999', highlight: true },
                    { label: 'Chủ tài khoản', value: 'LE VIET ANH' },
                    { label: 'Nội dung CK', value: `CLTB9D${user?.id}`, highlight: true }
                  ].map((item, index) => (
                    <div key={index} className="recharge-bank__row">
                      <span className="recharge-bank__label">{item.label}:</span>
                      <span className={`recharge-bank__value ${item.highlight ? 'recharge-bank__value--highlight' : ''}`}>
                        {item.value}
                      </span>
                    </div>
                  ))}
                </div>
                <div className="recharge-bank__conversion-info">
                  <div className="recharge-bank__conversion-title">Tỷ lệ quy đổi cơ bản:</div>
                  <div className="recharge-bank__conversion-rate">100.000đ = 100 xu (1.000đ = 1 xu)</div>
                  {timeSettings?.status === 1 && multiplier > 1 && (
                    <div className="recharge-bank__event-rate">
                      <Sparkles className="recharge-bank__event-icon" />
                      Đang có sự kiện nhân x{multiplier} giá trị nạp
                    </div>
                  )}
                  {amount && (
                    <div className="recharge-bank__example">
                      Ví dụ: Nạp {parseInt(amount).toLocaleString()}đ = {calculateCoins(amount).toLocaleString()} xu
                    </div>
                  )}
                </div>
              </div>

              <div className="recharge-bank__notes">
                <h3 className="recharge-bank__notes-title">
                  <Bell className="recharge-bank__notes-icon" />
                  Lưu ý quan trọng
                </h3>
                <ul className="recharge-bank__notes-list">
                  <li className="recharge-bank__note">
                    <Star className="recharge-bank__note-icon" />
                    Nội dung chuyển khoản phải chính xác 100%
                  </li>
                  <li className="recharge-bank__note">
                    <Star className="recharge-bank__note-icon" />
                    Xu sẽ được cộng tự động sau 1-3 phút
                  </li>
                  <li className="recharge-bank__note">
                    <Star className="recharge-bank__note-icon" />
                    Liên hệ Admin nếu cần hỗ trợ
                  </li>
                </ul>
              </div>
            </div>
          )}
        </div>
      </div>

      {/* RechargeEvents Component */}
      <RechargeEvents />

      {/* Shop Prompt Modal */}
      {showShopPrompt && (
        <div className="recharge-prompt">
          <div className="recharge-prompt__content">
            <div className="recharge-prompt__icon-wrapper">
              <Gift className="recharge-prompt__icon" />
            </div>
            <div className="recharge-prompt__text">
              <h3 className="recharge-prompt__title">
                Nạp xu thành công!
              </h3>
              <div className="recharge-prompt__description">
                Ghé thăm Kỳ Trân Các để khám phá các vật phẩm giá trị nhé!
              </div>
              <div className="recharge-prompt__actions">
                <button
                  onClick={() => navigate('/ky-tran-cac')}
                  className="recharge-prompt__button recharge-prompt__button--primary"
                >
                  Đến Kỳ Trân Các
                </button>
                <button
                  onClick={() => setShowShopPrompt(false)}
                  className="recharge-prompt__button recharge-prompt__button--secondary"
                >
                  Để sau
                </button>
              </div>
            </div>
            <button
              onClick={() => setShowShopPrompt(false)}
              className="recharge-prompt__close"
            >
              <svg className="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      )}
    </div>
  );
};

export default Recharge;