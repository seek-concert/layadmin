<?php
/*
|--------------------------------------------------------------------------
| 登陆
|--------------------------------------------------------------------------
*/
namespace app\admin\controller;
use think\Controller;

class Index extends Controller
{
    /* ========== 初始化 ========== */
    public function _initialize()
    {
        parent::_initialize();
    }

    /* ============ 登录页 ============== */
    public function index(){
        return view('login');
    }

    /* ============ 登录处理 ============== */
    public function login(){
        $data = input();
//        $this->success('111');
    }

}