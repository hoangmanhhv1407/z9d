import React from 'react';

const MainContent = () => {
  return (
    <section id="hvvh_mainsite_header_Cot2p" className="section section--hvvh_mainsite_header hvvh_mainsite_header scrollFrame">
      {/* Background */}
      <div className="section__background">
        <div id="blockHomeBackgroundSwiper" className="swiper background__list">
          <ul className="swiper-wrapper">
            <li className="swiper-slide event_slide">
              <div className="event_item">
                <img className="data desktop" src="/frontend/images/bg-1.jpg" alt="" />
                <img className="data mobile" src="/frontend/images/bg-mb-1.jpg" alt="" />
              </div>
            </li>
            <li className="swiper-slide event_slide">
              <div className="event_item">
                <img className="data desktop" src="/frontend/images/bg-2.jpg" alt="" />
                <img className="data mobile" src="/frontend/images/bg-mb-2.jpg" alt="" />
              </div>
            </li>
            <li className="swiper-slide event_slide">
              <div className="event_item">
                <img className="data desktop" src="/frontend/images/bg-3 PC.jpg" alt="" />
                <img className="data mobile" src="/frontend/images/bg-3-mb.jpg" alt="" />
              </div>
            </li>
          </ul>
          <div className="swiper-pagination"></div>
        </div>
      </div>

      {/* Content */}
      <div className="section__content">
        <div className="inner inner--hvvh_mainsite_header">
          <img src="/frontend/images/decor.png" className="decor desktop" alt="" />
          <div id="wrapper-moon" className="moon-wrapper desktop">
            <canvas id="moon"></canvas>
          </div>
          <div className="fake__shadow desktop"></div>
          <div className="fake__shadow2 desktop"></div>
          <a href="//thiennhai.vnggames.com/index.html" className="logo">
            <img src="/frontend/images/logo.png" alt="" />
          </a>
          <img src="/frontend/images/text-en.png" className="text__main" alt="" />

          {/* Desktop Buttons */}
          <div className="group__btn desktop-flex">
            <a href="https://shop.vnggames.com/vn/game/thiennhaivng" target="_blank" className="group__btn--topup">
              topup
            </a>
            <div className="column__btn">
              <a href="https://tnmnd.onelink.me/dCQg/nvihjr78" target="_blank" className="group__btn--apple">
                appstore
              </a>
              <a href="https://tnmnd.onelink.me/dCQg/nvihjr78" target="_blank" className="group__btn--google">
                googleplay
              </a>
              <a href="https://mlb-cdn.mto.zing.vn/apk/vn/qsgame_vn_0.9.321.321.apk" target="_blank" className="group__btn--apk">
                apk
              </a>
            </div>
            <a href="https://mlb-cdn.mto.zing.vn/pc/may_2024.exe" target="_blank" className="group__btn--pc">
              pc
            </a>
            <div className="list-column">
              <a href="https://thiennhai.vnggames.com/tin-tuc/huong-dan/huong-dan-nap-webshop.html" target="_blank" className="group__btn--guide">
                guide
              </a>
              <a href="https://thiennhai.vnggames.com/tin-tuc/huong-dan/dieu-khoan-bao-mat.html" target="_blank" className="group__btn--policy">
                policy
              </a>
            </div>
          </div>

          {/* Mobile Buttons */}
          <div className="list-btn-mb">
            <a href="https://shop.vnggames.com/vn/game/thiennhaivng" target="_blank" className="group__btn--topup-mb"></a>
            <a href="https://thiennhai.vnggames.com/tin-tuc/huong-dan/huong-dan-nap-webshop.html" target="_blank" className="group__btn--guide-mb"></a>
          </div>
          <a href="//thiennhai.vnggames.com/index.html" className="logo">
            <img src="/frontend/images/logo.png" className="mobile" alt="" />
          </a>
        </div>
      </div>
    </section>
  );
};

export default MainContent;