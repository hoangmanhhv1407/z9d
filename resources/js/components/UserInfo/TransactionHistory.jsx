import React, { useState, useEffect } from 'react';
import { History, Calendar, Search, RefreshCw, ChevronLeft, ChevronRight, Filter } from 'lucide-react';
import { getTransactionHistory } from '../../api';
import { toast } from 'react-hot-toast';
import { useAuth } from '../../contexts/AuthContext';
import '../../../css/TransactionHistory.css';

const TransactionHistory = () => {
  const [transactions, setTransactions] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const { user } = useAuth();
  
  // Pagination state
  const [currentPage, setCurrentPage] = useState(1);
  const [itemsPerPage] = useState(10);
  
  // Filter state
  const [searchTerm, setSearchTerm] = useState('');
  const [dateRange, setDateRange] = useState({
    startDate: '',
    endDate: ''
  });
  const [filterType, setFilterType] = useState('all');

  const transactionTypes = {
    1: { label: 'Nạp Xu', color: 'green', sign: '+', unit: 'Xu' },
    2: { label: 'Mua Vật Phẩm', color: 'red', sign: '-', unit: 'Xu' },
    3: { label: 'Admin Nạp', color: 'blue', sign: '+', unit: 'Xu' },
    4: { label: 'Mua Bằng Điểm Tích Lũy', color: 'purple', sign: '-', unit: 'Điểm' }
  };

  useEffect(() => {
    fetchTransactionHistory();
  }, []);

  const fetchTransactionHistory = async () => {
    try {
      setLoading(true);
      const response = await getTransactionHistory();
      setTransactions(response.data);
      setError(null);
    } catch (error) {
      console.error('Lỗi khi lấy lịch sử giao dịch:', error);
      setError('Không thể tải lịch sử giao dịch');
      toast.error('Có lỗi xảy ra khi tải lịch sử giao dịch');
    } finally {
      setLoading(false);
    }
  };

  const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleString('vi-VN', {
      year: 'numeric',
      month: '2-digit',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit'
    });
  };

  const formatCoin = (coin) => {
    return new Intl.NumberFormat('vi-VN').format(coin);
  };

  const getFilteredTransactions = () => {
    return transactions.filter(transaction => {
      const matchesSearch = 
        transaction.productName?.toLowerCase().includes(searchTerm.toLowerCase()) ||
        transaction.transactionCode?.toLowerCase().includes(searchTerm.toLowerCase());
      
      const matchesType = filterType === 'all' || transaction.type.toString() === filterType;
      
      const transactionDate = new Date(transaction.date);
      const matchesDate = 
        (!dateRange.startDate || transactionDate >= new Date(dateRange.startDate)) &&
        (!dateRange.endDate || transactionDate <= new Date(dateRange.endDate));

      return matchesSearch && matchesType && matchesDate;
    });
  };

