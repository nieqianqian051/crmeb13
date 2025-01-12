<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2020 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
namespace app\controller\api\v1\user;

use app\Request;
use app\services\pay\RechargeServices;
use app\services\user\UserRechargeServices;
use think\annotation\Inject;
use think\exception\ValidateException;

/**
 * 充值类
 * Class UserRecharge
 * @package app\api\controller\user
 */
class UserRecharge
{
    /**
     * @var UserRechargeServices
     */
    #[Inject]
    protected UserRechargeServices $services;

    /**
     * @param Request $request
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/9/19
     */
    public function recharge(Request $request)
    {
        [$price, $recharId, $type, $from] = $request->postMore([
            ['price', 0],
            ['rechar_id', 0],
            ['type', 0],
            ['from', 'weixin']
        ], true);
        if (!$price || $price <= 0) return app('json')->fail('充值金额不能为0元!');
        if (!in_array($type, [0, 1])) return app('json')->fail('充值方式不支持!');
        if (!in_array($from, ['weixin', 'weixinh5', 'routine'])) return app('json')->fail('充值方式不支持');
        $storeMinRecharge = sys_config('store_user_min_recharge');
        if ($price < $storeMinRecharge) return app('json')->fail('充值金额不能低于' . $storeMinRecharge);
        $uid = (int)$request->uid();
        $re = $this->services->recharge($uid, $price, $recharId, $type, $from);
        if ($re) {
            return app('json')->successful($type == 1 ? '操作成功' : '订单生成成功',  ['order_id' => $re['data']['order_id'] ?? '']);
        }
        return app('json')->fail('充值订单生产失败');
    }

    /**
     * 充值订单支付
     * @param Request $request
     * @return \think\Response
     */
    public function recharge_pay(Request $request)
    {
        [$uni, $payType, $from, $quitUrl] = $request->postMore([
            ['uni', ''],
            ['paytype', 'weixin'],
            ['from', 'weixin'],
            ['quitUrl', '']
        ], true);
        $info['order_id'] = $uni;
        try {
            /** @var RechargeServices $recharge */
            $recharge = app()->make(RechargeServices::class);
            $info['jsConfig'] = $recharge->recharge($uni, $payType,$from);
        } catch (\Exception $e) {
            throw new ValidateException($e->getMessage());
        }
        if ($from == 'weixinh5') {
            return app('json')->status('wechat_h5_pay', '前往支付', $info);
        } else {
            return app('json')->status('wechat_pay', '前往支付', $info);
        }
    }

    /**
     * 充值额度选择
     * @return mixed
     */
    public function index()
    {
        $rechargeQuota = sys_data('user_recharge_quota') ?? [];
        $data['recharge_quota'] = $rechargeQuota;
        $recharge_attention = sys_config('recharge_attention');
        $recharge_attention = explode("\n", $recharge_attention);
        $data['recharge_attention'] = $recharge_attention;
        $data['user_extract_balance_status'] = (int)sys_config('user_extract_balance_status', 1);
        return app('json')->successful($data);
    }
}
