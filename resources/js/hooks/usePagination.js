// hooks/usePagination.js
import { useState } from 'react';

export const usePagination = (data, itemsPerPage = 5) => { // Thay đổi itemsPerPage thành 5
    const [currentPage, setCurrentPage] = useState(1);

    const maxPage = Math.ceil(data.length / itemsPerPage);
    
    const currentData = () => {
        const begin = (currentPage - 1) * itemsPerPage;
        const end = begin + itemsPerPage;
        return data.slice(begin, end);
    };

    const next = () => {
        setCurrentPage(currentPage => Math.min(currentPage + 1, maxPage));
    };

    const prev = () => {
        setCurrentPage(currentPage => Math.max(currentPage - 1, 1));
    };

    const jump = (page) => {
        const pageNumber = Math.max(1, Math.min(page, maxPage));
        setCurrentPage(pageNumber);
    };

    return {
        next,
        prev,
        jump,
        currentData: currentData(),
        currentPage,
        maxPage
    };
};