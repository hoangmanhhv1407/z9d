import React, { useState, useEffect, useCallback } from 'react';
import { Search, Calendar, ChevronRight, Newspaper, Bell, Users, RefreshCw, ChevronLeft, ArrowRight, Clock } from 'lucide-react';
import axios from 'axios';
import { usePagination } from '../../hooks/usePagination';
import SafeHtmlParser from '../SafeHtmlParser';
import '../../../css/MainContent.css';

const MainContent = () => {
  const [activeTab, setActiveTab] = useState('news');
  const [blogs, setBlogs] = useState([]);
  const [hotBlogs, setHotBlogs] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [searchTerm, setSearchTerm] = useState('');
  const [currentSlide, setCurrentSlide] = useState(0);

  useEffect(() => {
    fetchBlogs();
    const slideInterval = setInterval(() => {
      if (hotBlogs.length > 1) {
        setCurrentSlide(current => (current + 1) % hotBlogs.length);
      }
    }, 5000);
    return () => clearInterval(slideInterval);
  }, [hotBlogs.length]);

  const fetchBlogs = async () => {
    try {
      const response = await axios.get('/api/blogs');
      if (response.data.success) {
        setHotBlogs(response.data.blog);
        const allBlogs = response.data.categoryBlogList.flatMap(category => 
          category.blog.map(blog => ({
            ...blog,
            category_name: category.name
          }))
        );
        setBlogs(allBlogs);
      } else {
        setError('Không thể tải tin tức');
      }
      setLoading(false);
    } catch (err) {
      setError('Có lỗi xảy ra, vui lòng thử lại sau');
      setLoading(false);
    }
  };

  const nextSlide = useCallback(() => {
    setCurrentSlide((current) => (current + 1) % hotBlogs.length);
  }, [hotBlogs.length]);

  const prevSlide = useCallback(() => {
    setCurrentSlide((current) => (current - 1 + hotBlogs.length) % hotBlogs.length);
  }, [hotBlogs.length]);

  const getFilteredBlogs = () => {
    return blogs.filter(blog =>
      blog.b_name.toLowerCase().includes(searchTerm.toLowerCase())
    );
  };

  const { currentData, currentPage, maxPage, next, prev } = usePagination(getFilteredBlogs(), 6);

  const tabs = [
    { id: 'news', label: 'Tin Tức', icon: Newspaper },
    { id: 'events', label: 'Sự kiện', icon: Bell },
    { id: 'community', label: 'Cộng đồng', icon: Users },
    { id: 'updates', label: 'Cập nhật', icon: RefreshCw }
  ];

  return (
    <div className="mc-container">
      {/* Featured Section */}
      {hotBlogs.length > 0 && (
        <div className="mc-featured-section">
          <div className="relative w-full h-full">
            {hotBlogs.map((blog, index) => (
              <div
                key={blog.id}
                className={`mc-featured-slide ${index === currentSlide ? 'active' : 'inactive'}`}
              >
                <img 
                  src={`/storage/blogs/${blog.b_thunbar}`} 
                  alt={blog.b_name} 
                  className="w-full h-full object-cover"
                />
                <div className="mc-featured-overlay">
                  <div className="mc-featured-content">
                    <div className="space-y-4 max-w-3xl">
                      <div className="flex items-center space-x-3">
                        <span className="mc-featured-tag">Nổi bật</span>
                        <span className="mc-featured-date">
                          <Clock size={14} className="mr-1" />
                          {new Date(blog.created_at).toLocaleDateString('vi-VN')}
                        </span>
                      </div>
                      <h2 className="mc-featured-title">
                        <SafeHtmlParser html={blog.b_name} />
                      </h2>
                      <p className="mc-featured-description">
                        <SafeHtmlParser html={blog.b_description} />
                      </p>
                      <a href={`/tin-tuc/${blog.b_slug}`} className="mc-featured-button">
                        Xem chi tiết
                        <ArrowRight size={18} className="ml-2" />
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>
          
          {/* Slider Controls */}
          <div className="mc-slider-controls">
            <button onClick={prevSlide} className="mc-slider-button">
              <ChevronLeft size={24} />
            </button>
            <button onClick={nextSlide} className="mc-slider-button">
              <ChevronRight size={24} />
            </button>
          </div>
        </div>
      )}

      {/* Main Content Section */}
      <div className="mc-main-section">
        {/* Header with Tabs and Search */}
        <div className="mc-tab-navigation">
          <div className="mc-tab-container">
            <div className="mc-tabs-list">
              {tabs.map((tab) => (
                <button
                  key={tab.id}
                  onClick={() => setActiveTab(tab.id)}
                  className={`mc-tab-button ${activeTab === tab.id ? 'active' : 'inactive'}`}
                >
                  <tab.icon size={18} className="mr-2" />
                  {tab.label}
                </button>
              ))}
            </div>
            
            <div className="mc-search-container">
              <input
                type="text"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                placeholder="Tìm kiếm..."
                className="mc-search-input"
              />
              <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500" size={16} />
            </div>
          </div>
        </div>

        {/* News Content */}
        <div className="p-6">
          {loading ? (
            <div className="mc-loading-container">
              <div className="mc-loading-spinner"></div>
              <p className="mc-loading-text">Đang tải tin tức...</p>
            </div>
          ) : error ? (
            <div className="mc-error-container">
              <p>{error}</p>
            </div>
          ) : (
            <div className="mc-news-grid">
              {currentData.map((blog) => (
                <a
                  key={blog.id}
                  href={`/tin-tuc/${blog.b_slug}`}
                  className="mc-news-card"
                >
                  <div className="mc-news-image-container">
                    <img
                      src={`/storage/blogs/${blog.b_thunbar}`}
                      alt={blog.b_name}
                      className="mc-news-image"
                    />
                    <div className="mc-news-category">
                      {blog.category_name}
                    </div>
                  </div>
                  <div className="mc-news-content">
                    <h3 className="mc-news-title">
                      <SafeHtmlParser html={blog.b_name} />
                    </h3>
                    <div className="mc-news-description">
                      <SafeHtmlParser html={blog.b_description || ''} />
                    </div>
                    <div className="mc-news-footer">
                      <div className="mc-news-date">
                        <Clock size={14} className="mr-1" />
                        {new Date(blog.created_at).toLocaleDateString('vi-VN')}
                      </div>
                      <div className="mc-news-link">
                        Xem thêm
                        <ArrowRight size={16} className="ml-1" />
                      </div>
                    </div>
                  </div>
                </a>
              ))}
            </div>
          )}

          {/* Pagination */}
          {maxPage > 1 && (
            <div className="mc-pagination">
              <button
                onClick={prev}
                disabled={currentPage === 1}
                className={`mc-pagination-button ${currentPage === 1 ? 'disabled' : 'active'}`}
              >
                <ChevronLeft size={20} />
              </button>
              <span className="text-gray-400">
                Trang {currentPage} / {maxPage}
              </span>
              <button
                onClick={next}
                disabled={currentPage === maxPage}
                className={`mc-pagination-button ${currentPage === maxPage ? 'disabled' : 'active'}`}
              >
                <ChevronRight size={20} />
              </button>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default MainContent;