// Thêm hàm để tính toán các trang sẽ hiển thị
const getPageNumbers = (currentPage, totalPages) => {
  const delta = 2; // Số trang hiển thị mỗi bên
  const range = [];
  const rangeWithDots = [];
  let l;

  // Luôn hiển thị trang 1
  range.push(1);

  if (totalPages <= 1) {
    return range;
  }

  // Tính toán các trang cần hiển thị
  for (let i = currentPage - delta; i <= currentPage + delta; i++) {
    if (i < totalPages && i > 1) {
      range.push(i);
    }
  }

  // Luôn hiển thị trang cuối
  range.push(totalPages);

  // Thêm dấu ... vào giữa các khoảng cách
  for (let i of range) {
    if (l) {
      if (i - l === 2) {
        rangeWithDots.push(l + 1);
      } else if (i - l !== 1) {
        rangeWithDots.push('...');
      }
    }
    rangeWithDots.push(i);
    l = i;
  }

  return rangeWithDots;
};

  const filteredTransactions = getFilteredTransactions();
  const totalPages = Math.ceil(filteredTransactions.length / itemsPerPage);
  const indexOfLastItem = currentPage * itemsPerPage;
  const indexOfFirstItem = indexOfLastItem - itemsPerPage;
  const currentItems = filteredTransactions.slice(indexOfFirstItem, indexOfLastItem);

  if (loading) {
    return (
      <div className="th-container">
        <div className="th-loading">
          <div className="th-loading__header"></div>
          {[...Array(5)].map((_, index) => (
            <div key={index} className="th-loading__row"></div>
          ))}
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="th-container">
        <div className="th-error">
          <p className="th-error__message">{error}</p>
          <button onClick={fetchTransactionHistory} className="th-error__button">
            <RefreshCw className="th-error__icon" />
            Thử lại
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="th-container">
      <div className="th-header">
        <h4 className="th-title">
          <History className="th-title__icon" />
          Lịch Sử Giao Dịch
        </h4>
      </div>

      <div className="th-filters">
        <div className="th-search">
          <Search className="th-search__icon" />
          <input
            type="text"
            placeholder="Tìm kiếm..."
            className="th-search__input"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
          />
        </div>

        <div className="th-date-range">
          <input
            type="date"
            className="th-date-range__input"
            value={dateRange.startDate}
            onChange={(e) => setDateRange(prev => ({ ...prev, startDate: e.target.value }))}
          />
          <input
            type="date"
            className="th-date-range__input"
            value={dateRange.endDate}
            onChange={(e) => setDateRange(prev => ({ ...prev, endDate: e.target.value }))}
          />
        </div>

        <select
          className="th-type-filter"
          value={filterType}
          onChange={(e) => setFilterType(e.target.value)}
        >
          <option value="all">Tất cả giao dịch</option>
          {Object.entries(transactionTypes).map(([key, { label }]) => (
            <option key={key} value={key}>{label}</option>
          ))}
        </select>

        <button onClick={fetchTransactionHistory} className="th-refresh">
          <RefreshCw className="th-refresh__icon" />
          Làm mới
        </button>
      </div>

      <div className="th-table-container">
        <table className="th-table">
          <thead>
            <tr>
              <th>STT</th>
              <th>Tên Vật Phẩm</th>
              <th>Số Lượng</th>
              <th>Xu</th>
              <th>Mã Giao Dịch</th>
              <th>Số Điện Thoại</th>
              <th>Hình Thức</th>
              <th>Ngày Giao Dịch</th>
            </tr>
          </thead>
          <tbody>
            {currentItems.length > 0 ? (
              currentItems.map((item, index) => {
                const typeInfo = transactionTypes[item.type];
                return (
                  <tr key={index} className="th-table__row">
                    <td>{indexOfFirstItem + index + 1}</td>
                    <td>{item.productName || 'N/A'}</td>
                    <td className="text-center">{item.qty || 1}</td>
                    <td className={`th-amount th-amount--${typeInfo.color}`}>
                      {typeInfo.sign} {formatCoin(item.coin)} {typeInfo.unit}
                    </td>
                    <td>{item.transactionCode || 'N/A'}</td>
                    <td>{item.phone || 'N/A'}</td>
                    <td>
                      <span className={`th-badge th-badge--${typeInfo.color}`}>
                        {typeInfo.label}
                      </span>
                    </td>
                    <td>{formatDate(item.date)}</td>
                  </tr>
                )
              })
            ) : (
              <tr>
                <td colSpan="8" className="th-empty">
                  Không có dữ liệu lịch sử giao dịch.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>

      {totalPages > 1 && (
  <div className="th-pagination">
    <div className="th-pagination__info">
      Hiển thị {indexOfFirstItem + 1} - {Math.min(indexOfLastItem, filteredTransactions.length)} trong số {filteredTransactions.length} giao dịch
    </div>
    <div className="th-pagination__controls">
      <button
        onClick={() => setCurrentPage(1)}
        disabled={currentPage === 1}
        className="th-pagination__arrow"
        title="Trang đầu"
      >
        ⟪
      </button>
      <button
        onClick={() => setCurrentPage(prev => Math.max(prev - 1, 1))}
        disabled={currentPage === 1}
        className="th-pagination__arrow"
      >
        <ChevronLeft />
      </button>

      {getPageNumbers(currentPage, totalPages).map((page, index) => (
        <button
          key={index}
          onClick={() => typeof page === 'number' && setCurrentPage(page)}
          className={`th-pagination__button ${
            currentPage === page ? 'th-pagination__button--active' : ''
          } ${typeof page !== 'number' ? 'th-pagination__dots' : ''}`}
          disabled={typeof page !== 'number'}
        >
          {page}
        </button>
      ))}

      <button
        onClick={() => setCurrentPage(prev => Math.min(prev + 1, totalPages))}
        disabled={currentPage === totalPages}
        className="th-pagination__arrow"
      >
        <ChevronRight />
      </button>
      <button
        onClick={() => setCurrentPage(totalPages)}
        disabled={currentPage === totalPages}
        className="th-pagination__arrow"
        title="Trang cuối"
      >
        ⟫
      </button>
    </div>
  </div>
)}

    </div>
  );
};

export default TransactionHistory;