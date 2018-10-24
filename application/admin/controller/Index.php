<?php
/*
|--------------------------------------------------------------------------
| 登陆
|--------------------------------------------------------------------------
*/
namespace app\admin\controller;
use app\admin\model\Admins;
use think\Controller;
use org\Verify;

class Index extends Controller
{
    /* ========== 初始化 ========== */
    public function _initialize()
    {
        parent::_initialize();
    }

    /* ============ 登录页 ============== */
    public function index(){
        return view('/login');
    }

    /* ============ 登录处理 ============== */
    public function login(){
        $data = input('param.');
        if(!$data['username']){
            return json(msg(0,'','请输入用户名！'));
        }
        if(!$data['password']){
            return json(msg(0,'','请输入密码！'));
        }
//        if(!$data['code']){
//            return json(msg(0,'','请输入验证码！'));
//        }

        //验证数据
//        if(!captcha_check($data['code'])){
//            return json(msg(0,'','验证码输入错误！'));
//        };
        $admin_info = Admins::field(['id','name','username','password','status','role_id'])
            ->with(['adminRole'])
            ->where(['username'=>$data['username']])
            ->find();
        if(is_null($admin_info)){
            return json(msg(0,'','该用户名不存在！'));
        }
        if(0===$admin_info['status']){
            return json(msg(0,'','该用户已被禁用！'));
        };
       if($admin_info['password']!=md5($data['password'])){
           return json(msg(0,'','密码输入错误！'));
       };

        $secret = create_guid();
        $login_ip = request()->ip();
        /* 写入登陆数据 */
        $updateAdminData = array (
            'secret'        => $secret,
            'last_login_ip'       => $login_ip,
            'updated_at'       => time(),
        );
        if (!model('admins')->where(['id'=>$admin_info['id']])->update($updateAdminData, FALSE)) {
            return json(msg(0,'','登录异常，请稍候尝试.'));
        }

        session('name',$admin_info->name);
        session('secret',$secret);
        session('type',$admin_info['admin_role']['type']);
        session('role_id',$admin_info['role_id']);
        session('role_name',$admin_info['admin_role']['name']);

        return  json(msg(1,url('home/index'),'登录成功'));
    }

    /* ============ 登录处理 ============== */
    public function logout(){
        session(null);
        return $this->redirect(url('index/index'));
    }


    /* ============ 清除缓存 ============== */
    public function delete_cacha(){
        if(!deleteAll('../runtime/cache')){
            return json('清除缓存失败');
        }
        if(!deleteAll('../runtime/temp')){
            return json('清除缓存失败');
        }

        return $this->success('清除缓存完成');
    }
}