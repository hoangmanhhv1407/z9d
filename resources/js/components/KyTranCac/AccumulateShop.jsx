import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';
import { Star, ChevronLeft, ChevronRight, X } from 'lucide-react';
import { toast } from 'react-hot-toast';
import { useAuth } from '../../contexts/AuthContext';
import SafeHtmlParser from '../SafeHtmlParser';
import api from '../../api';
import '../../../css/AccumulateShop.css';

const AccumulateShop = () => {
  const { user, updateUserData } = useAuth();
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(false);
  const [buyingItem, setBuyingItem] = useState(false);
  const [currentPage, setCurrentPage] = useState(1);
  const [selectedItem, setSelectedItem] = useState(null);
  const itemsPerPage = 32;

  useEffect(() => {
    fetchAccumulateItems();
  }, []);

  const fetchAccumulateItems = async () => {
    try {
      setLoading(true);
      const response = await api.get('/shop/accumulate-items');
      if (response.data.success) {
        const allProducts = response.data.data.reduce((acc, category) => {
          return [...acc, ...category.products];
        }, []);
        setProducts(allProducts);
      }
    } catch (error) {
      console.error('Error fetching accumulate items:', error);
      toast.error('Không thể tải danh sách vật phẩm');
    } finally {
      setLoading(false);
    }
  };

  const handleBuyItem = async (item) => {
    if (buyingItem) return;

    try {
      setBuyingItem(true);
      const response = await api.post('/shop/buy-accumulate', {
        product_id: item.id
      });

      if (response.data.success) {
        toast.success('Mua vật phẩm thành công!');
        
        updateUserData({
          accumulate: response.data.data.remaining_accumulate,
          coin: response.data.data.remaining_coin
        });

        setSelectedItem(null);
        
        setProducts(prevProducts =>
          prevProducts.map(product =>
            product.id === item.id
              ? { ...product, đã_mua: true }
              : product
          )
        );
      }
    } catch (error) {
      const errorMessage = error.response?.data?.message || 'Có lỗi xảy ra khi mua vật phẩm';
      toast.error(errorMessage);
    } finally {
      setBuyingItem(false);
    }
  };

  const renderPopup = () => {
    if (!selectedItem) return null;

    const canBuyWithAccumulate = user.accumulate >= selectedItem.accumulate_price;

    return ReactDOM.createPortal(
      <div className="ashop-popup">
        <div className="ashop-popup__container">
          <button 
            onClick={() => setSelectedItem(null)}
            className="ashop-popup__close"
          >
            <X size={24} />
          </button>

          <div className="ashop-popup__content">
            <div className="ashop-popup__image-wrapper">
              <img 
                src={selectedItem.image}
                alt={selectedItem.name}
                className="ashop-popup__image"
              />
            </div>

            <h3 className="ashop-popup__title">{selectedItem.name}</h3>

            <div className="ashop-popup__description">
              <SafeHtmlParser html={selectedItem.description || 'Không có mô tả.'} />
            </div>

            {selectedItem.content && (
              <div className="ashop-popup__details">
                <h4 className="ashop-popup__subtitle">Chi tiết sản phẩm</h4>
                <SafeHtmlParser html={selectedItem.content} />
              </div>
            )}

            <div className="ashop-popup__price-info">
              <div className="ashop-popup__price">
                <div className="ashop-popup__price-header">
                  <Star className="ashop-popup__price-icon" />
                  <span className="ashop-popup__price-label">Giá:</span>
                </div>
                <span className="ashop-popup__price-value">
                  {selectedItem.accumulate_price.toLocaleString()} Điểm
                </span>
              </div>

              <div className="ashop-popup__balance">
                <span className="ashop-popup__balance-label">Điểm tích lũy của bạn:</span>
                <span className="ashop-popup__balance-value">
                  {user.accumulate.toLocaleString()} Điểm
                </span>
              </div>
            </div>

            <button
              onClick={() => handleBuyItem(selectedItem)}
              disabled={buyingItem || !canBuyWithAccumulate}
              className={`ashop-popup__buy-button ${
                buyingItem || !canBuyWithAccumulate ? 'ashop-popup__buy-button--disabled' : ''
              }`}
            >
              {buyingItem 
                ? 'Đang xử lý...' 
                : canBuyWithAccumulate 
                  ? 'Mua ngay' 
                  : 'Không đủ điểm tích lũy'
              }
            </button>
          </div>
        </div>
      </div>,
      document.getElementById('popup-root')
    );
  };

  const totalPages = Math.ceil(products.length / itemsPerPage);
  const startIndex = (currentPage - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  const currentProducts = products.slice(startIndex, endIndex);

  return (
    <div className="ashop-container">
      {loading ? (
        <div className="ashop-loading">
          <div className="ashop-loading__spinner" />
        </div>
      ) : (
        <>
          <div className="ashop-grid">
            {currentProducts.map((item) => (
              <div
                key={item.id}
                onClick={() => setSelectedItem(item)}
                className="ashop-item"
              >
                <div className="ashop-item__image-container">
                  <img 
                    src={item.image} 
                    alt={item.name} 
                    className="ashop-item__image"
                    loading="lazy"
                  />
                </div>

                <div className="ashop-item__content">
                  <h3 className="ashop-item__title">
                    {item.name}
                  </h3>

                  <div className="ashop-item__price">
                    <Star className="ashop-item__price-icon" />
                    <span className="ashop-item__price-value">
                      {item.accumulate_price.toLocaleString()}
                    </span>
                  </div>

                  <button
                    onClick={(e) => {
                      e.stopPropagation();
                      setSelectedItem(item);
                    }}
                    className="ashop-item__buy-button"
                  >
                    Mua ngay
                  </button>
                </div>
              </div>
            ))}
          </div>

          {totalPages > 1 && (
            <div className="ashop-pagination">
              <button
                onClick={() => setCurrentPage(prev => Math.max(prev - 1, 1))}
                disabled={currentPage === 1}
                className="ashop-pagination__button"
              >
                <ChevronLeft size={20} />
              </button>

              {Array.from({ length: totalPages }, (_, i) => i + 1).map((page) => (
                <button
                  key={page}
                  onClick={() => setCurrentPage(page)}
                  className={`ashop-pagination__number ${
                    currentPage === page ? 'ashop-pagination__number--active' : ''
                  }`}
                >
                  {page}
                </button>
              ))}

              <button
                onClick={() => setCurrentPage(prev => Math.min(prev + 1, totalPages))}
                disabled={currentPage === totalPages}
                className="ashop-pagination__button"
              >
                <ChevronRight size={20} />
              </button>
            </div>
          )}
        </>
      )}

      {renderPopup()}
    </div>
  );
};

export default AccumulateShop;