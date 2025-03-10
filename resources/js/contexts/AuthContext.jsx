import React, { createContext, useState, useEffect, useCallback, useContext } from 'react';
import { getCurrentUser } from '../api';
import toast from 'react-hot-toast';

export const AuthContext = createContext();

// Thêm custom hook useAuth
export const useAuth = () => {
    const context = useContext(AuthContext);
    if (!context) {
        throw new Error('useAuth phải được sử dụng trong AuthProvider');
    }
    return context;
};

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);
    const [isAuthenticated, setIsAuthenticated] = useState(false);

    const fetchUser = useCallback(async () => {
        try {
            const token = localStorage.getItem('token');
            if (!token) {
                setLoading(false);
                setIsAuthenticated(false);
                setUser(null);
                return;
            }

            const response = await getCurrentUser();
            if (response.data.user) {
                setUser(response.data.user);
                setIsAuthenticated(true);
            } else {
                throw new Error('Không tìm thấy dữ liệu người dùng');
            }
        } catch (error) {
            console.error('Lỗi khi lấy thông tin người dùng:', error);
            if (error.response?.status === 401) {
                localStorage.removeItem('token');
                setUser(null);
                setIsAuthenticated(false);
                toast.error('Phiên đăng nhập đã hết hạn');
            }
        } finally {
            setLoading(false);
        }
    }, []);

    // Hàm cập nhật thông tin user cụ thể
    const updateUserData = useCallback((newData) => {
        setUser(prev => {
            if (!prev) return newData;
            return {
                ...prev,
                ...newData
            };
        });
    }, []);

    useEffect(() => {
        fetchUser();
    }, [fetchUser]);

    const login = useCallback((token, userData) => {
        if (token && userData) {
            localStorage.setItem('token', token);
            setUser(userData);
            setIsAuthenticated(true);
        }
    }, []);

    const logout = useCallback(async () => {
        try {
            localStorage.removeItem('token');
            setUser(null);
            setIsAuthenticated(false);
        } catch (error) {
            console.error('Lỗi đăng xuất:', error);
        }
    }, []);

    const value = {
        user,
        isAuthenticated,
        loading,
        login,
        logout,
        fetchUser,
        updateUserData
    };

    return (
        <AuthContext.Provider value={value}>
            {!loading && children}
        </AuthContext.Provider>
    );
};