import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import axios from 'axios';
import { CheckCircle, AlertCircle, Loader } from 'lucide-react';

const ActivateAccount = () => {
    const { token } = useParams();
    const [status, setStatus] = useState({ type: 'loading' });
    const navigate = useNavigate();

    useEffect(() => {
        const activateAccount = async () => {
            try {
                const response = await axios.get(`/api/activate-account/${token}`);
                setStatus({ 
                    type: 'success', 
                    message: response.data.message || 'Kích hoạt tài khoản thành công!' 
                });
                // Tự động chuyển hướng sau 3 giây
                setTimeout(() => {
                    navigate('/', { replace: true });
                    window.location.reload(); // Đảm bảo reload để cập nhật trạng thái
                }, 3000);
            } catch (error) {
                setStatus({
                    type: 'error',
                    message: error.response?.data?.message || 'Có lỗi xảy ra trong quá trình kích hoạt tài khoản.'
                });
            }
        };

        activateAccount();
    }, [token, navigate]);

    const handleGoHome = () => {
        navigate('/', { replace: true });
        window.location.reload();
    };

    return (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div className="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl">
                <div className="flex flex-col items-center text-center space-y-6">
                    {status.type === 'loading' && (
                        <>
                            <Loader className="w-16 h-16 text-blue-500 animate-spin" />
                            <h2 className="text-2xl font-bold text-blue-600">Đang xử lý...</h2>
                            <p className="text-gray-600">Vui lòng đợi trong giây lát</p>
                        </>
                    )}
                    
                    {status.type === 'success' && (
                        <>
                            <CheckCircle className="w-16 h-16 text-green-500" />
                            <h2 className="text-2xl font-bold text-green-600">Kích Hoạt Thành Công!</h2>
                            <p className="text-gray-600">{status.message}</p>
                            <p className="text-sm text-gray-500">Tự động chuyển hướng sau 3 giây...</p>
                            <button
                                onClick={handleGoHome}
                                className="px-6 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition-colors duration-300"
                            >
                                Về Trang Chủ Ngay
                            </button>
                        </>
                    )}
                    
                    {status.type === 'error' && (
                        <>
                            <AlertCircle className="w-16 h-16 text-red-500" />
                            <h2 className="text-2xl font-bold text-red-600">Kích Hoạt Thất Bại</h2>
                            <p className="text-gray-600">{status.message}</p>
                            <button
                                onClick={handleGoHome}
                                className="px-6 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors duration-300"
                            >
                                Về Trang Chủ
                            </button>
                        </>
                    )}
                </div>
            </div>
        </div>
    );
};

export default ActivateAccount;