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
            /*------ 超级管理员 ------*/
           $menus =  Menus::with(['menuChild'=>function($query){
               $query->where(['display'=>1]);
           }])
               ->where(['parent_id'=>0,'display'=>1])
               ->select();
        }else{
            /*------ 受约束角色 ------*/
            //当前角色可以显示的
            $role_id = session('role_id');
            $menus_ids_str = Roles::where(['id'=>$role_id])->field(['menu_ids'])->find();
            $menus_ids_arr = explode(',',$menus_ids_str['menu_ids']);

            $menus_arr = Menus::with(['menuChild'=>function($query)use($menus_ids_arr){
                $query->where('id','in',$menus_ids_arr);
            }])
                ->where('id','in',$menus_ids_arr)
                ->where(['parent_id'=>0])
                ->select();

            $menus = $menus_arr;
        }
        return $menus?:'';
    }
    
}