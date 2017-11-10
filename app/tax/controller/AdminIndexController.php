<?php

namespace app\tax\controller;

use cmf\controller\AdminBaseController;
use think\Db;

/**
 * Class AdminIndexController
 * @package app\tax\controller
 *
 * @adminMenuRoot(
 *     'name'   =>'案件管理',
 *     'action' =>'default',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10,
 *     'icon'   =>'group',
 *     'remark' =>'案件管理'
 * )
 *
 */
class AdminIndexController extends AdminBaseController
{

    public function index()
    {

    }

}
