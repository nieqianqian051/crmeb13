<template>
	<view :style="colorStyle">
		<view class="m-20 p-30rpx bg--w111-fff rd-24rpx">
			<view class="grid-column-3 grid-gap-20rpx">
				<view class="relative w-full h-204" v-for="(item,index) in formData.slider_image" :key="index">
					<image :src="item" mode="aspectFill" class="w-full h-204 rd-16rpx"></image>
					<view class="abs-rt w-32 h-32 del-pic flex-center" @click="DelPic(index)">
						<text class="iconfont icon-ic_close text--w111-fff fs-20"></text>
					</view>
					<view class="abs-lt poster-badge flex-center fs-18" v-if="index == 0">封面</view>
				</view>
				<view class="w-full h-204 flex-col flex-center upload bg--w111-f5f5f5 rd-16rpx text--w111-999"
					@click='uploadpic' v-if="!formData.video_url && formData.slider_image.length < 9">
					<text class="iconfont icon-ic_camera fs-60"></text>
					<text class="fs-24 lh-34rpx pt-12">添加照片</text>
				</view>
				<view class="w-full h-204 flex-col flex-center upload bg--w111-f5f5f5 rd-16rpx text--w111-999"
					@click='uploadVideo' v-if="!formData.slider_image.length && !formData.video_url">
					<text class="iconfont icon-icon_video fs-60"></text>
					<text class="fs-24 lh-34rpx pt-12">添加视频</text>
				</view>
				<block v-if="formData.video_url">
					<view class="w-full h-204 flex-col flex-center video-bg rd-16rpx relative" @tap="openVideoPopup">
						<view class="abs-rt w-32 h-32" @tap.stop="delVideo()"></view>
						<view class="video-player flex-center">
							<text class="iconfont icon-ic_right2 fs-28 text--w111-fff relative"></text>
						</view>
						<text class="pt-22 fs-18 text--w111-fff">点击可预览视频</text>
					</view>
					<view class="w-full h-204 flex-col flex-center upload bg--w111-f5f5f5 rd-16rpx text--w111-999"
						@click='uploadPoster' v-if="!formData.image">
						<text class="iconfont icon-ic_camera fs-60"></text>
						<text class="fs-24 lh-34rpx pt-8">添加封面</text>
					</view>
					<view class="w-full h-204 flex-col flex-center upload bg--w111-f5f5f5 rd-16rpx relative text--w111-999"
						@click='uploadPoster' v-if="formData.image">
						<image :src="formData.image" mode="aspectFill" class="w-full h-204 rd-16rpx"></image>
						<view class="abs-lt poster-badge flex-center fs-18">封面</view>
						<view class="poster-huan flex-center text--w111-fff fs-18">更换封面</view>
					</view>
				</block>
			</view>
			<view class="pt-36 pb-32 bb-e">
				<input class="fs-30"
				placeholder='填写标题会有更多赞噢~'
				placeholder-class='text--w111-999'
				v-model="formData.title"
				maxlength="20" />
			</view>
			<view class="pt-28 textarea">
				<textarea
					class="w-full"
					placeholder="分享你的体验和心得，你的文字我们都很喜欢~"
					auto-height
					name="comment"
					placeholder-class='placeholder'
					v-model="formData.content"
					:maxlength="600"></textarea>
			</view>
			<view class="w-full pt-30">
				<scroll-view scroll-x="true" class="white-nowrap vertical-middle w-full" show-scrollbar="false">
					<view class="inline-block mr-20" @tap="openDrawer(0)">
						<view class="h-52 px-16 rd-30rpx flex-center fs-24 b-e"># 话题</view>
					</view>
					<view class="inline-block mr-20" v-for="(item,index) in topicSelectedList" :key="index">
						<view class="h-52 px-16 label-item rd-30rpx flex-center fs-24"># {{item.name}} 
							<text class="iconfont icon-ic_close fs-24 pl-8" @tap.stop="delTopic(item)"></text> 
						</view>
					</view>
				</scroll-view>
			</view>
		</view>
		<view class="m-20 p-30 bg--w111-fff rd-24rpx flex-between-center fs-30" @tap="openDrawer(1)">
			<view class="flex-y-center">
				<text class="iconfont icon-icon_Link"></text>
				<view class="pl-8">添加宝贝<text v-show="productNum">({{productNum}})</text></view>
			</view>
			<view class="flex-y-center justify-end" v-show="productSelect.length">
				<image class="w-60 h-60 rd-6rpx block mr-12"
				v-for="(item,index) in productSelect" :key="index"
				:src="item.image"></image>
				<text class="iconfont icon-ic_rightarrow fs-32"></text>
			</view>
			<view class="fs-30 text--w111-999 flex-y-center" v-show="!productSelect.length">
				<text>请选择</text>
				<text class="iconfont icon-ic_rightarrow"></text>
			</view>

		</view>
		<view class="h-200"></view>
		<view class="pb-safe pt-32 w-full fixed-lb bg--w111-f5f5f5 flex-center">
			<view class="add-btn flex-center text--w111-fff fs-28" @tap="confirmSave">发布</view>
		</view>
		<base-drawer
			mode="bottom"
			:visible="visible"
			background-color="transparent"
			mask
			maskClosable
			@close="confirmProductDrawer">
			 <view class="w-full bg--w111-fff rd-t-40rpx py-32 relative">
				<view class="close-btn flex-center" @tap="confirmProductDrawer">
					<text class="iconfont icon-ic_close text--w111-666 fs-24"></text>
				</view>
				<view class="flex-center fs-30 text--w111-999">
					 <view :class="{'tab-active': tabActive == 0}" @tap="changeTab(0,'pay')">已购</view>
					 <view class="ml-70" :class="{'tab-active': tabActive == 1}" @tap="changeTab(1,'collect')">收藏</view>
					 <view class="ml-70" :class="{'tab-active': tabActive == 2}" @tap="changeTab(2,'visit')">浏览</view>
				</view>
				<view class="px-30 mt-50 flex-between-center">
					<view class="flex-1 px-32 h-58 rd-30rpx bg--w111-f5f5f5 flex-y-center">
						<text class="iconfont icon-ic_search text--w111-999 fs-28"></text>
						<input type="text" class="flex-1 pl-20 fs-26" placeholder="输入关键字搜索" placeholder-class="text--w111-ccc" v-model="where.keyword" />
					</view>
					<text class="fs-28 pl-24" @tap="getSearch">搜索</text>
				</view>
				<view class="px-30">
					<scroll-view scroll-y="true" @scrolltolower="getProductList" style="height: 780rpx;">
						<view class="flex-between-center" v-for="(item,index) in productList" :key="index">
							<text class="iconfont text--w111-ccc fs-36"
								:class="item.isSelect ? 'icon-a-ic_CompleteSelect' : 'icon-ic_unselect'"
								@tap="selectItem(item,index)"></text>
							<view class="flex-1 flex h-160 mt-30 pl-30">
								<image class="w-160 h-160 rd-16rpx" :src='item.image' mode="aspectFill"></image>
								<view class="h-160 flex-1 flex-col justify-between pl-30">
									<view class="h-68 lh-34rpx fs-30 line2">{{item.store_name}}</view>
									<baseMoney :money="item.price" symbolSize="24" integerSize="40" decimalSize="24" weight></baseMoney>
								</view>
							</view>
						</view>
						<view v-if="!productList.length">
							<emptyPage title="暂无商品，去看点别的吧～" ></emptyPage>
						</view>
					</scroll-view>
				</view>
				<view class="mx-20 pb-safe bg--w111-fff">
				    <view class="mt-52 h-88 flex-center rd-44rpx bg-color fs-28 text--w111-fff mt-24"
						@tap="confirmProductDrawer">确定({{productNum}}/5)</view>
				</view>
			 </view>
		</base-drawer>
		<base-drawer
			mode="bottom"
			:visible="showTopic"
			background-color="transparent"
			mask
			maskClosable
			@close="confirmTopicDrawer">
			 <view class="w-full bg--w111-fff rd-t-40rpx py-32 relative">
				<view class="close-btn flex-center" @tap="confirmTopicDrawer">
					<text class="iconfont icon-ic_close text--w111-666 fs-24"></text>
				</view>
				<view class="fs-36 fw-500 flex-x-center">参与话题</view>
				<view class="px-30 mt-50 flex-between-center">
					<view class="flex-1 px-32 h-58 rd-30rpx bg--w111-f5f5f5 flex-y-center">
						<text class="iconfont icon-ic_search text--w111-999 fs-28"></text>
						<input type="text" class="flex-1 pl-20 fs-26" placeholder="输入关键字搜索" placeholder-class="text--w111-ccc" v-model="where.keyword" />
					</view>
					<text class="fs-28 pl-24">搜索</text>
				</view>
				<view class="px-30">
					<scroll-view scroll-y="true" style="height: 780rpx;">
						<view class="fs-30 mt-48">推荐话题</view>
						<view class="flex flex-wrap mt-32">
							<view class="h-58 px-24 rd-8rpx b-d flex-center fs-24 mr-28 mb-28"
							v-for="(item,index) in recommendTopic" :key="index"
							:class="{'label-item':item.isChoose}"
							@tap="changeTopic(item)">{{item.name}}</view>
						</view>
						<view class="fs-30 mt-24">全部话题</view>
						<view class="flex flex-wrap mt-32">
							<view class="h-58 px-24 rd-8rpx b-d flex-center fs-24 mr-28 mb-28"
							v-for="(item,index) in topicList" :key="index"
							:class="{'label-item':item.isChoose}"
							@tap="changeTopic(item)">{{item.name}}</view>
						</view>
					</scroll-view>
				</view>
				<view class="mx-20 pb-safe bg--w111-fff">
				    <view class="mt-52 h-88 flex-center rd-44rpx bg-color fs-28 text--w111-fff mt-24"
						@tap="confirmTopicDrawer">确定({{topicNum}}/5)</view>
				</view>
			 </view>
		</base-drawer>
		<uni-popup ref="popup">
			<view class="flex-center">
				<view class="fixed-lt h-80 flex-y-center pl-20 z-999" :style="{'top': sysHeight + 'px'}"
					@tap="hideModal">
					<text class="iconfont icon-ic_close fs-40 text--w111-fff"></text>
				</view>
				<!--#ifndef APP-PLUS-->
				<video id="myVideo" autoplay loop muted :src="formData.video_url"
					:style="[videoStyle]"></video>
				<!--#endif-->
				<!--#ifdef APP-PLUS-->
				<view v-html="videoHtml"></view>
				<!--#endif-->
			</view>
		</uni-popup>
		<canvas canvas-id="canvas"  v-if="canvasStatus"
		:style="{width: canvasWidth + 'px', height: canvasHeight + 'px',position: 'absolute',left:'-100000px',top:'-100000px'}"></canvas>
	</view>
