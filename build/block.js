(()=>{"use strict";var e={20:(e,t,r)=>{var o=r(609),n=Symbol.for("react.element"),s=(Symbol.for("react.fragment"),Object.prototype.hasOwnProperty),i=o.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,a={key:!0,ref:!0,__self:!0,__source:!0};function c(e,t,r){var o,c={},p=null,l=null;for(o in void 0!==r&&(p=""+r),void 0!==t.key&&(p=""+t.key),void 0!==t.ref&&(l=t.ref),t)s.call(t,o)&&!a.hasOwnProperty(o)&&(c[o]=t[o]);if(e&&e.defaultProps)for(o in t=e.defaultProps)void 0===c[o]&&(c[o]=t[o]);return{$$typeof:n,type:e,key:p,ref:l,props:c,_owner:i.current}}t.jsx=c,t.jsxs=c},848:(e,t,r)=>{e.exports=r(20)},609:e=>{e.exports=window.React}},t={};const r=window.wp.i18n,o=window.wc.wcBlocksRegistry,n=window.wp.htmlEntities,s=window.wc.wcSettings;var i=function r(o){var n=t[o];if(void 0!==n)return n.exports;var s=t[o]={exports:{}};return e[o](s,s.exports,r),s.exports}(848);const a=(0,s.getSetting)("ankapay_data",{}),c=(0,r.__)("ANKA Pay","anka-commerce"),p=(0,n.decodeEntities)(a.title)||c,l=()=>(0,n.decodeEntities)(a.description||""),d=()=>a.icon?(0,i.jsx)("img",{src:esc_url(a.icon),style:{float:"right"}}):"",w=()=>(0,i.jsxs)("span",{style:{width:"100%"},children:[p,(0,i.jsx)(d,{})]}),_={name:"ankapay",label:(0,i.jsx)(w,{}),content:(0,i.jsx)(l,{}),edit:(0,i.jsx)(l,{}),icon:(0,i.jsx)(d,{}),canMakePayment:()=>!0,ariaLabel:p,supports:{features:a.supports}};(0,o.registerPaymentMethod)(_)})();