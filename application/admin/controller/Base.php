<?php
/*
|--------------------------------------------------------------------------
| 基础控制器
|--------------------------------------------------------------------------
*/
namespace app\admin\controller;
use app\admin\model\Menus;
use app\admin\model\Roles;
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
        if(session('type')==0){
            //超级管理员
           $menus =  Menus::with(['menuChild'=>function($query){
               $query->where(['display'=>1]);
           }])
               ->where(['parent_id'=>0,'display'=>1])
               ->select();
//        dump(objToArray($menus));
        }else{
            $role_id = session('role_id');
            $menus_ids = Roles::where(['id'=>$role_id])->field(['menu_ids'])->find();
            $ids = explode(',',$menus_ids);
//            dump($ids);
            $menus = '';
        }
        return $menus;


    }
    
}