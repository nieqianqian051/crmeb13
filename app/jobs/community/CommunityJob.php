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

namespace app\jobs\community;


use app\services\community\CommunityCommentServices;
use app\services\community\CommunityServices;
use app\services\community\CommunityUserServices;
use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;
use think\facade\Log;

/**
 * 社区帖子
 * Class CommunityJob
 * @package app\jobs\community
 */
class CommunityJob extends BaseJobs
{
    use QueueTrait;


    /**
     * 用户发帖数数据矫正
     * @param $id
     * @return true
     * User: liusl
     * DateTime: 2024/9/9 17:56
     */
    public function communityUserSync($id)
    {
        try {
            /** @var CommunityUserServices $communityUserServices */
            $communityUserServices = app()->make(CommunityUserServices::class);
            $communityUserServices->syncUserNum($id);
        } catch (\Throwable $e) {
            response_log_write([
                'message' => '用户发帖数数据矫正:' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
        return true;
    }

    /**
     * 评价同步社区
     * @param $data
     * @return true
     * User: liusl
     * DateTime: 2024/8/27 12:15
     */
    public function computedSync($data)
    {
        if (!$data) return true;
        try {
            /** @var CommunityServices $communityServices */
            $communityServices = app()->make(CommunityServices::class);
            $data['image'] = $data['slider_image'][0] ?? '';
            $data['slider_image'] = json_encode($data['slider_image']);
            $data['is_verify'] = sys_config('community_verify', 1) ? 0 : 1;
            $data['topic_id'] = [];
            $communityServices->saveData($data);
        } catch (\Throwable $e) {
            response_log_write([
                'message' => '商品评价同步到社区失败,错误原因:' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
        return true;
    }

    /**
     * 删除帖子后续
     * @param $id
     * @return true
     * User: liusl
     * DateTime: 2024/8/28 16:16
     */
    public function communityDelete($id)
    {
        if (!$id) return true;
        try {
            /** @var CommunityServices $communityServices */
            $communityServices = app()->make(CommunityServices::class);
            $communityServices->deleteFollow($id);
            //用户发帖数数据矫正
            $this->communityUserSync($id);
        } catch (\Throwable $e) {
            response_log_write([
                'message' => '帖子删除错误,错误原因:' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
        return true;
    }

    /**
     * 帖子评论删除后续操作
     * @param $id
     * @return true
     * User: liusl
     * DateTime: 2024/8/28 16:18
     */
    public function CommunityCommentDelete($id)
    {
        if (!$id) return true;
        try {
            /** @var CommunityCommentServices $communityCommentServices */
            $communityCommentServices = app()->make(CommunityCommentServices::class);
            $communityCommentServices->deleteFollow($id);
        } catch (\Throwable $e) {
            response_log_write([
                'message' => '帖子评论删除错误,错误原因:' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
        return true;
    }

    /**
     * 用户评论数数据矫正
     * @param $id
     * @return true
     * User: liusl
     * DateTime: 2024/9/10 11:08
     */
    public function communityCommentSync($id)
    {
        try {
            /** @var CommunityCommentServices $communityCommentServices */
            $communityCommentServices = app()->make(CommunityCommentServices::class);
            $communityCommentServices->syncCommentNum($id);
        } catch (\Throwable $e) {
            response_log_write([
                'message' => '用户评论数数据矫正失败:' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
        return true;
    }

    /**
     * 话题对应帖子数矫正
     * @param $id
     * @return true
     * User: liusl
     * DateTime: 2024/11/11 17:58
     */
    public function communityTopicSync($id)
    {
        try {
            /** @var CommunityServices $communityServices */
            $communityServices = app()->make(CommunityServices::class);
            $communityServices->syncTopicNum($id);
        } catch (\Throwable $e) {
            response_log_write([
                'message' => '话题帖子数矫正:' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
        return true;
    }
}
