import React, { useState } from 'react';
import { Link } from 'react-router-dom';

const DangNhap = ({ userInfo, onLogin, onLogout }) => {
  const [showLoginModal, setShowLoginModal] = useState(false);

  const handleLoginClick = () => {
    setShowLoginModal(true);
  };

  const handleCloseModal = () => {
    setShowLoginModal(false);
  };

  return (
    <header className="bg-gray-800 text-white">
      <div className="container mx-auto px-4">
        <nav className="flex items-center justify-between py-4">
          <ul className="flex space-x-4">
            <li>
              <Link to="/" className="hover:text-gray-300">
                <img src="/frontend/images/header-home.png" alt="trang chủ" className="w-6 h-6" />
              </Link>
            </li>
            <li>
              <Link to="/tin-tuc" className="hover:text-gray-300">Tin tức</Link>
            </li>
            {/* Thêm các mục menu khác */}
          </ul>
          <Link to="/">
            <img src="/frontend/images/header-logo.png" alt="logo" className="w-28 h-16" />
          </Link>
          {userInfo ? (
            <div className="flex items-center">
              <span>{userInfo.username}</span>
              <button onClick={onLogout} className="ml-4 text-red-500 hover:text-red-400">Đăng xuất</button>
            </div>
          ) : (
            <button onClick={handleLoginClick} className="hover:text-gray-300">Đăng nhập/Đăng ký</button>
          )}
        </nav>
      </div>

      {showLoginModal && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
          <div className="bg-white p-6 rounded-lg">
            <h2 className="text-2xl font-bold mb-4 text-gray-800">Đăng nhập</h2>
            {/* Thêm form đăng nhập ở đây */}
            <button onClick={handleCloseModal} className="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
              Đóng
            </button>
          </div>
        </div>
      )}
    </header>
  );
};

export default DangNhap;