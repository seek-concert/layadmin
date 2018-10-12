<?php
/*
|--------------------------------------------------------------------------
| 登陆
|--------------------------------------------------------------------------
*/
namespace app\admin\controller;
use app\admin\model\Admins;
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
        if(!$data['username']){
            return $this->error('请输入用户名！');
        }
        if(!$data['password']){
            return $this->error('请输入密码！');
        }
        if(!$data['code']){
            return $this->error('请输入验证码！');
        }

        //验证数据
        if(!captcha_check($data['code'])){
            return $this->error('验证码输入错误！');
        };
        $admin_info = Admins::field(['id','name','username','password','status'])
            ->where(['username'=>$data['username']])
            ->find();
        if(is_null($admin_info)){
            return $this->error('该用户名不存在！');
        }
        if(0===$admin_info->status){
            return $this->error('该用户已被禁用！');
        };
       if($admin_info->password!=md5($data['password'])){
           return $this->error('密码输入错误！');
       };

        $secret = create_guid();
        $loginip = get_client_ip();
        /* 写入登陆数据 */
        $updateAdminData = array (
            'secret'        => $secret,
            'lastloginip'       => $loginip,
        );
        if (!model('admins')->where(['id'=>$admin_info['id']])->update($updateAdminData, FALSE)) {
            return $this->error('登录异常，请稍候尝试.');
        }
        session('name',$admin_info->name);
        session('secret',$secret);

        return  $this->success('登录成功',url('home/index'));
    }

    /* ============ 登录处理 ============== */
    public function logout(){
        session(null);
        return $this->redirect(url('index/index'));
    }


    /* ============ 清除缓存 ============== */
    public function delete_cacha(){
        if(!deleteAll('../runtime/cache')){
            return $this->error('清除缓存失败');
        }
        if(!deleteAll('../runtime/temp')){
            return $this->error('清除缓存失败');
        }

        return $this->success('清除缓存完成');
    }
}