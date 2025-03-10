import{c as e,r as t,A as v,j as s,k as y,L as n,U as N,s as k,t as u,b as g}from"./app-4d58a1c1.js";import{C as f}from"./credit-card-11b7fc7f.js";import{C as M}from"./crown-80023408.js";import{T as b}from"./timer-024e62f0.js";/**
 * @license lucide-react v0.453.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const h=e("ArrowUpRight",[["path",{d:"M7 7h10v10",key:"1tivn9"}],["path",{d:"M7 17 17 7",key:"1vkiza"}]]);/**
 * @license lucide-react v0.453.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const w=e("Castle",[["path",{d:"M22 20v-9H2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2Z",key:"109fe4"}],["path",{d:"M18 11V4H6v7",key:"mon5oj"}],["path",{d:"M15 22v-4a3 3 0 0 0-3-3a3 3 0 0 0-3 3v4",key:"1k4jtn"}],["path",{d:"M22 11V9",key:"3zbp94"}],["path",{d:"M2 11V9",key:"1x5rnq"}],["path",{d:"M6 4V2",key:"1rsq15"}],["path",{d:"M18 4V2",key:"1jsdo1"}],["path",{d:"M10 4V2",key:"75d9ly"}],["path",{d:"M14 4V2",key:"8nj3z6"}]]);/**
 * @license lucide-react v0.453.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const C=e("Download",[["path",{d:"M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4",key:"ih7n3h"}],["polyline",{points:"7 10 12 15 17 10",key:"2ggqvy"}],["line",{x1:"12",x2:"12",y1:"15",y2:"3",key:"1vk2je"}]]);/**
 * @license lucide-react v0.453.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const A=e("Gauge",[["path",{d:"m12 14 4-4",key:"9kzdfg"}],["path",{d:"M3.34 19a10 10 0 1 1 17.32 0",key:"19p75a"}]]);/**
 * @license lucide-react v0.453.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const T=e("LogOut",[["path",{d:"M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4",key:"1uf3rs"}],["polyline",{points:"16 17 21 12 16 7",key:"1gabdz"}],["line",{x1:"21",x2:"9",y1:"12",y2:"12",key:"1uyos4"}]]);/**
 * @license lucide-react v0.453.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const S=e("Map",[["path",{d:"M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z",key:"169xi5"}],["path",{d:"M15 5.764v15",key:"1pn4in"}],["path",{d:"M9 3.236v15",key:"1uimfh"}]]);/**
 * @license lucide-react v0.453.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const L=e("Signal",[["path",{d:"M2 20h.01",key:"4haj6o"}],["path",{d:"M7 20v-4",key:"j294jx"}],["path",{d:"M12 20v-8",key:"i3yub9"}],["path",{d:"M17 20V8",key:"1tkaf5"}],["path",{d:"M22 4v16",key:"sih9yq"}]]);/**
 * @license lucide-react v0.453.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const H=e("Swords",[["polyline",{points:"14.5 17.5 3 6 3 3 6 3 17.5 14.5",key:"1hfsw2"}],["line",{x1:"13",x2:"19",y1:"19",y2:"13",key:"1vrmhu"}],["line",{x1:"16",x2:"20",y1:"16",y2:"20",key:"1bron3"}],["line",{x1:"19",x2:"21",y1:"21",y2:"19",key:"13pww6"}],["polyline",{points:"14.5 6.5 18 3 21 3 21 6 17.5 9.5",key:"hbey2j"}],["line",{x1:"5",x2:"9",y1:"14",y2:"18",key:"1hf58s"}],["line",{x1:"7",x2:"4",y1:"17",y2:"20",key:"pidxm4"}],["line",{x1:"3",x2:"5",y1:"19",y2:"21",key:"1pehsh"}]]);const O=()=>{const{isAuthenticated:l,user:a,logout:x,loading:m}=t.useContext(v),[c,p]=t.useState(!0),[d,r]=t.useState(!1);t.useEffect(()=>{(async()=>{if(l&&(a!=null&&a.email))try{const i=await g.get(`/api/check-activation-status/${a.email}`);p(i.data.isActivated)}catch(i){console.error("Lỗi kiểm tra trạng thái kích hoạt:",i)}})()},[l,a]);const j=()=>{!c&&(a!=null&&a.email)&&r(!0)};return s.jsxs("div",{className:"ls-container",children:[s.jsx("div",{className:"ls-card ls-download-section",children:l&&!c?s.jsxs("button",{onClick:j,className:"ls-activation-button",children:[s.jsx(y,{className:"icon"}),s.jsx("span",{children:"KÍCH HOẠT TÀI KHOẢN"})]}):s.jsxs("a",{href:"https://zplay9d.net/tin-tuc/tai-ve-va-cai-dat-game",className:"ls-download-button",target:"_blank",rel:"noopener noreferrer",children:[s.jsx(C,{className:"icon"}),s.jsx("span",{children:"TẢI GAME"})]})}),!m&&l&&a&&s.jsxs("div",{className:"ls-card ls-user-info",children:[s.jsxs(n,{to:"/thong-tin-tai-khoan",className:"ls-user-profile",children:[s.jsx("div",{className:"ls-avatar",children:s.jsx(N,{className:"ls-avatar-icon"})}),s.jsxs("div",{className:"ls-user-details",children:[s.jsx("h3",{children:a.userid}),s.jsx("span",{children:"Thành viên"})]}),s.jsx("button",{onClick:o=>{o.preventDefault(),x()},className:"ls-logout-btn",children:s.jsx(T,{})})]}),s.jsxs("div",{className:"ls-user-stats",children:[s.jsxs("div",{className:"ls-stat-item",children:[s.jsx("span",{className:"ls-stat-label",children:"Xu:"}),s.jsxs("div",{className:"ls-coin-display",children:[s.jsx("span",{children:Number(a.coin||0).toLocaleString()}),s.jsx("img",{src:"/frontend/images/gold.png",alt:"xu"})]})]}),s.jsxs(n,{to:"/ky-tran-cac",className:"ls-stat-item clickable",children:[s.jsx("span",{className:"ls-stat-label",children:"Kỳ Trân Các"}),s.jsx(h,{})]}),s.jsxs(n,{to:"/thong-tin-tai-khoan",className:"ls-stat-item clickable",children:[s.jsx("span",{className:"ls-stat-label",children:"Nạp Xu Tự Động"}),s.jsx(f,{})]})]})]}),s.jsxs("div",{className:"ls-card ls-server-info",children:[s.jsxs("div",{className:"ls-card-header",children:[s.jsx(M,{className:"ls-header-icon"}),s.jsx("h3",{children:"Thông Tin Server"})]}),s.jsxs("div",{className:"ls-server-status online",children:[s.jsxs("div",{className:"ls-status-indicator",children:[s.jsx(L,{}),s.jsx("span",{children:"Trạng Thái:"})]}),s.jsx("span",{className:"ls-status-text",children:"ĐANG HOẠT ĐỘNG"})]}),s.jsxs("div",{className:"ls-daily-events",children:[s.jsx("div",{className:"ls-events-header",children:s.jsx("span",{children:"Lịch Sự Kiện Hàng Ngày"})}),s.jsxs("div",{className:"ls-events-list",children:[s.jsxs("div",{className:"ls-event-item",children:[s.jsxs("div",{className:"ls-event-name",children:[s.jsx(H,{className:"ls-event-icon"}),s.jsx("span",{children:"Hắc Bạch Đại Chiến:"})]}),s.jsx("span",{className:"ls-event-time",children:"20:00 - 21:00"})]}),s.jsxs("div",{className:"ls-event-item",children:[s.jsxs("div",{className:"ls-event-name",children:[s.jsx(w,{className:"ls-event-icon"}),s.jsx("span",{children:"Trang Viên Chiến:"})]}),s.jsx("span",{className:"ls-event-time",children:"21:00 - 22:00"})]})]})]}),s.jsxs("div",{className:"ls-server-stats",children:[s.jsxs("div",{className:"ls-stat-group",children:[s.jsxs("div",{className:"ls-stat-row",children:[s.jsxs("div",{className:"ls-stat-label",children:[s.jsx(A,{className:"ls-stat-icon"}),s.jsx("span",{children:"Tỉ Lệ Kinh Nghiệm:"})]}),s.jsx("span",{className:"ls-stat-value",children:"x5"})]}),s.jsxs("div",{className:"ls-stat-row",children:[s.jsxs("div",{className:"ls-stat-label",children:[s.jsx(h,{className:"ls-stat-icon"}),s.jsx("span",{children:"Giai Đoạn:"})]}),s.jsx("span",{className:"ls-stat-value",children:"Giai đoạn 1"})]})]}),s.jsxs("div",{className:"ls-stat-group",children:[s.jsxs("div",{className:"ls-stat-row",children:[s.jsxs("div",{className:"ls-stat-label",children:[s.jsx(b,{className:"ls-stat-icon"}),s.jsx("span",{children:"Level Tối Đa:"})]}),s.jsx("span",{className:"ls-stat-value",children:"300"})]}),s.jsxs("div",{className:"ls-stat-row",children:[s.jsxs("div",{className:"ls-stat-label",children:[s.jsx(S,{className:"ls-stat-icon"}),s.jsx("span",{children:"Map Giới Hạn:"})]}),s.jsx("span",{className:"ls-stat-value",children:"Tô Châu"})]})]})]})]}),d&&k.createPortal(s.jsx(u,{isOpen:d,email:a==null?void 0:a.email,onClose:()=>r(!1)}),document.getElementById("popup-root"))]})};export{O as default};
