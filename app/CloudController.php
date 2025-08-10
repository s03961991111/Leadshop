<?php
/**
 * @link https://www.leadshop.vip/
 * @copyright Copyright ©2020-2021 浙江禾成云计算有限公司
 */

namespace leadmall\app;
use basics\app\BasicsController as BasicsModules;
use leadmall\Map;
use yii\web\Response;

class CloudController extends BasicsModules
{
    public function actionIndex()
    {
        // 假设授权服务已经关闭，我们不再尝试获取授权数据
        $authData = [
            'status' => false,
            'message' => '授权服务暂时不可用',
        ];

        // 根据您的应用需求，您可以返回 JSON 格式的响应或渲染一个视图来显示错误信息
        \Yii::$app->response->format = Response::FORMAT_JSON;
        
        return $authData;
        
        // 如果您希望跳转到一个错误页面（例如403 Forbidden），可以使用如下代码：
        // return $this->redirect(['error', 'message' => '授权服务暂时不可用']);
        // 然后在 error 视图中处理这个错误信息。
    }
}