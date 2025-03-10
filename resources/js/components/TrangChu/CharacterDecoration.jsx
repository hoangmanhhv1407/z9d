import React, { useState } from 'react';

const CharacterDecoration = ({ position = "main" }) => {
  const [isLoaded, setIsLoaded] = useState(false);

  const floatAnimation = {
    animation: 'float 6s ease-in-out infinite',
  };

  const floatKeyframes = `
    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-20px); }
    }
  `;

  const decorationConfig = {
    main: {
      className: "absolute top-96 -right-80 2xl:-right-80 pointer-events-none z-30",
      imageSrc: "/frontend/images/cai bang.png",
      imageAlt: "Nhân vật Cái Bang"
    },
    rankingLeft: {
      className: "absolute bottom-0 -left-80 2xl:-left-80 pointer-events-none z-30", // Điều chỉnh vị trí để ảnh vào trong một chút
      imageSrc: "/frontend/images/ancucaothu.png",
      imageAlt: "Ẩn Cư Cao Thủ"
    }
  };

  const config = decorationConfig[position];

  const handleImageLoad = () => {
    setIsLoaded(true);
  };

  return (
    <>
      <style>{floatKeyframes}</style>
      <div className={config.className}>
        <div className="relative" style={floatAnimation}>
          <div 
            className="absolute inset-0 bg-gradient-to-r from-yellow-500/10 to-transparent filter blur-lg"
          />
          <div className="relative">
            {!isLoaded && (
              <div className="absolute inset-0 flex items-center justify-center">
                <div className="w-16 h-16 border-4 border-yellow-500 border-t-transparent rounded-full animate-spin"></div>
              </div>
            )}
            <img 
              src={config.imageSrc}
              alt={config.imageAlt}
              loading="lazy"
              onLoad={handleImageLoad}
              className={`h-[720px] w-auto object-contain transition-opacity duration-500 ${
                isLoaded ? 'opacity-100' : 'opacity-0'
              }`}
              style={{
                filter: 'drop-shadow(2px 4px 6px rgba(0, 0, 0, 0.3))'
              }}
            />
          </div>
        </div>
      </div>
    </>
  );
};

export default CharacterDecoration;