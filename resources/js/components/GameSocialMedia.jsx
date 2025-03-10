import React, { useState } from 'react';

const GameSocialMedia = () => {
  const [activeTooltip, setActiveTooltip] = useState(null);

  const socialLinks = [
    {
      id: 'facebook',
      name: 'FANPAGE CHÍNH THỨC',
      icon: 'https://cdnjs.cloudflare.com/ajax/libs/simple-icons/11.6.0/facebook.svg',
      bgColor: 'from-blue-600 to-blue-800',
      hoverEffect: 'hover:shadow-blue-500/50',
      memberCount: '50K+ Thành viên',
      description: 'Cập nhật tin tức game nhanh nhất',
      url: 'https://www.facebook.com/cltbzplay/'
    },
    {
      id: 'zalo',
      name: 'CỘNG ĐỒNG ZALO',
      // Do Zalo không có logo trên CDN phổ biến, sử dụng placeholder có phong cách tương tự
      icon: '/frontend/images/7044033_zalo_icon.png',
      bgColor: 'from-blue-400 to-blue-600',
      hoverEffect: 'hover:shadow-blue-400/50',
      memberCount: '20K+ Thành viên',
      description: 'Hỗ trợ người chơi 24/7',
      url: 'https://zalo.me/g/rbimrn842'
    },
    {
      id: 'discord',
      name: 'DISCORD SERVER',
      icon: 'https://cdnjs.cloudflare.com/ajax/libs/simple-icons/11.6.0/discord.svg',
      bgColor: 'from-indigo-600 to-indigo-800',
      hoverEffect: 'hover:shadow-indigo-500/50',
      memberCount: '15K+ Members',
      description: 'Voice chat & Tìm team',
      url: 'https://discord.gg/your-discord-invite'
    },
    {
      id: 'telegram',
      name: 'TELEGRAM GROUP',
      icon: 'https://cdnjs.cloudflare.com/ajax/libs/simple-icons/11.6.0/telegram.svg',
      bgColor: 'from-sky-400 to-sky-600',
      hoverEffect: 'hover:shadow-sky-400/50',
      memberCount: '10K+ Subscribers',
      description: 'Thông báo sự kiện & Giftcode',
      url: 'https://t.me/your-telegram-group'
    }
  ];

  return (
    <div className="fixed right-6 top-1/2 -translate-y-1/2 z-50 space-y-4">
      {socialLinks.map((social) => (
        <div
          key={social.id}
          className="relative group"
          onMouseEnter={() => setActiveTooltip(social.id)}
          onMouseLeave={() => setActiveTooltip(null)}
        >
          {/* Tooltip */}
          <div
            className={`absolute right-full mr-4 top-1/2 -translate-y-1/2 bg-gray-900/95 rounded-lg p-4 w-64
                       border border-gray-700/50 backdrop-blur-sm transition-all duration-300
                       ${activeTooltip === social.id ? 'opacity-100 visible -translate-x-0' : 'opacity-0 invisible translate-x-4'}`}
          >
            <div className="text-white">
              <h3 className="font-bold text-sm mb-1">{social.name}</h3>
              <p className="text-gray-400 text-xs mb-2">{social.description}</p>
              <div className="flex items-center text-xs text-yellow-400">
                <span className="flex items-center">
                  <span className="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                  Online:
                </span>
                <span className="ml-2">{social.memberCount}</span>
              </div>
            </div>
            <div className="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 transform rotate-45 w-3 h-3 bg-gray-900 border-t border-r border-gray-700/50"></div>
          </div>

          {/* Icon Button */}
          <a
            href={social.url}
            target="_blank"
            rel="noopener noreferrer"
            className="block"
          >
            <div className={`relative w-14 h-14 rounded-2xl overflow-hidden transition-all duration-300
                          bg-gradient-to-br ${social.bgColor}
                          hover:scale-110 ${social.hoverEffect} shadow-lg
                          before:absolute before:inset-0 before:bg-black/20 before:z-10
                          after:absolute after:inset-0 after:bg-gradient-to-t after:from-black/40 after:to-transparent`}
            >
              {/* Background Animation */}
              <div className="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div className="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,_rgba(255,255,255,0.2),_transparent_50%)]
                              animate-[ping_3s_cubic-bezier(0,0,0.2,1)_infinite]"></div>
              </div>

              {/* Icon */}
              <div className="relative z-20 w-full h-full flex items-center justify-center">
                <img
                  src={social.icon}
                  alt={social.name}
                  className="w-7 h-7 object-contain invert" // Thêm invert để làm trắng icon
                />
              </div>

              {/* Border Light Effect */}
              <div className="absolute inset-0 border-2 border-white/20 rounded-2xl opacity-0 group-hover:opacity-100
                            transition-opacity duration-300"></div>
            </div>
          </a>
        </div>
      ))}
    </div>
  );
};

export default GameSocialMedia;