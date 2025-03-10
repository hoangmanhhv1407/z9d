import React, { useState, useEffect } from 'react';
import { useAuth } from '../../contexts/AuthContext';
import { dailyGiftApi } from '../../api';
import { toast } from 'react-hot-toast';
import { 
  Gift, Crown, Calendar, Timer, BadgeCheck, 
  Info, AlertCircle, ChevronRight, Package, 
  Users, Star, CheckCircle2
} from 'lucide-react';

const DailyGift = () => {
    const { user, isAuthenticated, loading } = useAuth();
    const [dailyGifts, setDailyGifts] = useState({ normal: [], vip: [] });
    const [selectedCharacter, setSelectedCharacter] = useState('');
    const [characters, setCharacters] = useState([]);
    const [pageLoading, setPageLoading] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const [vipInfo, setVipInfo] = useState({ currentVip: 1 });
    const [currentStreak, setCurrentStreak] = useState(3);
    const [activeTab, setActiveTab] = useState('normal');

    useEffect(() => {
        if (!loading && isAuthenticated && user) {
            fetchData();
        }
    }, [loading, isAuthenticated, user]);

    const fetchData = async () => {
        try {
            setPageLoading(true);
            const [giftsRes, charsRes, vipRes] = await Promise.all([
                dailyGiftApi.getDailyGifts(),
                dailyGiftApi.getUserCharacters(),
                dailyGiftApi.getVipInfo()
            ]);

            setDailyGifts(giftsRes.data);
            setCharacters(charsRes.data);
            setVipInfo(vipRes.data);
        } catch (error) {
            toast.error(error.response?.data?.error || 'Có lỗi xảy ra khi tải dữ liệu');
        } finally {
            setPageLoading(false);
        }
    };

    const handleClaimGift = async (giftCode) => {
        if (!selectedCharacter) {
            toast.error('Vui lòng chọn nhân vật!');
            return;
        }

        try {
            setIsLoading(true);
            const response = await dailyGiftApi.claimGift({
                gift_code: giftCode,
                character_id: selectedCharacter
            });

            if (response.data.success) {
                toast.success('Nhận quà thành công!');
                fetchData(); // Refresh data after claiming
            }
        } catch (error) {
            toast.error(error.response?.data?.error || 'Có lỗi xảy ra khi nhận quà');
        } finally {
            setIsLoading(false);
        }
    };
  // Lấy danh sách quà dựa theo tab đang chọn
  const giftsToShow = activeTab === 'normal' ? dailyGifts.normal : dailyGifts.vip;

    if (loading || pageLoading) {
      return (
          <div className="flex items-center justify-center min-h-screen">
              <div className="animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent"></div>
          </div>
      );
  }
  const GiftItem = ({ day, isToday, isPast, claimed, giftInfo, isVip, onClaimGift, isLoading, selectedCharacter }) => (
    <div className="relative pl-20 pb-8">
        <div className={`
            absolute left-6 w-4 h-4 rounded-full border-4 z-10
            ${claimed 
                ? 'border-green-500 bg-white' 
                : isToday
                    ? 'border-blue-500 bg-white animate-pulse'
                    : 'border-gray-300 bg-gray-100'
            }
        `} />
        
        <div className={`
            bg-white rounded-xl p-6 shadow-sm
            ${isToday ? 'ring-2 ring-blue-500' : ''}
            hover:shadow-md transition-shadow
        `}>
            <div className="flex items-center justify-between mb-4">
                <div className="flex items-center gap-4">
                    {/* Thêm phần ảnh sản phẩm */}
                    <div className="flex-shrink-0">
                        <div className={`
                            w-16 h-16 rounded-xl flex items-center justify-center relative
                            ${isToday ? 'bg-blue-100' : 'bg-gray-100'}
                            overflow-hidden
                        `}>
                            {/* Nếu có ảnh sản phẩm */}
                            {giftInfo?.image_url ? (
                                <img 
                                    src={giftInfo.image_url} 
                                    alt={`Quà ngày ${day}`}
                                    className="w-full h-full object-cover"
                                />
                            ) : (
                                // Nếu không có ảnh, hiển thị placeholder
                                <img 
                                    src="/api/placeholder/64/64" 
                                    alt="Gift placeholder"
                                    className="w-full h-full object-cover opacity-50"
                                />
                            )}
                            <div className="absolute bottom-0 right-0 bg-white/90 px-2 py-0.5 text-sm font-medium rounded-tl-lg">
                                {day}
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 className="font-medium">
                            {isVip ? `Quà VIP Ngày ${day}` : `Quà Ngày ${day}`}
                        </h3>
                        <div className="text-sm text-gray-500 mt-1">
                            {giftInfo?.name || (isVip ? 'Premium Gift Box' : 'Standard Gift Box')}
                        </div>
                        {giftInfo?.description && (
                            <div className="text-xs text-gray-400 mt-0.5">
                                {giftInfo.description}
                            </div>
                        )}
                    </div>
                </div>

                <div>
                    {claimed ? (
                        <span className="inline-flex items-center px-3 py-1 rounded-full text-sm text-green-700 bg-green-100">
                            <CheckCircle2 className="w-4 h-4 mr-1" />
                            Đã nhận
                        </span>
                    ) : isToday ? (
                        <button
                            onClick={() => onClaimGift(isVip ? `VIPDAY${day}` : `DAY${day}`)}
                            disabled={isLoading || !selectedCharacter}
                            className={`
                                px-4 py-2 rounded-lg font-medium text-white
                                ${isVip 
                                    ? 'bg-amber-500 hover:bg-amber-600' 
                                    : 'bg-blue-600 hover:bg-blue-700'
                                }
                                disabled:opacity-50 disabled:cursor-not-allowed
                                transition-colors
                            `}
                        >
                            Nhận Quà
                        </button>
                    ) : (
                        <span className="inline-flex items-center px-3 py-1 rounded-full text-sm text-gray-600 bg-gray-100">
                            <Timer className="w-4 h-4 mr-1" />
                            {isPast ? 'Đã qua' : 'Sắp mở'}
                        </span>
                    )}
                </div>
            </div>

            {/* Gift Info */}
            <div className="mt-4 pt-4 border-t border-gray-100">
                <div className="flex items-center gap-4 text-sm text-gray-600">
                    <div className="flex items-center gap-2">
                        <Package className="w-4 h-4 text-amber-500" />
                        {giftInfo?.quantity || 1}x {giftInfo?.type || 'Hộp Quà'}
                    </div>
                    {isVip && (
                        <div className="flex items-center gap-2">
                            <Crown className="w-4 h-4 text-amber-500" />
                            VIP Exclusive
                        </div>
                    )}
                    {giftInfo?.rarity && (
                        <div className="flex items-center gap-2">
                            <Star className="w-4 h-4 text-amber-500" />
                            {giftInfo.rarity}
                        </div>
                    )}
                </div>
            </div>
        </div>
    </div>
);

  return (
    <div className="space-y-8">            
        {/* Header */}
        <div className="bg-blue-600 rounded-xl">
            <div className="px-6 py-6">
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h1 className="text-2xl font-bold text-white">Điểm Danh Nhận Quà</h1>
                        <div className="flex items-center gap-4 mt-2">
                            <div className="flex items-center gap-1 text-white bg-white/20 px-3 py-1 rounded-full">
                                <Crown className="w-4 h-4" />
                                <span>VIP {vipInfo.currentVip}</span>
                            </div>
                            <div className="flex items-center gap-1 text-white bg-white/20 px-3 py-1 rounded-full">
                                <Calendar className="w-4 h-4" />
                                <span>Ngày {currentStreak}/7</span>
                            </div>
                        </div>
                    </div>
                    
                    <select
                        className="px-4 py-2 rounded-lg bg-white text-gray-700 border-0 focus:ring-2 focus:ring-white/50"
                        value={selectedCharacter}
                        onChange={(e) => setSelectedCharacter(e.target.value)}
                    >
                        <option value="">Chọn nhân vật (Cấp 108+)</option>
                        {characters.map((char) => (
                            <option key={char.unique_id} value={char.unique_id}>
                                {char.chr_name}
                            </option>
                        ))}
                    </select>
                </div>
            </div>
        </div>

        {/* Stats Cards */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {/* Streak Progress */}
            <div className="bg-emerald-50 rounded-xl p-6 border-2 border-emerald-200">
                <h3 className="font-semibold flex items-center gap-2 text-emerald-800">
                    <Timer className="w-5 h-5" />
                    Chuỗi Điểm Danh
                </h3>
                <div className="mt-4 space-y-4">
                    <div className="w-full bg-emerald-100 rounded-full h-2.5">
                        <div 
                            className="bg-emerald-500 h-2.5 rounded-full transition-all duration-500"
                            style={{ width: `${(currentStreak / 7) * 100}%` }}
                        />
                    </div>
                    <div className="flex justify-between text-sm">
                        <span className="text-emerald-800 font-medium">{currentStreak}/7 ngày</span>
                        <span className="text-emerald-600">
                            Còn {7 - currentStreak} ngày
                        </span>
                    </div>
                </div>
            </div>

            {/* VIP Progress */}
            <div className="bg-amber-50 rounded-xl p-6 border-2 border-amber-200">
                <h3 className="font-semibold flex items-center gap-2 text-amber-800">
                    <Crown className="w-5 h-5" />
                    Tiến Trình VIP
                </h3>
                <div className="mt-4 space-y-4">
                    <div className="w-full bg-amber-100 rounded-full h-2.5">
                        <div 
                            className="bg-amber-500 h-2.5 rounded-full transition-all duration-500"
                            style={{ width: `${(vipInfo.currentVip / 7) * 100}%` }}
                        />
                    </div>
                    <div className="flex justify-between text-sm">
                        <span className="text-amber-800 font-medium">VIP {vipInfo.currentVip}</span>
                        <span className="text-amber-600">
                            {vipInfo.xuNeeded} xu → VIP {vipInfo.currentVip + 1}
                        </span>
                    </div>
                </div>
            </div>

            {/* Stats */}
            <div className="bg-purple-50 rounded-xl p-6 border-2 border-purple-200">
                <h3 className="font-semibold flex items-center gap-2 text-purple-800">
                    <Package className="w-5 h-5" />
                    Thống Kê
                </h3>
                <div className="grid grid-cols-2 gap-4 mt-4">
                    <div className="bg-purple-100 p-4 rounded-lg">
                        <div className="text-2xl font-bold text-purple-800">12</div>
                        <div className="text-sm text-purple-600">Quà đã nhận</div>
                    </div>
                    <div className="bg-purple-100 p-4 rounded-lg">
                        <div className="text-2xl font-bold text-purple-800">{characters.length}</div>
                        <div className="text-sm text-purple-600">Nhân vật</div>
                    </div>
                </div>
            </div>
        </div>

        {/* Tabs */}
        <div className="flex space-x-2">
            <button
                className={`px-6 py-3 rounded-lg font-medium transition-all ${
                    activeTab === 'normal'
                    ? 'bg-blue-600 text-white'
                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                }`}
                onClick={() => setActiveTab('normal')}
            >
                Quà Thường
            </button>
            <button
                className={`px-6 py-3 rounded-lg font-medium transition-all ${
                    activeTab === 'vip'
                    ? 'bg-amber-500 text-white'
                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                }`}
                onClick={() => setActiveTab('vip')}
            >
                Quà VIP
            </button>
        </div>

        {/* Gift Timeline */}
        <div className="relative">
        <div className="absolute left-8 top-0 bottom-0 w-0.5 bg-gray-200" />
    
        {[1, 2, 3, 4, 5, 6, 7].map((day) => {
                    const isToday = currentStreak + 1 === day;
                    const isPast = day <= currentStreak;
                    const gift = giftsToShow?.find(gift => gift.day === day);
                    const claimed = gift?.already_claimed || false;

                    return (
                        <GiftItem
                            key={day}
                            day={day}
                            isToday={isToday}
                            isPast={isPast}
                            claimed={claimed}
                            giftInfo={gift}
                            isVip={activeTab === 'vip'}
                            onClaimGift={handleClaimGift}
                            isLoading={isLoading}
                            selectedCharacter={selectedCharacter}
            />);
            })}
        </div>

        {/* Recent Rewards */}
        <div className="bg-white rounded-xl p-6 shadow-sm">
            <h3 className="font-semibold mb-6 text-gray-800 flex items-center gap-2">
                <Gift className="w-5 h-5 text-blue-600" />
                Phần Quà Gần Đây
            </h3>
<div className="space-y-4">
    {[1, 2, 3].map((item) => (
        <div 
            key={item} 
            className="flex items-center justify-between py-4 border-b border-gray-100 last:border-0 hover:bg-gray-50 px-4 rounded-lg transition-colors"
        >
            <div className="flex items-center gap-4">
                <div className="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden">
                    {/* Thay thế icon bằng ảnh sản phẩm */}
                    <img 
                        src="/api/placeholder/48/48" 
                        alt={`Quà ngày ${item}`}
                        className="w-full h-full object-cover"
                    />
                </div>
                <div>
                    <div className="font-medium text-gray-800">Quà Ngày {item}</div>
                    <div className="text-sm text-gray-500">Đã nhận vào 09:15</div>
                </div>
            </div>
            {item === 1 ? (
                <span className="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-600 text-white">
                    Hôm nay
                </span>
            ) : (
                <span className="text-sm text-gray-500">
                    {item === 2 ? 'Hôm qua' : '2 ngày trước'}
                </span>
            )}
        </div>
    ))}
</div>
        </div>

        {/* Info Card */}
        <div className="bg-gray-50 rounded-xl p-6 border border-gray-200">
            <div className="flex items-center gap-3 mb-6">
                <div className="p-2 bg-blue-100 rounded-lg">
                    <Info className="w-5 h-5 text-blue-600" />
                </div>
                <h3 className="font-semibold text-gray-800">Thông tin điểm danh</h3>
            </div>
            <ul className="grid gap-4">
                {[
                    'Điểm danh đủ 3 ngày liên tiếp nhận thêm phần quà đặc biệt',
                    'Điểm danh đủ 7 ngày liên tiếp nhận Hộp Quà Hiếm',
                    'Reset điểm danh vào 00:00 hàng ngày',
                    'Người chơi VIP nhận thêm phần quà VIP hàng ngày'
                ].map((info, index) => (
                    <li key={index} className="flex items-center gap-3 text-gray-600">
                        <div className="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <ChevronRight className="w-4 h-4 text-blue-600" />
                        </div>
                        {info}
                    </li>
                ))}
            </ul>
        </div>
    </div>
);
};

export default DailyGift;