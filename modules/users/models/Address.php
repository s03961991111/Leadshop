<?php
/**
 * 退货地址
 * @link https://www.leadshop.vip/
 * @copyright Copyright ©2020-2021 浙江禾成云计算有限公司
 */
namespace users\models;

use framework\common\CommonModels;

class Address extends CommonModels
{
    const id           = ['bigkey' => 10, 'unique', 'comment' => 'ID'];
    const name         = ['varchar' => 50, 'notNull', 'comment' => '收件人'];
    const mobile       = ['varchar' => 50, 'notNull', 'comment' => '联系电话'];
    const province     = ['varchar' => 50, 'notNull', 'comment' => '省'];
    const city         = ['varchar' => 50, 'notNull', 'comment' => '市'];
    const district     = ['varchar' => 50, 'notNull', 'comment' => '区县'];
    const address      = ['varchar' => 255, 'notNull', 'comment' => '详细地址'];
    const status       = ['tinyint' => 1, 'notNull', 'comment' => '0非默认  1默认'];
    const UID          = ['bigint' => 20, 'notNull', 'comment' => '用户ID'];
    const created_time = ['bigint' => 10, 'comment' => '创建时间'];
    const updated_time = ['bigint' => 10, 'comment' => '修改时间'];
    const deleted_time = ['bigint' => 10, 'comment' => '删除时间'];
    const is_deleted   = ['tinyint' => 1, 'default' => 0, 'comment' => '删除状态'];

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
            [['name', 'mobile', 'province', 'city', 'district', 'address', 'UID'], 'required', 'message' => '{attribute}不能为空'],
            [['UID', 'status'], 'integer', 'message' => '{attribute}必须是整数'],
            [['mobile'], 'match', 'pattern' => '/^1[0-9]{10}$/', 'message' => '{attribute}必须为手机号'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_address}}';
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

    /**
     * 定义场景字段
     * @return [type] [description]
     */
    public function scenarios()
    {
        $scenarios           = parent::scenarios();
        $scenarios['create'] = ['name', 'mobile', 'province', 'city', 'district', 'address', 'UID', 'status'];
        $scenarios['update'] = ['name', 'mobile', 'province', 'city', 'district', 'address', 'status'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'     => '联系人',
            'mobile'   => '联系方式',
            'province' => '省',
            'city'     => '市',
            'district' => '区',
            'address'  => '详细地址',
        ];
    }
}
