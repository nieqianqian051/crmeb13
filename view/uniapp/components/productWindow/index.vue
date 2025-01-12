<template>
	<view>
		<view class="product-window"
			:class="(attr.cartAttr === true ? 'on' : '') + ' ' + (iSbnt?'join':'') + ' ' + (iScart?'joinCart':'')"
			@touchmove.stop.prevent="moveHandle">
			<view class="textpic acea-row">
				<view class="pictrue" @click="showImg()">
					<image :src="attr.productSelect.image"></image>
					<view class="icon flex-center" v-if="fangda">
						<view class="iconfont icon-ic_enlarge"></view>
					</view>
				</view>
				<view class="pl-24 pt-24 flex-col">
					<view class="flex-y-center" v-if="attr.productSelect.integral">
						<image src="../../static/img/mall05.png" class="w-32 h-32"></image>
						<text class="lh-40rpx font-num fs-40 SemiBold pl-8">{{attr.productSelect.integral}}</text>
						<text class="fs-28 lh-40rpx px-8 text--w111-666">+</text>
						<baseMoney :money="attr.productSelect.price" symbolSize="28" integerSize="40" decimalSize="28" color="var(--view-theme)" weight></baseMoney>
					</view>
					<baseMoney
					:money="attr.productSelect.price"
					symbolSize="32"
					integerSize="48"
					decimalSize="32"
					incolor="var(--primary-theme-con)"
					weight v-else></baseMoney>
					<view class="inline-block h-48 lh-48rpx text-center rd-24rpx bg-color fs-24 text--w111-fff px-20 mt-16"
						v-if="type == 0">
						预估到手 <text class="fs-28 fw-600 pl-8">¥{{attr.productSelect.pay_price}}</text>
					</view>
					<view class="mt-12 fs-24 text--w111-999" v-if="type == 1 || type == 3">限量:{{ attr.productSelect.quota_show || 0 }}</view>
					<view class="mt-12 fs-24 text--w111-999" v-else>库存:{{ attr.productSelect.stock || attr.productSelect.product_stock || 0 }}</view>
				</view>
				<view class="iconfont icon-ic_close1" @click="closeAttr"></view>
			</view>
			<view class="rollTop">
				<scroll-view scroll-y="true" :style="'max-height: '+windowHeight+'rpx'" >
					<view class="productWinList">
						<view class="item" v-for="(item, indexw) in attr.productAttr" :key="indexw">
							<view class="title">{{ item.attr_name }}</view>
							<view class="listn acea-row row-middle">
								<view class="itemn" :class="item.index === itemn.attr ? 'on' : ''"
									v-for="(itemn, indexn) in item.attr_value" @click="tapAttr(indexw, indexn)"
									:key="indexn">
									{{ itemn.attr }}
								</view>
							</view>
						</view>
					</view>
				</scroll-view>
				<view class="cart acea-row row-between-wrapper" v-if="type != 'setMeal' && type !='points'">
					<view class="title">数量</view>
					<view class="carnum acea-row row-left">
						<view class="item reduce acea-row row-center-wrapper"
							:class="attr.productSelect.cart_num <= 1 ? 'on' : ''" v-if="attr.productSelect.cart_num <= 1">
							<text class="iconfont icon-ic_Reduce"></text>
						</view>
						<view class="item reduce acea-row row-center-wrapper"
							:class="attr.productSelect.cart_num <= 1 ? 'on' : ''" @click="CartNumDes" v-else>
							<text class="iconfont icon-ic_Reduce"></text>
						</view>
						<view class='item num acea-row row-middle'>
							<input type="number" v-model="attr.productSelect.cart_num"
								data-name="productSelect.cart_num" :always-embed="true" :adjust-position="true" cursor-spacing="30"
								@input="bindCode(attr.productSelect.cart_num)"></input>
						</view>
						<view class="item plus acea-row row-center-wrapper"
							:class="attr.productSelect.cart_num >= attr.productSelect.stock ? 'on' : ''" @click="CartNumAdd">
							<text class="iconfont icon-ic_increase"></text>
						</view>
						<!-- <view v-else class='item plus'
							:class='(attr.productSelect.cart_num >= attr.productSelect.quota) || (attr.productSelect.cart_num >= attr.productSelect.product_stock) || (attr.productSelect.cart_num >= attr.productSelect.num) || (type== 1 && attr.productSelect.cart_num >= attr.productSelect.once_num)? "on":""'
							@click='CartNumAdd'>+</view> -->
					</view>
				</view>
			</view>
			<view v-if="isExtends">
				<view class="joinBnt bg-gray" v-if="attr.productSelect.stock <= 0">已售罄</view>
				<view class="joinBnt bg-color" v-else @click="cartConfirm">确定</view>
			</view>
			<view class="joinBnt bg-color"
				v-if="iSbnt && attr.productSelect.product_stock>0 &&attr.productSelect.quota>0" @click="goCat">我要参团
			</view>
			<view class="joinBnt on"
				v-else-if="(iSbnt && attr.productSelect.quota<=0)||(iSbnt &&attr.productSelect.product_stock<=0)">已售罄
			</view>
			<view class="joinBnt bg-color" v-if="iScart && attr.productSelect.stock" @click="goCat">确定</view>
			<view class="joinBnt on" v-else-if="iScart && !attr.productSelect.stock">已售罄</view>
		</view>
		<view class="mask z-25" @touchmove.stop.prevent="moveHandle" :hidden="attr.cartAttr === false" @click="closeAttr"></view>
	</view>
