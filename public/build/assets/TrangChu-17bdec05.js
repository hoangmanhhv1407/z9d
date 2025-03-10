import{r as s,j as e,_ as n}from"./app-4d58a1c1.js";const g=()=>{const[t,i]=s.useState(null),l=[{id:"facebook",name:"FANPAGE CHÍNH THỨC",icon:"https://cdnjs.cloudflare.com/ajax/libs/simple-icons/11.6.0/facebook.svg",bgColor:"from-blue-600 to-blue-800",hoverEffect:"hover:shadow-blue-500/50",memberCount:"50K+ Thành viên",description:"Cập nhật tin tức game nhanh nhất",url:"https://www.facebook.com/cltbzplay/"},{id:"zalo",name:"CỘNG ĐỒNG ZALO",icon:"/frontend/images/7044033_zalo_icon.png",bgColor:"from-blue-400 to-blue-600",hoverEffect:"hover:shadow-blue-400/50",memberCount:"20K+ Thành viên",description:"Hỗ trợ người chơi 24/7",url:"https://zalo.me/g/rbimrn842"},{id:"discord",name:"DISCORD SERVER",icon:"https://cdnjs.cloudflare.com/ajax/libs/simple-icons/11.6.0/discord.svg",bgColor:"from-indigo-600 to-indigo-800",hoverEffect:"hover:shadow-indigo-500/50",memberCount:"15K+ Members",description:"Voice chat & Tìm team",url:"https://discord.gg/your-discord-invite"},{id:"telegram",name:"TELEGRAM GROUP",icon:"https://cdnjs.cloudflare.com/ajax/libs/simple-icons/11.6.0/telegram.svg",bgColor:"from-sky-400 to-sky-600",hoverEffect:"hover:shadow-sky-400/50",memberCount:"10K+ Subscribers",description:"Thông báo sự kiện & Giftcode",url:"https://t.me/your-telegram-group"}];return e.jsx("div",{className:"fixed right-6 top-1/2 -translate-y-1/2 z-50 space-y-4",children:l.map(a=>e.jsxs("div",{className:"relative group",onMouseEnter:()=>i(a.id),onMouseLeave:()=>i(null),children:[e.jsxs("div",{className:`absolute right-full mr-4 top-1/2 -translate-y-1/2 bg-gray-900/95 rounded-lg p-4 w-64
                       border border-gray-700/50 backdrop-blur-sm transition-all duration-300
                       ${t===a.id?"opacity-100 visible -translate-x-0":"opacity-0 invisible translate-x-4"}`,children:[e.jsxs("div",{className:"text-white",children:[e.jsx("h3",{className:"font-bold text-sm mb-1",children:a.name}),e.jsx("p",{className:"text-gray-400 text-xs mb-2",children:a.description}),e.jsxs("div",{className:"flex items-center text-xs text-yellow-400",children:[e.jsxs("span",{className:"flex items-center",children:[e.jsx("span",{className:"w-2 h-2 bg-green-500 rounded-full mr-1"}),"Online:"]}),e.jsx("span",{className:"ml-2",children:a.memberCount})]})]}),e.jsx("div",{className:"absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 transform rotate-45 w-3 h-3 bg-gray-900 border-t border-r border-gray-700/50"})]}),e.jsx("a",{href:a.url,target:"_blank",rel:"noopener noreferrer",className:"block",children:e.jsxs("div",{className:`relative w-14 h-14 rounded-2xl overflow-hidden transition-all duration-300
                          bg-gradient-to-br ${a.bgColor}
                          hover:scale-110 ${a.hoverEffect} shadow-lg
                          before:absolute before:inset-0 before:bg-black/20 before:z-10
                          after:absolute after:inset-0 after:bg-gradient-to-t after:from-black/40 after:to-transparent`,children:[e.jsx("div",{className:"absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300",children:e.jsx("div",{className:`absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,_rgba(255,255,255,0.2),_transparent_50%)]\r
                              animate-[ping_3s_cubic-bezier(0,0,0.2,1)_infinite]`})}),e.jsx("div",{className:"relative z-20 w-full h-full flex items-center justify-center",children:e.jsx("img",{src:a.icon,alt:a.name,className:"w-7 h-7 object-contain invert"})}),e.jsx("div",{className:`absolute inset-0 border-2 border-white/20 rounded-2xl opacity-0 group-hover:opacity-100\r
                            transition-opacity duration-300`})]})})]},a.id))})},b=s.lazy(()=>n(()=>import("./BackgroundSlideshow-18879a06.js"),["assets/BackgroundSlideshow-18879a06.js","assets/app-4d58a1c1.js","assets/app-c0bed085.css"])),u=s.lazy(()=>n(()=>import("./MainContent-8b5cd391.js"),["assets/MainContent-8b5cd391.js","assets/app-4d58a1c1.js","assets/app-c0bed085.css","assets/SafeHtmlParser-3deb5c6b.js","assets/clock-fb634083.js","assets/arrow-right-0ebd1bc5.js","assets/chevron-left-797d531f.js","assets/search-707095e5.js","assets/bell-23e919c4.js","assets/MainContent-265f9d25.css"])),p=s.lazy(()=>n(()=>import("./RankingTable-f2b14a84.js"),["assets/RankingTable-f2b14a84.js","assets/app-4d58a1c1.js","assets/app-c0bed085.css","assets/clock-fb634083.js","assets/crown-80023408.js","assets/star-2e2e9a2f.js","assets/gift-a60ca994.js","assets/timer-024e62f0.js","assets/arrow-right-0ebd1bc5.js","assets/RankingTable-a2f07dc7.css"])),j=s.lazy(()=>n(()=>import("./LeftSidebar-84f63abf.js"),["assets/LeftSidebar-84f63abf.js","assets/app-4d58a1c1.js","assets/app-c0bed085.css","assets/credit-card-11b7fc7f.js","assets/crown-80023408.js","assets/timer-024e62f0.js","assets/LeftSidebar-e2f1b2cb.css"]));s.lazy(()=>n(()=>import("./EventSchedule-234d1804.js"),["assets/EventSchedule-234d1804.js","assets/app-4d58a1c1.js","assets/app-c0bed085.css","assets/EventSchedule-7bebeb75.css"]));const v=()=>e.jsxs("div",{className:"skeleton-pulse",children:[e.jsx("div",{className:"skeleton-sidebar h-12 mb-4"}),e.jsxs("div",{className:"space-y-3",children:[e.jsx("div",{className:"skeleton-sidebar h-32"}),e.jsx("div",{className:"space-y-2",children:[1,2,3,4].map(t=>e.jsx("div",{className:"skeleton-sidebar h-10"},t))})]})]}),f=()=>e.jsxs("div",{className:"skeleton-pulse space-y-4",children:[e.jsx("div",{className:"skeleton-content h-[300px]"}),e.jsx("div",{className:"space-y-4",children:[1,2,3].map(t=>e.jsxs("div",{className:"flex gap-4",children:[e.jsx("div",{className:"skeleton-content w-32 h-24"}),e.jsxs("div",{className:"flex-1 space-y-2",children:[e.jsx("div",{className:"skeleton-content h-6 w-3/4"}),e.jsx("div",{className:"skeleton-content h-4 w-1/4"})]})]},t))})]}),N=()=>e.jsx("div",{className:"loading-overlay",children:e.jsxs("div",{className:"loading-spinner-container",children:[e.jsxs("div",{className:"loading-spinner",children:[e.jsx("div",{className:"spinner-ring spinner-ring-static"}),e.jsx("div",{className:"spinner-ring spinner-ring-dynamic"})]}),e.jsx("div",{className:"loading-text",children:e.jsxs("div",{className:"loading-dots",children:[e.jsx("span",{className:"loading-dot",children:"Đang"}),e.jsx("span",{className:"loading-dot",style:{animationDelay:"0.1s"},children:"tải"}),e.jsx("span",{className:"loading-dot",style:{animationDelay:"0.2s"},children:"."}),e.jsx("span",{className:"loading-dot",style:{animationDelay:"0.3s"},children:"."}),e.jsx("span",{className:"loading-dot",style:{animationDelay:"0.4s"},children:"."})]})})]})}),_=()=>{s.useState([{id:1,b_name:"BẢN CẬP NHẬT MỚI GAME",b_thunbar:"/frontend/images/update1.jpg",created_at:"2024-03-20"},{id:2,b_name:"SỰ KIỆN HOÀNG GIA",b_thunbar:"/frontend/images/update2.jpg",created_at:"2024-03-21"}]);const[t,i]=s.useState(!1),[l,a]=s.useState(!0),o=x("(max-width: 768px)");s.useEffect(()=>{const r=setTimeout(()=>{a(!1)},1500);return()=>clearTimeout(r)},[]);function x(r){const[c,m]=s.useState(!1);return s.useEffect(()=>{const d=window.matchMedia(r);d.matches!==c&&m(d.matches);const h=()=>m(d.matches);return window.addEventListener("resize",h),()=>window.removeEventListener("resize",h)},[c,r]),c}return e.jsxs(e.Fragment,{children:[l&&e.jsx(N,{}),e.jsxs("div",{className:"flex flex-col min-h-screen",children:[!o&&e.jsxs("section",{className:"tc-hero-section",children:[e.jsx(s.Suspense,{fallback:e.jsx("div",{className:"w-full h-full bg-gray-900 animate-pulse"}),children:e.jsx(b,{})}),e.jsx("div",{className:"tc-hero-decor",style:{backgroundImage:"url(/frontend/images/decor.png)"}})]}),e.jsxs("section",{className:`tc-main-section ${o?"mobile":""}`,children:[e.jsx("div",{className:"tc-main-background",style:{backgroundImage:"url(/frontend/images/bg-ulti.jpg)"}}),e.jsxs("div",{className:"tc-content-wrapper",children:[o&&e.jsx("div",{className:"fixed bottom-4 right-4 z-50",children:e.jsxs("button",{onClick:()=>i(!t),className:"tc-sidebar-toggle","aria-label":"Toggle Sidebar",children:[e.jsx("span",{className:`tc-toggle-line ${t?"tc-active":""}`}),e.jsx("span",{className:`tc-toggle-line tc-middle ${t?"tc-active":""}`}),e.jsx("span",{className:`tc-toggle-line tc-bottom ${t?"tc-active":""}`})]})}),e.jsxs("div",{className:"tc-content-grid",children:[e.jsx("div",{className:`tc-sidebar-column ${t?"tc-active":""}`,children:e.jsx("div",{className:"tc-sidebar-sticky",children:e.jsx(s.Suspense,{fallback:e.jsx(v,{}),children:e.jsx(j,{})})})}),e.jsx("div",{className:"tc-main-column",children:e.jsx(s.Suspense,{fallback:e.jsx(f,{}),children:e.jsx(u,{})})})]}),e.jsx("div",{className:"mt-12 relative",children:e.jsx(s.Suspense,{fallback:e.jsx("div",{className:"animate-pulse bg-gray-700/50 h-96 rounded-xl"}),children:e.jsx(p,{})})}),e.jsx(g,{})]})]})]})]})};export{_ as default};
