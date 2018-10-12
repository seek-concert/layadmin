<?php
/*
|--------------------------------------------------------------------------
| 菜单节点管理
|--------------------------------------------------------------------------
*/
namespace app\admin\controller;

class Menu extends Base
{
    /* ========== 初始化 ========== */
    public function _initialize()
    {
        parent::_initialize();
    }

    /* ============ 菜单列表管理 ============== */
    public function index(){
        return view();
    }
}