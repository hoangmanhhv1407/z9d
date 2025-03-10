import React from 'react';
import { Facebook, MessageCircle, MessagesSquare, Send } from 'lucide-react';

const FloatingSocialMedia = () => {
  return (
    <div className="fixed right-4 top-1/2 -translate-y-1/2 z-50 space-y-3">
      {/* Facebook */}
      <a
        href="#facebook"
        className="flex items-center group"
      >
        <span className="opacity-0 group-hover:opacity-100 bg-blue-600 text-white px-3 py-1 rounded-lg mr-2 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
          Facebook
        </span>
        <div className="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white hover:scale-110 transition-transform duration-300 shadow-lg hover:shadow-blue-500/50">
          <Facebook size={24} />
        </div>
      </a>

      {/* Zalo */}
      <a
        href="#zalo"
        className="flex items-center group"
      >
        <span className="opacity-0 group-hover:opacity-100 bg-blue-500 text-white px-3 py-1 rounded-lg mr-2 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
          Zalo
        </span>
        <div className="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white hover:scale-110 transition-transform duration-300 shadow-lg hover:shadow-blue-400/50">
          <MessageCircle size={24} />
        </div>
      </a>

      {/* Discord */}
      <a
        href="#discord"
        className="flex items-center group"
      >
        <span className="opacity-0 group-hover:opacity-100 bg-indigo-600 text-white px-3 py-1 rounded-lg mr-2 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
          Discord
        </span>
        <div className="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center text-white hover:scale-110 transition-transform duration-300 shadow-lg hover:shadow-indigo-500/50">
          <MessagesSquare size={24} />
        </div>
      </a>

      {/* Telegram */}
      <a
        href="#telegram"
        className="flex items-center group"
      >
        <span className="opacity-0 group-hover:opacity-100 bg-sky-500 text-white px-3 py-1 rounded-lg mr-2 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
          Telegram
        </span>
        <div className="w-12 h-12 bg-sky-500 rounded-full flex items-center justify-center text-white hover:scale-110 transition-transform duration-300 shadow-lg hover:shadow-sky-400/50">
          <Send size={24} />
        </div>
      </a>
    </div>
  );
};

export default FloatingSocialMedia;