import React from 'react';
import { Gift, Check, Lock } from 'lucide-react';

const RechargeMilestoneTimeline = ({ currentAmount = 0 }) => {
  const milestones = [
    {
      amount: 50000,
      gift: "Túi Quà Tân Thủ",
      image: "/api/placeholder/40/40"
    },
    {
      amount: 200000,
      gift: "Rương May Mắn",
      image: "/api/placeholder/40/40"
    },
    {
      amount: 500000,
      gift: "Set Trang Bị VIP",
      image: "/api/placeholder/40/40"
    },
    {
      amount: 1000000,
      gift: "Thú Cưỡi Huyền Thoại",
      image: "/api/placeholder/40/40"
    },
    {
      amount: 2000000,
      gift: "Title Đại Gia",
      image: "/api/placeholder/40/40"
    }
  ];

  // Tìm milestone hiện tại và tính phần trăm tiến độ
  const getCurrentMilestoneIndex = () => {
    for (let i = milestones.length - 1; i >= 0; i--) {
      if (currentAmount >= milestones[i].amount) {
        return i;
      }
    }
    return -1;
  };

  const currentMilestoneIndex = getCurrentMilestoneIndex();
  const nextMilestone = milestones[currentMilestoneIndex + 1];
  const prevMilestone = milestones[currentMilestoneIndex];

  // Tính phần trăm hoàn thành giữa 2 mốc
  const calculateProgress = () => {
    if (currentMilestoneIndex === -1) {
      const firstMilestone = milestones[0].amount;
      return (currentAmount / firstMilestone) * 100;
    }
    if (currentMilestoneIndex === milestones.length - 1) {
      return 100;
    }
    const start = prevMilestone.amount;
    const end = nextMilestone.amount;
    const progress = ((currentAmount - start) / (end - start)) * 100;
    return Math.min(100, Math.max(0, progress));
  };

  return (
    <div className="w-full bg-white rounded-xl shadow-lg p-6 mb-8">
      <div className="flex items-center justify-between mb-4">
        <h3 className="text-lg font-bold text-gray-800 flex items-center">
          <Gift className="w-5 h-5 mr-2 text-blue-500" />
          Mốc Quà Tặng
        </h3>
        <div className="text-sm text-gray-600">
          Đã nạp: <span className="font-bold text-blue-600">{currentAmount.toLocaleString()}đ</span>
        </div>
      </div>

      <div className="relative">
        {/* Timeline Bar */}
        <div className="h-2 bg-gray-200 rounded-full mb-8">
          <div 
            className="h-full bg-blue-500 rounded-full transition-all duration-500"
            style={{ width: `${calculateProgress()}%` }}
          />
        </div>

        {/* Milestone Points */}
        <div className="absolute left-0 right-0 top-0 -mt-3">
          <div className="flex justify-between">
            {milestones.map((milestone, index) => {
              const isCompleted = currentAmount >= milestone.amount;
              const isNext = currentAmount < milestone.amount && 
                            currentAmount >= (index > 0 ? milestones[index - 1].amount : 0);

              return (
                <div 
                  key={milestone.amount} 
                  className="relative flex flex-col items-center"
                >
                  {/* Milestone Point */}
                  <div 
                    className={`w-8 h-8 rounded-full flex items-center justify-center ${
                      isCompleted 
                        ? 'bg-blue-500 text-white' 
                        : isNext
                          ? 'bg-blue-100 border-2 border-blue-500'
                          : 'bg-gray-200'
                    }`}
                  >
                    {isCompleted ? (
                      <Check className="w-5 h-5" />
                    ) : isNext ? (
                      <Gift className="w-5 h-5 text-blue-500" />
                    ) : (
                      <Lock className="w-4 h-4 text-gray-400" />
                    )}
                  </div>

                  {/* Gift Image & Info */}
                  <div className="absolute -bottom-20 transform -translate-x-1/2 left-1/2">
                    <div className={`flex flex-col items-center ${
                      isCompleted ? 'opacity-100' : 'opacity-50'
                    }`}>
                      <img 
                        src={milestone.image} 
                        alt={milestone.gift}
                        className="w-10 h-10 rounded-lg shadow-md mb-2"
                      />
                      <span className="text-xs font-medium text-gray-600 text-center w-20">
                        {milestone.amount.toLocaleString()}đ
                      </span>
                      <span className="text-xs text-gray-500 text-center w-24">
                        {milestone.gift}
                      </span>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      </div>
    </div>
  );
};

export default RechargeMilestoneTimeline;