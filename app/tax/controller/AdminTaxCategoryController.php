<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace app\tax\controller;

use cmf\controller\AdminBaseController;
use app\tax\model\TaxPortalCategoryModel;
use think\Db;
use app\admin\model\ThemeModel;


class AdminTaxCategoryController extends AdminBaseController
{
    /**
     * 新闻分类列表
     * @adminMenu(
     *     'name'   => '分类管理',
     *     'parent' => 'tax/AdminTaxArticle/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '新闻分类列表',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $portalCategoryModel = new TaxPortalCategoryModel();
        $categoryTree        = $portalCategoryModel->adminCategoryTableTree();

        $this->assign('category_tree', $categoryTree);
        return $this->fetch();
    }

    /**
     * 添加新闻分类
     * @adminMenu(
     *     'name'   => '添加新闻分类',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加新闻分类',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        $parentId            = $this->request->param('parent', 0, 'intval');
        $portalCategoryModel = new TaxPortalCategoryModel();
        $categoriesTree      = $portalCategoryModel->adminCategoryTree($parentId);

        $this->assign('categories_tree', $categoriesTree);
        return $this->fetch();
    }

    /**
     * 添加新闻分类提交
     * @adminMenu(
     *     'name'   => '添加新闻分类提交',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加新闻分类提交',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        $portalCategoryModel = new TaxPortalCategoryModel();

        $data = $this->request->param();

        $result = $this->validate($data, 'TaxPortalCategory');

        if ($result !== true) {
            $this->error($result);
        }

        $result = $portalCategoryModel->addCategory($data);

        if ($result === false) {
            $this->error('添加失败!');
        }

        $this->success('添加成功!', url('AdminTaxCategory/index'));

    }

    /**
     * 编辑新闻分类
     * @adminMenu(
     *     'name'   => '编辑新闻分类',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑新闻分类',
     *     'param'  => ''
     * )
     */
    public function edit()
    {
        $id = $this->request->param('id', 0, 'intval');
        if ($id > 0) {
            $category = TaxPortalCategoryModel::get($id)->toArray();

            $portalCategoryModel = new TaxPortalCategoryModel();
            $categoriesTree      = $portalCategoryModel->adminCategoryTree($category['parent_id'], $id);

            $this->assign($category);
            $this->assign('categories_tree', $categoriesTree);
            return $this->fetch();
        } else {
            $this->error('操作错误!');
        }

    }

    /**
     * 编辑新闻分类提交
     * @adminMenu(
     *     'name'   => '编辑新闻分类提交',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑新闻分类提交',
     *     'param'  => ''
     * )
     */
    public function editPost()
    {
        $data = $this->request->param();

        $result = $this->validate($data, 'TaxPortalCategory');

        if ($result !== true) {
            $this->error($result);
        }

        $portalCategoryModel = new TaxPortalCategoryModel();

        $result = $portalCategoryModel->editCategory($data);

        if ($result === false) {
            $this->error('保存失败!');
        }

        $this->success('保存成功!');
    }

    /**
     * 新闻分类选择对话框
     * @adminMenu(
     *     'name'   => '新闻分类选择对话框',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '新闻分类选择对话框',
     *     'param'  => ''
     * )
     */
    public function select()
    {
        $ids                 = $this->request->param('ids');
        $selectedIds         = explode(',', $ids);
        $portalCategoryModel = new TaxPortalCategoryModel();

        $tpl = <<<tpl
<tr class='data-item-tr'>
    <td>
        <input type='checkbox' class='js-check' data-yid='js-check-y' data-xid='js-check-x' name='ids[]'
               value='\$id' data-name='\$name' \$checked>
    </td>
    <td>\$id</td>
    <td>\$spacer <a href='\$url' target='_blank'>\$name</a></td>
</tr>
tpl;

        $categoryTree = $portalCategoryModel->adminCategoryTableTree($selectedIds, $tpl);

        $where      = ['delete_time' => 0];
        $categories = $portalCategoryModel->where($where)->select();

        $this->assign('categories', $categories);
        $this->assign('selectedIds', $selectedIds);
        $this->assign('categories_tree', $categoryTree);
        return $this->fetch();
    }

    /**
     * 新闻分类排序
     * @adminMenu(
     *     'name'   => '新闻分类排序',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '新闻分类排序',
     *     'param'  => ''
     * )
     */
    public function listOrder()
    {
        parent::listOrders(Db::name('tax_portal_category'));
        $this->success("排序更新成功！", '');
    }

    /**
     * 删除新闻分类
     * @adminMenu(
     *     'name'   => '删除新闻分类',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '删除新闻分类',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        $portalCategoryModel = new TaxPortalCategoryModel();
        $id                  = $this->request->param('id');
        //获取删除的内容
        $findCategory = $portalCategoryModel->where('id', $id)->find();

        if (empty($findCategory)) {
            $this->error('分类不存在!');
        }

        $categoryChildrenCount = $portalCategoryModel->where(['parent_id'=>$id,'delete_time'=>'0'])->count();

        if ($categoryChildrenCount > 0) {
            $this->error('此分类有子类无法删除!');
        }

        $categoryPostCount = Db::name('tax_portal_category_post')->alias('a')->join('__TAX_PORTAL_POST__ b','a.post_id = b.id')->where(['a.category_id'=>$id,'b.delete_time'=>'0'])->count();

        if ($categoryPostCount > 0) {
            $this->error('此分类有新闻无法删除!');
        }

        $result = $portalCategoryModel
            ->where('id', $id)
            ->update(['delete_time' => time()]);
        if ($result) {
            $this->success('删除成功!');
        } else {
            $this->error('删除失败');
        }
    }
}
