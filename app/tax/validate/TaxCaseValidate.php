<?php
namespace app\tax\validate;

use think\Validate;

class TaxCaseValidate extends Validate
{
    protected $rule = [
        'case_status' => 'require',
        'phone'		  => 'require|length:11',
        'id_card'     => 'require|length:18'
    ];
    protected $message = [
        'case_status.require' => '案件状态不能为空！',
        'phone.require'       => '手机号不能为空',
        'id_card.require'     => '身份证号不能为空'
    ];

    protected $scene = [
        'edit' => ['case_status'],
    ];
}