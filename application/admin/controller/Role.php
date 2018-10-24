<?php
/*
|--------------------------------------------------------------------------
| 后台用户角色管理
|--------------------------------------------------------------------------
*/

namespace app\admin\controller;


use app\admin\model\Menus;
use app\admin\model\Roles;

class Role extends Base
{
    /* ========== 初始化 ========== */
    public function _initialize()
    {
        parent::_initialize();
    }

    /* ========== 用户角色管理列表 ========== */
    public function index(){
        if(request()->isAjax()){
            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (!empty($param['searchText'])) {
                $where['name'] = ['like', '%' . $param['searchText'] . '%'];
            }
            $where['type'] = 1;
            $selectResult = Roles::where($where)->limit($offset, $limit)->select();

            $status = config('role_status');

            // 拼装参数
            foreach($selectResult as $key=>$vo){

                $selectResult[$key]['status'] = $status[$vo['status']];

                $selectResult[$key]['operate'] = showOperate(makeButton('role',$vo['id'])).'<a href="javascript:giveQx('.$vo['id'].')"><button type="button" class="btn btn-info btn-sm"><i class="fa fa-institution"></i> 分配权限</button></a>';
            }

            $return['total'] = Roles::where($where)->count();  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }
        return view();
    }

    /* ============ 添加用户角色 ============== */
    public function add(){
        if(request()->isPost()){
            $param = input('post.');
            $rule = [
                ['name', 'require', '请填写角色名称!'],
                ['name', 'unique:role', '角色名称已存在!'],
                ['status', 'require', '请填写角色状态!']
            ];
            $result = $this->validate($param, $rule);
            if (true !== $result) {
                return $this->error($result);
            }

            $param['type'] = 1;
            $model = new Roles();
            $rs =  $model->save($param);
            if($rs){
                return $this->success('添加成功!',url('role/index'));
            }else{
                return $this->error('添加失败!');
            }
        }

        $this->assign([
            'status' => config('role_status')
        ]);

        return view();
    }


    /* ============ 修改用户角色 ============== */
    public function edit(){
        $param = input('');
        if(!$param['id']){
            return $this->error('请选择用户角色！');
        }
        if(request()->isPost()){
            $rule = [
                ['name', 'require', '请填写角色名称!'],
                ['status', 'require', '请填写角色状态!']
            ];
            $result = $this->validate($param, $rule);
            if (true !== $result) {
                return $this->error($result);
            }

            $param['type'] = 1;
            $model = new Roles();
            $rs =  $model->save($param,['id'=>$param['id']]);
            if($rs){
                return $this->success('修改成功!',url('role/index'));
            }else{
                return $this->error('修改失败!');
            }
        }

        $role = Roles::where(['id'=>$param['id']])->find();
        $this->assign([
            'id'=>$param['id'],
            'infos' => $role,
            'status' => config('role_status')
        ]);

        return view();
    }

    /* ============ 删除用户角色 ============== */
    public function del(){
        $param = input('');
        if(!$param['id']){
            return $this->error('请选择用户角色！');
        }
        $rs = Roles::where(['id'=>$param['id']])->delete();
        if($rs){
            return $this->success('删除成功!');
        }else{
            return $this->error('删除失败!');
        }
    }

    /* ============ 分配权限 ============== */
    public function giveAccess()
    {
        $param = input('param.');
        // 获取现在的权限
        if('get' == $param['type']){
            $result = Menus::field('id,name,parent_id')->select();
            $str = '';

            $rule = Roles::field('menu_ids')->where('id', $param['id'])->find();

            if(!empty($rule)){
                $rule = explode(',', $rule);
            }

            foreach($result as $key=>$vo){
                $str .= '{ "id": "' . $vo['id'] . '", "pId":"' . $vo['parent_id'] . '", "name":"' . $vo['name'].'"';

                if(!empty($rule) && in_array($vo['id'], $rule)){
                    $str .= ' ,"checked":1';
                }

                $str .= '},';

            }

            $nodeStr = '[' . rtrim($str, ',') . ']';

            return json(msg(1, $nodeStr, 'success'));
        }

        // 分配新权限
        if('give' == $param['type']){

            $doparam = [
                'id' => $param['id'],
                'menu_ids' => $param['menu_ids']
            ];
            $user = new Roles();
            $rs = $user->save($doparam,['id'=>$param['id']]);
            if($rs){
               return $this->success('分配成功');
            }else{
                return $this->error('分配失败');
            }
        }
    }

}