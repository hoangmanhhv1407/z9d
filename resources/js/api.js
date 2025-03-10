import axios from 'axios';

const api = axios.create({
    baseURL: '/api/',
    withCredentials: true,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

// Sửa lại interceptor
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = token; // Token đã có Bearer prefix nên không cần thêm
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Auth APIs
export const authApi = {
    login: async (userData) => {
        try {
            const response = await api.post('/login', userData);
            if (response.data.access_token) {
                // Lưu token đã có Bearer prefix
                localStorage.setItem('token', response.data.access_token);
            }
            return response;
        } catch (error) {
            console.error('Login error:', error);
            throw error;
        }
    },
    getCurrentUser: async () => {
        try {
            const token = localStorage.getItem('token');
            console.log('Current token:', token); // Debug log
            return await api.get('/user');
        } catch (error) {
            console.error('Get current user error:', error);
            throw error;
        }
    }
};


// Thêm interceptor kiểm tra token admin
api.interceptors.response.use(
    response => response,
    error => {
      if (error.response?.status === 401) {
        localStorage.removeItem('admin_token');
        // Redirect to admin login
      }
      return Promise.reject(error);
    }
  );

// admin
const adminApi = {
    login: async (credentials) => {
      const response = await api.post('/api/admins/login', credentials);
      if (response.data.access_token) {
        localStorage.setItem('admin_token', response.data.access_token);
      }
      return response;
    },
  
    // Thêm admin_token vào header cho các request admin
    callAdminApi: async (endpoint, options = {}) => {
      const adminToken = localStorage.getItem('admin_token');
      if (!adminToken) {
        throw new Error('No admin token');
      }
  
      return api({
        ...options,
        url: `/api/admins/${endpoint}`,
        headers: {
          ...options.headers,
          Authorization: adminToken
        }
      });
    }
  };

  

export const register = (userData) => api.post('/register', userData);
export const login = (userData) => api.post('/login', userData);
// api.jsx
// Thêm log để debug
export const logout = async () => {
    try {
        const token = localStorage.getItem('token');
        if (!token) {
            throw new Error('No token found');
        }

        const tokenWithPrefix = token.startsWith('Bearer ') ? token : `Bearer ${token}`;
        
        const response = await api.post('/logout', {}, {
            headers: {
                'Authorization': tokenWithPrefix,
                'Accept': 'application/json'
            }
        });

        return response;
    } catch (error) {
        // Nếu token hết hạn hoặc không hợp lệ, vẫn cho phép logout ở client
        if (error.response?.status === 401) {
            localStorage.removeItem('token');
            return { data: { message: 'Logged out successfully' } };
        }
        throw error;
    }
};
export const getCurrentUser = () => api.get('/user');
export const resetPassword = (passwordData) => api.post('/reset-password', passwordData);
export const getCoinBalance = () => api.get('/coin-balance');
export const getCurrentCoins = () => api.get('/current-coins');
export const getTransactionHistory = () => api.get('/transaction-history');
// API endpoints cho DailyGift
export const dailyGiftApi = {
    getDailyGifts: () => api.get('/daily-gifts'),
    getUserCharacters: () => api.get('/user-characters'),
    getVipInfo: () => api.get('/get-vip-info'),
    claimGift: (data) => api.post('/claim-gift', data)
};
export const giftCodeApi = {
    claimGiftCode: (data) => api.post('/claim-giftcode', data)
};

export const shopApi = {
    getItems: () => api.get('/shop/items'),
    getItemsByCategory: (categoryId) => api.get(`/shop/category/${categoryId}`),
    buyItem: (data) => api.post('/shop/buy', data),
    getAccumulateItems: () => api.get('/shop/accumulate-items'),
    buyAccumulateItem: (data) => api.post('/shop/buy-accumulate', data)
  };
export default api;