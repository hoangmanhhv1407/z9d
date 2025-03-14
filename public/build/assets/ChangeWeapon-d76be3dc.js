import{c as W,u as E,r as n,x as v,h as t,j as e,R as N,l as V}from"./app-510cf451.js";import{S as y}from"./swords-47031b54.js";import{S as F}from"./UserProfile-c93d18b3.js";import"./crown-a686e901.js";import"./coins-5a2b9d22.js";import"./gift-afa5557a.js";/**
 * @license lucide-react v0.453.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const A=W("ChevronDown",[["path",{d:"m6 9 6 6 6-6",key:"qrunsl"}]]);/**
 * @license lucide-react v0.453.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const R=W("ChevronUp",[["path",{d:"m18 15-6-6-6 6",key:"153udz"}]]);const z=()=>{E();const[g,_]=n.useState([]),[i,f]=n.useState(""),[r,o]=n.useState([]),[d,w]=n.useState(null),[C,m]=n.useState(!1),[p,b]=n.useState(!1),[x,k]=n.useState(!1),[l,j]=n.useState(null);n.useEffect(()=>{q()},[]),n.useEffect(()=>{i&&S(i)},[i]);const q=async()=>{try{m(!0);const s=await v.getUserCharacters();s.data.success?(_(s.data.data),s.data.data.length>0&&f(s.data.data[0].unique_id)):t.error(s.data.message||"Không thể tải danh sách nhân vật")}catch(s){console.error("Error fetching characters:",s),t.error("Không thể tải danh sách nhân vật")}finally{m(!1)}},S=async s=>{try{m(!0);const a=await v.getCharacterWeapons(s);a.data.success?o(a.data.data.weapons||[]):(t.error(a.data.message||"Không thể tải danh sách vũ khí"),o([]))}catch(a){console.error("Error fetching weapons:",a),t.error("Không thể tải danh sách vũ khí"),o([])}finally{m(!1)}},K=s=>{w(s),j(s)},T=s=>{f(s.target.value),w(null),j(null)},D=async()=>{var s,a;if(!i||!d){t.error("Vui lòng chọn nhân vật và vũ khí");return}try{b(!0);const c=await v.changeWeapon({character_id:i,weapon_id:d.id});c.data.success?(t.success("Thay đổi vũ khí thành công!"),S(i),w(null),j(null)):t.error(c.data.message||"Thay đổi vũ khí thất bại")}catch(c){console.error("Error changing weapon:",c),t.error(((a=(s=c.response)==null?void 0:s.data)==null?void 0:a.message)||"Có lỗi xảy ra khi thay đổi vũ khí")}finally{b(!1)}},u=s=>{if(!r||r.length===0)return;let a=[...r];switch(s){case"level":a.sort((c,h)=>h.level-c.level);break;case"damage":a.sort((c,h)=>h.damage-c.damage);break;case"name":a.sort((c,h)=>c.name.localeCompare(h.name));break}o(a),k(!1)};return e.jsxs("div",{className:"cw-container",children:[e.jsxs("div",{className:"cw-header",children:[e.jsx(y,{className:"cw-header__icon"}),e.jsx("h4",{className:"cw-header__title",children:"Thay Đổi Vũ Khí"})]}),e.jsxs("div",{className:"cw-content",children:[e.jsxs("div",{className:"cw-character-select",children:[e.jsx("label",{htmlFor:"character",className:"cw-label",children:"Chọn nhân vật:"}),e.jsx("div",{className:"cw-select-wrapper",children:C?e.jsxs("div",{className:"cw-loading-select",children:[e.jsx(N,{className:"cw-loading-icon"}),e.jsx("span",{children:"Đang tải..."})]}):e.jsx("select",{id:"character",value:i,onChange:T,className:"cw-select",disabled:p,children:g.length===0?e.jsx("option",{value:"",children:"Không có nhân vật"}):g.map(s=>e.jsxs("option",{value:s.unique_id,children:[s.chr_name," (Cấp ",s.inner_level,")"]},s.unique_id))})})]}),e.jsxs("div",{className:"cw-main-grid",children:[e.jsxs("div",{className:"cw-weapons-list",children:[e.jsxs("div",{className:"cw-weapons-header",children:[e.jsx("h4",{className:"cw-weapons-title",children:"Danh Sách Vũ Khí"}),e.jsxs("div",{className:"cw-weapons-filter",onClick:()=>k(!x),children:[e.jsx("span",{children:"Sắp xếp"}),x?e.jsx(R,{className:"cw-dropdown-icon"}):e.jsx(A,{className:"cw-dropdown-icon"}),x&&e.jsxs("div",{className:"cw-dropdown-menu",children:[e.jsx("button",{className:"cw-dropdown-item",onClick:()=>u("level"),children:"Theo cấp độ"}),e.jsx("button",{className:"cw-dropdown-item",onClick:()=>u("damage"),children:"Theo sát thương"}),e.jsx("button",{className:"cw-dropdown-item",onClick:()=>u("name"),children:"Theo tên"})]})]})]}),C?e.jsxs("div",{className:"cw-loading",children:[e.jsx(N,{className:"cw-loading-icon"}),e.jsx("span",{children:"Đang tải danh sách vũ khí..."})]}):e.jsx("div",{className:"cw-weapons-grid",children:r.length===0?e.jsxs("div",{className:"cw-no-weapons",children:[e.jsx(V,{className:"cw-no-weapons-icon"}),e.jsx("span",{children:"Không có vũ khí nào"})]}):r.map(s=>e.jsxs("div",{className:`cw-weapon-item ${(d==null?void 0:d.id)===s.id?"cw-weapon-selected":""}`,onClick:()=>K(s),children:[e.jsx("div",{className:"cw-weapon-image",children:e.jsx("img",{src:s.image,alt:s.name})}),e.jsxs("div",{className:"cw-weapon-info",children:[e.jsx("h5",{className:"cw-weapon-name",children:s.name}),e.jsxs("div",{className:"cw-weapon-stats",children:[e.jsxs("span",{className:"cw-weapon-level",children:["Cấp ",s.level]}),e.jsxs("span",{className:"cw-weapon-damage",children:["DMG: ",s.damage]})]})]})]},s.id))})]}),e.jsx("div",{className:"cw-weapon-details",children:l?e.jsxs(e.Fragment,{children:[e.jsx("div",{className:"cw-detail-header",children:e.jsx("h4",{className:"cw-detail-title",children:l.name})}),e.jsxs("div",{className:"cw-detail-content",children:[e.jsx("div",{className:"cw-detail-image",children:e.jsx("img",{src:l.image,alt:l.name})}),e.jsxs("div",{className:"cw-detail-info",children:[e.jsxs("div",{className:"cw-detail-basic",children:[e.jsxs("div",{className:"cw-detail-level",children:[e.jsx("span",{className:"cw-detail-label",children:"Cấp độ:"}),e.jsx("span",{className:"cw-detail-value",children:l.level})]}),e.jsxs("div",{className:"cw-detail-damage",children:[e.jsx("span",{className:"cw-detail-label",children:"Sát thương:"}),e.jsx("span",{className:"cw-detail-value",children:l.damage})]})]}),e.jsxs("div",{className:"cw-detail-requirements",children:[e.jsx("h5",{className:"cw-detail-section-title",children:"Yêu Cầu"}),e.jsxs("div",{className:"cw-requirement-item",children:[e.jsx("span",{className:"cw-requirement-label",children:"Cấp độ tối thiểu:"}),e.jsx("span",{className:"cw-requirement-value",children:l.requirements.level})]})]}),e.jsxs("div",{className:"cw-detail-attributes",children:[e.jsx("h5",{className:"cw-detail-section-title",children:"Thuộc Tính"}),l.attributes.map((s,a)=>e.jsxs("div",{className:"cw-attribute-item",children:[e.jsxs("span",{className:"cw-attribute-label",children:[s.name,":"]}),e.jsx("span",{className:"cw-attribute-value",children:s.value})]},a))]})]})]}),e.jsx("div",{className:"cw-detail-actions",children:e.jsx("button",{className:"cw-action-button",onClick:D,disabled:p,children:p?e.jsxs(e.Fragment,{children:[e.jsx(N,{className:"cw-button-icon cw-spinning"}),e.jsx("span",{children:"Đang xử lý..."})]}):e.jsxs(e.Fragment,{children:[e.jsx(y,{className:"cw-button-icon"}),e.jsx("span",{children:"Thay Đổi Vũ Khí"})]})})})]}):e.jsxs("div",{className:"cw-no-selection",children:[e.jsx(F,{className:"cw-no-selection-icon"}),e.jsx("p",{className:"cw-no-selection-text",children:"Vui lòng chọn vũ khí để xem chi tiết"})]})})]})]})]})};export{z as default};
