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
        return view();
    }

    /* ============ 后台展示区主页 ============== */
    public function welcome(){
        return view();
    }
}