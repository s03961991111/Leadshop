<?php
/**
 * 商品详情模型
 * @link https://www.leadshop.vip/
 * @copyright Copyright ©2020-2021 浙江禾成云计算有限公司
 */

namespace goods\models;

use framework\common\CommonModels;

class GoodsBody extends CommonModels
{
    const id              = ['bigkey' => 20, 'unique', 'comment' => 'ID'];
    const goods_id        = ['bigint' => 20, 'notNull', 'comment' => '商品ID'];
    const goods_introduce = ['varchar' => 255, 'comment' => '商品副标题'];
    const goods_args      = ['text' => 0, 'comment' => '商品参数'];
    const content         = ['text' => 0, 'comment' => '商品详情'];
    const created_time    = ['bigint' => 10, 'comment' => '创建时间'];
    const updated_time    = ['bigint' => 10, 'comment' => '修改时间'];
    const deleted_time    = ['bigint' => 10, 'comment' => '删除时间'];
    const is_deleted      = ['tinyint' => 1, 'default' => 0, 'comment' => '删除状态'];
    /**
     * 实现数据验证
     * 需要数据写入，必须在rules添加对应规则
     * 在控制中执行[模型]->attributes = $postData;
     * 否则会导致验证不生效，并且写入数据为空
     * @return [type] [description]
     */
    public function rules()
    {
        return [

        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_body}}';
    }

    /**
     * 增加额外属性
     * @return [type] [description]
     */
    public function attributes()
    {
        $attributes = parent::attributes();
        return $attributes;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

        ];
    }

}
