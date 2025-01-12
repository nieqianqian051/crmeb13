(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["components/recommend/index"],{"5ac3":function(t,n,e){"use strict";e.r(n);var o=e("9c84"),a=e.n(o);for(var u in o)["default"].indexOf(u)<0&&function(t){e.d(n,t,(function(){return o[t]}))}(u);n["default"]=a.a},6259:function(t,n,e){"use strict";e.d(n,"b",(function(){return o})),e.d(n,"c",(function(){return a})),e.d(n,"a",(function(){}));var o=function(){var t=this.$createElement;this._self._c},a=[]},"7bc6":function(t,n,e){"use strict";e.r(n);var o=e("6259"),a=e("5ac3");for(var u in a)["default"].indexOf(u)<0&&function(t){e.d(n,t,(function(){return a[t]}))}(u);var c=e("828b"),i=Object(c["a"])(a["default"],o["b"],o["c"],!1,null,null,null,!1,o["a"],void 0);n["default"]=i.exports},"9c84":function(t,n,e){"use strict";(function(t){var o=e("47a9");Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var a=e("8f59"),u=e("b1d8"),c=o(e("c962")),i={props:{hostProduct:{type:Array,default:function(){return[]}},title:{type:String,default:"猜你喜欢"}},components:{waterfallsFlow:function(){e.e("components/WaterfallsFlow/WaterfallsFlow").then(function(){return resolve(e("f612"))}.bind(null,e)).catch(e.oe)}},mixins:[c.default],computed:(0,a.mapGetters)(["uid"]),methods:{goDetail:function(n){(0,u.goShopDetail)(n,this.uid).catch((function(e){t.navigateTo({url:"/pages/goods_details/index?id=".concat(n.id)})}))}}};n.default=i}).call(this,e("df3c")["default"])}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'components/recommend/index-create-component',
    {
        'components/recommend/index-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('df3c')['createComponent'](__webpack_require__("7bc6"))
        })
    },
    [['components/recommend/index-create-component']]
]);
