<?php
namespace app\tax\model;

use think\Model;
use think\Db;

class TaxCaseModel extends Model
{
	//字段类型自动转换
	protected $type = [
        'more' => 'array',
    ];
    //添加时自动完成字段
    protected $insert = ['archive_id']; 
    //只读字段，一旦写入不会再更改
    protected $readonly = [];
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;
    /**
    * 生成唯一ID
    */
    // protected function setArchiveIdAttr()
    // {
    //     return request()->param();
    // }
    /**
    * 获取案件状态
    */
    public function getCaseStatusAttr($value){
    	$status = [0=>'未处理',1=>'处理中',2=>'处理完'];
    	return $status[$value];
    }
    /**
    * 关联意见表
    */
    public function CaseOpinions()
    {
        return $this->hasMany('TaxCaseOpinionModel','case_id','id');
    }
    /**
    * 后台案件列表
    */
    public function adminCaseList($filter)
    {

        $where = [
            'a.create_time' => ['>=', 0],
            'a.delete_time' => 0
        ];
        $join = [
            ['__USER__ b', 'a.user_id = b.id']
        ];

        $field = 'a.*';

        $startTime = empty($filter['start_time']) ? 0 : strtotime($filter['start_time']);
        $endTime   = empty($filter['end_time']) ? 0 : strtotime($filter['end_time']);

        if (!empty($startTime) && !empty($endTime)) {
            $where['a.create_time'] = [['>= time', $startTime], ['<= time', $endTime]];
        } else {
            if (!empty($startTime)) {
                $where['a.create_time'] = ['>= time', $startTime];
            }
            if (!empty($endTime)) {
                $where['a.create_time'] = ['<= time', $endTime];
            }
        }

        $keyword = empty($filter['keyword']) ? '' : $filter['keyword'];
        if (!empty($keyword)) {
            $where['a.case_title'] = ['like', "%$keyword%"];
        }

        $case = $this->alias('a')->field($field)
            ->where($where)
            ->order('update_time', 'DESC')
            ->paginate(10);

        return $case;
    }
    /**
    * 编辑案件
    */
    public function adminEditCase($data)
    {

        $this->allowField(true)->isUpdate(true)->data($data, true)->save();
        
        $opinions            = trim($data['opinion']) != '' ? ['opinion'=>$data['opinion']] : [];
        $opinions['user_id'] = cmf_get_current_admin_id();
        // 添加一条案件的处理意见
        $this->CaseOpinions()->save($opinions);
        return $this;
    }
}