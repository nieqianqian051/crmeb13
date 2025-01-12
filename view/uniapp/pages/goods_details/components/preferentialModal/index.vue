<script>
import { setCouponReceive } from '@/api/api.js';
import baseDrawer from '@/components/tui-drawer/tui-drawer.vue';
export default {
	props: {
		visible: {
			type: Boolean,
			default: false
		},
		discountInfo: {
			type: Object,
			default: () => {}
		},
		coupon: {
			type: Object,
			default: () => {}
		},
		computedPrice: {
			type: Object,
			default: () => {}
		},
		productSelect:{
			type: Object,
			default: () => {}
		}
	},
	data() {
		return {
			ruleshow: false
		};
	},
	filters: {
		typeFilter(val) {
			let obj = {
				0: '通用券',
				1: '品类券',
				2: '商品券',
				3: '品牌券'
			};
			return obj[val];
		}
	},
	components: {
		baseDrawer
	},
	methods: {
		closeDrawer() {
			this.$emit('closeDrawer');
		},
		getCouponUser(item, index) {
			if (item.is_use) {
				this.$emit('closeDrawer');
				return;
			}
			setCouponReceive(item.id)
				.then((res) => {
					this.$emit('ChangCouponsUseState', index);
					this.$util.Tips({
						title: '领取成功'
					});
				})
				.catch((err) => {
					this.$util.Tips({
						title: err
					});
				});
		},
		toggleRule(index) {
			this.$emit('ruleToggle', index);
		}
	}
};
</script>
<template>
	<view>
		<base-drawer mode="bottom" :visible="visible" background-color="transparent" mask maskClosable @close="closeDrawer">
			<view class="w-full bg--w111-f5f5f5 rd-t-40rpx py-32">
				<view class="text-center fs-32 text--w111-333 fw-500">优惠</view>
				<view class="mt-48 px-20">
					<scroll-view scroll-y="true" style="height: 800rpx;">
						<view v-if="productSelect.price > productSelect.pay_price">
							<view class="flex-center">
								<view class="inline-block p-14 rd-16rpx bg-primary-light text-center">
									<view class="text-primary-con fs-32 lh-32rpx Regular fw-600">¥{{ productSelect.pay_price }}</view>
									<view class="fs-20 lh-28rpx text-primary-con pt-6">预估到手</view>
								</view>
							</view>
							<view class="border_bg relative mt-20 flex-center">
								<view class="w-132 h-88 rd-16rpx bg-primary-light flex-col flex-center relative z-10">
									<view class="text-primary-con fs-32 lh-32rpx fw-600 Regular">¥{{ productSelect.price }}</view>
									<view class="fs-20 lh-28rpx text-primary-con">售价</view>
								</view>
								<view class="reduce" v-if="computedPrice.deduction.first_order_price > 0"></view>
								<view class="w-132 h-88 rd-16rpx bg-primary-light flex-col flex-center relative z-10" v-if="computedPrice.deduction.first_order_price > 0">
									<view class="text-primary-con fs-32 lh-32rpx fw-600 Regular">¥{{ computedPrice.deduction.first_order_price }}</view>
									<view class="fs-20 lh-28rpx text-primary-con">首单优惠</view>
								</view>
								<view class="reduce" v-if="computedPrice.deduction.vip_price > 0"></view>
								<view class="w-132 h-88 rd-16rpx bg-primary-light flex-col flex-center relative z-10" v-if="computedPrice.deduction.vip_price > 0">
									<view class="text-primary-con fs-32 lh-32rpx fw-600 Regular">¥{{ computedPrice.deduction.vip_price }}</view>
									<view class="fs-20 lh-28rpx text-primary-con">会员优惠</view>
								</view>
								<view class="reduce" v-if="computedPrice.deduction.promotions_price > 0"></view>
								<view class="w-132 h-88 rd-16rpx bg-primary-light flex-col flex-center relative z-10" v-if="computedPrice.deduction.promotions_price > 0">
									<view class="text-primary-con fs-32 lh-32rpx fw-600 Regular">¥{{ computedPrice.deduction.promotions_price }}</view>
									<view class="fs-20 lh-28rpx text-primary-con">活动优惠</view>
								</view>
								<view class="reduce" v-if="computedPrice.deduction.coupon_price > 0"></view>
								<view class="w-132 h-88 rd-16rpx bg-primary-light flex-col flex-center relative z-10" v-if="computedPrice.deduction.coupon_price > 0">
									<view class="text-primary-con fs-32 lh-32rpx fw-600 Regular">¥{{ computedPrice.deduction.coupon_price }}</view>
									<view class="fs-20 lh-28rpx text-primary-con">优惠券</view>
								</view>
							</view>
						</view>
						<view v-if="discountInfo.discount.length">
							<view class="text--w111-333 fs-24 mt-48 lh-34rpx">商品可参与以下优惠促销活动</view>
							<view class="bg--w111-fff rd-24rpx px-24 mt-24">
								<view class="py-24 card-cell" v-for="(item, index) in discountInfo.discount" :key="index">
									<view class="inline-block px-8 h-38 lh-36rpx rd-8rpx fs-20 con-border text-primary-con">
										{{ item.title }}
									</view>
									<view class="fs-20 lh-28rpx text--w111-666 mt-14">有效期至{{ item.stop_time }}</view>
								</view>
							</view>
						</view>
						<view v-if="coupon.list.length">
							<view class="text--w111-333 fs-24 mt-48 lh-34rpx">可领取优惠券</view>
							<view v-for="(item, index) in coupon.list" :key="index">
								<view class="mt-24 h-170 bg--w111-fff text-center rd-16rpx flex">
									<view class="left-bg bg-gradient">
										<view class="left-container w-full h-full relative flex-col flex-between-center py-32">
											<baseMoney
											:money="item.coupon_price"
											symbolSize="28"
											integerSize="52"
											decimalSize="28"
											color="#ffffff"
											isCoupon
											v-if="item.coupon_type == 1"></baseMoney>
											<view v-else class="text--w111-fff fs-44 SemiBold">{{ parseFloat(item.coupon_price) / 10 }} <text class="pingfang fs-28 pl-4">折</text></view>
											<text class="fs-24 text--w111-fff" v-show="item.use_min_price == 0">无门槛券</text>
											<text class="fs-24 text--w111-fff" v-show="item.use_min_price != 0">满{{ item.use_min_price }}元可用</text>
										</view>
									</view>
									<view class="right-box pt-24 pl-24 pr-14 pb-22 flex-1 flex-col justify-between bg--w111-fff">
										<view class="flex-y-center">
											<view class="fs-28 fw-500 text--w111-333 lh-40rpx">{{ item.coupon_title }}</view>
										</view>
										<view class="flex-between-center">
											<view class="fs-20 text--w111-666 lh-28rpx" v-if="item.coupon_time">领取后{{ item.coupon_time }}天内可用</view>
											<view class="fs-20 text--w111-666 lh-28rpx" v-else>{{ item.start_time ? item.start_time + '-' : '' }}{{ item.end_time }}</view>
											<view class="con_btn bg-primary-light text-primary-con w-136 h-52 rd-28rpx fs-22 flex-center" @tap="getCouponUser(item, index)">
												{{ item.is_use ? '去使用' : '立即领取' }}
											</view>
										</view>
										<view class="flex-y-center fs-20 text--w111-999 lh-28rpx mt-20">
											<text>{{item.type | typeFilter}}</text>
											<view  v-show="item.rule" @tap.stop="toggleRule(index)">
												<text class="pl-8"> | 查看用券规则</text>
												<text class="iconfont icon-ic_downarrow fs-20 ml-4"></text>
											</view>
										</view>
									</view>
								</view>
								<view class="rule-desc" v-show="item.ruleshow" v-html="item.rule"></view>
							</view>
						</view>
					</scroll-view>
				</view>
				<view class="mx-20 pb-safe">
					<view class="mt-52 h-72 flex-center rd-36px bg-color fs-26 text--w111-fff" @tap="closeDrawer">确定</view>
				</view>
			</view>
		</base-drawer>
	</view>
