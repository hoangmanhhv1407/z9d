import React, { useState } from 'react';
import { Key, Eye, EyeOff, RefreshCw, Shield } from 'lucide-react';
import { toast } from 'react-hot-toast';
import api from '../../api';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import '../../../css/Security.css';

const Security = ({ user }) => {
  const [formData, setFormData] = useState({
    old_password: '',
    password: '',
    password_confirmation: ''
  });
  
  const [showOldPassword, setShowOldPassword] = useState(false);
  const [showNewPassword, setShowNewPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  const [loading, setLoading] = useState(false);

  const navigate = useNavigate();
  const { logout } = useAuth();

  const validateForm = () => {
    if (!formData.old_password || !formData.password || !formData.password_confirmation) {
      toast.error('Vui lòng điền đầy đủ thông tin');
      return false;
    }

    if (formData.password.length < 6) {
      toast.error('Mật khẩu mới phải có ít nhất 6 ký tự');
      return false;  
    }

    if (formData.password !== formData.password_confirmation) {
      toast.error('Mật khẩu mới không khớp');
      return false;
    }

    return true;
  };

  const handlePasswordChange = async (e) => {
    e.preventDefault();
    
    if (!validateForm()) return;

    setLoading(true);

    try {
      const response = await api.post('/reset-password', formData);

      if (response.data.message) {
        toast.success(response.data.message);
        
        setTimeout(async () => {
          await logout();
          navigate('/');
        }, 1500);
      }
    } catch (error) {
      console.error('Error changing password:', error);
      const errorMessage = error.response?.data?.error || 'Có lỗi xảy ra khi đổi mật khẩu';
      toast.error(errorMessage);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="sec-container">
      <div className="sec-content">
        <div className="sec-header">
          <Shield className="sec-header__icon" />
          <h4 className="sec-header__title">Bảo Mật Tài Khoản</h4>
        </div>

        <div className="sec-warning">
          <div className="sec-warning__content">
            <svg className="sec-warning__icon" viewBox="0 0 20 20" fill="currentColor">
              <path fillRule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
            </svg>
            <p className="sec-warning__text">
              Sau khi đổi mật khẩu thành công, bạn sẽ được tự động đăng xuất và cần đăng nhập lại.
            </p>
          </div>
        </div>

        <form onSubmit={handlePasswordChange} className="sec-form">
          <div className="sec-form__group">
            <label className="sec-form__label">Mật khẩu cũ</label>
            <div className="sec-form__input-wrapper">
              <Key className="sec-form__icon" />
              <input
                type={showOldPassword ? 'text' : 'password'}
                value={formData.old_password}
                onChange={(e) => setFormData({ ...formData, old_password: e.target.value })}
                placeholder="Nhập mật khẩu cũ"
                className="sec-form__input"
                disabled={loading}
              />
              <button
                type="button"
                onClick={() => setShowOldPassword(!showOldPassword)}
                className="sec-form__toggle"
              >
                {showOldPassword ? <EyeOff /> : <Eye />}
              </button>
            </div>
          </div>

          <div className="sec-form__group">
            <label className="sec-form__label">Mật khẩu mới</label>
            <div className="sec-form__input-wrapper">
              <Key className="sec-form__icon" />
              <input
                type={showNewPassword ? 'text' : 'password'}
                value={formData.password}
                onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                placeholder="Nhập mật khẩu mới"
                className="sec-form__input"
                disabled={loading}
              />
              <button
                type="button"
                onClick={() => setShowNewPassword(!showNewPassword)}
                className="sec-form__toggle"
              >
                {showNewPassword ? <EyeOff /> : <Eye />}
              </button>
            </div>
          </div>

          <div className="sec-form__group">
            <label className="sec-form__label">Xác nhận mật khẩu mới</label>
            <div className="sec-form__input-wrapper">
              <Key className="sec-form__icon" />
              <input
                type={showConfirmPassword ? 'text' : 'password'}
                value={formData.password_confirmation}
                onChange={(e) => setFormData({ ...formData, password_confirmation: e.target.value })}
                placeholder="Xác nhận mật khẩu mới"
                className="sec-form__input"
                disabled={loading}
              />
              <button
                type="button"
                onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                className="sec-form__toggle"
              >
                {showConfirmPassword ? <EyeOff /> : <Eye />}
              </button>
            </div>
          </div>

          <button type="submit" className="sec-form__submit" disabled={loading}>
            {loading ? (
              <>
                <RefreshCw className="sec-form__submit-icon sec-form__submit-icon--spinning" />
                <span>Đang xử lý...</span>
              </>
            ) : (
              'Đổi mật khẩu'
            )}
          </button>
        </form>
      </div>
    </div>
  );
};

export default Security;