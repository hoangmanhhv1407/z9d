import { useState, useEffect } from 'react';

export const useScript = (src) => {
  const [loaded, setLoaded] = useState(false);
  const [error, setError] = useState(false);

  useEffect(() => {
    // Kiểm tra xem script đã được load chưa
    if (document.querySelector(`script[src="${src}"]`)) {
      setLoaded(true);
      return;
    }

    const script = document.createElement('script');
    script.src = src;
    script.async = true;

    // Xử lý sự kiện load thành công
    script.onload = () => {
      setLoaded(true);
    };

    // Xử lý sự kiện lỗi
    script.onerror = () => {
      setError(true);
    };

    document.body.appendChild(script);

    return () => {
      document.body.removeChild(script);
    };
  }, [src]);

  return [loaded, error];
};