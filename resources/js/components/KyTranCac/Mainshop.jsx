import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';
import { Coins, ChevronLeft, ChevronRight, Tag, X, AlertCircle, Clock } from 'lucide-react';
import { toast } from 'react-hot-toast';
import api from '../../api';
import { useAuth } from '../../contexts/AuthContext';
import SafeHtmlParser from '../SafeHtmlParser';
import '../../../css/MainShop.css';

const MainShop = ({ activeTab, setActiveTab }) => {
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(false);
  const [buyingItem, setBuyingItem] = useState(false);
  const [currentPage, setCurrentPage] = useState(1);
  const [selectedItem, setSelectedItem] = useState(null);
  const [quantity, setQuantity] = useState(1);
  const [selectedCategory, setSelectedCategory] = useState(null);
  const itemsPerPage = 32;

  const { user, updateUserData } = useAuth();

  useEffect(() => {
    fetchShopItems();
  }, []);

  useEffect(() => {
    if (categories.length > 0 && !selectedCategory) {
      const firstCategory = categories[0].name;
      setSelectedCategory(firstCategory);
      setActiveTab(firstCategory);
    }
  }, [categories]);

  useEffect(() => {
    setCurrentPage(1);
  }, [activeTab]);

  const fetchShopItems = async () => {
    try {
      setLoading(true);
      const response = await api.get('/shop/items');
      if (response.data.success) {
        // Dữ liệu đã được sắp xếp từ phía server
        setCategories(response.data.data);
      }
    } catch (error) {
      console.error('Error fetching shop items:', error);
      toast.error('Không thể tải danh sách vật phẩm');
    } finally {
      setLoading(false);
    }
  };

  const handleBuyItem = async (item, quantity) => {
    if (buyingItem) return;

    const totalCost = item.price * quantity;
    if (user.coin < totalCost) {
      toast.error('Không đủ xu để mua vật phẩm này');
      return;
    }

    try {
      setBuyingItem(true);
      const response = await api.post('/shop/buy', {
        product_id: item.id,
        quantity: quantity
      });

      if (response.data.success) {
        toast.success('Mua vật phẩm thành công!');
        
        updateUserData({
          coin: response.data.data.remaining_coin,
          accumulate: response.data.data.accumulate,
          tongtieucoin: response.data.data.tongtieucoin
        });

        setCategories(prevCategories =>
          prevCategories.map(category => ({
            ...category,
            products: category.products.map(product =>
              product.id === item.id
                ? { ...product, remaining_turns: response.data.data.remaining_turns }
                : product
            )
          }))
        );

        setSelectedItem(null);
      }
    } catch (error) {
      const errorMessage = error.response?.data?.message || 'Có lỗi xảy ra khi mua vật phẩm';
      toast.error(errorMessage);
    } finally {
      setBuyingItem(false);
    }
  };

  const handleCategorySelect = (categoryName) => {
    setSelectedCategory(categoryName);
    setActiveTab(categoryName);
    setCurrentPage(1);
  };

  const formatDate = (dateString) => {
    if (!dateString) return '';
    const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
    return new Date(dateString).toLocaleDateString('vi-VN', options);
  };

  const isNewItem = (dateString) => {
    if (!dateString) return false;
    const itemDate = new Date(dateString);
    const now = new Date();
    // Sản phẩm được coi là mới nếu được thêm trong 7 ngày qua
    return (now - itemDate) / (1000 * 60 * 60 * 24) <= 7;
  };

  const currentCategoryProducts = categories.find(
    (category) => category.name === (selectedCategory || activeTab)
  )?.products || [];

  const totalPages = Math.ceil(currentCategoryProducts.length / itemsPerPage);
  const startIndex = (currentPage - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  const currentProducts = currentCategoryProducts.slice(startIndex, endIndex);

  const renderPopup = () => {
    if (!selectedItem) return null;

    const maxQuantity = selectedItem.limit ? selectedItem.remaining_turns : 99;
    const totalCost = selectedItem.price * quantity;
    const canAfford = user.coin >= totalCost;
    const isItemNew = isNewItem(selectedItem.created_at);

    return ReactDOM.createPortal(
      <div className="mshop-popup">
        <div className="mshop-popup__container">
          <button className="mshop-popup__close" onClick={() => setSelectedItem(null)}>
            <X size={24} />
          </button>

          <div className="mshop-popup__content">
            <div className="mshop-popup__image-container">
              <img 
                src={selectedItem.image} 
                alt={selectedItem.name} 
                className="mshop-popup__image"
              />
              {isItemNew && (
                <div className="mshop-popup__new-badge">MỚI</div>
              )}
            </div>
            
            <h3 className="mshop-popup__title">{selectedItem.name}</h3>

            {selectedItem.created_at && (
              <div className="mshop-popup__date">
                <Clock size={16} />
                <span>Ngày thêm: {formatDate(selectedItem.created_at)}</span>
              </div>
            )}

            {selectedItem.limit > 0 && (
              <div className="mshop-popup__limit">
                <div className="mshop-popup__limit-row">
                  <span>Giới hạn mua:</span>
                  <span>{selectedItem.limit} lượt/tuần</span>
                </div>
                <div className="mshop-popup__limit-row">
                  <span>Còn lại:</span>
                  <span>{selectedItem.remaining_turns} lượt</span>
                </div>
              </div>
            )}

            <div className="mshop-popup__description">
              <div className="mshop-popup__section">
                <h4>Mô tả sản phẩm</h4>
                <SafeHtmlParser html={selectedItem.description || 'Không có mô tả.'} />
              </div>
            </div>

            {selectedItem.content && (
              <div className="mshop-popup__details">
                <div className="mshop-popup__section">
                  <h4>Chi tiết sản phẩm</h4>
                  <SafeHtmlParser html={selectedItem.content} />
                </div>
              </div>
            )}

            <div className="mshop-popup__price">
              <Coins className="mshop-popup__price-icon" />
              <span>{selectedItem.price.toLocaleString()} xu</span>
            </div>

            <div className="mshop-popup__quantity">
              <label>Số lượng:</label>
              <div className="mshop-popup__quantity-controls">
                <button 
                  onClick={() => setQuantity(prev => Math.max(1, prev - 1))}
                  disabled={quantity <= 1}
                >
                  -
                </button>
                <span>{quantity}</span>
                <button 
                  onClick={() => setQuantity(prev => Math.min(maxQuantity, prev + 1))}
                  disabled={quantity >= maxQuantity}
                >
                  +
                </button>
              </div>
            </div>

            <div className="mshop-popup__total">
              <div className="mshop-popup__total-row">
                <span>Tổng tiền:</span>
                <span>{totalCost.toLocaleString()} xu</span>
              </div>
              {!canAfford && (
                <div className="mshop-popup__error">
                  <AlertCircle size={16} />
                  Không đủ xu (thiếu {(totalCost - user.coin).toLocaleString()} xu)
                </div>
              )}
            </div>

            <button
              onClick={() => handleBuyItem(selectedItem, quantity)}
              disabled={buyingItem || !canAfford || quantity > maxQuantity}
              className={`mshop-popup__buy-button ${buyingItem || !canAfford ? 'mshop-popup__buy-button--disabled' : ''}`}
            >
              {buyingItem ? (
                <div className="mshop-popup__loading">
                  <div className="mshop-popup__spinner" />
                  Đang xử lý...
                </div>
              ) : 'Xác nhận mua'}
            </button>
          </div>
        </div>
      </div>,
      document.getElementById('popup-root')
    );
  };

  return (
    <div className="mshop-container">
      <aside className="mshop-sidebar">
        <h2 className="mshop-sidebar__title">Kỳ Trân Các</h2>
        <ul className="mshop-sidebar__list">
          {categories.map((category) => (
            <li
              key={category.id}
              onClick={() => handleCategorySelect(category.name)}
              className={`mshop-sidebar__item ${(selectedCategory || activeTab) === category.name ? 'mshop-sidebar__item--active' : ''}`}
            >
              <Tag className="mshop-sidebar__icon" />
              {category.name}
            </li>
          ))}
        </ul>
      </aside>

      <div className="mshop-content">
        {loading ? (
          <div className="mshop-loading">
            <div className="mshop-loading__spinner" />
            <span className="mshop-loading__text">Đang tải...</span>
          </div>
        ) : (
          <>
            <div className="mshop-grid">
              {currentProducts.length === 0 ? (
                <div className="mshop-empty">
                  Không có sản phẩm nào trong danh mục này
                </div>
              ) : (
                currentProducts.map((item) => (
                  <div
                    key={item.id}
                    onClick={() => setSelectedItem(item)}
                    className="mshop-item"
                  >
                    <div className="mshop-item__image-container">
                      <img src={item.image} alt={item.name} className="mshop-item__image" />
                      {isNewItem(item.created_at) && (
                        <div className="mshop-item__new-badge">MỚI</div>
                      )}
                    </div>

                    {item.limit > 0 && (
                      <div className="mshop-item__limit">
                        Còn {item.remaining_turns}/{item.limit}
                      </div>
                    )}

                    <div className="mshop-item__info">
                      <h3 className="mshop-item__name">{item.name}</h3>
                      <div className="mshop-item__price">
                        <Coins className="mshop-item__price-icon" />
                        <span>{item.price.toLocaleString()}</span>
                      </div>

                      <button
                        onClick={(e) => {
                          e.stopPropagation();
                          setSelectedItem(item);
                        }}
                        className="mshop-item__buy-button"
                      >
                        Mua ngay
                      </button>
                    </div>
                  </div>
                ))
              )}
            </div>

            {totalPages > 1 && (
              <div className="mshop-pagination">
                <button
                  onClick={() => setCurrentPage((prev) => Math.max(prev - 1, 1))}
                  disabled={currentPage === 1}
                  className="mshop-pagination__button"
                >
                  <ChevronLeft size={20} />
                </button>

                {Array.from({ length: totalPages }, (_, i) => i + 1).map((page) => (
                  <button
                    key={page}
                    onClick={() => setCurrentPage(page)}
                    className={`mshop-pagination__number ${
                      currentPage === page ? 'mshop-pagination__number--active' : ''
                    }`}
                  >
                    {page}
                  </button>
                ))}

                <button
                  onClick={() => setCurrentPage((prev) => Math.min(prev + 1, totalPages))}
                  disabled={currentPage === totalPages}
                  className="mshop-pagination__button"
                >
                  <ChevronRight size={20} />
                </button>
              </div>
            )}
          </>
        )}
      </div>

      {renderPopup()}
    </div>
  );
};

export default MainShop;