</template>

<script>
	let sysHeight = uni.getSystemInfoSync().statusBarHeight;
	import colors from "@/mixins/color";
	import store from '@/store';
	import { mapState, mapGetters } from 'vuex';
	import { toLogin } from '@/libs/login.js';
	import {TOKENNAME, HTTP_REQUEST_URL} from '@/config/app.js';
	import baseDrawer from '@/components/tui-drawer/tui-drawer.vue';
	import emptyPage from '@/components/emptyPage.vue';
	import { getProductslist } from "@/api/store.js";
	import {
		getTopicApi,
		getProductApi,
		communitySaveApi,
		communityInfoApi,
		communityUpdateApi,
		getCommunityConfig
	} from "@/api/community.js"
	export default {
		data() {
			return {
				sysHeight,
				id:'',
				canvasStatus: false,
				visible:false,
				tabActive:0,
				keyword:'',
				topicList:[],
				recommendTopic:[],
				topicSelectedList:[],
				showTopic:false,
				productList:[],
				productSelect:[],
				loading:false,
				where:{
					page:1,
					limit:20,
					type:'pay',
					keyword:''
				},
				formData:{
					content_type:null, //内容类型1：图文2视频
					title:'',
					content:'',
					image:'', //视频封面
					video_url:'',
					slider_image:[],
					topic_id:[],
					product_id:[]
				},
				upload_max: 50,
				uploadUrl: `${HTTP_REQUEST_URL}/api/upload/video`,
				videoContext:'',
				productNum:0,
				topicNum:0,
				topic_id:null,
				canvasWidth:'',
				canvasHeight:''
			};
		},
		components:{ baseDrawer, emptyPage },
		computed: {
			...mapGetters(['isLogin']),
			videoHtml() {
				return `<video  autoplay loop muted controls="controls" width="100%" height="870px"><source src="${this.formData.video_url}" type="video/mp4"></video>`;
			},
			videoStyle(){
				return {
					width: uni.getSystemInfoSync().windowWidth + 'px',
					height: uni.getSystemInfoSync().windowHeight + 'px',
				}
			}
		},
		mixins: [colors],
		onLoad(options) {
			if(this.isLogin){
				if(options.id){
					this.id = options.id;
					this.getInfo();
				}
				if(options.topic_id && !options.id){
					this.topic_id = options.topic_id;
				}
				this.getTopic();
				this.getProductList();
				// #ifndef APP-PLUS
				this.videoContext = uni.createVideoContext('myVideo', this);
				// #endif
			}else{
				toLogin();
			}

		},
		methods:{
			DelPic(index) {
				this.formData.slider_image.splice(index, 1);
			},
			delVideo(){
				this.formData.video_url = ''
			},
			openVideoPopup(){
				this.$refs.popup.open();
				this.videoContext = uni.createVideoContext('myVideo', this);
				this.$nextTick(() => {
					this.videoContext.play();
				})
			},
			hideModal(){
				this.$refs.popup.close();
			},
			uploadPoster(){
				let that = this;
				this.canvasStatus = true
				that.$util.uploadImageChange({count:1,url:'upload/image'}, function(res) {
					that.formData.image = res.data.url;
				}, (res) => {
					this.canvasStatus = false
				}, (res) => {
					this.canvasWidth = res.w
					this.canvasHeight = res.h
				});
			},
			uploadpic() {
				let that = this;
				this.canvasStatus = true
				that.$util.uploadImageChange({count:9,url:'upload/image'}, function(res) {
					if(that.formData.slider_image.length == 9) return that.$util.Tips({
						title: '最多上传9张'
					});
					that.formData.slider_image.push(res.data.url);
				}, (res) => {
					this.canvasStatus = false
				}, (res) => {
					this.canvasWidth = res.w
					this.canvasHeight = res.h
				});
			},
			// 上传视频
			uploadVideo() {
				let that = this
				uni.chooseVideo({
					sourceType: ['camera', 'album'],
					success: res => {
						uni.showLoading({
							title: '视频上传中',
						});
						if (Math.ceil(res.size / 1024) < that.upload_max * 1024) {
							uni.uploadFile({
								url: that.uploadUrl,
								filePath: res.tempFilePath,
								name: 'file',
								header: {
									// #ifdef MP
									'Content-Type': 'multipart/form-data',
									// #endif
									[TOKENNAME]: 'Bearer ' + store.state.app.token
								},
								success: uploadFileRes => {
									uni.hideLoading();
									let data = JSON.parse(uploadFileRes.data);
									if(data.status == 200){
										that.formData.video_url = data.data.url;
									}else{
										return this.$util.Tips({
											title: data.msg
										});
									}
								},
								complete: function(err) {
									uni.hideLoading();
								}
							});
						} else {
							uni.hideLoading();
							uni.showModal({
								title: '提示',
								showCancel:false,
								content: `视频超出限制${that.upload_max}MB`
							});
						}
					},
					fail: err => {
						that.$util.Tips({
							title: err
						});
					}
				});
			},
			openDrawer(type){
				if(type == 1){
					if(this.id){
						this.productList.map(itemA => {
							this.formData.product_id.forEach(itemB=>{
								if(itemB === itemA.product_id){
									this.$set(itemA,'isSelect',true);
								}
							})
						});
					}
					this.visible = !this.visible;
				}else{
					if(this.id){
						this.topicList.map(itemA=> {
							this.topicSelectedList.forEach(itemB=> {
								if(itemB.id === itemA.id){
									this.$set(itemA,'isChoose',true);
								}
							})
						})
					}
					this.showTopic = !this.showTopic;
				}
			},
			confirmProductDrawer(){
				this.visible = false;
			},
			confirmTopicDrawer(){
				this.showTopic = false;
			},
			getTopic(){
				getTopicApi().then(res=>{
					res.data.forEach(item=>{
						this.$set(item,'isChoose',false);
					})
					this.topicList = res.data;
					this.recommendTopic = this.topicList.filter(val=> val.is_recommend == 1);
					if(this.topic_id){
						res.data.forEach(item=>{
							if(item.id == this.topic_id){
								this.topicSelectedList[0] = item;
							}
						})
					}
				})
			},
			changeTopic(item){
				if (this.topicSelectedList.length > 4 && !item.isChoose) {
					return;
				}
				if(!item.isChoose){
					item.isChoose = true;
					this.topicSelectedList.push(item)
					this.topicNum++;
				}else{
					item.isChoose = false;
					this.topicSelectedList.splice(this.topicSelectedList.findIndex(itemn => ((itemn.id == item.id))), 1)
					this.topicNum--;
				}
			},
			delTopic(item){
				let index = this.topicList.findIndex(itemn => itemn.id == item.id);
				this.topicList[index].isChoose = false;
				this.topicSelectedList.splice(this.topicSelectedList.findIndex(itemn => ((itemn.id == item.id))), 1)
				this.topicNum--;
			},
			getProductList(){
				if (this.loading) return;
				this.loading = true;
				getProductApi(this.where).then(res => {
					res.data.map(item=>{
						this.$set(item,'isSelect',false);
						this.formData.product_id.forEach(itemB=>{
							if(itemB === item.product_id){
								this.$set(item,'isSelect',true);
							}
						})
					})
					let list = res.data;
					let loading = list.length < this.where.limit;
					this.productList = this.productList.concat(list);
					this.where.page++;
					this.loading = loading;
				}).catch(err => {
					this.loading = false;
					return this.$util.Tips({
						title: err
					});
				});
			},
			getSearch(){
				this.loading = false;
				this.where.page = 1;
				this.productList = [];
				this.getProductList();
			},
			changeTab(val,type){
				this.tabActive = val;
				this.where.type = type;
				this.getSearch()
			},
			selectItem(item){
				if(item.isSelect){
					item.isSelect = false;
					if(this.formData.product_id.includes(item.product_id)){
						let i = this.productSelect.findIndex(o=> o.id == item.product_id);
						this.productSelect.splice(i,1);
						this.formData.product_id.splice(i,1);
						console.log(this.formData.product_id);
						console.log(this.productSelect);
						console.log(this.productNum);
						this.productNum--;
					}
				}else{
					if(this.productSelect.length == 5) return this.$util.Tips({
						title: '最多选择5件商品'
					}) 
					item.isSelect = true;
					let arr = [];
					this.productSelect.forEach(k=>{
						arr.push(k.id);
					})
					if(!arr.includes(item.product_id)){
						this.productNum++;
						this.productSelect.push(item);
						this.formData.product_id.push(item.product_id)
					}
				}
			},
			confirmSave(){
				// if(!this.formData.title) return this.$util.Tips({
				// 	title: '请输入标题'
				// })
				if(!this.formData.content) return this.$util.Tips({
					title: '请输入内容'
				})
				// if(!this.topicSelectedList.length) return this.$util.Tips({
				// 	title: '请选择话题'
				// })
				if(!this.formData.video_url && !this.formData.slider_image.length) return this.$util.Tips({
					title: '请上传要分享的图片'
				})
				if(this.formData.video_url && !this.formData.image) return this.$util.Tips({
					title: '请上传海报'
				})
				this.topicSelectedList.forEach(item=>{
					this.formData.topic_id.push(item.id)
				})
				this.formData.content_type = this.formData.slider_image.length ? 1 : 2;
				uni.showLoading({
					title:'正在提交...'
				})
				let funApi = '';
				funApi = this.id ? communityUpdateApi(this.id,this.formData) : communitySaveApi(this.formData);
				funApi.then(res=>{
					uni.hideLoading()
					return this.$util.Tips({
						title: res.msg
					},{
						tab:4,
						url:'/pages/discoverIndex/index'
					});
				}).catch(err => {
					uni.hideLoading()
					return this.$util.Tips({
						title: err
					});
				});
			},
			getInfo(){
				communityInfoApi(this.id).then(res=>{
					let info = res.data;
					let keys = Object.keys(this.formData);
					keys.map((i) => {
					    this.formData[i] = info[i];
					});
					if(this.formData.product_id == null){
						this.formData.product_id = [];
					}
					this.topicSelectedList = res.data.topic;
					if(res.data.product && res.data.product.length){
						this.productSelect = res.data.product;
					}
					this.productNum = res.data.product.length;
					this.topicNum = res.data.topic.length;
				}).catch(err => {
					return this.$util.Tips({
						title: err
					});
				});
			},
		}
	}
