<?php
/*
|--------------------------------------------------------------------------
| 登陆
|--------------------------------------------------------------------------
*/
namespace app\admin\controller;

class Home extends Base
{
    /* ========== 初始化 ========== */
    public function _initialize()
    {
        parent::_initialize();
    }

    /* ============ 后台首页 ============== */
    public function index(){
        //左侧菜单栏目
        $menu = $this->get_menu();
        $this->assign('menus',objToArray($menu));
        //管理员信息
        $admin_info['name'] = session('name');
        $admin_info['role_name'] = session('role_name');
        $this->assign('infos',$admin_info);
        return view();
    }

    /* ============ 后台展示区主页 ============== */
    public function welcome(){
        return view();
    }
}