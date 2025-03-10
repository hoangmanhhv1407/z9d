import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { Calendar, Eye, ArrowLeft, MessageCircle } from 'lucide-react';
import axios from 'axios';
import SafeHtmlParser from '../SafeHtmlParser';

const BlogContentSkeleton = () => (
  <div className="animate-pulse space-y-4">
    <div className="bg-gray-700/50 h-[300px] rounded-xl"></div>
    <div className="space-y-3">
      <div className="bg-gray-700/50 h-6 w-2/3 rounded"></div>
      <div className="bg-gray-700/50 h-4 rounded w-full"></div>
      <div className="bg-gray-700/50 h-4 rounded w-5/6"></div>
    </div>
  </div>
);

const BlogNews = () => {
  const [blog, setBlog] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [relatedBlogs, setRelatedBlogs] = useState([]);
  const { slug } = useParams();

  useEffect(() => {
    const fetchBlogDetails = async () => {
      try {
        setLoading(true);
        const response = await axios.get(`/api/blogs/${slug}`);
        if (response.data.success) {
          setBlog(response.data.postDetail);
          setRelatedBlogs(response.data.postHot || []);
          setError(null);
        } else {
          setError('Không thể tải bài viết');
        }
      } catch (err) {
        console.error('Error fetching blog:', err);
        setError('Có lỗi xảy ra khi tải bài viết');
      } finally {
        setLoading(false);
      }
    };

    fetchBlogDetails();
    window.scrollTo(0, 0);
  }, [slug]);

  return (
    <div className="flex flex-col min-h-screen">
      <div className="flex-1">
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
            {/* Breadcrumb và Title */}
            <div className="w-full mb-8">
              {/* Breadcrumb */}
              <div className="flex items-center text-lg mb-4">
                <Link to="/" className="text-gray-800 hover:text-yellow-500 transition-colors">
                  <svg 
                    className="w-6 h-6"
                    fill="currentColor" 
                    viewBox="0 0 20 20"
                  >
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                  </svg>
                </Link>
                <span className="mx-3 text-gray-800 text-xl">/</span>
                <Link to="/tin-tuc" className="text-gray-800 hover:text-yellow-500 transition-colors">
                  Tin tức
                </Link>
                <span className="mx-3 text-gray-800 text-xl">/</span>
                <span className="text-yellow-500 line-clamp-1">
                  {blog?.b_name}
                </span>
              </div>

              {/* Title */}
              <h1 className="text-4xl md:text-5xl xl:text-6xl font-bold text-gray-900 relative z-10 after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-full after:h-[2px] after:bg-gradient-to-r after:from-gray-900 after:via-gray-700 after:to-transparent pb-4">
                {blog?.b_name}
              </h1>
              
              {/* Date */}
              <div className="text-xl text-gray-800 mt-4">
                {new Date(blog?.created_at).toLocaleDateString('vi-VN')}
              </div>
            </div>

            <div className="bg-transparent rounded-2xl p-6 md:p-8">
              {loading ? (
                <BlogContentSkeleton />
              ) : error ? (
                <div className="text-center py-8">
                  <p className="text-red-500">{error}</p>
                </div>
              ) : blog ? (
                <article className="prose prose-xl max-w-none">
                  {/* Article Content */}
                  <div className="prose prose-xl max-w-none 
                    prose-headings:text-gray-900
                    prose-h1:text-4xl
                    prose-h2:text-3xl
                    prose-h3:text-2xl
                    prose-p:text-xl prose-p:text-gray-800
                    prose-a:text-blue-600 hover:prose-a:text-blue-500
                    prose-strong:text-gray-900
                    prose-ul:text-gray-800
                    prose-ol:text-gray-800
                    prose-li:text-xl
                    prose-li:marker:text-gray-900
                    prose-blockquote:text-gray-800 prose-blockquote:border-gray-900
                    prose-hr:border-gray-800">
                    <SafeHtmlParser html={blog.b_content} />
                  </div>

                  {/* Related Posts Section */}
                  {relatedBlogs.length > 0 && (
   <section className="mt-12 pt-8 border-t border-gray-700/50">
   <h2 className="text-3xl md:text-4xl font-bold mb-6 text-white"> {/* Thay đổi màu tiêu đề thành trắng */}
     Bài viết liên quan
   </h2>
   
   <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
     {relatedBlogs.slice(0, 3).map((relatedBlog) => (
       <Link
         key={relatedBlog.id}
         to={`/tin-tuc/${relatedBlog.b_slug}`}
         className="group"
       >
         <div className="bg-gray-900/30 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-yellow-500/20 hover:bg-gray-800/40 border border-gray-700/50">
           <div className="aspect-w-16 aspect-h-9">
             <img
               src={`/storage/blogs/${relatedBlog.b_thunbar}`}
               alt={relatedBlog.b_name}
               className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
             />
           </div>
           <div className="p-4">
             <h3 className="text-xl font-bold text-white group-hover:text-yellow-400 transition-colors duration-300 mb-2 line-clamp-2"> {/* Thay đổi màu tiêu đề thành trắng */}
               <SafeHtmlParser html={relatedBlog.b_name} />
             </h3>
             <div className="flex items-center text-lg text-gray-300 group-hover:text-yellow-300 transition-colors duration-300"> {/* Chỉnh màu ngày tháng sáng hơn */}
               <Calendar className="w-5 h-5 mr-2" />
               {new Date(relatedBlog.created_at).toLocaleDateString('vi-VN')}
             </div>
           </div>
         </div>
       </Link>
     ))}
   </div>
 </section>
                  )}
                </article>
              ) : (
                <div className="text-center py-8">
                  <p className="text-gray-800 text-xl">Không tìm thấy bài viết</p>
                </div>
              )}
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
    </div>
  );
};

export default BlogNews;