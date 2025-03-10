import React, { useState } from 'react';
import { Gift, RefreshCw } from 'lucide-react';
import axios from 'axios';
import { toast } from 'react-hot-toast';
import { useAuth } from '../../contexts/AuthContext';

const GiftCode = () => {
  const [code, setCode] = useState('');
  const [loading, setLoading] = useState(false);
  const { user } = useAuth();

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // Kiểm tra token
    const token = localStorage.getItem('token');
    if (!token) {
      toast.error('Vui lòng đăng nhập để sử dụng gift code');
      return;
    }

    if (!code.trim()) {
      toast.error('Vui lòng nhập mã gift code');
      return;
    }

    setLoading(true);

    try {
      // Gửi request với token
      const response = await axios.post('/api/claim-giftcode', {
        code: code.trim()
      }, {
        headers: {
          'Authorization': token
        }
      });


      if (response.data.alert === 'success') {
        toast.success('Nhập Gift Code thành công');
        setCode('');
      } else {
        // Xử lý các trường hợp lỗi cụ thể từ backend
        let errorMessage = 'Có lỗi xảy ra khi nhập gift code';
        
        if (response.data.message) {
          switch (response.data.message) {
            case 'Gift Code không tồn tại':
              errorMessage = 'Gift Code không tồn tại';
              break;
            case 'Gift Code này đã được sử dụng':
              errorMessage = 'Gift Code này đã được sử dụng';
              break;
            case 'Tài khoản đã sử dụng hết Gift Code này':
              errorMessage = 'Tài khoản đã sử dụng hết Gift Code này';
              break;
            case 'Gift Code đã hết hạn':
              errorMessage = 'Gift Code đã hết hạn';
              break;
            default:
              errorMessage = response.data.message;
          }
        }
        
        toast.error(errorMessage);
      }
    } catch (error) {
      console.error('Error claiming gift code:', error);
      const errorMessage = error.response?.data?.message || 'Có lỗi xảy ra khi nhận gift code';
      toast.error(errorMessage);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="bg-white rounded-xl shadow-lg p-6">
      <h4 className="mb-6 flex items-center text-3xl font-extrabold text-gray-900">
        <Gift className="w-8 h-8 mr-4 text-blue-600" />
        Nhập Gift Code
      </h4>

      <form onSubmit={handleSubmit} className="space-y-6">
        <div className="space-y-2">
          <label className="block text-lg font-semibold text-gray-700">
            Gift Code
          </label>
          <div className="relative">
            <input
              type="text"
              value={code}
              onChange={(e) => setCode(e.target.value.toUpperCase())}
              placeholder="Nhập gift code của bạn"
              className="w-full bg-gray-50 text-gray-800 rounded-lg px-4 py-3 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 uppercase"
              disabled={loading}
            />
            <Gift className="absolute right-4 top-3.5 text-gray-400" size={20} />
          </div>
        </div>

        <button
          type="submit"
          disabled={loading || !user}
          className={`w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 rounded-lg transition duration-200 flex items-center justify-center shadow-lg hover:shadow-xl ${
            (!user || loading) ? 'opacity-50 cursor-not-allowed' : ''
          }`}
        >
          {loading ? (
            <>
              <RefreshCw className="w-5 h-5 animate-spin mr-2" />
              Đang xử lý...
            </>
          ) : (
            <>
              <Gift className="w-5 h-5 mr-2" />
              {user ? 'Nhận Gift Code' : 'Vui lòng đăng nhập'}
            </>
          )}
        </button>
      </form>

      <div className="mt-6 space-y-4">
        <div className="p-4 bg-blue-50 rounded-lg border border-blue-200">
          <h5 className="font-semibold text-blue-800 mb-2">Hướng dẫn:</h5>
          <ul className="list-disc pl-5 space-y-1 text-sm text-blue-700">
            <li>Nhập chính xác gift code bạn nhận được</li>
            <li>Gift code có thể chỉ sử dụng được một lần duy nhất</li>
            <li>Một số gift code có thể giới hạn số lần sử dụng cho mỗi tài khoản</li>
            <li>Gift code có thể có thời hạn sử dụng</li>
          </ul>
        </div>

        <div className="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
          <h5 className="font-semibold text-yellow-800 mb-2">Lưu ý:</h5>
          <ul className="list-disc pl-5 space-y-1 text-sm text-yellow-700">
            <li>Kiểm tra kỹ gift code trước khi nhập</li>
            <li>Không chia sẻ gift code cho người khác</li>
            <li>Gift code chỉ có hiệu lực trong thời gian quy định</li>
            <li>Mọi vấn đề về gift code vui lòng liên hệ Admin</li>
          </ul>
        </div>
      </div>
    </div>
  );
};

export default GiftCode;