</template>

<script>
	let windowHeight = (uni.getSystemInfoSync().windowHeight*(3/4)-238)*2;
	export default {

		props: {
			attr: {
				type: Object,
				default: () => {}
			},
			storeInfo: {
				type: Object,
				default: () => {}
			},
			limitNum: {
				type: Number,
				value: 0
			},
			isShow: {
				type: Number,
				value: 0
			},
			iSbnt: {
				type: Number,
				value: 0
			},
			iSplus: {
				type: Number,
				value: 0
			},
			iScart: {
				type: Number,
				value: 0
			},
			is_vip: {
				type: Number,
				value: 0
			},
			type: {
				type: [Number, String],
				default: 0
			},
			fangda: {
				type: Boolean,
				default: true
			},
			isExtends:{
				type: Boolean,
				default: false
			},
			showFooter:{
				type:Boolean,
				default: false
			}
		},
		data() {
			return {
				windowHeight:windowHeight,
			};
		},
		mounted() {},
		methods: {
			// getpreviewImage: function() {
			// 	uni.previewImage({
			// 		urls: this.attr.productSelect.image.split(','),
			// 		current: this.attr.productSelect.image
			// 	});
			// },
			moveHandle(){},
			goCat: function() {
				this.$emit('goCat');
			},
			/**
			 * 购物车手动输入数量
			 *
			 */
			bindCode: function(e) {
				this.$emit('iptCartNum', this.attr.productSelect.cart_num);
			},
			closeAttr: function() {
				this.$emit('myevent');
			},
			CartNumDes: function() {
				this.$emit('ChangeCartNum', false);
			},
			CartNumAdd: function() {
				this.$emit('ChangeCartNum', true);
			},
			tapAttr: function(indexw, indexn) {
				let that = this;
				that.$emit("attrVal", {
					indexw: indexw,
					indexn: indexn
				});
				this.$set(this.attr.productAttr[indexw], 'index', this.attr.productAttr[indexw].attr_values[indexn]);
				let value = that
					.getCheckedValue()
					.join(",");
				that.$emit("ChangeAttr", value);

			},
			//获取被选中属性；
			getCheckedValue: function() {
				let productAttr = this.attr.productAttr;
				let value = [];
				for (let i = 0; i < productAttr.length; i++) {
					for (let j = 0; j < productAttr[i].attr_values.length; j++) {
						if (productAttr[i].index === productAttr[i].attr_values[j]) {
							value.push(productAttr[i].attr_values[j]);
						}
					}
				}
				return value;
			},
			showImg() {
				this.$emit('getImg');
			},
			cartConfirm(){
				this.$emit('onConfirm');
			}
		}
	}
</script>

