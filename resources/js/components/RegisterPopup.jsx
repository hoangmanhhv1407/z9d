import React, { useState, useContext, useEffect } from 'react';
import { X, User, Lock, Mail, Eye, EyeOff } from 'lucide-react';
import { register } from '../api';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';
import { AuthContext } from "../contexts/AuthContext";
import TermsPopup from './TermsPopup';
import toast from 'react-hot-toast';
import RegistrationStatusPopup from './RegistrationStatusPopup';
import '../../css/RegisterPopup.css';

const RegisterPopup = ({ isOpen, onClose, onSwitchToLogin }) => {
    const [formData, setFormData] = useState({
        u_name: '',
        email: '',
        password: '',
        password_confirmation: ''
    });

    const [showTerms, setShowTerms] = useState(true);
    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);
    const [error, setError] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [showActivationStatus, setShowActivationStatus] = useState(false);
    const [registeredEmail, setRegisteredEmail] = useState('');

    const navigate = useNavigate();
    const { login: authLogin } = useContext(AuthContext);

    useEffect(() => {
        if (isOpen && !showTerms) {
            const script = document.createElement('script');
            script.src = `https://www.google.com/recaptcha/api.js?render=${import.meta.env.VITE_RECAPTCHA_SITE_KEY}`;
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);

            return () => {
                document.head.removeChild(script);
            };
        }
    }, [isOpen, showTerms]);

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
        setError('');
    };

    const executeRecaptcha = async () => {
        try {
            return await new Promise((resolve, reject) => {
                window.grecaptcha?.ready(async () => {
                    try {
                        const token = await window.grecaptcha.execute(
                            import.meta.env.VITE_RECAPTCHA_SITE_KEY, 
                            { action: 'register' }
                        );
                        resolve(token);
                    } catch (error) {
                        reject(error);
                    }
                });
            });
        } catch (error) {
            console.error('reCAPTCHA error:', error);
            throw new Error('Không thể xác thực reCAPTCHA. Vui lòng thử lại sau.');
        }
    };

    const sendActivationMail = async (email) => {
        try {
            const response = await axios.post('/api/send-activation-mail', { email });
            toast.success('Đã gửi email kích hoạt. Vui lòng kiểm tra hộp thư.');
        } catch (error) {
            toast.error('Có lỗi xảy ra khi gửi email kích hoạt.');
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setIsLoading(true);
        setError('');

        try {
            const recaptchaToken = await executeRecaptcha();
            const response = await register({
                ...formData,
                recaptcha_token: recaptchaToken
            });
            
            if (response.data.alert === 'success') {
                await sendActivationMail(formData.email);
                setRegisteredEmail(formData.email);
                setShowActivationStatus(true);
                
                setFormData({
                    u_name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                });

                toast.success('Đăng ký tài khoản thành công! Vui lòng kiểm tra email để kích hoạt tài khoản.');
            }
        } catch (error) {
            let errorMessage = 'Đã có lỗi xảy ra. Vui lòng thử lại sau';
            
            if (error.response?.data?.error) {
                errorMessage = typeof error.response.data.error === 'object'
                    ? Object.values(error.response.data.error).flat().join(', ')
                    : error.response.data.error;
            } else if (error.message) {
                errorMessage = error.message;
            }
            
            setError(errorMessage);
            toast.error(errorMessage);
        } finally {
            setIsLoading(false);
        }
    };

    if (!isOpen) return null;

    if (showTerms) {
        return (
            <TermsPopup 
                onAccept={() => setShowTerms(false)}
                onClose={onClose}
            />
        );
    }

    return (
        <>
            <div className="rdp-overlay">
                <div className="rdp-container">
                    {/* Header */}
                    <div className="rdp-header">
                        <div className="rdp-title-container">
                            <h2 className="rdp-title">ĐĂNG KÝ TÀI KHOẢN</h2>
                        </div>
                        <button 
                            onClick={onClose} 
                            className="rdp-close"
                        >
                            <X size={24} />
                        </button>
                    </div>
                    
                    {/* Logo */}
                    <div className="rdp-logo-wrapper">
                        <img 
                            src="/frontend/images/z9dlogo.png" 
                            alt="Logo" 
                            className="rdp-logo-img"
                        />
                    </div>

                    {/* Form */}
                    <form onSubmit={handleSubmit} className="rdp-form">
                        <div className="rdp-input-group">
                            <User className="rdp-input-icon" size={20} />
                            <input
                                type="text"
                                name="u_name"
                                value={formData.u_name}
                                onChange={handleInputChange}
                                placeholder="Tên đăng nhập"
                                className="rdp-input"
                                disabled={isLoading}
                            />
                        </div>

                        <div className="rdp-input-group">
                            <Mail className="rdp-input-icon" size={20} />
                            <input
                                type="email"
                                name="email"
                                value={formData.email}
                                onChange={handleInputChange}
                                placeholder="Email"
                                className="rdp-input"
                                disabled={isLoading}
                            />
                        </div>

                        <div className="rdp-input-group">
                            <Lock className="rdp-input-icon" size={20} />
                            <input
                                type={showPassword ? "text" : "password"}
                                name="password"
                                value={formData.password}
                                onChange={handleInputChange}
                                placeholder="Mật khẩu"
                                className="rdp-input"
                                disabled={isLoading}
                            />
                            <button 
                                type="button"
                                onClick={() => setShowPassword(!showPassword)}
                                className="rdp-password-toggle"
                                disabled={isLoading}
                            >
                                {showPassword ? <EyeOff size={20} /> : <Eye size={20} />}
                            </button>
                        </div>

                        <div className="rdp-input-group">
                            <Lock className="rdp-input-icon" size={20} />
                            <input
                                type={showConfirmPassword ? "text" : "password"}
                                name="password_confirmation"
                                value={formData.password_confirmation}
                                onChange={handleInputChange}
                                placeholder="Xác nhận mật khẩu"
                                className="rdp-input"
                                disabled={isLoading}
                            />
                            <button 
                                type="button"
                                onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                                className="rdp-password-toggle"
                                disabled={isLoading}
                            >
                                {showConfirmPassword ? <EyeOff size={20} /> : <Eye size={20} />}
                            </button>
                        </div>

                        {error && (
                            <div className="rdp-error-message">
                                <p className="rdp-error-text">{error}</p>
                            </div>
                        )}

                        <button 
                            type="submit" 
                            className={`rdp-button ${isLoading ? 'rdp-button-loading' : ''}`}
                            disabled={isLoading}
                        >
                            {isLoading ? (
                                <span className="rdp-loading-content">
                                    <div className="rdp-loading-spinner"></div>
                                    Đang đăng ký...
                                </span>
                            ) : 'Đăng ký'}
                        </button>
                    </form>

                    <div className="rdp-login-prompt">
                        <p>
                            Đã có tài khoản?{' '}
                            <button 
                                onClick={onSwitchToLogin} 
                                className="rdp-login-link"
                                disabled={isLoading}
                            >
                                Đăng nhập
                            </button>
                        </p>
                    </div>

                    <div className="rdp-recaptcha-notice">
                        <p>
                            Trang web này được bảo vệ bởi reCAPTCHA và tuân theo
                            {' '}<a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer" className="rdp-recaptcha-link">Chính sách Bảo mật</a>{' '}
                            và{' '}<a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer" className="rdp-recaptcha-link">Điều khoản Dịch vụ</a>{' '}
                            của Google.
                        </p>
                    </div>
                </div>
            </div>

            <RegistrationStatusPopup
                isOpen={showActivationStatus}
                email={registeredEmail}
                onClose={() => {
                    setShowActivationStatus(false);
                    onClose();
                    navigate('/');
                }}
            />
        </>
    );
};

export default RegisterPopup;