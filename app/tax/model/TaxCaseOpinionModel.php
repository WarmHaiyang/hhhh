<?php
namespace app\tax\model;

use think\Model;
use think\Db;
/**
* 处理意见表
*/
class TaxCaseOpinionModel extends Model
{
	// 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;
    // 关闭自动写入update_time字段
    protected $updateTime = false;
	/**
    * 关联用户
    */
    public function user()
    {
        return $this->belongsTo('UserModel','user_id')->setEagerlyType(1);
    }
    /**
    * 关联案件
    */
    public function taxCase()
    {
        return $this->belongsTo('taxCaseModel','case_id')->setEagerlyType(1);
    }
	/**
    * 处理意见列表
    */
    public function adminOpinionList($filter){
        $where = [
            'a.create_time' => ['>=', 0]
        ];
        //如果有case_id
        if(!empty($filter['case_id'])){
            $where['t.id'] = ['eq', $filter['case_id']];
        }

        $join = [
            ['__USER__ u', 'a.user_id = u.id'],
            ['__TAX_CASE__ t', 'a.case_id = t.id']
        ];

        $field = 'a.*,t.case_status,u.user_login,u.user_nickname,u.user_email';

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
            $where['t.archive_id'] = ['eq', "$keyword"];
        }

        $case = $this->alias('a')->field($field)
            ->join($join)
            ->where($where)
            ->order('a.create_time', 'DESC')
            ->paginate(10);

        return $case;
    }
}