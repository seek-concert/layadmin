<?php
/*
|--------------------------------------------------------------------------
| 后台菜单模型
|--------------------------------------------------------------------------
*/

namespace app\admin\model;
use think\Model;

class Menus extends Model
{
    protected $table = 'lay_menu';
    protected $field=true;
    protected $type = [

    ];

    /*------ 菜单子级 ------*/
    public function menuChild()
    {
        return $this->hasMany('menus','parent_id');
    }

    /*------ 菜单子级 ------*/
    public function menuParent()
    {
        return $this->hasOne('menus','id','parent_id');
    }
}