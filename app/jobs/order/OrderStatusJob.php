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

namespace app\jobs\order;


use app\services\order\StoreOrderStatusServices;
use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;

/**
 * Class OrderStatusJob
 * @package app\jobs\order
 */
class OrderStatusJob extends BaseJobs
{

    use QueueTrait;

	/**
 	* 写入订单记录
	* @param $type
	* @param $orderId
	* @param $data
	* @return bool
	*/
    public function doJob($type, $orderId, $data = [])
    {
		if (!$type || !$orderId) {
			return true;
		}
		try {
			/** @var StoreOrderStatusServices $statusService */
			$statusService = app()->make(StoreOrderStatusServices::class);
			$statusService->saveStatus($type, $orderId, $data);
		} catch (\Throwable $e) {
			response_log_write([
				'message' => '写入订单【'.$type.'】记录失败,失败原因:' . $e->getMessage(),
				'file' => $e->getFile(),
				'line' => $e->getLine()
			]);
		}
		return true;
    }

}
