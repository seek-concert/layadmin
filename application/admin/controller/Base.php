<?php
/*
|--------------------------------------------------------------------------
| 基础控制器
|--------------------------------------------------------------------------
*/
namespace app\admin\controller;
use think\Controller;

class Base extends Controller
{
    /* ========== 初始化 ========== */
    public function _initialize()
    {
        parent::_initialize();
    }

    /* ========== 左侧菜单栏 ========== */
    public function get_menu(){
        $role_id = session('role_id');

    }
    
}