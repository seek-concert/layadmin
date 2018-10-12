<?php
/*
|--------------------------------------------------------------------------
| 后台用户管理
|--------------------------------------------------------------------------
*/
namespace app\admin\controller;

class Admin extends Base
{
    /* ========== 初始化 ========== */
    public function _initialize()
    {
        parent::_initialize();
    }

    /* ============ 后台首页 ============== */
    public function index(){
        return view('admin-info');
    }
}