require('../../common/vendor.js');(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/goods/lottery/components/noticeBar"],{"0331":function(t,n,e){"use strict";Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var i={name:"noticeBar",data:function(){return{animateUp:!1,listData:JSON.parse(JSON.stringify(this.showMsg)),timer:null}},props:{showMsg:{type:Array},prefix:{type:String}},mounted:function(){this.timer=setInterval(this.scrollAnimate,2500)},methods:{scrollAnimate:function(){var t=this;this.animateUp=!0,setTimeout((function(){t.listData.push(t.listData[0]),t.listData.shift(),t.animateUp=!1}),500)}},destroyed:function(){clearInterval(this.timer)}};n.default=i},"1dc7":function(t,n,e){},"230f":function(t,n,e){"use strict";e.r(n);var i=e("0331"),a=e.n(i);for(var r in i)["default"].indexOf(r)<0&&function(t){e.d(n,t,(function(){return i[t]}))}(r);n["default"]=a.a},6475:function(t,n,e){"use strict";e.d(n,"b",(function(){return i})),e.d(n,"c",(function(){return a})),e.d(n,"a",(function(){}));var i=function(){var t=this.$createElement;this._self._c},a=[]},cf05:function(t,n,e){"use strict";e.r(n);var i=e("6475"),a=e("230f");for(var r in a)["default"].indexOf(r)<0&&function(t){e.d(n,t,(function(){return a[t]}))}(r);e("d8f2");var o=e("828b"),s=Object(o["a"])(a["default"],i["b"],i["c"],!1,null,"c4ca2592",null,!1,i["a"],void 0);n["default"]=s.exports},d8f2:function(t,n,e){"use strict";var i=e("1dc7"),a=e.n(i);a.a}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'pages/goods/lottery/components/noticeBar-create-component',
    {
        'pages/goods/lottery/components/noticeBar-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('df3c')['createComponent'](__webpack_require__("cf05"))
        })
    },
    [['pages/goods/lottery/components/noticeBar-create-component']]
]);
