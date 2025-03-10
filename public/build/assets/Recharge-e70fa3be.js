import{c as Y,r as n,h as y,n as j,j as e,i as te,X as ae,p as _,o as ce,k as E,f as he,u as de}from"./app-4d58a1c1.js";import{C as oe}from"./crown-80023408.js";import{S as K}from"./star-2e2e9a2f.js";import{C as $}from"./clock-fb634083.js";import{T as D}from"./timer-024e62f0.js";import{I as me}from"./info-dabd5de1.js";import{G as J}from"./gift-a60ca994.js";import{C as xe}from"./coins-89cc77ad.js";import{C as ue}from"./credit-card-11b7fc7f.js";import{B as _e}from"./bell-23e919c4.js";/**
 * @license lucide-react v0.453.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const je=Y("Banknote",[["rect",{width:"20",height:"12",x:"2",y:"6",rx:"2",key:"9lu3g6"}],["circle",{cx:"12",cy:"12",r:"2",key:"1c9p78"}],["path",{d:"M6 12h.01M18 12h.01",key:"113zkx"}]]);/**
 * @license lucide-react v0.453.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const le=Y("QrCode",[["rect",{width:"5",height:"5",x:"3",y:"3",rx:"1",key:"1tu5fj"}],["rect",{width:"5",height:"5",x:"16",y:"3",rx:"1",key:"1v8r4q"}],["rect",{width:"5",height:"5",x:"3",y:"16",rx:"1",key:"1x03jg"}],["path",{d:"M21 16h-3a2 2 0 0 0-2 2v3",key:"177gqh"}],["path",{d:"M21 21v.01",key:"ents32"}],["path",{d:"M12 7v3a2 2 0 0 1-2 2H7",key:"8crl2c"}],["path",{d:"M3 12h.01",key:"nlz23k"}],["path",{d:"M12 3h.01",key:"n36tog"}],["path",{d:"M12 16v.01",key:"133mhm"}],["path",{d:"M16 12h1",key:"1slzba"}],["path",{d:"M21 12v.01",key:"1lwtk9"}],["path",{d:"M12 21v-1",key:"1880an"}]]);/**
 * @license lucide-react v0.453.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const H=Y("Sparkles",[["path",{d:"M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z",key:"4pj2yx"}],["path",{d:"M20 3v4",key:"1olli1"}],["path",{d:"M22 5h-4",key:"1gvqau"}],["path",{d:"M4 17v2",key:"vumght"}],["path",{d:"M5 18H3",key:"zchphs"}]]);const Ne=()=>{var V,F;const[h,N]=n.useState(null),[g,x]=n.useState(null),[i,I]=n.useState(null),[Q,b]=n.useState(!0),[u,k]=n.useState(null),[T,A]=n.useState(!1),[M,B]=n.useState(!1),[p,R]=n.useState("overview");n.useEffect(()=>{o(),L(),X()},[]);const X=async()=>{try{const s=await y.get("/time-settings");s.data.success&&I(s.data.data)}catch(s){console.error("Error fetching time settings:",s)}},o=async()=>{try{const s=await y.get("/recharge-events/config");s.data.success&&N(s.data.data)}catch{j.error("Không thể tải cấu hình sự kiện")}finally{b(!1)}},L=async()=>{try{const s=await y.get("/recharge-events/user-status");s.data.success&&x(s.data.data)}catch(s){console.error("Error:",s)}},m=s=>s?new Date(s).toLocaleString("vi-VN"):"",O=async()=>{var s,a;if(!T){A(!0);try{const r=await y.post("/recharge-events/claim-first-recharge");r.data.success&&(j.custom(l=>e.jsx("div",{className:"bg-white rounded-lg shadow-lg p-4 max-w-md w-full",children:e.jsxs("div",{className:"flex items-start",children:[e.jsx("div",{className:"flex-shrink-0",children:e.jsx(J,{className:"h-6 w-6 text-green-500"})}),e.jsxs("div",{className:"ml-3 w-full",children:[e.jsx("p",{className:"text-sm font-medium text-gray-900",children:"Nhận quà thành công!"}),e.jsx("div",{className:"mt-2 space-y-1",children:r.data.data.gifts.map((c,t)=>e.jsxs("div",{className:"flex items-center text-sm text-gray-500",children:[e.jsx(_,{className:"w-4 h-4 mr-1 text-green-500"}),e.jsx("span",{children:c.name})]},t))})]})]})}),{duration:5e3}),L())}catch(r){j.error(((a=(s=r.response)==null?void 0:s.data)==null?void 0:a.message)||"Có lỗi xảy ra")}finally{A(!1)}}},z=async s=>{var a,r;if(!M){B(!0);try{const l=await y.post("/recharge-events/claim-milestone",{amount:s});l.data.success&&(j.custom(c=>e.jsx("div",{className:"bg-white rounded-lg shadow-lg p-4 max-w-md w-full",children:e.jsxs("div",{className:"flex items-start",children:[e.jsx("div",{className:"flex-shrink-0",children:e.jsx(J,{className:"h-6 w-6 text-green-500"})}),e.jsxs("div",{className:"ml-3 w-full",children:[e.jsx("p",{className:"text-sm font-medium text-gray-900",children:"Nhận quà thành công!"}),e.jsx("div",{className:"mt-2 space-y-1",children:l.data.data.gifts.map((t,v)=>e.jsxs("div",{className:"flex items-center text-sm text-gray-500",children:[e.jsx(_,{className:"w-4 h-4 mr-1 text-green-500"}),e.jsx("span",{children:t.name})]},v))})]})]})}),{duration:5e3}),L())}catch(l){j.error(((r=(a=l.response)==null?void 0:a.data)==null?void 0:r.message)||"Có lỗi xảy ra khi nhận quà")}finally{B(!1)}}},f=[{id:"first_recharge",title:"Nạp Lần Đầu",icon:oe,color:"blue",description:"Ưu đãi đặc biệt cho người chơi mới",status:h==null?void 0:h.first_recharge_status,timeRange:{start:h==null?void 0:h.first_recharge_start_time,end:h==null?void 0:h.first_recharge_end_time}},{id:"milestone",title:"Nạp Theo Mốc",icon:K,color:"amber",description:"Phần thưởng hấp dẫn theo từng mốc nạp",status:h==null?void 0:h.milestone_status,timeRange:{start:h==null?void 0:h.milestone_start_time,end:h==null?void 0:h.milestone_end_time}},{id:"multiplier_event",title:"Sự Kiện Nhân Xu",icon:$,color:"purple",description:i?`Nhận x${i.number} giá trị nạp`:"Nhân giá trị nạp",status:(i==null?void 0:i.status)===1,lastUpdated:i==null?void 0:i.updated_at}];if(Q)return e.jsx("div",{className:"re-loading",children:e.jsx("div",{className:"re-loading__spinner"})});const C=({selectedEvent:s,timeSettings:a,formatDateTime:r})=>{var l;return e.jsxs("div",{className:"re-overview",children:[e.jsxs("div",{className:"re-overview__grid",children:[e.jsxs("div",{className:"re-overview__card",children:[e.jsx("div",{className:"re-overview__label",children:"Trạng thái"}),e.jsx("div",{className:"re-overview__value",children:s.status?"Đang diễn ra":"Tạm dừng"})]}),s.id!=="multiplier_event"&&e.jsxs("div",{className:"re-overview__card",children:[e.jsx("div",{className:"re-overview__label",children:"Thời gian còn lại"}),e.jsx("div",{className:"re-overview__value",children:r((l=s.timeRange)==null?void 0:l.end)})]})]}),s.id==="multiplier_event"&&e.jsxs("div",{className:"re-overview__multiplier",children:[e.jsxs("div",{className:"re-overview__multiplier-header",children:[e.jsx("span",{className:"re-overview__multiplier-label",children:"Hệ số nhân hiện tại"}),e.jsxs("span",{className:"re-overview__multiplier-value",children:["x",(a==null?void 0:a.number)||1]})]}),e.jsxs("div",{className:"mt-2 text-sm text-purple-600 flex items-center",children:[e.jsx($,{className:"w-4 h-4 mr-1"}),"Cập nhật: ",r(a==null?void 0:a.created_at)]}),e.jsx("div",{className:"mt-1 text-sm text-purple-600",children:(a==null?void 0:a.status)===1?e.jsxs("span",{className:"flex items-center",children:[e.jsx(_,{className:"w-4 h-4 mr-1"}),"Sự kiện đang diễn ra"]}):e.jsxs("span",{className:"flex items-center",children:[e.jsx(ae,{className:"w-4 h-4 mr-1"}),"Sự kiện tạm dừng"]})})]})]})},U=({selectedEvent:s,eventConfig:a,userStatus:r,timeSettings:l,handleClaimFirstRecharge:c,handleClaimMilestone:t,claimingFirstRecharge:v,claimingMilestone:Z})=>{var S;const ne=w=>Math.floor(w/1e3);return e.jsxs("div",{className:"re-rewards",children:[s.id==="first_recharge"&&(a==null?void 0:a.first_recharge_gifts)&&e.jsxs("div",{className:"re-reward-card",children:[e.jsxs("div",{className:"re-reward-header",children:[e.jsx("span",{className:"re-reward-title",children:"Quà nạp lần đầu (tối thiểu 10 xu)"}),e.jsx("button",{onClick:c,disabled:v||!(r!=null&&r.has_first_recharge)||(r==null?void 0:r.has_claimed_first_recharge),className:`re-reward-button ${r!=null&&r.has_claimed_first_recharge?"re-reward-button--disabled":"re-reward-button--active"}`,children:v?e.jsxs(e.Fragment,{children:[e.jsx(ce,{className:"w-4 h-4 animate-spin"}),e.jsx("span",{children:"Đang xử lý..."})]}):r!=null&&r.has_claimed_first_recharge?"Đã nhận":r!=null&&r.has_first_recharge?"Nhận quà":"Chưa đủ điều kiện"})]}),e.jsx("div",{className:"re-reward-content",children:e.jsx("div",{className:"re-reward-grid",children:a.first_recharge_gifts.map((w,G)=>e.jsxs("div",{className:"re-reward-item",children:[e.jsx("img",{src:w.image,alt:w.name,className:"re-reward-image"}),e.jsx("div",{className:"re-reward-info",children:e.jsx("div",{className:"re-reward-name",children:w.name})})]},G))})})]}),s.id==="milestone"&&((S=a==null?void 0:a.milestone_gifts)==null?void 0:S.map((w,G)=>{var ee,se,re;const q=w.amount,P=ne(q);return e.jsxs("div",{className:"re-reward-card",children:[e.jsxs("div",{className:"re-reward-header",children:[e.jsxs("span",{className:"re-reward-title",children:["Mốc ",P.toLocaleString()," xu",e.jsxs("span",{className:"text-xs text-gray-500 ml-1",children:["(",q.toLocaleString(),"đ)"]})]}),e.jsx("button",{onClick:()=>t(q),disabled:((ee=r==null?void 0:r.claimed_milestones)==null?void 0:ee.includes(q))||Z||(r==null?void 0:r.total_recharge)<P,className:`re-reward-button ${(se=r==null?void 0:r.claimed_milestones)!=null&&se.includes(q)||(r==null?void 0:r.total_recharge)<P?"re-reward-button--disabled":"re-reward-button--active"}`,children:Z?e.jsxs("div",{className:"flex items-center",children:[e.jsx(ce,{className:"w-4 h-4 animate-spin mr-2"}),e.jsx("span",{children:"Đang xử lý..."})]}):(re=r==null?void 0:r.claimed_milestones)!=null&&re.includes(q)?e.jsxs("span",{className:"flex items-center",children:[e.jsx(_,{className:"w-4 h-4 mr-1"}),"Đã nhận"]}):(r==null?void 0:r.total_recharge)<P?"Chưa đủ điều kiện":"Nhận quà"})]}),e.jsx("div",{className:"re-reward-content",children:e.jsx("div",{className:"re-reward-grid",children:w.products.map((W,ie)=>e.jsxs("div",{className:"re-reward-item",children:[e.jsx("img",{src:W.image,alt:W.name,className:"re-reward-image"}),e.jsx("div",{className:"re-reward-info",children:e.jsx("div",{className:"re-reward-name",children:W.name})})]},ie))})})]},G)})),s.id==="multiplier_event"&&e.jsx("div",{className:`re-multiplier-reward ${(l==null?void 0:l.status)===0?"opacity-50":""}`,children:e.jsxs("div",{className:"text-center space-y-4",children:[e.jsxs("div",{className:"text-4xl font-bold text-purple-600",children:["x",(l==null?void 0:l.number)||1]}),e.jsx("div",{className:"text-purple-700",children:(l==null?void 0:l.status)===0?"Tất cả giao dịch nạp sẽ được nhân với hệ số này":"Sự kiện đang tạm dừng"}),e.jsx("div",{className:"text-sm text-purple-600",children:(l==null?void 0:l.status)===1?"Áp dụng tự động cho mọi giao dịch":"Vui lòng đợi đến khi sự kiện được kích hoạt"})]})})]})},d=({selectedEvent:s,timeSettings:a,formatDateTime:r})=>e.jsxs("div",{className:"re-rules",children:[s.id==="first_recharge"&&e.jsxs(e.Fragment,{children:[e.jsxs("div",{className:"re-rules-section re-rules-section--blue",children:[e.jsx("h4",{className:"re-rules-title re-rules-title--blue",children:"Điều kiện nhận quà"}),e.jsxs("ul",{className:"re-rules-list",children:[e.jsxs("li",{className:"re-rules-item",children:[e.jsx(_,{className:"re-rules-icon"}),e.jsx("span",{className:"re-rules-text re-rules-text--blue",children:"Nạp tối thiểu 10.000đ trong lần nạp đầu tiên"})]}),e.jsxs("li",{className:"re-rules-item",children:[e.jsx(_,{className:"re-rules-icon"}),e.jsx("span",{className:"re-rules-text re-rules-text--blue",children:"Chưa từng nhận quà nạp lần đầu trước đây"})]}),e.jsxs("li",{className:"re-rules-item",children:[e.jsx(_,{className:"re-rules-icon"}),e.jsx("span",{className:"re-rules-text re-rules-text--blue",children:"Nạp trong thời gian diễn ra sự kiện"})]})]})]}),e.jsxs("div",{className:"re-rules-section re-rules-section--amber",children:[e.jsx("h4",{className:"re-rules-title re-rules-title--amber",children:"Lưu ý quan trọng"}),e.jsxs("ul",{className:"re-rules-list",children:[e.jsxs("li",{className:"re-rules-item",children:[e.jsx(E,{className:"re-rules-icon"}),e.jsx("span",{className:"re-rules-text re-rules-text--amber",children:"Quà chỉ được nhận một lần duy nhất"})]}),e.jsxs("li",{className:"re-rules-item",children:[e.jsx(E,{className:"re-rules-icon"}),e.jsx("span",{className:"re-rules-text re-rules-text--amber",children:"Vui lòng kiểm tra kỹ điều kiện trước khi nạp"})]})]})]})]}),s.id==="milestone"&&e.jsxs(e.Fragment,{children:[e.jsxs("div",{className:"re-rules-section re-rules-section--blue",children:[e.jsx("h4",{className:"re-rules-title re-rules-title--blue",children:"Điều kiện nhận quà"}),e.jsxs("ul",{className:"re-rules-list",children:[e.jsxs("li",{className:"re-rules-item",children:[e.jsx(_,{className:"re-rules-icon"}),e.jsx("span",{className:"re-rules-text re-rules-text--blue",children:"Đạt đủ số tiền nạp theo từng mốc"})]}),e.jsxs("li",{className:"re-rules-item",children:[e.jsx(_,{className:"re-rules-icon"}),e.jsx("span",{className:"re-rules-text re-rules-text--blue",children:"Tổng nạp được tính trong thời gian sự kiện"})]}),e.jsxs("li",{className:"re-rules-item",children:[e.jsx(_,{className:"re-rules-icon"}),e.jsx("span",{className:"re-rules-text re-rules-text--blue",children:"Có thể nhận nhiều mốc cùng lúc nếu đủ điều kiện"})]})]})]}),e.jsxs("div",{className:"re-rules-section re-rules-section--amber",children:[e.jsx("h4",{className:"re-rules-title re-rules-title--amber",children:"Lưu ý quan trọng"}),e.jsxs("ul",{className:"re-rules-list",children:[e.jsxs("li",{className:"re-rules-item",children:[e.jsx(E,{className:"re-rules-icon"}),e.jsx("span",{className:"re-rules-text re-rules-text--amber",children:"Mỗi mốc chỉ được nhận một lần trong sự kiện"})]}),e.jsxs("li",{className:"re-rules-item",children:[e.jsx(E,{className:"re-rules-icon"}),e.jsx("span",{className:"re-rules-text re-rules-text--amber",children:"Hãy nhận quà ngay khi đạt đủ điều kiện"})]})]})]})]}),s.id==="multiplier_event"&&e.jsxs(e.Fragment,{children:[e.jsxs("div",{className:"re-rules-section re-rules-section--blue",children:[e.jsx("h4",{className:"re-rules-title re-rules-title--blue",children:"Cách thức hoạt động"}),e.jsxs("ul",{className:"re-rules-list",children:[e.jsxs("li",{className:"re-rules-item",children:[e.jsx(_,{className:"re-rules-icon"}),e.jsxs("span",{className:"re-rules-text re-rules-text--blue",children:["Nạp trong thời gian sự kiện nhận x",(a==null?void 0:a.number)||1," giá trị"]})]}),e.jsxs("li",{className:"re-rules-item",children:[e.jsx(_,{className:"re-rules-icon"}),e.jsx("span",{className:"re-rules-text re-rules-text--blue",children:"Áp dụng tự động cho tất cả giao dịch nạp"})]})]})]}),e.jsxs("div",{className:"re-rules-section re-rules-section--amber",children:[e.jsx("h4",{className:"re-rules-title re-rules-title--amber",children:"Thông tin sự kiện"}),e.jsxs("div",{className:"re-rules-list",children:[e.jsxs("div",{className:"re-rules-item",children:[e.jsx($,{className:"re-rules-icon"}),e.jsxs("span",{className:"re-rules-text re-rules-text--amber",children:["Cập nhật lần cuối: ",r(a==null?void 0:a.updated_at)]})]}),e.jsxs("div",{className:"re-rules-item",children:[e.jsx(E,{className:"re-rules-icon"}),e.jsxs("span",{className:"re-rules-text re-rules-text--amber",children:["Trạng thái: ",(a==null?void 0:a.status)===1?"Đang hoạt động":"Tạm dừng"]})]})]})]})]})]});return e.jsxs("div",{className:"re-container",children:[e.jsx("div",{className:"re-stats",children:e.jsxs("div",{className:"re-stats__grid",children:[e.jsxs("div",{className:"re-stats__item",children:[e.jsx("span",{className:"re-stats__label",children:"Tổng nạp"}),e.jsxs("span",{className:"re-stats__value",children:[(V=g==null?void 0:g.total_recharge)==null?void 0:V.toLocaleString(),"đ"]})]}),e.jsxs("div",{className:"re-stats__item",children:[e.jsx("span",{className:"re-stats__label",children:"Sự kiện đang diễn ra"}),e.jsx("span",{className:"re-stats__value",children:f.filter(s=>s.status).length})]}),e.jsxs("div",{className:"re-stats__item",children:[e.jsx("span",{className:"re-stats__label",children:"Quà chưa nhận"}),e.jsx("span",{className:"re-stats__value",children:f.filter(s=>{var a;return s.status&&!((a=g==null?void 0:g.claimed_milestones)!=null&&a.includes(s.id))}).length})]})]})}),e.jsx("div",{className:"re-events",children:f.map(s=>e.jsxs("div",{className:`re-event ${s.status?"re-event--active":"re-event--inactive"}`,onClick:()=>s.status&&k(s),children:[e.jsx("div",{className:"re-event__badge",children:e.jsx("div",{className:`re-event__status re-event__status--${s.id}`,children:s.id==="multiplier_event"?(i==null?void 0:i.status)===1?"Đang diễn ra":"Chưa diễn ra":"Đang diễn ra"})}),e.jsxs("div",{className:"re-event__content",children:[e.jsxs("div",{className:"re-event__header",children:[e.jsx(s.icon,{className:`re-event__icon re-event__icon--${s.color}`}),e.jsxs("div",{children:[e.jsx("h3",{className:"re-event__title",children:s.title}),e.jsx("p",{className:"re-event__description",children:s.description})]})]}),s.id!=="multiplier_event"&&s.timeRange&&e.jsxs("div",{className:"re-event__time",children:[e.jsxs("div",{className:"re-event__time-item",children:[e.jsx(D,{className:"re-event__time-icon"}),e.jsxs("span",{children:["Từ: ",m(s.timeRange.start)]})]}),e.jsxs("div",{className:"re-event__time-item",children:[e.jsx(D,{className:"re-event__time-icon"}),e.jsxs("span",{children:["Đến: ",m(s.timeRange.end)]})]})]}),s.id==="multiplier_event"&&e.jsxs("div",{className:"re-event__update",children:[e.jsx($,{className:"re-event__update-icon"}),e.jsxs("span",{children:["Cập nhật: ",m(s.lastUpdated)]})]}),e.jsxs("button",{className:`re-event__button re-event__button--${s.color}`,disabled:!s.status,children:[e.jsx(me,{className:"re-event__button-icon"}),e.jsx("span",{children:"Chi tiết"})]})]})]},s.id))}),u&&te.createPortal(e.jsxs("div",{className:"re-modal",children:[e.jsx("div",{className:"re-modal__overlay",onClick:()=>k(null)}),e.jsxs("div",{className:"re-modal__container",children:[e.jsxs("div",{className:"re-modal__header",children:[e.jsx("button",{onClick:()=>k(null),className:"re-modal__close",children:e.jsx(ae,{})}),e.jsxs("div",{className:"re-modal__title",children:[e.jsx(u.icon,{className:"re-modal__title-icon"}),e.jsxs("div",{children:[e.jsx("h2",{className:"re-modal__title-text",children:u.title}),e.jsx("p",{className:"re-modal__title-desc",children:u.description})]})]})]}),e.jsxs("div",{className:"re-modal__content",children:[e.jsx("div",{className:"re-tabs",children:["overview","rewards","rules"].map(s=>e.jsxs("button",{onClick:()=>R(s),className:`re-tabs__button ${p===s?"re-tabs__button--active":""}`,children:[s==="overview"&&"Tổng quan",s==="rewards"&&"Phần thưởng",s==="rules"&&"Điều kiện"]},s))}),e.jsxs("div",{className:"re-tab-content",children:[p==="overview"&&e.jsx(C,{selectedEvent:u,timeSettings:i,formatDateTime:m}),p==="rewards"&&e.jsx(U,{selectedEvent:u,eventConfig:h,userStatus:g,timeSettings:i,handleClaimFirstRecharge:O,handleClaimMilestone:z,claimingFirstRecharge:T,claimingMilestone:M}),p==="rules"&&e.jsx(d,{selectedEvent:u,timeSettings:i,formatDateTime:m})]})]}),e.jsxs("div",{className:"re-modal__footer",children:[e.jsx("div",{className:"re-modal__footer-info",children:u.id==="multiplier_event"?e.jsxs(e.Fragment,{children:[e.jsx($,{className:"re-modal__footer-icon"}),"Cập nhật: ",m(i==null?void 0:i.updated_at)]}):e.jsxs(e.Fragment,{children:[e.jsx(D,{className:"re-modal__footer-icon"}),"Kết thúc: ",m((F=u.timeRange)==null?void 0:F.end)]})}),e.jsx("button",{onClick:()=>k(null),className:"re-modal__footer-button",children:"Đóng"})]})]})]}),document.getElementById("popup-root"))]})};const Te=({setActiveTab:h})=>{const[N,g]=n.useState(null),[x,i]=n.useState(""),[I,Q]=n.useState(""),[b,u]=n.useState(!1),[k,T]=n.useState(600),[A,M]=n.useState(0),[B,p]=n.useState(!1),[R,X]=n.useState("qr"),[o,L]=n.useState(null),[m,O]=n.useState(null),z=1e3,f=n.useRef(null),C=n.useRef(null),U=he(),{user:d}=de(),V=[{value:5e4,hot:!1},{value:1e5,hot:!1},{value:2e5,hot:!1},{value:5e5,hot:!0},{value:1e6,hot:!0},{value:2e6,special:!0}];n.useEffect(()=>{F(),d!=null&&d.coin&&M(d.coin)},[d]),n.useEffect(()=>{if(!d)return;if(!localStorage.getItem("token")){j.error("Vui lòng đăng nhập lại");return}if(window.Echo){const t=window.Echo.private(`user.${d.id}`);t.subscribed(()=>{console.log("Subscribed to channel")}).error(v=>{v.type==="AuthError"&&j.error("Phiên đăng nhập hết hạn, vui lòng đăng nhập lại")}),t.listen(".NewTransactionEvent",v=>{j.success(`Nạp thành công: ${v.amount.toLocaleString()}đ!`),M(v.currentCoins),r(),p(!0),setTimeout(()=>p(!1),1e4)})}return()=>{window.Echo&&window.Echo.leave(`user.${d.id}`)}},[d]),n.useEffect(()=>(b&&(f.current=setInterval(()=>{T(c=>c<=1?(clearInterval(f.current),r(),j.error("Hết thời gian xử lý giao dịch! Vui lòng thử lại sau."),600):c-1)},1e3)),()=>clearInterval(f.current)),[b]);const F=async()=>{try{const c=await y.get("/time-settings");if(c.data.success){const t=c.data.data;g(t.number),L(t)}}catch(c){console.error("Error fetching multiplier:",c)}},s=()=>{if(!x||x<1e3){j.error("Vui lòng nhập số tiền cần nạp (tối thiểu 1,000 VND)");return}C.current.readOnly=!0,u(!0),a(x)},a=async c=>{try{const t=await y.post("/generate-qr-code",{amount:c});t.data.success?(Q(t.data.qrCode),O(t.data.bankInfo)):(j.error("Không thể tạo mã QR. Vui lòng thử lại sau."),r())}catch(t){console.error("QR code error:",t),j.error("Có lỗi xảy ra. Vui lòng thử lại sau."),r()}},r=()=>{i(""),C.current&&(C.current.readOnly=!1),u(!1),Q(""),T(600),clearInterval(f.current)},l=c=>{if(!c)return 0;const t=Math.floor(parseInt(c)/z);return(o==null?void 0:o.status)===1&&N>1?Math.floor(parseInt(c)*N/1e3):t};return e.jsxs("div",{className:"recharge-container",children:[e.jsx("div",{className:"recharge-header",children:e.jsxs("div",{className:"recharge-header__content",children:[e.jsxs("div",{className:"recharge-header__info",children:[e.jsxs("h1",{className:"recharge-header__title",children:[e.jsx(xe,{className:"recharge-header__icon"}),"Nạp Xu"]}),e.jsx("p",{className:"recharge-header__subtitle",children:"Nạp xu nhanh chóng và an toàn"})]}),e.jsxs("div",{className:"recharge-header__balance",children:[e.jsx("div",{className:"recharge-header__amount",children:parseInt(A).toLocaleString()}),e.jsx("div",{className:"recharge-header__label",children:"Xu hiện có"})]})]})}),e.jsxs("div",{className:"recharge-content",children:[e.jsxs("div",{className:"recharge-methods",children:[e.jsx("div",{className:"recharge-methods__header",children:e.jsxs("h2",{className:"recharge-methods__title",children:[e.jsx(ue,{className:"recharge-methods__icon"}),"Chọn Phương Thức Thanh Toán"]})}),e.jsxs("div",{className:"recharge-methods__body",children:[e.jsx("div",{className:"recharge-methods__options",children:["qr","bank"].map(c=>e.jsx("button",{onClick:()=>X(c),className:`recharge-method ${R===c?"recharge-method--active":""}`,children:e.jsx("div",{className:"recharge-method__content",children:c==="qr"?e.jsxs(e.Fragment,{children:[e.jsx(le,{className:"recharge-method__icon"}),e.jsx("span",{className:"recharge-method__label",children:"Quét Mã QR"})]}):e.jsxs(e.Fragment,{children:[e.jsx(je,{className:"recharge-method__icon"}),e.jsx("span",{className:"recharge-method__label",children:"Chuyển Khoản"})]})})},c))}),e.jsx("div",{className:"recharge-rate",children:e.jsxs("div",{className:"recharge-rate__content",children:[e.jsx("span",{className:"recharge-rate__label",children:"Tỷ lệ quy đổi cơ bản:"}),e.jsx("span",{className:"recharge-rate__value",children:"100.000đ = 100 xu (1.000đ = 1 xu)"}),(o==null?void 0:o.status)===1&&N>1&&e.jsxs("div",{className:"recharge-rate__event",children:[e.jsx(H,{className:"recharge-rate__event-icon"}),e.jsxs("span",{children:["Đang có sự kiện nhân x",N," giá trị nạp"]})]})]})}),e.jsx("div",{className:"recharge-amounts",children:V.map(c=>e.jsxs("button",{onClick:()=>i(c.value.toString()),className:"recharge-amount",children:[e.jsxs("div",{className:"recharge-amount__value",children:[c.value.toLocaleString(),"đ"]}),c.hot&&e.jsx("div",{className:"recharge-amount__badge",children:"HOT"}),c.special&&e.jsx("div",{className:"recharge-amount__special",children:e.jsx(H,{className:"recharge-amount__special-icon"})})]},c.value))}),e.jsxs("div",{className:"recharge-custom",children:[e.jsx("label",{className:"recharge-custom__label",children:"Hoặc nhập số tiền khác"}),e.jsxs("div",{className:"recharge-custom__input-wrapper",children:[e.jsx("input",{type:"number",ref:C,value:x,onChange:c=>i(c.target.value),className:"recharge-custom__input",placeholder:"Nhập số tiền muốn nạp",min:"10000",step:"10000",disabled:b}),e.jsx("div",{className:"recharge-custom__currency",children:"VND"})]}),x&&e.jsxs("div",{className:"recharge-custom__preview",children:[e.jsxs("div",{className:"recharge-custom__xu",children:["Bạn sẽ nhận được: ",l(x).toLocaleString()," xu"]}),(o==null?void 0:o.status)===1&&N>1&&e.jsxs("div",{className:"recharge-custom__bonus",children:[e.jsx(H,{className:"recharge-custom__bonus-icon"}),"Đang có sự kiện nhân x",N," giá trị nạp"]})]})]}),R==="qr"&&e.jsx("button",{onClick:b?r:s,className:`recharge-action ${b?"recharge-action--cancel":""}`,children:b?"Hủy giao dịch":"Tạo mã QR"})]})]}),e.jsx("div",{className:"recharge-details",children:R==="qr"?e.jsx("div",{className:"recharge-qr",children:I?e.jsxs("div",{className:"recharge-qr__content",children:[e.jsx("img",{src:I,alt:"QR Code Thanh Toán",className:"recharge-qr__image"}),e.jsxs("div",{className:"recharge-qr__info",children:[e.jsxs("p",{className:"recharge-qr__amount",children:["Số tiền: ",e.jsxs("strong",{children:[parseInt(x).toLocaleString()," VND"]})]}),e.jsxs("p",{className:"recharge-qr__coins",children:["Xu nhận được: ",e.jsxs("strong",{children:[l(x).toLocaleString()," xu"]})]}),e.jsxs("p",{className:"recharge-qr__bank",children:["Ngân hàng: ",e.jsx("strong",{children:"MB Bank"})]}),e.jsxs("p",{className:"recharge-qr__account",children:["Số tài khoản: ",e.jsx("strong",{children:(m==null?void 0:m.accountNumber)||"90511239999"})]}),e.jsxs("p",{className:"recharge-qr__description",children:["Nội dung CK: ",e.jsx("strong",{children:(m==null?void 0:m.description)||`CLTB9D${d==null?void 0:d.id}`})]}),e.jsx("p",{className:"recharge-qr__instruction",children:"Quét mã QR bằng ứng dụng ngân hàng để thanh toán"})]}),e.jsxs("div",{className:"recharge-qr__timer",children:[e.jsxs("div",{className:"recharge-qr__countdown",children:[e.jsx(D,{className:"recharge-qr__timer-icon"}),"Thời gian còn lại: ",Math.floor(k/60),":",(k%60).toString().padStart(2,"0")]}),e.jsx("div",{className:"recharge-qr__progress",children:e.jsx("div",{className:"recharge-qr__progress-bar",style:{width:`${k/600*100}%`}})})]})]}):e.jsxs("div",{className:"recharge-qr__placeholder",children:[e.jsx(le,{className:"recharge-qr__placeholder-icon"}),e.jsx("p",{className:"recharge-qr__placeholder-text",children:"Nhập số tiền và nhấn tạo mã QR để hiển thị mã thanh toán"})]})}):e.jsxs("div",{className:"recharge-bank",children:[e.jsxs("div",{className:"recharge-bank__info",children:[e.jsx("h3",{className:"recharge-bank__title",children:"Thông tin chuyển khoản"}),e.jsx("div",{className:"recharge-bank__details",children:[{label:"Ngân hàng",value:"MB Bank"},{label:"Số tài khoản",value:"90511239999",highlight:!0},{label:"Chủ tài khoản",value:"LE VIET ANH"},{label:"Nội dung CK",value:`CLTB9D${d==null?void 0:d.id}`,highlight:!0}].map((c,t)=>e.jsxs("div",{className:"recharge-bank__row",children:[e.jsxs("span",{className:"recharge-bank__label",children:[c.label,":"]}),e.jsx("span",{className:`recharge-bank__value ${c.highlight?"recharge-bank__value--highlight":""}`,children:c.value})]},t))}),e.jsxs("div",{className:"recharge-bank__conversion-info",children:[e.jsx("div",{className:"recharge-bank__conversion-title",children:"Tỷ lệ quy đổi cơ bản:"}),e.jsx("div",{className:"recharge-bank__conversion-rate",children:"100.000đ = 100 xu (1.000đ = 1 xu)"}),(o==null?void 0:o.status)===1&&N>1&&e.jsxs("div",{className:"recharge-bank__event-rate",children:[e.jsx(H,{className:"recharge-bank__event-icon"}),"Đang có sự kiện nhân x",N," giá trị nạp"]}),x&&e.jsxs("div",{className:"recharge-bank__example",children:["Ví dụ: Nạp ",parseInt(x).toLocaleString(),"đ = ",l(x).toLocaleString()," xu"]})]})]}),e.jsxs("div",{className:"recharge-bank__notes",children:[e.jsxs("h3",{className:"recharge-bank__notes-title",children:[e.jsx(_e,{className:"recharge-bank__notes-icon"}),"Lưu ý quan trọng"]}),e.jsxs("ul",{className:"recharge-bank__notes-list",children:[e.jsxs("li",{className:"recharge-bank__note",children:[e.jsx(K,{className:"recharge-bank__note-icon"}),"Nội dung chuyển khoản phải chính xác 100%"]}),e.jsxs("li",{className:"recharge-bank__note",children:[e.jsx(K,{className:"recharge-bank__note-icon"}),"Xu sẽ được cộng tự động sau 1-3 phút"]}),e.jsxs("li",{className:"recharge-bank__note",children:[e.jsx(K,{className:"recharge-bank__note-icon"}),"Liên hệ Admin nếu cần hỗ trợ"]})]})]})]})})]}),e.jsx(Ne,{}),B&&e.jsx("div",{className:"recharge-prompt",children:e.jsxs("div",{className:"recharge-prompt__content",children:[e.jsx("div",{className:"recharge-prompt__icon-wrapper",children:e.jsx(J,{className:"recharge-prompt__icon"})}),e.jsxs("div",{className:"recharge-prompt__text",children:[e.jsx("h3",{className:"recharge-prompt__title",children:"Nạp xu thành công!"}),e.jsx("div",{className:"recharge-prompt__description",children:"Ghé thăm Kỳ Trân Các để khám phá các vật phẩm giá trị nhé!"}),e.jsxs("div",{className:"recharge-prompt__actions",children:[e.jsx("button",{onClick:()=>U("/ky-tran-cac"),className:"recharge-prompt__button recharge-prompt__button--primary",children:"Đến Kỳ Trân Các"}),e.jsx("button",{onClick:()=>p(!1),className:"recharge-prompt__button recharge-prompt__button--secondary",children:"Để sau"})]})]}),e.jsx("button",{onClick:()=>p(!1),className:"recharge-prompt__close",children:e.jsx("svg",{className:"h-5 w-5 text-gray-400",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24",children:e.jsx("path",{strokeLinecap:"round",strokeLinejoin:"round",strokeWidth:"2",d:"M6 18L18 6M6 6l12 12"})})})]})})]})};export{Te as default};
