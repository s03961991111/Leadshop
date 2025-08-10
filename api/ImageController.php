<?php
/**
 * 商品管理
 * @link https://www.leadshop.vip/
 * @copyright Copyright ©2020-2021 浙江禾成云计算有限公司
 */

namespace leadmall\api;

use basics\api\BasicsController as BasicsModules;
use app\components\Upload;
use leadmall\Map;
use Yii;

class ImageController extends BasicsModules implements Map
{
    /**
     * 上传
     * @return [type] [description]
     */
    public function actionCreate()
    {
        $upload = new Upload();
        $type   = Yii::$app->request->post('type', 1);

        if ($type == 1) {
            $content = Yii::$app->request->post('content', false);

            if (empty($content)) {
                Error('图片不能为空');
            }

            $file      = $upload->image_base64($content);
            $host = $upload::$upload_way == 0 ? Yii::$app->request->hostInfo : '';
            $url       = $host . $file['url'];

        } elseif ($type == 2) {

            $content = $_FILES['content'];

            if (empty($content)) {
                Error('视频不能为空');
            }

            $file = $upload->video($content);
            $url  = Yii::$app->request->hostInfo . $file['url'];

        } else {
            Error('未定义操作');
        }

        $UID         = null;
        $AppID       = Yii::$app->params['AppID'];
        $merchant_id = 1;

        $this->module->event->user_upload = ['url' => $file['url'], 'size' => $file['size'], 'AppID' => $AppID, 'merchant_id' => $merchant_id, 'UID' => $UID];
        $this->module->trigger('user_upload');

        return $url;
    }
}
