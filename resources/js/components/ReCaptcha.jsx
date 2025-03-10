import React, { useEffect, useCallback } from 'react';
import toast from 'react-hot-toast';

const ReCaptcha = ({ onVerify }) => {
  const RECAPTCHA_SITE_KEY = import.meta.env.VITE_RECAPTCHA_SITE_KEY;

  const loadReCaptcha = useCallback(() => {
    try {
      // Xóa script cũ nếu có
      const existingScript = document.querySelector(
        `script[src^="https://www.google.com/recaptcha/api.js"]`
      );
      if (existingScript) {
        existingScript.remove();
      }

      // Tạo script mới
      const script = document.createElement('script');
      script.src = `https://www.google.com/recaptcha/api.js?render=${RECAPTCHA_SITE_KEY}`;
      script.async = true;
      script.defer = true;
      
      script.onload = () => {
        if (!window.grecaptcha) {
          console.error('reCAPTCHA not loaded');
          toast.error('Không thể tải reCAPTCHA. Vui lòng tải lại trang.');
          return;
        }

        window.grecaptcha.ready(() => {
          initializeReCaptcha();
        });
      };

      script.onerror = () => {
        console.error('Error loading reCAPTCHA');
        toast.error('Không thể tải reCAPTCHA. Vui lòng kiểm tra kết nối mạng.');
      };

      document.head.appendChild(script);
      
      return () => {
        document.head.removeChild(script);
      };
    } catch (error) {
      console.error('Error in loadReCaptcha:', error);
      toast.error('Có lỗi xảy ra khi tải reCAPTCHA');
    }
  }, [RECAPTCHA_SITE_KEY]);

  const initializeReCaptcha = useCallback(() => {
    if (!window.grecaptcha) {
      console.error('reCAPTCHA not initialized');
      return;
    }

    try {
      window.grecaptcha.execute(RECAPTCHA_SITE_KEY, { action: 'register' })
        .then(token => {
          if (onVerify) {
            onVerify(token);
          }
        })
        .catch(error => {
          console.error('reCAPTCHA execution error:', error);
          toast.error('Lỗi xác thực reCAPTCHA. Vui lòng thử lại.');
        });
    } catch (error) {
      console.error('Error in initializeReCaptcha:', error);
      toast.error('Có lỗi khi khởi tạo reCAPTCHA');
    }
  }, [RECAPTCHA_SITE_KEY, onVerify]);

  useEffect(() => {
    if (!RECAPTCHA_SITE_KEY) {
      console.error('reCAPTCHA site key is missing');
      toast.error('Thiếu cấu hình reCAPTCHA');
      return;
    }

    const cleanup = loadReCaptcha();

    // Refresh token mỗi 110 giây
    const refreshInterval = setInterval(() => {
      initializeReCaptcha();
    }, 110000);

    return () => {
      clearInterval(refreshInterval);
      if (cleanup) cleanup();
    };
  }, [loadReCaptcha, initializeReCaptcha, RECAPTCHA_SITE_KEY]);

  return null;
};

export default ReCaptcha;