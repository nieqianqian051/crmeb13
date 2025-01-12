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

namespace app\services\order;


use app\dao\order\StoreOrderStatusDao;
use app\services\BaseServices;
use think\annotation\Inject;
use think\exception\ValidateException;

/**
 * 订单状态
 * Class StoreOrderStatusServices
 * @package app\services\order
 * @mixin StoreOrderStatusDao
 */
class StoreOrderStatusServices extends BaseServices
{
    /**
     * @var StoreOrderStatusDao
     */
    #[Inject]
    protected StoreOrderStatusDao $dao;

    /**
     * 订单状态分页
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getStatusList(array $where)
    {
        $list = $this->dao->getStatusList($where);
        foreach ($list as &$item) {
            if (is_int($item['change_time'])) $item['change_time'] = date('Y-m-d H:i:s', $item['change_time']);
        }
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

	/**
 	* 保存订单记录
	* @param $type
	* @param $orderId
	* @param $data
	* @return bool
	*/
	public function saveStatus($type, $orderId, $data)
	{
		if (!$type || !$orderId) {
			return true;
		}
		$statusData = ['oid' => $orderId, 'change_time' => time(), 'change_type' => ''];
		switch ($type) {
			case 'create'://创建
				$statusData['change_type'] = 'cache_key_create_order';
				$statusData['change_message'] = '订单生成';
				if (isset($data['changePrice']) && $data['changePrice'] > 0) {//有改价
					$this->saveStatus('edit', $orderId, $data);
				}
				break;
			case 'edit'://改价
				$statusData['change_type'] = 'order_edit';
				$statusData['change_message'] = '商品总价为：' . ($data['totalPrice'] ?? 0) . ' 修改实际支付金额为：' . ($data['payPrice'] ?? 0);
				break;
			case 'pay'://支付
				$statusData['change_type'] = 'pay_success';
				$statusData['change_message'] = '用户付款成功';
				break;
			case 'delivery'://发货
				break;
			case 'take'://收货
				break;
			case 'reply'://评价
				break;
			default:
				throw new ValidateException('未知订单类型记录');
				break;
		}
		/** @var StoreOrderStatusServices $statusService */
		$statusService = app()->make(StoreOrderStatusServices::class);
		$statusService->save($statusData);
		return true;
	}

}
