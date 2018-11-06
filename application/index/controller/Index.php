<?php
namespace app\index\controller;
use think\Controller;

class Index extends Controller
{
    /* ============ 首页 ============== */
    public function index()
    {
        return view();
    }

    /* ============ 游客信息 ============== */
    public function add_chatuser(){
        //是否已生成游客信息
        $ip = Request()->ip();
        $userinfo = db('user')->where('reg_ip',$ip)->find();
        if($userinfo){
            return json([
                'code' => 0,
                'msg' => '获取成功',
                'data' => [
                    'id'=>$userinfo['id'],
                    'username'=>$userinfo['username'],
                    'status'=>$userinfo['status'],
                    'sign'=>$userinfo['sign'],
                    'avatar'=>$userinfo['avatar'],
                ]
            ]);
        }
        //生成游客
        $datas['username'] = '游客'.date('YmdHis').rand(0000,9999);
        $datas['password'] = md5('123456');
        $datas['type'] = 1;
        $datas['status'] = '1';
        $datas['sign'] = '游客';
        $datas['avatar'] = 'http://chongmai.oss-cn-shanghai.aliyuncs.com/imgall/20180713/5b481935b7c5b.png';
        $datas['reg_ip'] = $ip;
        $datas['created_at'] = time();
        $rs = db('user')->insertGetId($datas);
        if($rs){
            return json([
                'code' => 0,
                'msg' => '生成成功',
                'data' => [
                    'id'=>$rs,
                    'username'=>$datas['username'],
                    'status'=>$datas['status'],
                    'sign'=>$datas['sign'],
                    'avatar'=>$datas['avatar'],
                ]
            ]);
        }else{
            return json([
                'code' => 1,
                'msg' => '生成失败',
                'data' => []
            ]);
        }

    }
}