<style scoped lang="scss">
	.vip-money {
		color: #282828;
		font-size: 28rpx;
		font-weight: 700;
		margin-left: 6rpx;
	}

	.vipImg {
		width: 56rpx;
		height: 20rpx;
		margin-left: 6rpx;

		image {
			width: 100%;
			height: 100%;
			display: block;
		}
	}

	.product-window {
		position: fixed;
		bottom: 0;
		width: 100%;
		left: 0;
		background-color: #fff;
		z-index: 100;
		border-radius: 40rpx 40rpx 0 0;
		transform: translate3d(0, 100%, 0);
		transition: all 0.3s ease-in-out;
		padding-bottom: constant(safe-area-inset-bottom); ///兼容 IOS<11.2/
		padding-bottom: env(safe-area-inset-bottom);///兼容 IOS>11.2/
	}

	.product-window.on {
		transform: translate3d(0, 0, 0);
	}

	.product-window.join {
		padding-bottom: 30rpx;
	}

	.product-window.joinCart {
		padding-bottom: 30rpx;
		z-index: 4000;
	}

	.product-window .textpic {
		padding: 0 32rpx;
		margin-top: 48rpx;
		position: relative;
	}

	.product-window .textpic .pictrue {
		width: 180rpx;
		height: 180rpx;
		position: relative;
		.icon{
			width: 30rpx;
			height: 30rpx;
			background-color: rgba(0,0,0,0.4);
			border-radius: 4rpx;
			position: absolute;
			bottom: 8rpx;
			right: 8rpx;
			text-align: center;
			line-height: 23rpx;
			.iconfont{
				color: #fff;
				font-size: 20rpx;
			}
		}
	}

	.product-window .textpic .pictrue image {
		width: 100%;
		height: 100%;
		border-radius: 16rpx;
	}

	.product-window .textpic .text {
		width: 410rpx;
		font-size: 32rpx;
		color: #202020;
	}

	.product-window .textpic .text .money {
		font-size: 24rpx;
		margin-top: 40rpx;
		.icon{
			display: inline-block;
			font-size: 16rpx;
			font-weight: normal;
			background: #FF9500;
      color: #fff;
			border-radius: 18rpx;
			padding: 2rpx 6rpx;
			margin-left: 10rpx;
			.iconfont{
				font-size: 16rpx;
				margin-right: 4rpx;
				color: #fff;
			}
		}
	}

	.product-window .textpic .text .money .num {
		font-size: 36rpx;
	}

	.product-window .textpic .text .money .stock {
		color: #999;
		margin-left: 6rpx;
	}

	.product-window .textpic .icon-ic_close1 {
		position: absolute;
		right: 30rpx;
		top: -5rpx;
		font-size: 35rpx;
		color: #8a8a8a;
	}

	.product-window .rollTop {
		margin-top: 36rpx;
	}
	.product-window .productWinList{
	}
	.product-window .productWinList .item~.item {
		margin-top: 36rpx;
	}

	.product-window .productWinList .item .title {
		font-size: 28rpx;
		font-weight: 500;
		color: #333;
		padding: 0 32rpx;
	}

	.product-window .productWinList .item .listn {
		padding: 0 32rpx 0 16rpx;
	}

	.product-window .productWinList .item .listn .itemn {
		// height: 56rpx;
		line-height: 56rpx;
		border: 1rpx solid #F2F2F2;
		font-size: 24rpx;
		color: #333;
		padding: 0 44rpx;
		border-radius: 28rpx;
		margin: 24rpx 0 0 16rpx;
		background-color: #F2F2F2;
		word-break: break-all;
	}

	.product-window .productWinList .item .listn .itemn.on {
		color: var(--view-theme);
		background: var(--view-minorColorT);
		border-color: var(--view-theme);
	}

	.product-window .productWinList .item .listn .itemn.limit {
		color: #999;
		text-decoration: line-through;
	}

	.product-window .cart {
		padding: 0 30rpx;
	}

	.product-window .cart .title {
		font-size: 28rpx;
		color: #333;
	}

	.product-window .cart .carnum {
		height: 54rpx;
		margin-top: 24rpx;
	}

	.product-window .cart .carnum .iconfont {
		font-size: 25rpx;
	}

	.product-window .cart .carnum view {
		// border: 1px solid #a4a4a4;
		width: 84rpx;
		text-align: center;
		height: 100%;
		line-height: 54rpx;
		color: #282828;
		font-size: 45rpx;
	}

	.product-window .cart .carnum .reduce {
		border-right: 0;
		border-radius: 6rpx 0 0 6rpx;
		line-height: 48rpx;
		font-size: 60rpx;
	}

	.product-window .cart .carnum .reduce.on {
		// border-color: #e3e3e3;
		color: #DEDEDE;
	}

	.product-window .cart .carnum .plus {
		border-left: 0;
		border-radius: 0 6rpx 6rpx 0;
		line-height: 46rpx;
	}

	.product-window .cart .carnum .plus.on {
		// border-color: #e3e3e3;
		color: #dedede;
	}

	.product-window .cart .carnum .num {
		background: rgba(242, 242, 242, 1);
		color: #282828;
		font-size: 28rpx;
		border-radius: 4rpx;
	}

	.product-window .joinBnt {
		font-size: 28rpx;
		width: 710rpx;
		height: 80rpx;
		border-radius: 40rpx;
		text-align: center;
		line-height: 80rpx;
		color: #fff;
		margin: 74rpx auto 12rpx;
	}

	.product-window .joinBnt.on {
		background-color: #bbb;
		color: #fff;
	}
	.mt-74{
		margin-top: 74rpx;
	}
	.join_cart{
		background-color: var(--view-bntColor);
	}
	.bg-gray{
		background-color: #CCCCCC;
	}
	.z-25{
		z-index: 25;
	}
</style>
