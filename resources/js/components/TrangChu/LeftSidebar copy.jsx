import React, { useContext, useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { Download, Gift, CreditCard, HelpCircle, User, Shield, Sword, Crown, LogOut, Signal, Castle, Timer, Map, Gauge, ArrowUpRight, Swords, AlertCircle } from 'lucide-react';
import { AuthContext } from '../../contexts/AuthContext';
import axios from 'axios';
import RegistrationStatusPopup from '../RegistrationStatusPopup';
import toast from 'react-hot-toast';

const LeftSidebar = () => {
  const { isAuthenticated, user, logout, loading, fetchUser } = useContext(AuthContext);
  const [isActivated, setIsActivated] = useState(true); // Mặc định là true để tránh nhấp nháy
  const [showActivationPopup, setShowActivationPopup] = useState(false);

  useEffect(() => {
    const checkActivationStatus = async () => {
      if (isAuthenticated && user?.email) {
        try {
          const response = await axios.get(`/api/check-activation-status/${user.email}`);
          setIsActivated(response.data.isActivated);
        } catch (error) {
          console.error('Lỗi kiểm tra trạng thái kích hoạt:', error);
        }
      }
    };

    checkActivationStatus();
  }, [isAuthenticated, user]);

  const handleActivationClick = () => {
    if (!isActivated && user?.email) {
      setShowActivationPopup(true);
    }
  };

  const navLinks = [
    { to: '/thong-tin-tai-khoan', text: 'Nạp Xu', icon: CreditCard, primary: true },
    { to: '/huong-dan', text: 'Hướng Dẫn', icon: CreditCard },
    { to: '/su-kien', text: 'Sự Kiện', icon: Gift },
    { to: '/ho-tro', text: 'Hỗ Trợ', icon: HelpCircle }
  ];

  // Thông tin server mới
  const serverStatus = "online"; // hoặc "offline" tùy trạng thái

  return (
    <div className="space-y-6">
    {/* Nút Tải Game/Kích hoạt */}
    <div className="bg-gradient-to-br from-gray-900/90 to-gray-800/90 rounded-2xl p-6 shadow-xl border border-gray-700/50 backdrop-blur-xl">
      {isAuthenticated && !isActivated ? (
        // Nút kích hoạt tài khoản khi chưa kích hoạt
        <button
          onClick={handleActivationClick}
          className="w-full bg-gradient-to-r from-red-500 to-red-600 text-white font-bold py-3 px-6 rounded-xl
            hover:from-red-600 hover:to-red-700 transition-all duration-300 transform hover:scale-105 shadow-lg
            hover:shadow-red-500/20 flex items-center justify-center space-x-2"
        >
          <AlertCircle className="w-5 h-5" />
          <span>KÍCH HOẠT TÀI KHOẢN</span>
        </button>
      ) : (
        // Nút tải game bình thường
        <Link to="/tai-game">
          <button
            className="w-full bg-gradient-to-r from-yellow-500 to-amber-500 text-gray-900 font-bold py-3 px-6 rounded-xl
              hover:from-yellow-400 hover:to-amber-400 transition-all duration-300 transform hover:scale-105 shadow-lg
              hover:shadow-yellow-500/20"
          >
            TẢI GAME
          </button>
        </Link>
      )}
    </div>

      {/* Phần hiển thị thành viên cho người dùng đã đăng nhập */}
      {!loading && isAuthenticated && user && (
        <div className="bg-gradient-to-br from-gray-900/90 to-gray-800/90 rounded-2xl p-6 shadow-xl border border-gray-700/50 backdrop-blur-xl ">
          <div className="space-y-4">
            <div className="flex items-center justify-between p-3 bg-gray-800/50 rounded-xl">
              <div className="flex items-center space-x-3">
                <div className="w-12 h-12 rounded-full ring-2 ring-yellow-500 bg-gray-700 flex items-center justify-center">
                  <User className="w-6 h-6 text-yellow-500" />
                </div>
                <a href="/thong-tin-tai-khoan">
                  <div>
                    <div className="text-white font-bold">{user.userid}</div>
                    <div className="text-yellow-500 text-sm">Thành viên</div>
                  </div>
                </a>
              </div>
              <button onClick={logout} className="text-gray-400 hover:text-white transition-colors">
                <LogOut className="w-6 h-6" />
              </button>
            </div>
            <div className="grid grid-cols-1 gap-3">
              <div className="bg-gray-800/30 p-3 rounded-xl">
                <div className="flex justify-between items-center">
                  <span className="text-gray-400">Xu:</span>
                  <div className="flex items-center space-x-2">
                    <span className="text-yellow-400 font-bold">
                      {Number(user.coin || 0).toLocaleString()}
                    </span>
                    <img 
                      src="/frontend/images/gold.png" 
                      alt="xu" 
                      className="w-5 h-5 object-contain"
                    />
                  </div>
                </div>
              </div>
              <Link to="/ky-tran-cac" className="bg-gray-800/30 p-3 rounded-xl hover:bg-gray-700/30 transition-colors">
                <div className="flex justify-between items-center">
                  <span className="text-gray-400">Kỳ Trân Các</span>
                  <svg 
                    className="w-5 h-5 text-yellow-500" 
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                  >
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                  </svg>
                </div>
              </Link>
              <Link to="/thong-tin-tai-khoan" className="bg-gray-800/30 p-3 rounded-xl hover:bg-gray-700/30 transition-colors">
                <div className="flex justify-between items-center">
                  <span className="text-gray-400">Nạp Xu Tự Động</span>
                  <div className="flex items-center space-x-2">
                    <svg 
                      className="w-5 h-5 text-green-500" 
                      fill="none" 
                      stroke="currentColor" 
                      viewBox="0 0 24 24"
                    >
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                  </div>
                </div>
              </Link>
            </div>
          </div>
        </div>
      )}

  {/* Server Stats - Layout mới */}
<div className="bg-gradient-to-br from-gray-900/90 to-gray-800/90 rounded-2xl p-6 shadow-xl border border-gray-700/50 backdrop-blur-xl">
  {/* Header */}
  <div className="flex items-center space-x-2 mb-4">
    <Crown className="w-6 h-6 text-yellow-500" />
    <h3 className="text-lg font-bold text-white">Thông Tin Server</h3>
  </div>

  {/* Server Status Banner */}
  <div className={`mb-4 rounded-lg p-3 flex items-center justify-between
    ${serverStatus === 'online' 
      ? 'bg-green-900/30 border border-green-500/30' 
      : 'bg-red-900/30 border border-red-500/30'}`}>
    <div className="flex items-center space-x-2">
      <Signal className={`w-5 h-5 ${serverStatus === 'online' ? 'text-green-400' : 'text-red-400'}`} />
      <span className="font-medium text-sm">Trạng Thái:</span>
    </div>
    <span className={`font-bold ${serverStatus === 'online' ? 'text-green-400' : 'text-red-400'}`}>
      {serverStatus === 'online' ? 'ĐANG HOẠT ĐỘNG' : 'BẢO TRÌ'}
    </span>
  </div>

  {/* Server Events */}
  <div className="mb-4 rounded-lg bg-gradient-to-r from-indigo-900/30 to-blue-900/30 border border-blue-500/30">
    {/* Title */}
    <div className="border-b border-blue-500/30 p-3">
      <span className="text-blue-400 font-medium text-sm">Lịch Sự Kiện Hàng Ngày</span>
    </div>
    {/* Event Times */}
    <div className="p-3 space-y-3">
      <div className="flex justify-between items-center">
        <div className="flex items-center space-x-2">
          <Swords className="w-4 h-4 text-blue-400" />
          <span className="text-sm text-gray-300">Hắc Bạch Đại Chiến:</span>
        </div>
        <span className="text-yellow-400 font-medium text-sm">20:00 - 21:00</span>
      </div>
      <div className="flex justify-between items-center">
        <div className="flex items-center space-x-2">
          <Castle className="w-4 h-4 text-blue-400" />
          <span className="text-sm text-gray-300">Trang Viên Chiến:</span>
        </div>
        <span className="text-yellow-400 font-medium text-sm">21:00 - 22:00</span>
      </div>
    </div>
  </div>

  {/* Server Info Grid */}
  <div className="grid grid-cols-1 gap-3">
    {/* Rate Info */}
    <div className="bg-gray-800/30 rounded-lg">
      <div className="flex items-center justify-between border-b border-gray-700/50 p-3">
        <div className="flex items-center space-x-2">
          <Gauge className="w-4 h-4 text-yellow-500" />
          <span className="text-sm text-gray-300">Tỉ Lệ Kinh Nghiệm:</span>
        </div>
        <span className="text-white font-bold text-sm">x5</span>
      </div>
      <div className="flex items-center justify-between p-3">
        <div className="flex items-center space-x-2">
          <ArrowUpRight className="w-4 h-4 text-yellow-500" />
          <span className="text-sm text-gray-300">Giai Đoạn:</span>
        </div>
        <span className="text-white font-bold text-sm">Giai đoạn 1</span>
      </div>
    </div>

    {/* Level and Map Limits */}
    <div className="bg-gray-800/30 rounded-lg">
      <div className="flex items-center justify-between border-b border-gray-700/50 p-3">
        <div className="flex items-center space-x-2">
          <Timer className="w-4 h-4 text-yellow-500" />
          <span className="text-sm text-gray-300">Level Tối Đa:</span>
        </div>
        <span className="text-white font-bold text-sm">300</span>
      </div>
      <div className="flex items-center justify-between p-3">
        <div className="flex items-center space-x-2">
          <Map className="w-4 h-4 text-yellow-500" />
          <span className="text-sm text-gray-300">Map Giới Hạn:</span>
        </div>
        <span className="text-white font-bold text-sm">Tô Châu</span>
      </div>
    </div>
  </div>
</div>

      {/* Navigation Links - chỉ hiển thị khi CHƯA đăng nhập */}
      {!isAuthenticated && (
        <div className="bg-gradient-to-br from-gray-900/90 to-gray-800/90 rounded-2xl p-6 shadow-xl border border-gray-700/50 backdrop-blur-xl">
          <div className="space-y-3">
            {navLinks.map((link, index) => (
              <Link key={index} to={link.to}>
                <button className={`w-full group ${
                  link.primary 
                    ? 'bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-400 hover:to-amber-400 text-gray-900'
                    : 'bg-gray-800/30 hover:bg-gray-700/30 text-white'
                  } rounded-xl p-4 flex items-center justify-between transform transition-all duration-300 hover:scale-105`}>
                  <span className="flex items-center">
                    <link.icon className={`w-5 h-5 mr-3 ${link.primary ? 'text-gray-900' : 'text-yellow-500'}`} />
                    <span className="font-semibold">{link.text}</span>
                  </span>
                  <span className="opacity-0 group-hover:opacity-100 transition-opacity">
                    →
                  </span>
                </button>
              </Link>
            ))}
          </div>
        </div>
      )}
            {/* Thêm popup kích hoạt */}
            {showActivationPopup && (
        <RegistrationStatusPopup
          isOpen={showActivationPopup}
          email={user?.email}
          onClose={() => setShowActivationPopup(false)}
        />
      )}
    </div>
  );
};

export default LeftSidebar;