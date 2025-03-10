import React, { useState } from 'react';
import { Mail, X, RefreshCw, User, Eye, EyeOff, Lock, ArrowLeft } from 'lucide-react';
import { toast } from 'react-hot-toast';
import axios from 'axios';
import '../../css/ForgotPassword.css';

const ForgotPassword = ({ isOpen, onClose, onSwitchToLogin }) => {
  const [step, setStep] = useState(1);
  const [formData, setFormData] = useState({
    email: '',
    username: '',
    code: '',
    password: '',
    password_confirmation: ''
  });
  const [errors, setErrors] = useState({});
  const [isLoading, setIsLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  const [verificationCode, setVerificationCode] = useState(['', '', '', '', '', '']);
  const codeInputRefs = Array(6).fill(0).map((_, i) => React.createRef());



// Hàm xử lý input cho mã xác nhận
const handleCodeChange = (index, value) => {
  if (value.length > 1) return; // Chỉ cho phép 1 ký tự mỗi ô

  const newCode = [...verificationCode];
  newCode[index] = value;
  setVerificationCode(newCode);

  // Tự động focus ô tiếp theo
  if (value && index < 5) {
    codeInputRefs[index + 1].current.focus();
  }

  // Cập nhật formData.code
  setFormData(prev => ({
    ...prev,
    code: newCode.join('')
  }));
};

// Hàm xử lý phím backspace
const handleKeyDown = (index, e) => {
  if (e.key === 'Backspace' && !verificationCode[index] && index > 0) {
    codeInputRefs[index - 1].current.focus();
  }
};

// Hàm xử lý paste
const handlePaste = (e) => {
  e.preventDefault();
  const pastedData = e.clipboardData.getData('text').slice(0, 6);
  const newCode = [...verificationCode];
  
  for (let i = 0; i < pastedData.length; i++) {
    if (i < 6) {
      newCode[i] = pastedData[i];
    }
  }
  
  setVerificationCode(newCode);
  setFormData(prev => ({
    ...prev,
    code: newCode.join('')
  }));

  // Focus vào ô cuối cùng có dữ liệu
  const lastFilledIndex = Math.min(pastedData.length - 1, 5);
  if (lastFilledIndex >= 0) {
    codeInputRefs[lastFilledIndex].current.focus();
  }
};

  const validateStep1 = () => {
    const newErrors = {};
    if (!formData.username.trim()) newErrors.username = 'Vui lòng nhập tên tài khoản';
    if (!formData.email.trim()) {
      newErrors.email = 'Vui lòng nhập email';
    } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
      newErrors.email = 'Email không đúng định dạng';
    }
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const validateStep2 = () => {
    const newErrors = {};
    if (!formData.code || formData.code.length !== 6) {
      newErrors.code = 'Mã xác nhận phải có 6 ký tự';
    }
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const validateStep3 = () => {
    const newErrors = {};
    if (!formData.password) {
      newErrors.password = 'Vui lòng nhập mật khẩu mới';
    } else if (formData.password.length < 6) {
      newErrors.password = 'Mật khẩu phải có ít nhất 6 ký tự';
    }
    if (formData.password !== formData.password_confirmation) {
      newErrors.password_confirmation = 'Mật khẩu không khớp';
    }
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
    if (errors[name]) setErrors(prev => ({ ...prev, [name]: '' }));
  };

  const handleStep1Submit = async (e) => {
    e.preventDefault();
    if (!validateStep1()) return;

    setIsLoading(true);
    try {
      const response = await axios.post('/api/password/email', {
        email: formData.email,
        username: formData.username
      });
      toast.success('Mã xác nhận đã được gửi đến email của bạn');
      setStep(2);
    } catch (error) {
      toast.error(error.response?.data?.message || 'Có lỗi xảy ra');
    } finally {
      setIsLoading(false);
    }
  };

  const handleStep2Submit = async (e) => {
    e.preventDefault();
    if (!validateStep2()) return;

    setIsLoading(true);
    try {
      const response = await axios.post('/api/password/verify-code', {
        email: formData.email,
        code: formData.code
      });
      setStep(3);
      toast.success('Xác nhận mã thành công');
    } catch (error) {
      toast.error(error.response?.data?.message || 'Mã xác nhận không hợp lệ');
    } finally {
      setIsLoading(false);
    }
  };

  const handleStep3Submit = async (e) => {
    e.preventDefault();
    if (!validateStep3()) return;

    setIsLoading(true);
    try {
      await axios.post('/api/password/reset', {
        email: formData.email,
        code: formData.code,
        password: formData.password,
        password_confirmation: formData.password_confirmation
      });
      toast.success('Đặt lại mật khẩu thành công');
      onClose();
      onSwitchToLogin();
    } catch (error) {
      toast.error(error.response?.data?.message || 'Có lỗi xảy ra');
    } finally {
      setIsLoading(false);
    }
  };

  const resendCode = async () => {
    setIsLoading(true);
    try {
      await axios.post('/api/password/email', {
        email: formData.email,
        username: formData.username
      });
      toast.success('Đã gửi lại mã xác nhận');
    } catch (error) {
      toast.error('Không thể gửi lại mã xác nhận');
    } finally {
      setIsLoading(false);
    }
  };

  
  if (!isOpen) return null;

  const getStepTitle = () => {
    switch(step) {
      case 1:
        return 'Xác Thực Tài Khoản';
      case 2:
        return 'Nhập Mã Xác Nhận';
      case 3:
        return 'Đặt Lại Mật Khẩu';
      default:
        return 'Quên Mật Khẩu';
    }
  };

  return (
    <div className="fp-overlay">
      <div className="fp-container">
        {/* Header */}
        <div className="fp-header">
          {step > 1 && (
            <button 
              onClick={() => setStep(step - 1)}
              className="fp-back-button"
            >
              <ArrowLeft size={20} />
            </button>
          )}
          <h2 className="fp-title">{getStepTitle()}</h2>
          <button onClick={onClose} className="fp-close-button">
            <X size={20} />
          </button>
        </div>

        {/* Logo */}
        <div className="fp-logo-wrapper">
          <img 
            src="/frontend/images/z9dlogo.png" 
            alt="Logo" 
            className="fp-logo-img"
          />
        </div>

        {/* Progress Steps */}
        <div className="fp-progress-steps">
          {[1, 2, 3].map((stepNumber) => (
            <div 
              key={stepNumber}
              className={`fp-step ${step >= stepNumber ? 'fp-active' : ''}`}
            >
              <div className="fp-step-number">{stepNumber}</div>
              <div className="fp-step-line"></div>
            </div>
          ))}
        </div>

        {/* Step 1: Nhập thông tin tài khoản */}
        {step === 1 && (
          <form onSubmit={handleStep1Submit} className="fp-form">
            <div className="fp-form-group">
              <User className="fp-input-icon" size={20} />
              <input
                type="text"
                name="username"
                value={formData.username}
                onChange={handleChange}
                placeholder="Tên tài khoản"
                className="fp-input-field"
                disabled={isLoading}
              />
              {errors.username && <span className="fp-error-message">{errors.username}</span>}
            </div>

            <div className="fp-form-group">
              <Mail className="fp-input-icon" size={20} />
              <input
                type="email"
                name="email"
                value={formData.email}
                onChange={handleChange}
                placeholder="Email đã đăng ký"
                className="fp-input-field"
                disabled={isLoading}
              />
              {errors.email && <span className="fp-error-message">{errors.email}</span>}
            </div>

            <button
              type="submit"
              className={`fp-submit-button ${isLoading ? 'fp-loading' : ''}`}
              disabled={isLoading}
            >
              {isLoading ? (
                <span className="fp-loading-content">
                  <RefreshCw className="fp-loading-icon" size={20} />
                  Đang xử lý...
                </span>
              ) : 'Gửi mã xác nhận'}
            </button>
          </form>
        )}

        {/* Step 2: Nhập mã xác nhận */}
        {step === 2 && (
          <div className="fp-verification-container">
            <div className="fp-email-info">
              <p>Chúng tôi đã gửi mã xác nhận đến:</p>
              <strong>{formData.email}</strong>
            </div>

            <form onSubmit={handleStep2Submit} className="fp-verification-form">
              <div className="fp-code-input-group">
                {verificationCode.map((digit, index) => (
                  <input
                    key={index}
                    ref={codeInputRefs[index]}
                    type="text"
                    maxLength={1}
                    value={digit}
                    onChange={(e) => handleCodeChange(index, e.target.value)}
                    onKeyDown={(e) => handleKeyDown(index, e)}
                    onPaste={index === 0 ? handlePaste : undefined}
                    className="fp-code-input-digit"
                    disabled={isLoading}
                    autoComplete="off"
                  />
                ))}
              </div>
              {errors.code && <span className="fp-error-message">{errors.code}</span>}

              <button
                type="submit"
                className={`fp-submit-button ${isLoading ? 'fp-loading' : ''}`}
                disabled={isLoading || verificationCode.join('').length !== 6}
              >
                {isLoading ? 'Đang xác thực...' : 'Xác nhận'}
              </button>

              <button
                type="button"
                onClick={resendCode}
                className="fp-resend-button"
                disabled={isLoading}
              >
                Gửi lại mã xác nhận
              </button>
            </form>
          </div>
        )}

        {/* Step 3: Đặt lại mật khẩu */}
        {step === 3 && (
          <form onSubmit={handleStep3Submit} className="fp-reset-form">
            <div className="fp-form-group">
              <Lock className="fp-input-icon" size={20} />
              <input
                type={showPassword ? "text" : "password"}
                name="password"
                value={formData.password}
                onChange={handleChange}
                placeholder="Mật khẩu mới"
                className="fp-input-field"
                disabled={isLoading}
              />
              <button
                type="button"
                onClick={() => setShowPassword(!showPassword)}
                className="fp-password-toggle"
              >
                {showPassword ? <EyeOff size={20} /> : <Eye size={20} />}
              </button>
              {errors.password && <span className="fp-error-message">{errors.password}</span>}
            </div>

            <div className="fp-form-group">
              <Lock className="fp-input-icon" size={20} />
              <input
                type={showConfirmPassword ? "text" : "password"}
                name="password_confirmation"
                value={formData.password_confirmation}
                onChange={handleChange}
                placeholder="Xác nhận mật khẩu mới"
                className="fp-input-field"
                disabled={isLoading}
              />
              <button
                type="button"
                onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                className="fp-password-toggle"
              >
                {showConfirmPassword ? <EyeOff size={20} /> : <Eye size={20} />}
              </button>
              {errors.password_confirmation && (
                <span className="fp-error-message">{errors.password_confirmation}</span>
              )}
            </div>

            <button
              type="submit"
              className={`fp-submit-button ${isLoading ? 'fp-loading' : ''}`}
              disabled={isLoading}
            >
              {isLoading ? 'Đang xử lý...' : 'Đặt lại mật khẩu'}
            </button>
          </form>
        )}

        <div className="fp-login-link">
          <button
            onClick={onSwitchToLogin}
            className="fp-switch-login-button"
            disabled={isLoading}
          >
            Quay lại đăng nhập
          </button>
        </div>
      </div>
    </div>
  );
};

export default ForgotPassword;