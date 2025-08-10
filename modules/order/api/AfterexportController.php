<?php
/**
 * 售后订单导出控制器
 * @link https://www.leadshop.vip/
 * @copyright Copyright ©2020-2021 浙江禾成云计算有限公司
 */
namespace order\api;

use framework\common\BasicController;
use Yii;
use yii\data\ActiveDataProvider;

class AfterexportController extends BasicController
{
    public $modelClass = 'order\models\OrderAfterExport';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        return $actions;
    }

    public function actionIndex()
    {
        //获取头部信息
        $headers = Yii::$app->getRequest()->getHeaders();
        //获取分页信息
        $pageSize = $headers->get('X-Pagination-Per-Page') ?? 20;

        $merchant_id = 1;
        $AppID       = Yii::$app->params['AppID'];
        $where       = [
            'is_deleted'  => 0,
            'merchant_id' => $merchant_id,
            'AppID'       => $AppID,
        ];

        $data = new ActiveDataProvider(
            [
                'query'      => $this->modelClass::find()->where($where)->orderBy(['created_time' => SORT_DESC])->asArray(),
                'pagination' => ['pageSize' => $pageSize, 'validatePage' => false],
            ]
        );

        $list = $data->getModels();
        foreach ($list as $key => &$value) {
            $value['conditions']       = to_array($value['conditions']);
            $value['parameter']        = to_array($value['parameter']);
            $value['order_after_data'] = to_array($value['order_after_data']);
        }
        $data->setModels($list);
        return $data;
    }

    public function actionCreate()
    {

        $keyword = Yii::$app->request->post('conditions', []); //查询条件
        $filter  = Yii::$app->request->post('parameter', []); //筛选的字段

        //处理获取商品类型
        $tab_key = $keyword['tab_key'] ?? 'all';
        switch ($tab_key) {
            case 'waitaudit': //待审核
                $where = ['after.status' => [100, 102], 'after.is_deleted' => 0];
                break;
            case 'bybuyer': //用户操作
                $where = ['after.status' => [121, 131, 133], 'after.is_deleted' => 0];
                break;
            case 'bymerchant': //商家操作
                $where = ['after.status' => [111, 122, 132], 'after.is_deleted' => 0];
                break;
            case 'finished': //已完成
                $where = ['after.status' => [200, 201], 'after.is_deleted' => 0];
                break;
            case 'closed': //已关闭
                $where = ['after.status' => 101, 'after.is_deleted' => 0];
                break;
            case 'recycle': //回收站
                $where = ['after.is_deleted' => 1];
                break;

            default: //默认获取全部
                $where = ['after.is_deleted' => 0];
                break;
        }

        $merchant_id = 1;
        $AppID       = Yii::$app->params['AppID'];
        $where       = ['and', $where, ['after.merchant_id' => $merchant_id, 'after.AppID' => $AppID]];

        //申请类型
        $type = $keyword['type'] ?? false;
        if ($type) {
            $where = ['and', $where, ['after.type' => $type]];
        }

        //订单来源
        $source = $keyword['source'] ?? false;
        if ($source) {
            $where = ['and', $where, ['after.source' => $source]];
        }

        //时间区间
        $time_start = $keyword['time_start'] ?? false;
        if ($time_start > 0) {
            $where = ['and', $where, ['>=', 'after.created_time', $time_start]];
        } else {
            $after                 = M('order', 'OrderAfter')::find()->where(['AppID' => $AppID])->orderBy(['created_time' => SORT_ASC])->one();
            $keyword['time_start'] = $after->created_time;
        }
        $time_end = $keyword['time_end'] ?? false;
        if ($time_end > 0) {
            $where = ['and', $where, ['<=', 'after.created_time', $time_end]];
        } else {
            $after               = M('order', 'OrderAfter')::find()->where(['AppID' => $AppID])->orderBy(['created_time' => SORT_DESC])->one();
            $keyword['time_end'] = $after->created_time;
        }

        $search_key = $keyword['search_key'] ?? false;
        $search     = $keyword['search'] ?? '';

        //订单编号
        if ($search_key == 'order_sn' && $search) {
            $where = ['and', $where, ['like', 'after.order_sn', $search]];
        }

        //订单编号
        if ($search_key == 'after_sn' && $search) {
            $where = ['and', $where, ['like', 'after.after_sn', $search]];
        }

        //买家昵称
        if ($search_key == 'buyer_nickname' && $search) {
            $where = ['and', $where, ['like', 'user.nickname', $search]];
        }

        //买家手机
        if ($search_key == 'buyer_mobile' && $search) {
            $where = ['and', $where, ['like', 'user.mobile', $search]];
        }

        //收货人名称
        if ($search_key == 'consignee_name' && $search) {
            $where = ['and', $where, ['like', 'buyer.name', $search]];
        }

        //收货人电话
        if ($search_key == 'consignee_mobile' && $search) {
            $where = ['and', $where, ['like', 'buyer.mobile', $search]];
        }
        $o_g_ids = [];
        //商品名称
        if ($search_key == 'goods_name' && $search) {
            $ids = M('Order', 'OrderGoods')::find()
                ->alias('g')
                ->leftJoin(['o' => M('Order', 'Order')::tablename()], 'o.order_sn = g.order_sn')
                ->where(['and', ['like', 'g.goods_name', $search], ['o.AppID' => $AppID, 'o.merchant_id' => $merchant_id]])->select('id')->asArray()->all();
            $ids     = array_column($ids, 'id');
            $o_g_ids = array_merge($o_g_ids, $ids);

        }
        //商品货号
        if ($search_key == 'goods_sn' && $search) {
            $ids = M('Order', 'OrderGoods')::find()
                ->alias('g')
                ->leftJoin(['o' => M('Order', 'Order')::tablename()], 'o.order_sn = g.order_sn')
                ->where(['and', ['like', 'g.goods_sn', $search], ['o.AppID' => $AppID, 'o.merchant_id' => $merchant_id]])->select('id')->asArray()->all();
            $ids     = array_column($ids, 'id');
            $o_g_ids = array_merge($o_g_ids, $ids);
        }

        if (!empty($o_g_ids)) {
            $o_g_ids = array_unique($o_g_ids);
            $where   = ['and', $where, ['goods.order_goods_id' => $o_g_ids]];
        }

        $data = M('order', 'OrderAfter')::find()
            ->alias('after')
            ->joinWith([
                'buyer as buyer',
                'goods as goods',
                'user as user',
                'order as order',
            ])
            ->where($where)
            ->groupBy(['after.id'])
            ->asArray()
            ->all();

        $tHeader     = [];
        $filterVal   = [];
        $filter_list = $filter;
        foreach ($filter_list as $v) {
            array_push($tHeader, $v['name']);
            array_push($filterVal, $v['value']);
        }

        $list = [];
        if (empty($filterVal)) {
            Error('未选择导出字段');
        }
        foreach ($data as $value) {
            if ($value['order_goods_id']) {
                $goods          = array_column($value['goods'], null, 'id');
                $new_goods[]    = isset($goods[$value['order_goods_id']]) ? $goods[$value['order_goods_id']] : null;
                $value['goods'] = $new_goods;
            }
            $build_data = $value;
            foreach ($value['goods'] as $goods) {
                $build_data['goods'] = $goods;
                $res                 = $this->listBuild($build_data, $filterVal);
                array_push($list, $res);
            }
        }

        $order_data = [
            'tHeader' => $tHeader,
            'list'    => $list,
        ];

        $ins_data = [
            'conditions'       => to_json($keyword),
            'parameter'        => to_json($filter),
            'order_after_data' => to_json($order_data),
            'merchant_id'      => $merchant_id,
            'AppID'            => $AppID,
        ];
        $model = new $this->modelClass;
        $model->setAttributes($ins_data);
        if ($model->save()) {
            return $order_data;
        } else {
            return $model;
            Error('保存失败');
        }

    }

    /**
     * 导出字段筛选
     * @param  [type] $data      [description]
     * @param  [type] $filterVal [description]
     * @return [type]            [description]
     */
    public function listBuild($data, $filterVal)
    {
        $return_data = [];
        foreach ($filterVal as $key) {
            $value = '';
            switch ($key) {
                case 'after_sn':
                    $value = $data['after_sn'];
                    break;
                case 'type':
                    $value = $data['type'] === 0 ? '仅退款' : ($data['type'] === 1 ? '退货退款' : '换货');
                    break;
                case 'order_sn':
                    $value = $data['order_sn'];
                    break;
                case 'created_time':
                    $value = $data['created_time'];
                    $value = $value ? date('Y-m-d H:i:s', $value) : '';
                    break;
                case 'status':
                    switch ($data['order']['status']) {
                        case 100:
                            $value = '待付款';
                            break;
                        case 101:
                            $value = '已取消';
                            break;
                        case 102:
                            $value = '已取消';
                            break;
                        case 103:
                            $value = '已取消';
                            break;
                        case 201:
                            $value = '已付款';
                            break;
                        case 202:
                            $value = '已发货';
                            break;
                        case 203:
                            $value = '已收货';
                            break;
                        case 204:
                            $value = '已完成';
                            break;

                    }
                    break;
                case 'return_reason':
                    $value = $data['return_reason'];
                    break;
                case 'buyer':
                    $buyer_info = to_array($data['buyer']['buyer_info']);
                    $value      = $buyer_info['mobile'] ?? '';
                    break;
                case 'consignee_name':
                    $consignee_info = $data['buyer'];
                    $value          = $consignee_info['name'];
                    break;
                case 'consignee_mobile':
                    $consignee_info = $data['buyer'];
                    $value          = $consignee_info['mobile'];
                    break;
                case 'consignee_address':
                    $consignee_info = $data['buyer'];
                    $value          = $consignee_info['province'] . $consignee_info['city'] . $consignee_info['district'] . $consignee_info['address'];
                    break;
                case 'user_logistics_company':
                    $user_freight_info = to_array($data['user_freight_info']);
                    $value             = $user_freight_info['logistics_company'] ?? '';
                    break;
                case 'user_freight_sn':
                    $user_freight_info = to_array($data['user_freight_info']);
                    $value             = $user_freight_info['freight_sn'] ?? '';
                    break;
                case 'merchant_logistics_company':
                    $merchant_freight_info = to_array($data['merchant_freight_info']);
                    $value                 = $merchant_freight_info['logistics_company'] ?? '';
                    break;
                case 'merchant_freight_sn':
                    $merchant_freight_info = to_array($data['merchant_freight_info']);
                    $value                 = $merchant_freight_info['freight_sn'] ?? '';
                    break;
                case 'goods_name':
                    $value = $data['goods']['goods_name'];
                    break;
                case 'goods_param':
                    $value = $data['goods']['goods_param'];
                    break;
                case 'goods_number':
                    $value = $data['goods']['goods_number'];
                    break;
            }

            array_push($return_data, $value);
        }

        return $return_data;

    }
}
