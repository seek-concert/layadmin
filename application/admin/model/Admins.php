<?php
/*
|--------------------------------------------------------------------------
| 后台用户模型
|--------------------------------------------------------------------------
*/
namespace app\admin\model;
use think\Model;

class Admins extends Model
{
    protected $table = 'lay_admin';
    protected $field=true;
    protected $type = [

    ];

    /*------ 后台用户角色 ------*/
    public function adminRole()
    {
        return $this->hasOne('roles','id','role_id');
    }
}