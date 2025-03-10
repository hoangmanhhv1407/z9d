import React from 'react';

const LoadingScreen = () => (
  <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div className="relative z-10 flex flex-col items-center">
      <div className="w-16 h-16 relative">
        <div className="absolute inset-0 border-4 border-yellow-500/30 rounded-full"></div>
        <div className="absolute inset-0 border-4 border-yellow-500 border-t-transparent rounded-full animate-spin"></div>
      </div>
      <div className="mt-4 text-yellow-500 font-medium">
        <div className="flex items-center space-x-1">
          <span className="animate-bounce">Đang</span>
          <span className="animate-bounce" style={{ animationDelay: '0.1s' }}>tải</span>
          <span className="animate-bounce" style={{ animationDelay: '0.2s' }}>.</span>
          <span className="animate-bounce" style={{ animationDelay: '0.3s' }}>.</span>
          <span className="animate-bounce" style={{ animationDelay: '0.4s' }}>.</span>
        </div>
      </div>
    </div>
  </div>
);

export default LoadingScreen;