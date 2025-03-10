import { useState, useEffect } from 'react';

/**
 * Hook kiểm tra xem thiết bị hiện tại có phải là di động hay không dựa vào breakpoint.
 * @param {number} breakpoint - Kích thước màn hình tối đa (mặc định là 768px).
 * @returns {boolean} - Trả về true nếu thiết bị là di động (kích thước màn hình nhỏ hơn breakpoint).
 */
const useIsMobile = (breakpoint = 1024) => {
  // Thiết lập trạng thái ban đầu cho `isMobile`
  const [isMobile, setIsMobile] = useState(window.matchMedia(`(max-width: ${breakpoint}px)`).matches);

  useEffect(() => {
    // Tạo media query dựa trên breakpoint
    const mediaQueryList = window.matchMedia(`(max-width: ${breakpoint}px)`);

    // Hàm xử lý khi media query thay đổi
    const handleChange = (e) => {
      setIsMobile(e.matches);
    };

    // Lắng nghe thay đổi của media query
    mediaQueryList.addEventListener('change', handleChange);

    // Cleanup listener khi component bị unmount
    return () => mediaQueryList.removeEventListener('change', handleChange);
  }, [breakpoint]);

  return isMobile;
};

export default useIsMobile;
