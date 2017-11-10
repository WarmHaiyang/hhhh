<?php
namespace app\admin\model;
use think\Model;

class TestModel extends Model
{
	#命名 cmf_test -> TestModel.php
	public function getSexAttr($value='')
	{
		switch ($value) {
			case '1':
				return '男';
				break;
			case '2':
				return "未知";
				break;
			default:
				return "女";
				break;
		}
	}

	public function user(){
		return $this->hasOne('AbcdModel');
	}
}