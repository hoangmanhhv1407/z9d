// LoginPopup.jsx
import React, { useState, useContext } from 'react';
import { X, User, Lock, Eye, EyeOff } from 'lucide-react';
import { login } from '../api';
import { useNavigate } from 'react-router-dom';
import { AuthContext } from "../contexts/AuthContext";
import toast from 'react-hot-toast';
import ForgotPassword from './ForgotPassword';
import '../../css/LoginPopup.css';

const LoginPopup = ({ isOpen, onClose, onSwitchToRegister }) => {
    const [u_name, setUsername] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const [showPassword, setShowPassword] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const [showForgotPassword, setShowForgotPassword] = useState(false);
    const navigate = useNavigate();
    const { login: authLogin } = useContext(AuthContext);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        setIsLoading(true);

        try {
            const response = await login({ u_name, password });
            
            if (response.data.access_token) {
                authLogin(response.data.access_token, response.data.user);
                setUsername('');
                setPassword('');
                onClose();
                toast.success('Đăng nhập thành công!', {
                    duration: 3000,
                    position: 'top-right'
                });
                navigate('/');
            }
        } catch (error) {
            if (error.response && error.response.status === 401) {
                setError('Tài khoản hoặc mật khẩu không chính xác');
                toast.error('Tài khoản hoặc mật khẩu không chính xác');
            } else {
                setError('Đã có lỗi xảy ra. Vui lòng thử lại sau');
                toast.error('Đã có lỗi xảy ra. Vui lòng thử lại sau');
            }
        } finally {
            setIsLoading(false);
        }
    };

    if (!isOpen) return null;

    return (
        <div className="lgn-overlay">
            <div className="lgn-container">
                {/* Header */}
                <div className="lgn-header">
                    <div className="lgn-title-container">
                        <h2 className="lgn-title">ĐĂNG NHẬP</h2>
                    </div>
                    <button onClick={onClose} className="lgn-close">
                        <X size={24} />
                    </button>
                </div>

                {/* Logo */}
                <div className="lgn-logo-wrapper">
                    <img 
                        src="/frontend/images/z9dlogo.png" 
                        alt="Logo" 
                        className="lgn-logo-img"
                    />
                </div>
                
                {/* Form */}
                <form onSubmit={handleSubmit} className="lgn-form">
                    <div className="lgn-input-group">
                        <User className="lgn-input-icon" size={20} />
                        <input
                            type="text"
                            value={u_name}
                            onChange={(e) => setUsername(e.target.value)}
                            placeholder="Tên đăng nhập"
                            className="lgn-input"
                            disabled={isLoading}
                        />
                    </div>

                    <div className="lgn-input-group">
                        <Lock className="lgn-input-icon" size={20} />
                        <input
                            type={showPassword ? "text" : "password"}
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            placeholder="Mật khẩu"
                            className="lgn-input"
                            disabled={isLoading}
                        />
                        <button 
                            type="button"
                            onClick={() => setShowPassword(!showPassword)}
                            className="lgn-password-toggle"
                            disabled={isLoading}
                        >
                            {showPassword ? <EyeOff size={20} /> : <Eye size={20} />}
                        </button>
                    </div>

                    <div className="lgn-forgot-password">
                        <button
                            type="button"
                            onClick={() => setShowForgotPassword(true)}
                            className="lgn-forgot-password-link"
                            disabled={isLoading}
                        >
                            Quên mật khẩu?
                        </button>
                    </div>

                    {error && (
                        <div className="lgn-error-message">
                            <p>{error}</p>
                        </div>
                    )}

                    <button 
                        type="submit" 
                        className={`lgn-button ${isLoading ? 'loading' : ''}`}
                        disabled={isLoading}
                    >
                        {isLoading ? (
                            <span className="lgn-loading-content">
                                <div className="lgn-loading-spinner"></div>
                                Đang đăng nhập...
                            </span>
                        ) : 'Đăng nhập'}
                    </button>
                </form>

                <div className="lgn-register-prompt">
                    <p>
                        Chưa có tài khoản?{' '}
                        <button 
                            onClick={onSwitchToRegister} 
                            className="lgn-register-link"
                            disabled={isLoading}
                        >
                            Đăng ký ngay
                        </button>
                    </p>
                </div>

                <div className="lgn-notice">
                    <h5>Lưu ý:</h5>
                    <ul>
                        <li>Không chia sẻ tài khoản cho người khác</li>
                        <li>Sử dụng mật khẩu mạnh và không dễ đoán</li>
                        <li>Đảm bảo đăng xuất khi sử dụng máy tính công cộng</li>
                    </ul>
                </div>
            </div>

            <ForgotPassword
                isOpen={showForgotPassword}
                onClose={() => setShowForgotPassword(false)}
                onSwitchToLogin={() => setShowForgotPassword(false)}
            />
        </div>
    );
};

export default LoginPopup;