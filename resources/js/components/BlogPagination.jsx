// components/BlogPagination.jsx
import React from 'react';
import { ChevronLeft, ChevronRight } from 'lucide-react';

const BlogPagination = ({ currentPage, maxPage, onPageChange }) => {
    return (
        <div className="flex justify-center items-center space-x-2 mt-6">
            <button
                onClick={() => onPageChange(currentPage - 1)}
                disabled={currentPage === 1}
                className={`p-2 rounded-lg transition-colors duration-200 
                    ${currentPage === 1 
                        ? 'bg-gray-800 text-gray-600 cursor-not-allowed' 
                        : 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white'
                    }`}
            >
                <ChevronLeft className="w-5 h-5" />
            </button>

            {[...Array(maxPage)].map((_, idx) => (
                <button
                    key={idx + 1}
                    onClick={() => onPageChange(idx + 1)}
                    className={`w-10 h-10 rounded-lg font-medium transition-all duration-200
                        ${currentPage === idx + 1
                            ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30'
                            : 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white'
                        }`}
                >
                    {idx + 1}
                </button>
            ))}

            <button
                onClick={() => onPageChange(currentPage + 1)}
                disabled={currentPage === maxPage}
                className={`p-2 rounded-lg transition-colors duration-200
                    ${currentPage === maxPage
                        ? 'bg-gray-800 text-gray-600 cursor-not-allowed'
                        : 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white'
                    }`}
            >
                <ChevronRight className="w-5 h-5" />
            </button>
        </div>
    );
};



export default BlogPagination;