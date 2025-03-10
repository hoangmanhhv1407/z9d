import React from 'react';
import './EventSchedule.css'; // Import CSS riêng cho các hiệu ứng

const EventSchedule = () => {
  const events = [
    { name: "Vô Cực Chiến Trường", status: "Đang diễn ra", time: "Diễn ra hàng ngày" },
    { name: "Đụng Độ Styria", status: "Đang diễn ra", time: "7:00, thứ 7, chủ nhật hàng tuần" },
    { name: "Battle Arena", status: "Đang diễn ra", time: "16:00 hàng ngày" },
    { name: "Cướp Cờ", status: "Đang diễn ra", time: "Diễn ra hàng ngày" },
    { name: "Thành Chiến", status: "Sắp diễn ra", time: "19:00 thứ 5 hàng tuần" },
    { name: "Đoạt Bảo", status: "Sắp diễn ra", time: "8:00 thứ 3 hàng tuần" },
    { name: "PK Liên Server", status: "Đang diễn ra", time: "Diễn ra hàng ngày" },
    { name: "Đua Tốp", status: "Sắp diễn ra", time: "20:00 thứ 6 hàng tuần" },
    { name: "Giải Đấu Võ Lâm", status: "Đang diễn ra", time: "10:00 thứ 4 hàng tuần" },
    { name: "Thách Đấu Server", status: "Sắp diễn ra", time: "14:00 chủ nhật hàng tuần" },
  ];

  return (
    <div className="event-schedule-container h-[600px] p-6">
      <h2 className="event-title text-2xl font-bold mb-6 text-center">Lịch hoạt động sự kiện</h2>
      <div className="event-list overflow-y-auto max-h-[500px] pr-2"> {/* Giới hạn chiều cao với thanh cuộn */}
        {events.map((event, index) => (
          <div key={index} className="event-item flex items-center py-4"> {/* Không có background riêng */}
            <div className="w-1/4 text-orange-400">
              <h3 className="text-lg font-semibold">{event.name}</h3>
              <p className="text-sm">{event.status}</p>
            </div>
            <div className="separator">
              <img src="/frontend/images/upn1.png" alt="separator" className="h-full" />
            </div>
            <div className="w-1/2 text-white">
              <p className="text-sm font-semibold">{event.time}</p>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default EventSchedule;