</script>

<style lang="scss">
.del-pic{
	background-color: #999;
	border-radius: 0 16rpx 0 16rpx;
}
.close-btn{
	position: absolute;
	right: 28rpx;
	top: 28rpx;
	width: 36rpx;
	height: 36rpx;
	border-radius: 50%;
	background-color: #eee;
	
}
.bb-e{
	border-bottom: 1rpx solid #eee;
}
.b-e{
	border: 1rpx solid #eee;
}
.b-d{
	border: 1rpx solid #ddd;
}
.textarea{
	min-height: 284rpx;
}
.label-item{
	color: var(--view-theme);
	background: var(--view-minorColorT);
	border: 1rpx solid transparent;
}
.icon-a-ic_CompleteSelect{
	color: var(--view-theme);
}
.pro-cell{
	padding: 36rpx 30rpx;
}
.add-btn{
	width: 690rpx;
	height: 88rpx;
	background: var(--view-theme);
	border-radius: 50rpx;
	background-color: var(--view-theme);
	margin-bottom: 32rpx;
}
.tab-active{
	font-weight: 500;
	color: #333;
	position: relative;
	&:after{
		content: '';
		position: absolute;
		left: 50%;
		transform: translateX(-50%);
		bottom: -14rpx;
		width: 40rpx;
		height: 5rpx;
		background: var(--view-theme);
		border-radius: 4rpx;
	}
}
.poster-badge{
	width: 54rpx;
	height: 29rpx;
	background: var(--view-theme);
	color: #fff;
	border-radius: 16rpx 0rpx 16rpx 0rpx;
}
.video-bg{
	background-image: url('@/static/img/video_bg.png');
	background-repeat: no-repeat;
	background-size: cover;
}
.video-player{
	width: 50rpx;
	height: 50rpx;
	border-radius: 50%;
	background: rgba(0,0,0,0.5);
	.iconfont{
		left: 2rpx;
	}
}
.poster-huan{
	position: absolute;
	bottom: 0;
	width: 100%;
	height: 40rpx;
	background: rgba(0,0,0,0.5);
	border-radius: 0rpx 0rpx 16rpx 16rpx;
}
.canvas-box {
	position: absolute;
	left:-100px;
	top:-100px;
	width: 750px;
	height: 1624px;
}
</style>
