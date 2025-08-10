<?php
/**
 * Created by PhpStorm.
 * User: Andy - 阿德 569937993@qq.com
 * Date: 2021/7/30
 * Time: 10:42
 */

namespace app\components\cloud;
class CloudAuth extends BaseCloud
{
    // 修改 getAuthData 方法为返回一个默认数据结构
    public function getAuthData($params = [])
    {
        // 返回一个默认的数据结构，表示授权信息无法获取
        return [
            'status' => false,
            'message' => '授权服务已关闭',
            // 根据您的业务逻辑添加其他必要的默认属性
            // 'data' => [],
        ];
    }
}