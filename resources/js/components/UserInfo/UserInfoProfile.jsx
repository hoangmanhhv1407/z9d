import React from 'react';
import { User, Mail, Shield, CreditCard } from 'lucide-react';
import '../../../css/UserInfoProfile.css';

const UserInfoProfile = ({ user }) => {
  const profileItems = [
    {
      label: 'Tài khoản',
      icon: User,
      value: user?.userid,
      iconColor: 'blue',
      valueColor: 'gray'
    },
    {
      label: 'Email',
      icon: Mail,
      value: user?.email || 'Chưa cập nhật',
      iconColor: 'blue',
      valueColor: 'gray'
    },
    {
      label: 'Trạng thái tài khoản',
      icon: Shield,
      value: 'Đang hoạt động',
      iconColor: 'green',
      valueColor: 'green'
    },
    {
      label: 'Xu hiện có',
      icon: CreditCard,
      value: `${Number(user?.coin || 0).toLocaleString()} Xu`,
      iconColor: 'yellow',
      valueColor: 'yellow'
    }
  ];

  return (
    <div className="uip-container">
      <h4 className="uip-header">
        <User className="uip-header__icon" />
        Thông Tin Cá Nhân
      </h4>

      <div className="uip-grid">
        {profileItems.map((item, index) => (
          <div key={index} className="uip-item">
            <label className="uip-item__label">
              {item.label}
            </label>
            <div className="uip-item__content">
              <item.icon className={`uip-item__icon uip-item__icon--${item.iconColor}`} />
              <span className={`uip-item__value uip-item__value--${item.valueColor}`}>
                {item.value}
              </span>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default UserInfoProfile;