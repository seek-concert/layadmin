<?php
// +----------------------------------------------------------------------
// | @自定义常用
// +----------------------------------------------------------------------


/** 批量 更新或插入数据的sql
 * @param string $table         数据表名
 * @param array $insert_columns 数据字段
 * @param array $values         原始数据
 * @param array|string $update_columns 更新字段
 * @return bool|array          返回false(条件不符)，返回array(sql语句)
 */
function batch_update_or_insert_sql($table='', $insert_columns=[], $values=[], $update_columns=[]){
    if(empty($table) || empty($insert_columns) || empty($values) || empty($update_columns)){
        return false;
    }
    // 数据字段必须包含更新字段
    if(is_string($update_columns)){
        if(!in_array($update_columns,$insert_columns)){
            return false;
        }
    }else{
        $common_columns= array_intersect($insert_columns,$update_columns);
        sort($common_columns);
        sort($update_columns);
        if($common_columns != $update_columns){
            return false;
        }
    }

    //数据字段
    $sql_insert_columns=[];
    foreach ($insert_columns as $insert_column){
        $sql_insert_columns[]='`'.$insert_column.'`';
    }
    $sql_insert_columns=implode(',',$sql_insert_columns);
    //数据分页
    $num=100;
    $page_values=[];
    foreach ($values as $k=>$value){
        $p=ceil(($k+1)/$num);
        $temp_values=[];
        foreach ($insert_columns as $insert_column){
            $temp=(string)$value[$insert_column] or '';
            $temp_values[]="'".$temp."'";
        }
        $temp_values=implode(',',$temp_values);
        $page_values[$p][]='('.$temp_values.')';
    }
    //更新字段
    if(is_string($update_columns)){
        $sql_update_columns= ' `'.$update_columns.'` = values(`'.$update_columns.'`) ';
    }else{
        $sql_update_columns=[];
        foreach ($update_columns as $update_column){
            $sql_update_columns[]= ' `'.$update_column.'` = values(`'.$update_column.'`) ';
        }
        $sql_update_columns=implode(',',$sql_update_columns);
    }
    // 生成sql
    $sqls=[];
    foreach($page_values as $p=>$value){
        $sql_values=implode(',',$value);
        $sqls[]='insert into `'.$table.'` ('.$sql_insert_columns.') values '.$sql_values.' on duplicate key update '.$sql_update_columns;
    }

    return $sqls;
}

/** 批量更新数据 sql
 * @param string $table         数据表名
 * @param array $insert_columns 数据字段
 * @param array $values         原始数据
 * @param array|string $update_columns  更新字段
 * @param array|string $where_columns   条件字段
 * @return bool|string          返回false(条件不符)，返回string(sql语句)
 */
function batch_update_sql($table='', $insert_columns=[], $values=[], $update_columns=[], $where_columns='id'){
    if(empty($table) || empty($insert_columns) || empty($values) || empty($update_columns) || empty($where_columns)){
        return false;
    }
    // 数据字段必须包含更新字段
    if(is_string($update_columns)){
        if(!in_array($update_columns,$insert_columns)){
            return false;
        }
    }else{
        $common_columns= array_intersect($insert_columns,$update_columns);
        sort($common_columns);
        sort($update_columns);
        if($common_columns != $update_columns){
            return false;
        }
    }
    // 数据字段必须包含条件字段
    if(is_string($where_columns)){
        if(!in_array($where_columns,$insert_columns)){
            return false;
        }
    }else{
        $common_columns= array_intersect($insert_columns,$where_columns);
        sort($common_columns);
        sort($where_columns);
        if($common_columns != $where_columns){
            return false;
        }
    }

    /* ++++++++++ 创建虚拟表 ++++++++++ */
    //创建虚拟表 表名
    $temp_table='`'.$table.'_temp`';
    //创建虚拟表 sql
    $sqls[]='create temporary table '.$temp_table.' like `'.$table.'`';

    /* ++++++++++ 添加数据 ++++++++++ */
    //数据字段
    $sql_insert_columns=[];
    foreach ($insert_columns as $insert_column){
        $sql_insert_columns[]='`'.$insert_column.'`';
    }
    $sql_insert_columns=implode(',',$sql_insert_columns);
    //数据分页
    $num=100;
    $page_values=[];
    foreach ($values as $k=>$value){
        $p=ceil(($k+1)/$num);
        $temp_values=[];
        foreach ($insert_columns as $insert_column){
            $temp=(string)$value[$insert_column] or '';
            $temp_values[]="'".$temp."'";
        }
        $temp_values=implode(',',$temp_values);
        $page_values[$p][]='('.$temp_values.')';
    }

    //插入数据 sql
    foreach($page_values as $p=>$value){
        $sql_values=implode(',',$value);
        $sqls[]='insert into '.$temp_table.' ('.$sql_insert_columns.') values '.$sql_values;
    }


    /* ++++++++++ 批量更新 ++++++++++ */
    //更新字段
    if(is_string($update_columns)){
        $sql_update_columns= '`'.$table.'`.`'.$update_columns.'`='.$temp_table.'.`'.$update_columns.'`';
    }else{
        $sql_update_columns=[];
        foreach ($update_columns as $update_column){
            $sql_update_columns[]= '`'.$table.'`.`'.$update_column.'`='.$temp_table.'.`'.$update_column.'`';
        }
        $sql_update_columns=implode(',',$sql_update_columns);
    }
    //条件字段
    if(is_string($where_columns)){
        $sql_where_columns= '`'.$table.'`.`'.$where_columns.'`='.$temp_table.'.`'.$where_columns.'`';
    }else{
        $sql_where_columns=[];
        foreach ($where_columns as $where_column){
            $sql_where_columns[]= '`'.$table.'`.`'.$where_column.'`='.$temp_table.'.`'.$where_column.'`';
        }
        $sql_where_columns=implode(' and ',$sql_where_columns);
    }
    //更新数据 sql
    $sqls[]='update `'.$table.'`,'.$temp_table.' set '.$sql_update_columns.' where '.$sql_where_columns;

    return $sqls;
}


/** 获取操作控制器和方法
 * @return array
 */
function get_method(){
    $actionNameStr=request()->route()->getActionName();
    $array=explode('\\',$actionNameStr);
    $count=count($array);
    $actionNameStr=$array[$count-1];
    $array=explode('@',$actionNameStr);

    return $array;
}

/** 生成GUID
 * @return string
 */
function create_guid(){
    $charid = strtoupper(md5(uniqid(mt_rand(), true)));
    $hyphen = chr(45);// "-"
    $guid = substr($charid, 6, 2).substr($charid, 4, 2).
        substr($charid, 2, 2).substr($charid, 0, 2).$hyphen
        .substr($charid, 10, 2).substr($charid, 8, 2).$hyphen
        .substr($charid,14, 2).substr($charid,12, 2).$hyphen
        .substr($charid,16, 4).$hyphen.substr($charid,20,12);
    return $guid;
}