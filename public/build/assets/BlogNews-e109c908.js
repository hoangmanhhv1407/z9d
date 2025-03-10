import{r,a as p,j as e,L as n,b as u}from"./app-4d58a1c1.js";import{S as d}from"./SafeHtmlParser-3deb5c6b.js";import{C as b}from"./calendar-00b0d0e9.js";const v=()=>e.jsxs("div",{className:"animate-pulse space-y-4",children:[e.jsx("div",{className:"bg-gray-700/50 h-[300px] rounded-xl"}),e.jsxs("div",{className:"space-y-3",children:[e.jsx("div",{className:"bg-gray-700/50 h-6 w-2/3 rounded"}),e.jsx("div",{className:"bg-gray-700/50 h-4 rounded w-full"}),e.jsx("div",{className:"bg-gray-700/50 h-4 rounded w-5/6"})]})]}),N=()=>{const[t,m]=r.useState(null),[g,o]=r.useState(!0),[c,l]=r.useState(null),[i,h]=r.useState([]),{slug:x}=p();return r.useEffect(()=>{(async()=>{try{o(!0);const s=await u.get(`/api/blogs/${x}`);s.data.success?(m(s.data.postDetail),h(s.data.postHot||[]),l(null)):l("Không thể tải bài viết")}catch(s){console.error("Error fetching blog:",s),l("Có lỗi xảy ra khi tải bài viết")}finally{o(!1)}})(),window.scrollTo(0,0)},[x]),e.jsx("div",{className:"flex flex-col min-h-screen",children:e.jsxs("div",{className:"flex-1",children:[e.jsx("div",{className:"w-screen h-[657px] bg-cover bg-center -mt-16",style:{backgroundImage:"url(/frontend/images/bg-top1.jpg)",backgroundRepeat:"no-repeat",marginLeft:"calc(-50vw + 50%)",marginRight:"calc(-50vw + 50%)"}}),e.jsxs("div",{className:"relative",children:[e.jsx("div",{className:"absolute inset-0 w-screen h-[1082px] bg-cover bg-center",style:{backgroundImage:"url(/frontend/images/bg-tin-tuc-1.jpg)",backgroundRepeat:"no-repeat",marginLeft:"calc(-50vw + 50%)",marginRight:"calc(-50vw + 50%)",zIndex:1}}),e.jsxs("div",{className:"relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8",children:[e.jsxs("div",{className:"w-full mb-8",children:[e.jsxs("div",{className:"flex items-center text-lg mb-4",children:[e.jsx(n,{to:"/",className:"text-gray-800 hover:text-yellow-500 transition-colors",children:e.jsx("svg",{className:"w-6 h-6",fill:"currentColor",viewBox:"0 0 20 20",children:e.jsx("path",{d:"M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"})})}),e.jsx("span",{className:"mx-3 text-gray-800 text-xl",children:"/"}),e.jsx(n,{to:"/tin-tuc",className:"text-gray-800 hover:text-yellow-500 transition-colors",children:"Tin tức"}),e.jsx("span",{className:"mx-3 text-gray-800 text-xl",children:"/"}),e.jsx("span",{className:"text-yellow-500 line-clamp-1",children:t==null?void 0:t.b_name})]}),e.jsx("h1",{className:"text-4xl md:text-5xl xl:text-6xl font-bold text-gray-900 relative z-10 after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-full after:h-[2px] after:bg-gradient-to-r after:from-gray-900 after:via-gray-700 after:to-transparent pb-4",children:t==null?void 0:t.b_name}),e.jsx("div",{className:"text-xl text-gray-800 mt-4",children:new Date(t==null?void 0:t.created_at).toLocaleDateString("vi-VN")})]}),e.jsx("div",{className:"bg-transparent rounded-2xl p-6 md:p-8",children:g?e.jsx(v,{}):c?e.jsx("div",{className:"text-center py-8",children:e.jsx("p",{className:"text-red-500",children:c})}):t?e.jsxs("article",{className:"prose prose-xl max-w-none",children:[e.jsx("div",{className:`prose prose-xl max-w-none \r
                    prose-headings:text-gray-900\r
                    prose-h1:text-4xl\r
                    prose-h2:text-3xl\r
                    prose-h3:text-2xl\r
                    prose-p:text-xl prose-p:text-gray-800\r
                    prose-a:text-blue-600 hover:prose-a:text-blue-500\r
                    prose-strong:text-gray-900\r
                    prose-ul:text-gray-800\r
                    prose-ol:text-gray-800\r
                    prose-li:text-xl\r
                    prose-li:marker:text-gray-900\r
                    prose-blockquote:text-gray-800 prose-blockquote:border-gray-900\r
                    prose-hr:border-gray-800`,children:e.jsx(d,{html:t.b_content})}),i.length>0&&e.jsxs("section",{className:"mt-12 pt-8 border-t border-gray-700/50",children:[e.jsxs("h2",{className:"text-3xl md:text-4xl font-bold mb-6 text-white",children:[" ","Bài viết liên quan"]}),e.jsx("div",{className:"grid gap-6 md:grid-cols-2 lg:grid-cols-3",children:i.slice(0,3).map(a=>e.jsx(n,{to:`/tin-tuc/${a.b_slug}`,className:"group",children:e.jsxs("div",{className:"bg-gray-900/30 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-yellow-500/20 hover:bg-gray-800/40 border border-gray-700/50",children:[e.jsx("div",{className:"aspect-w-16 aspect-h-9",children:e.jsx("img",{src:`/storage/blogs/${a.b_thunbar}`,alt:a.b_name,className:"w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"})}),e.jsxs("div",{className:"p-4",children:[e.jsxs("h3",{className:"text-xl font-bold text-white group-hover:text-yellow-400 transition-colors duration-300 mb-2 line-clamp-2",children:[" ",e.jsx(d,{html:a.b_name})]}),e.jsxs("div",{className:"flex items-center text-lg text-gray-300 group-hover:text-yellow-300 transition-colors duration-300",children:[" ",e.jsx(b,{className:"w-5 h-5 mr-2"}),new Date(a.created_at).toLocaleDateString("vi-VN")]})]})]})},a.id))})]})]}):e.jsx("div",{className:"text-center py-8",children:e.jsx("p",{className:"text-gray-800 text-xl",children:"Không tìm thấy bài viết"})})})]}),e.jsx("div",{className:"absolute w-screen h-[1380px] bg-cover bg-center",style:{backgroundImage:"url(/frontend/images/bg-tin-tuc-1-2.jpg)",backgroundRepeat:"no-repeat",backgroundSize:"cover",marginLeft:"calc(-50vw + 50%)",marginRight:"calc(-50vw + 50%)",bottom:"0",zIndex:-1}})]})]})})};export{N as default};
