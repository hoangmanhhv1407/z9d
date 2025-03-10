import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Mail, CheckCircle, AlertCircle, Loader, ExternalLink } from 'lucide-react';
import axios from 'axios';
import toast from 'react-hot-toast';
import { useAuth } from '../contexts/AuthContext';
import '../../css/RegistrationStatusPopup.css';

const RegistrationStatusPopup = ({ isOpen, email, onClose, credentials = null }) => {
  const [isActivated, setIsActivated] = useState(false);
  const [isChecking, setIsChecking] = useState(false);
  const [error, setError] = useState(null);
  const [activationCode, setActivationCode] = useState(['', '', '', '', '', '']);
  const navigate = useNavigate();
  const { login: authLogin } = useAuth();

  const getEmailProvider = (email) => {
    if (!email) return null;
    const domain = email.split('@')[1];
    const providers = {
      'gmail.com': 'https://gmail.com',
      'yahoo.com': 'https://mail.yahoo.com',
      'outlook.com': 'https://outlook.live.com',
      'hotmail.com': 'https://outlook.live.com'
    };
    return providers[domain] || null;
  };

  const handleCodeChange = (index, value) => {
    const newCode = [...activationCode];
    newCode[index] = value ? value[0] : '';
    setActivationCode(newCode);

    if (value !== '' && index < 5) {
      const nextInput = document.querySelector(`input[name=code-${index + 1}]`);
      if (nextInput) nextInput.focus();
    }
  };

  const handlePaste = (e, index) => {
    e.preventDefault();
    const pasteData = e.clipboardData.getData('text').slice(0, 6 - index);
    const pasteArray = pasteData.split('');
    const newCode = [...activationCode];
    
    for (let i = 0; i < pasteArray.length; i++) {
      newCode[index + i] = pasteArray[i];
    }
    setActivationCode(newCode);

    pasteArray.forEach((char, i) => {
      const input = document.querySelector(`input[name=code-${index + i}]`);
      if (input) input.value = char;
    });

    const nextIndex = index + pasteArray.length;
    if (nextIndex < 6) {
      const nextInput = document.querySelector(`input[name=code-${nextIndex}]`);
      if (nextInput) nextInput.focus();
    }
  };

  const handleKeyDown = (index, e) => {
    if (e.key === 'Backspace' && activationCode[index] === '' && index > 0) {
      const prevInput = document.querySelector(`input[name=code-${index - 1}]`);
      if (prevInput) prevInput.focus();
    }
  };

  const handleSubmitCode = async () => {
    const code = activationCode.join('');
    if (code.length !== 6) {
      toast.error('Vui lòng nhập đủ mã kích hoạt 6 ký tự');
      return;
    }

    setIsChecking(true);
    try {
      const response = await axios.post('/api/verify-activation-code', {
        email,
        activation_code: code
      });

      if (response.data.success) {
        setIsActivated(true);
        toast.success('Kích hoạt tài khoản thành công!');

        if (response.data.access_token) {
          localStorage.setItem('token', response.data.access_token);
          authLogin(response.data.access_token, response.data.user);
          toast.success('Đăng nhập tự động thành công!');
        }

        setTimeout(() => {
          onClose();
          navigate('/', { replace: true });
          window.location.reload();
        }, 3000);
      }
    } catch (error) {
      setError(error.response?.data?.message || 'Mã kích hoạt không đúng. Vui lòng thử lại.');
      toast.error(error.response?.data?.message || 'Mã kích hoạt không đúng. Vui lòng thử lại.');
      setActivationCode(['', '', '', '', '', '']);
    } finally {
      setIsChecking(false);
    }
  };

  const resendActivation = async () => {
    try {
      const response = await axios.post('/api/send-activation-mail', { email });
      toast.success('Đã gửi lại mã kích hoạt. Vui lòng kiểm tra hộp thư của bạn.');
    } catch (error) {
      toast.error('Có lỗi xảy ra khi gửi lại mã kích hoạt.');
    }
  };

  if (!isOpen) return null;

  const emailProvider = getEmailProvider(email);

  return (
    <div className="activation-overlay">
      <div className="activation-container">
        <div className="activation-content">
          {isActivated ? (
            <div className="success-state">
              <CheckCircle className="success-icon" />
              <h2 className="success-title">Kích Hoạt Thành Công!</h2>
              <p className="success-message">
                Tài khoản của bạn đã được kích hoạt thành công.
                {credentials ? ' Hệ thống sẽ tự động đăng nhập cho bạn.' : ''}
              </p>
              <div className="redirect-message">
                Tự động chuyển hướng sau 3 giây...
              </div>
            </div>
          ) : (
            <div className="verification-state">
              <Mail className="mail-icon" />
              <h2 className="verification-title">Nhập Mã Kích Hoạt</h2>

              <div className="verification-content">
                <div className="notification-box">
                  <p className="notification-title">Thông báo quan trọng!</p>
                  <p className="notification-message">
                    Vui lòng kiểm tra email hộp thư đến hoặc trong hộp thư spam của bạn , để lấy mã kích hoạt 6 ký tự.
                    Bạn cần kích hoạt tài khoản để có thể chơi game và sử dụng tất cả tính năng.
                  </p>
                </div>

                <p className="email-info">
                  Mã kích hoạt đã được gửi đến:
                  <br />
                  <span className="email-address">{email}</span>
                </p>

                <div className="code-input-group">
                  {activationCode.map((digit, index) => (
                    <input
                      key={index}
                      type="text"
                      name={`code-${index}`}
                      value={digit}
                      onChange={(e) => handleCodeChange(index, e.target.value)}
                      onKeyDown={(e) => handleKeyDown(index, e)}
                      onPaste={(e) => handlePaste(e, index)}
                      className="code-input"
                      maxLength={1}
                    />
                  ))}
                </div>

                <button
                  onClick={handleSubmitCode}
                  disabled={isChecking}
                  className="submit-button"
                >
                  {isChecking ? (
                    <span className="loading-content">
                      <Loader className="loading-spinner" />
                      Đang xác thực...
                    </span>
                  ) : (
                    'Xác nhận kích hoạt'
                  )}
                </button>

                {emailProvider && (
                  <a
                    href={emailProvider}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="mail-provider-link"
                  >
                    <span>Mở {email.split('@')[1]}</span>
                    <ExternalLink size={16} />
                  </a>
                )}

                <button
                  onClick={resendActivation}
                  className="resend-button"
                >
                  Gửi lại mã kích hoạt
                </button>

                {error && (
                  <div className="error-message">
                    <AlertCircle className="error-icon" />
                    <span>{error}</span>
                  </div>
                )}
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default RegistrationStatusPopup;