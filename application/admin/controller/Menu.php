<?php
/*
|--------------------------------------------------------------------------
| 菜单节点管理
|--------------------------------------------------------------------------
*/
namespace app\admin\controller;

use app\admin\model\Menus;

class Menu extends Base
{
    /* ========== 初始化 ========== */
    public function _initialize()
    {
        parent::_initialize();
    }

    /* ============ 菜单列表管理 ============== */
    public function index(){
        if(request()->isAjax()){

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (!empty($param['searchText'])) {
                $where['name'] = ['like', '%' . $param['searchText'] . '%'];
            }

            $selectResult = Menus::where($where)->limit($offset, $limit)->select();

            $display = config('menu_display');

            // 拼装参数
            foreach($selectResult as $key=>$vo){

                $selectResult[$key]['display'] = $display[$vo['display']];

                $selectResult[$key]['operate'] = showOperate(makeButton('menu',$vo['id']));
            }

            $return['total'] = Menus::where($where)->count();  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }
        return view();
    }

    /* ============ 树形菜单管理 ============== */
    public function tree_menu(){
        return view();
    }

    /* ============ 添加菜单节点 ============== */
    public function add(){
        if(request()->isPost()){
            $param = input('post.');
            $rule = [
                ['parent_id', 'require', '请填写上级菜单节点!'],
                ['name', 'require', '请填写菜单节点!'],
                ['url', 'require', '请填写菜单节点url!']
            ];
            $result = $this->validate($param, $rule);
            if (true !== $result) {
               return $this->error($result);
            }

            $model = new Menus();
            $rs =  $model->save($param);
            if($rs){
                return $this->success('添加成功!');
            }else{
                return $this->error('添加失败!');
            }
        }

        $menu = Menus::where(['display'=>1])->select();
        $this->assign([
            'menu_info' => $menu,
            'display' => config('menu_display')
        ]);

        return view();
    }
}