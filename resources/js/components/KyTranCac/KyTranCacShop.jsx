import React from 'react';
import { Coins } from 'lucide-react';
import CanvasPlaceholder from '../CanvasPlaceholder';

const KyTranCacShop = ({ kyTranCacItems, handleSelectItem, selectedItem }) => {
  return (
    <div className="bg-[#e0d0b6] p-6 rounded-xl shadow-lg flex flex-col h-full">
      <div className="flex justify-between items-center mb-6">
        <h2 className="text-3xl font-bold text-[#c8aa6e]">Kỳ Trân Các Shop</h2>
        <button className="text-3xl text-[#c8aa6e] hover:text-[#b89a5e] transition-colors duration-300">&times;</button>
      </div>
      <div className="grid grid-cols-2 gap-4 overflow-y-auto pr-2 h-[600px]">
        {kyTranCacItems.map((item) => (
          <div
            key={item.id}
            className={`bg-[#d0c0a0] p-4 rounded-xl flex items-center cursor-pointer transition-all duration-300 hover:shadow-lg hover:scale-105 ${
              selectedItem?.id === item.id ? 'ring-2 ring-[#c8aa6e] shadow-lg' : ''
            }`}
            onClick={() => handleSelectItem(item)}
          >
            <div className="mr-3 flex-shrink-0">
              <CanvasPlaceholder
                width={80}
                height={80}
                text={item.icon}
                backgroundColor="#e0d0b6"
                textColor="#5c5b57"
              />
            </div>
            <div className="flex flex-col justify-between flex-grow overflow-hidden">
              <span className="text-lg font-semibold truncate">{item.name}</span>
              <span className="flex items-center text-lg mt-2">
                <Coins size={18} className="mr-1 text-yellow-500" />
                {item.price}
              </span>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default KyTranCacShop;