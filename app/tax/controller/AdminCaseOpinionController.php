<?php
namespace app\tax\controller;

use cmf\controller\AdminBaseController;
use app\tax\model\TaxCaseOpinionModel;
use think\Db;

class AdminCaseOpinionController extends AdminBaseController
{
	/**
     * 
     * @adminMenu(
     *     'name'   => '处理意见列表',
     *     'parent' => 'tax/AdminIndex/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '处理意见列表',
     *     'param'  => ''
     * )
     */

    public function index()
    {
    	$param = $this->request->param();
    	$TaxCaseOpinionModel  = new TaxCaseOpinionModel();

    	$data  = $TaxCaseOpinionModel->adminOpinionList($param);

        $data->appends($param);

    	$this->assign('start_time', isset($param['start_time']) ? $param['start_time'] : '');
        $this->assign('end_time', isset($param['end_time']) ? $param['end_time'] : '');
        $this->assign('keyword', isset($param['keyword']) ? $param['keyword'] : '');

        $this->assign('opinion', $data->items());
        $this->assign('page', $data->render());
        return $this->fetch();
    }

    
}
