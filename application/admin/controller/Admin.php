<?php
/*
|--------------------------------------------------------------------------
| 后台用户管理
|--------------------------------------------------------------------------
*/
namespace app\admin\controller;

use app\admin\model\Admins;
use app\admin\model\Roles;

class Admin extends Base
{
    /* ========== 初始化 ========== */
    public function _initialize()
    {
        parent::_initialize();
    }

    /* ============ 后台用户列表管理 ============== */
    public function index(){
        if(request()->isAjax()){

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (!empty($param['searchText'])) {
                $where['name'] = ['like', '%' . $param['searchText'] . '%'];
            }

            $selectResult = Admins::with(['adminRole'=>function($query){
                $query->field(['id','name']);
            }])->where($where)->limit($offset, $limit)->select();

            $status = config('user_status');

            // 拼装参数
            foreach($selectResult as $key=>$vo){

                $selectResult[$key]['status'] = $status[$vo['status']];
                $selectResult[$key]['role_name'] = $vo['admin_role']['name'];
                $selectResult[$key]['updated_at'] = $vo['updated_at']?date('Y-m-d H:i:s',$vo['updated_at']):'-';

                $selectResult[$key]['operate'] = showOperate(makeButton('admin',$vo['id']));
            }

            $return['total'] = Admins::where($where)->count();  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }
        return view();
    }

    /* ============ 添加后台用户 ============== */
    public function add(){
        if(request()->isPost()){
            $param = input('post.');
            $rule = [
                ['name', 'require', '请填写用户昵称!'],
                ['username', 'require', '请填写登陆账号!'],
                ['username', 'unique:admin', '账号已存在!'],
                ['password', 'require', '请填写登陆密码!'],
                ['role_id', 'require', '请选择用户角色!'],
            ];
            $result = $this->validate($param, $rule);
            if (true !== $result) {
                return $this->error($result);
            }

            $param['password'] = md5($param['password']);
            $param['secret'] = create_guid();
            $model = new Admins();
            $rs =  $model->save($param);
            if($rs){
                return $this->success('添加成功!',url('admin/index'));
            }else{
                return $this->error('添加失败!');
            }
        }

        $role = Roles::where(['type'=>1])->select();
        $this->assign([
            'role' => $role,
            'status' => config('user_status')
        ]);

        return view();
    }


    /* ============ 修改后台用户 ============== */
    public function edit(){
        $param = input('');
        if(!$param['id']){
            return $this->error('请选择后台用户！');
        }
        $admin = Admins::where(['id'=>$param['id']])->find();

        if(request()->isPost()){
            $rule = [
                ['name', 'require', '请填写用户昵称!'],
                ['username', 'require', '请填写登陆账号!'],
                ['password', 'require', '请填写登陆密码!'],
                ['role_id', 'require', '请选择用户角色!'],
            ];
            $result = $this->validate($param, $rule);
            if (true !== $result) {
                return $this->error($result);
            }

            if($param['password']!=$admin['password']){
                $param['password'] = md5($param['password']);
            }

            $model = new Admins();
            $rs =  $model->save($param,['id'=>$param['id']]);
            if($rs){
                return $this->success('修改成功!',url('admin/index'));
            }else{
                return $this->error('修改失败!');
            }
        }

        $role = Roles::where(['type'=>1])->select();


        $this->assign([
            'id'=>$param['id'],
            'role' => $role,
            'infos' => $admin,
            'status' => config('user_status')
        ]);

        return view();
    }

    /* ============ 删除后台用户 ============== */
    public function del(){
        $param = input('');
        if(!$param['id']){
            return $this->error('请选择后台用户！');
        }
        $rs = Admins::where(['id'=>$param['id']])->delete();
        if($rs){
            return $this->success('删除成功!');
        }else{
            return $this->error('删除失败!');
        }
    }
}