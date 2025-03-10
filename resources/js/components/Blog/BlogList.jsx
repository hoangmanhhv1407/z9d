import React, { useState, useEffect, Suspense, lazy } from 'react';
import { Link } from 'react-router-dom';
import { Search, Calendar, Eye, FileText, Bell, Users, RefreshCw } from 'lucide-react';
import axios from 'axios';
import SafeHtmlParser from '../SafeHtmlParser';

const BackgroundSlideshow = lazy(() => import('../TrangChu/BackgroundSlideshow'));
const LeftSidebar = lazy(() => import('../LeftSidebar'));

// Loading Skeleton cho Blog Item
const BlogItemSkeleton = () => (
  <div className="animate-pulse">
    <div className="bg-gray-700/50 h-48 rounded-xl mb-4"></div>
    <div className="space-y-2">
      <div className="bg-gray-700/50 h-6 w-3/4 rounded"></div>
      <div className="bg-gray-700/50 h-4 w-1/2 rounded"></div>
    </div>
  </div>
);

const BlogList = () => {
  const [blogs, setBlogs] = useState([]);
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [searchTerm, setSearchTerm] = useState('');
  const [currentCategory, setCurrentCategory] = useState('all');
  const [activeTab, setActiveTab] = useState('news');

  useEffect(() => {
    fetchBlogs();
  }, []);

  const fetchBlogs = async () => {
    try {
      setLoading(true);
      const response = await axios.get('/api/blogs');
      if (response.data.success) {
        const allBlogs = response.data.categoryBlogList.flatMap(category => 
          category.blog?.map(blog => ({
            ...blog,
            category_name: category.name
          })) || []
        );
        setBlogs(allBlogs);
        setCategories(response.data.categoryBlogList);
      } else {
        setError('Không thể tải danh sách tin tức');
      }
    } catch (err) {
      setError('Có lỗi xảy ra khi tải danh sách tin tức');
    } finally {
      setLoading(false);
    }
  };

  const filteredBlogs = blogs.filter(blog => {
    const matchesSearch = blog.b_name?.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesCategory = currentCategory === 'all' || blog.b_category_id === currentCategory;
    return matchesSearch && matchesCategory;
  });

  const tabs = [
    { id: 'news', label: 'Tin Tức', icon: FileText },
    { id: 'events', label: 'Sự kiện', icon: Bell },
    { id: 'community', label: 'Cộng đồng', icon: Users },
    { id: 'updates', label: 'Cập nhật', icon: RefreshCw }
  ];

  return (
    <div className="flex flex-col min-h-screen">
      {/* Banner Top */}
      <div
        className="w-screen h-[657px] bg-cover bg-center -mt-16"
        style={{
          backgroundImage: 'url(/frontend/images/bg-top1.jpg)',
          backgroundRepeat: 'no-repeat',
          marginLeft: 'calc(-50vw + 50%)',
          marginRight: 'calc(-50vw + 50%)',
        }}
      />

      {/* Main Content Area với các background layers */}
      <div className="relative">
        {/* Background Layer 1 */}
        <div
          className="absolute inset-0 w-screen h-[1082px] bg-cover bg-center"
          style={{
            backgroundImage: 'url(/frontend/images/bg-tin-tuc-1.jpg)',
            backgroundRepeat: 'no-repeat',
            marginLeft: 'calc(-50vw + 50%)',
            marginRight: 'calc(-50vw + 50%)',
            zIndex: 1,
          }}
        />

        {/* Content Container */}
        <div className="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
          {/* Grid Layout */}
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {/* Left Sidebar */}
            <div className="lg:col-span-3">
              <div className="lg:sticky lg:top-24">
                <Suspense fallback={<div className="animate-pulse space-y-4">
                  <div className="h-48 bg-gray-700/50 rounded-xl"></div>
                  <div className="h-24 bg-gray-700/50 rounded-xl"></div>
                </div>}>
                  <LeftSidebar />
                </Suspense>
              </div>
            </div>

            {/* Main Content */}
            <div className="lg:col-span-9">
              {/* Breadcrumb */}
              <div className="flex items-center text-lg mb-6">
                <Link to="/" className="text-gray-300 hover:text-yellow-500 transition-colors">
                  Trang chủ
                </Link>
                <span className="mx-3 text-gray-400">/</span>
                <span className="text-yellow-500">Tin tức</span>
              </div>

              {/* Content Section */}
              <div className="bg-gray-900/90 backdrop-blur-sm rounded-2xl shadow-2xl overflow-hidden border border-gray-700/50">
                {/* Tabs */}
                <div className="border-b border-gray-700 bg-gradient-to-r from-gray-900 to-gray-800">
                  <div className="flex overflow-x-auto">
                    {tabs.map((tab) => (
                      <button
                        key={tab.id}
                        onClick={() => setActiveTab(tab.id)}
                        className={`flex items-center px-6 py-4 text-sm font-medium whitespace-nowrap transition-all duration-200
                          ${activeTab === tab.id 
                            ? 'text-yellow-400 border-b-2 border-yellow-500 bg-yellow-500/10' 
                            : 'text-gray-400 hover:text-gray-300 hover:bg-gray-800'}`}
                      >
                        <tab.icon className="w-4 h-4 mr-2" />
                        {tab.label}
                      </button>
                    ))}
                  </div>
                </div>

                <div className="p-6">
                  {/* Search and Filters */}
                  <div className="space-y-4 mb-6">
                    <div className="relative">
                      <input
                        type="text"
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                        placeholder="Tìm kiếm tin tức..."
                        className="w-full bg-gray-800/50 text-gray-200 rounded-lg pl-10 pr-4 py-3 border border-gray-700 focus:outline-none focus:border-yellow-500/50"
                      />
                      <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500" size={20} />
                    </div>

                    <div className="flex flex-wrap gap-2">
                      <button
                        onClick={() => setCurrentCategory('all')}
                        className={`px-4 py-2 rounded-lg transition-all duration-200 ${
                          currentCategory === 'all' 
                            ? 'bg-yellow-500 text-black' 
                            : 'bg-gray-800 text-gray-300 hover:bg-gray-700'
                        }`}
                      >
                        Tất cả
                      </button>
                      {categories.map((category) => (
                        <button
                          key={category.id}
                          onClick={() => setCurrentCategory(category.id)}
                          className={`px-4 py-2 rounded-lg transition-all duration-200 ${
                            currentCategory === category.id 
                              ? 'bg-yellow-500 text-black' 
                              : 'bg-gray-800 text-gray-300 hover:bg-gray-700'
                          }`}
                        >
                          {category.name}
                        </button>
                      ))}
                    </div>
                  </div>

                  {/* Blog Grid */}
                  {loading ? (
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                      {[1, 2, 3, 4].map((index) => (
                        <BlogItemSkeleton key={index} />
                      ))}
                    </div>
                  ) : error ? (
                    <div className="text-center py-8">
                      <p className="text-red-400">{error}</p>
                    </div>
                  ) : filteredBlogs.length === 0 ? (
                    <div className="text-center py-8">
                      <p className="text-gray-400">Không tìm thấy bài viết nào</p>
                    </div>
                  ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                      {filteredBlogs.map((blog) => (
                        <Link
                          key={blog.id}
                          to={`/tin-tuc/${blog.b_slug}`}
                          className="group block bg-gray-800/50 rounded-xl overflow-hidden hover:bg-gray-700/50 transition-all duration-200 border border-gray-700/30"
                        >
                          <div className="aspect-w-16 aspect-h-9 relative overflow-hidden">
                            <img
                              src={`/storage/blogs/${blog.b_thunbar}`}
                              alt={blog.b_name}
                              className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            />
                          </div>
                          <div className="p-4">
                            <div className="flex items-center space-x-2 mb-2">
                              <span className="bg-yellow-500/20 text-yellow-400 text-xs px-2 py-1 rounded-full">
                                {blog.category_name}
                              </span>
                            </div>
                            <h3 className="text-lg font-bold text-white mb-2 line-clamp-2 group-hover:text-yellow-400 transition-colors">
                              <SafeHtmlParser html={blog.b_name} />
                            </h3>
                            <div className="flex items-center space-x-4 text-sm text-gray-400">
                              <span className="flex items-center">
                                <Calendar className="w-4 h-4 mr-1" />
                                {new Date(blog.created_at).toLocaleDateString('vi-VN')}
                              </span>
                              <span className="flex items-center">
                                <Eye className="w-4 h-4 mr-1" />
                                {blog.views || 0} lượt xem
                              </span>
                            </div>
                            {blog.b_description && (
                              <div className="text-gray-400 mt-2 text-sm line-clamp-2">
                                <SafeHtmlParser html={blog.b_description} />
                              </div>
                            )}
                          </div>
                        </Link>
                      ))}
                    </div>
                  )}
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Background Layer 2 */}
        <div
          className="absolute w-screen h-[1380px] bg-cover bg-center"
          style={{
            backgroundImage: 'url(/frontend/images/bg-tin-tuc-1-2.jpg)',
            backgroundRepeat: 'no-repeat',
            backgroundSize: 'cover',
            marginLeft: 'calc(-50vw + 50%)',
            marginRight: 'calc(-50vw + 50%)',
            bottom: '0',
            zIndex: -1,
          }}
        />
      </div>
    </div>
  );
};

export default BlogList;