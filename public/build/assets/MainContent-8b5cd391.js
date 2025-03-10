import{c as R,r as c,b as E,j as e,C as f,d as I,R as V}from"./app-4d58a1c1.js";import{S as x}from"./SafeHtmlParser-3deb5c6b.js";import{C as b}from"./clock-fb634083.js";import{A as w}from"./arrow-right-0ebd1bc5.js";import{C}from"./chevron-left-797d531f.js";import{S as A}from"./search-707095e5.js";import{B as H}from"./bell-23e919c4.js";/**
 * @license lucide-react v0.453.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const P=R("Newspaper",[["path",{d:"M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2",key:"7pis2x"}],["path",{d:"M18 14h-8",key:"sponae"}],["path",{d:"M15 18h-5",key:"95g1m2"}],["path",{d:"M10 6h8v4h-8V6Z",key:"smlsk5"}]]),X=(d,i=5)=>{const[o,l]=c.useState(1),a=Math.ceil(d.length/i);return{next:()=>{l(t=>Math.min(t+1,a))},prev:()=>{l(t=>Math.max(t-1,1))},jump:t=>{const n=Math.max(1,Math.min(t,a));l(n)},currentData:(()=>{const t=(o-1)*i,n=t+i;return d.slice(t,n)})(),currentPage:o,maxPage:a}};const O=()=>{const[d,i]=c.useState("news"),[o,l]=c.useState([]),[a,v]=c.useState([]),[N,u]=c.useState(!0),[j,t]=c.useState(null),[n,S]=c.useState(""),[y,p]=c.useState(0);c.useEffect(()=>{k();const s=setInterval(()=>{a.length>1&&p(m=>(m+1)%a.length)},5e3);return()=>clearInterval(s)},[a.length]);const k=async()=>{try{const s=await E.get("/api/blogs");if(s.data.success){v(s.data.blog);const m=s.data.categoryBlogList.flatMap(g=>g.blog.map($=>({...$,category_name:g.name})));l(m)}else t("Không thể tải tin tức");u(!1)}catch{t("Có lỗi xảy ra, vui lòng thử lại sau"),u(!1)}},_=c.useCallback(()=>{p(s=>(s+1)%a.length)},[a.length]),M=c.useCallback(()=>{p(s=>(s-1+a.length)%a.length)},[a.length]),z=()=>o.filter(s=>s.b_name.toLowerCase().includes(n.toLowerCase())),{currentData:B,currentPage:r,maxPage:h,next:D,prev:L}=X(z(),6),T=[{id:"news",label:"Tin Tức",icon:P},{id:"events",label:"Sự kiện",icon:H},{id:"community",label:"Cộng đồng",icon:I},{id:"updates",label:"Cập nhật",icon:V}];return e.jsxs("div",{className:"mc-container",children:[a.length>0&&e.jsxs("div",{className:"mc-featured-section",children:[e.jsx("div",{className:"relative w-full h-full",children:a.map((s,m)=>e.jsxs("div",{className:`mc-featured-slide ${m===y?"active":"inactive"}`,children:[e.jsx("img",{src:`/storage/blogs/${s.b_thunbar}`,alt:s.b_name,className:"w-full h-full object-cover"}),e.jsx("div",{className:"mc-featured-overlay",children:e.jsx("div",{className:"mc-featured-content",children:e.jsxs("div",{className:"space-y-4 max-w-3xl",children:[e.jsxs("div",{className:"flex items-center space-x-3",children:[e.jsx("span",{className:"mc-featured-tag",children:"Nổi bật"}),e.jsxs("span",{className:"mc-featured-date",children:[e.jsx(b,{size:14,className:"mr-1"}),new Date(s.created_at).toLocaleDateString("vi-VN")]})]}),e.jsx("h2",{className:"mc-featured-title",children:e.jsx(x,{html:s.b_name})}),e.jsx("p",{className:"mc-featured-description",children:e.jsx(x,{html:s.b_description})}),e.jsxs("a",{href:`/tin-tuc/${s.b_slug}`,className:"mc-featured-button",children:["Xem chi tiết",e.jsx(w,{size:18,className:"ml-2"})]})]})})})]},s.id))}),e.jsxs("div",{className:"mc-slider-controls",children:[e.jsx("button",{onClick:M,className:"mc-slider-button",children:e.jsx(C,{size:24})}),e.jsx("button",{onClick:_,className:"mc-slider-button",children:e.jsx(f,{size:24})})]})]}),e.jsxs("div",{className:"mc-main-section",children:[e.jsx("div",{className:"mc-tab-navigation",children:e.jsxs("div",{className:"mc-tab-container",children:[e.jsx("div",{className:"mc-tabs-list",children:T.map(s=>e.jsxs("button",{onClick:()=>i(s.id),className:`mc-tab-button ${d===s.id?"active":"inactive"}`,children:[e.jsx(s.icon,{size:18,className:"mr-2"}),s.label]},s.id))}),e.jsxs("div",{className:"mc-search-container",children:[e.jsx("input",{type:"text",value:n,onChange:s=>S(s.target.value),placeholder:"Tìm kiếm...",className:"mc-search-input"}),e.jsx(A,{className:"absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500",size:16})]})]})}),e.jsxs("div",{className:"p-6",children:[N?e.jsxs("div",{className:"mc-loading-container",children:[e.jsx("div",{className:"mc-loading-spinner"}),e.jsx("p",{className:"mc-loading-text",children:"Đang tải tin tức..."})]}):j?e.jsx("div",{className:"mc-error-container",children:e.jsx("p",{children:j})}):e.jsx("div",{className:"mc-news-grid",children:B.map(s=>e.jsxs("a",{href:`/tin-tuc/${s.b_slug}`,className:"mc-news-card",children:[e.jsxs("div",{className:"mc-news-image-container",children:[e.jsx("img",{src:`/storage/blogs/${s.b_thunbar}`,alt:s.b_name,className:"mc-news-image"}),e.jsx("div",{className:"mc-news-category",children:s.category_name})]}),e.jsxs("div",{className:"mc-news-content",children:[e.jsx("h3",{className:"mc-news-title",children:e.jsx(x,{html:s.b_name})}),e.jsx("div",{className:"mc-news-description",children:e.jsx(x,{html:s.b_description||""})}),e.jsxs("div",{className:"mc-news-footer",children:[e.jsxs("div",{className:"mc-news-date",children:[e.jsx(b,{size:14,className:"mr-1"}),new Date(s.created_at).toLocaleDateString("vi-VN")]}),e.jsxs("div",{className:"mc-news-link",children:["Xem thêm",e.jsx(w,{size:16,className:"ml-1"})]})]})]})]},s.id))}),h>1&&e.jsxs("div",{className:"mc-pagination",children:[e.jsx("button",{onClick:L,disabled:r===1,className:`mc-pagination-button ${r===1?"disabled":"active"}`,children:e.jsx(C,{size:20})}),e.jsxs("span",{className:"text-gray-400",children:["Trang ",r," / ",h]}),e.jsx("button",{onClick:D,disabled:r===h,className:`mc-pagination-button ${r===h?"disabled":"active"}`,children:e.jsx(f,{size:20})})]})]})]})]})};export{O as default};