</template>
<style lang="scss">
.border_bg {
	width: 100%;
	height: 130rpx;
	border: 1rpx solid var(--view-theme);
	border-radius: 16rpx;
	background-size: 100%;
	&:before {
		content: '';
		position: absolute;
		top: -1rpx;
		left: 50%;
		width: 18rpx;
		height: 18rpx;
		border: 1rpx solid var(--view-theme);
		background-color: #f5f5f5;
		box-sizing: border-box;
		transform: rotate(45deg) translate(-50%);
	}
}
.border_bg:after {
	content: '';
	position: absolute;
	top: 0;
	left: 50%;
	width: 36rpx;
	height: 36rpx;
	background-color: #f5f5f5;
	transform: translateX(-50%);
	// border-bottom-color: #f5f5f5;
	// top: -9px;
}
.left-bg {
	width: 190rpx;
	height: 170rpx;
	background-size: 100%;
	background-repeat: no-repeat;
	border-radius: 24rpx 0 0 24rpx;
}
.right-box {
	border-radius: 0 24rpx 24rpx 0;
}
.left-container {
	background: radial-gradient(circle at 0px 84rpx, #f5f5f5 12rpx, transparent 0px) top;
	&:after {
		content: '';
		position: absolute;
		top: 0;
		right: 0px;
		width: 6rpx;
		height: 100%;
		background-image: radial-gradient(circle at 6rpx 12rpx, #ffffff 6rpx, transparent 6rpx);
		background-size: 6rpx 18rpx;
	}
}
.text-primary-con {
	color: var(--view-theme);
}
.bg-primary-light {
	background: var(--view-minorColorT);
}
.con-border {
	border: 1rpx solid var(--view-theme);
}
.scroll-content {

}
.reduce {
	width: 18rpx;
	height: 4rpx;
	background-color: var(--view-theme);
	margin: 0 10rpx;
}
.card-cell ~ .card-cell {
	border-top: 1px solid #eee;
}
.rule-desc {
	margin-top: -16rpx;
	padding: 40rpx 24rpx 24rpx;
	white-space: pre-wrap;
	font-size: 20rpx;
	line-height: 28rpx;
	background: linear-gradient(180deg, #f7f7f7 0%, #ffffff 100%);
	border-radius: 0 0 16rpx 16rpx;
	color: #999;
}
.Regular {
	font-family: 'Regular';
}
</style>
