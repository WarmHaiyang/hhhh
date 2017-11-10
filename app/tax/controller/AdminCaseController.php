<?php
namespace app\tax\controller;

use cmf\controller\AdminBaseController;
use app\tax\model\TaxCaseModel;
use think\Db;
use juhe\Juhe;

class AdminCaseController extends AdminBaseController
{
	/**
     * 
     * @adminMenu(
     *     'name'   => '案件列表',
     *     'parent' => 'tax/AdminIndex/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10,
     *     'icon'   => '',
     *     'remark' => '案件列表',
     *     'param'  => ''
     * )
     */
    public function index()
    {
    	$param = $this->request->param();
    	$TaxCaseModel  = new TaxCaseModel();

    	$data  = $TaxCaseModel->adminCaseList($param);
    	$data->appends($param);
    	$this->assign('start_time', isset($param['start_time']) ? $param['start_time'] : '');
        $this->assign('end_time', isset($param['end_time']) ? $param['end_time'] : '');
        $this->assign('keyword', isset($param['keyword']) ? $param['keyword'] : '');
        $this->assign('case', $data->items());
        $this->assign('page', $data->render());
        
        return $this->fetch();
    }

    /**
     * 
     * @adminMenu(
     *     'name'   => '案件编辑',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '案件编辑',
     *     'param'  => ''
     * )
     */
    public function edit()
    {
    	$id = $this->request->param('id', 0, 'intval');
    	$TaxCaseModel = new TaxCaseModel();
    	$post 		  = $TaxCaseModel->where('id', $id)->find();

    	$this->assign('case', $post);
    	$this->assign('case_status',$post->getData('case_status')); //原始数据
    	return $this->fetch();
    }

    /**
     * 编辑案件提交
     * @adminMenu(
     *     'name'   => '编辑案件提交',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑案件提交',
     *     'param'  => ''
     * )
     */
    public function editPost()
    {

        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $case   = $data['case'];
            $result = $this->validate($case, 'TaxCase.edit'); // edit场景
            if ($result !== true) {
                $this->error($result);
            }

            $TaxCaseModel = new TaxCaseModel();
            
            $TaxCaseModel->adminEditCase($data['case']);
            if($TaxCaseModel->getData('case_status') == '2'){ //已完成,发短信
                $juhe = new Juhe([
                    'key'   => '4f947d29c482967df06c74e1a07a7b58', //您申请的APPKEY
                    'mobile'    => '15539728747', //接受短信的用户手机号码
                    'tpl_id'    => '11099', //您申请的短信模板ID，根据实际情况修改
                    'tpl_value' =>'#code#=1234' //您设置的模板变量，根据实际情况修改
                ]);
                if($juhe->send() == '0'){
                    $this->success('保存成功，短信通知成功!');
                }else{
                    $this->success('保存成功，短信通知失败，请联系管理员!');
                }
            }else{
                $this->success('保存成功!');
            }
        }
    }
}
