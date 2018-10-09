;
/*
|--------------------------------------------------------------------------
|| @【常用ajax与自定义】
|| @by 代码坞 seek-concert
|--------------------------------------------------------------------------
*/

/*
| 【ajax提交】
| @action url地址
| @datas 数据
| @method 提交方式
| return  array
*/
function ajaxAct(action,datas,method='post') {
    $.ajax({
        url:action,
        data:datas,
        type:method,
        dataType:'json',
        async:false,
        success:function (resp) {
            rst=resp;
        },
        error:function (resp) {
                rst={
                    code:"0",
                    msg:"网络错误，请稍候重试！",
                    data:{},
                    url:''
                }

        }
    });
}

/*
| 【ajax上传】
| @action url地址
| @datas 数据
| return  array
*/
function ajaxUpd(action,datas) {
    $.ajax({
        url:action,
        data:datas,
        type:'post',
        dataType:'json',
        async:false,
        cache: false,
        contentType: false,
        processData: false,
        success:function (resp) {
            rst=resp;
        },
        error:function (resp) {
            rst={
                code:"0",
                msg:"网络错误，请稍候重试！",
                data:{},
                url:''
            }
        }
    });
}

/*
| 【ajax表单提交】
| @btn 按钮上可绑定额外的参数
*/
function ajaxFormSub(btn) {
    var _btn = $(btn);
    var _form = _btn.data('form') ? $(_btn.data('form')) : _btn.parents('form:first'); //获取表单
    var _action = _btn.data('action') ? _btn.data('action') : _form.attr('action'); //获取表单地址
    var _method=_btn.data('method')?_btn.data('method'):_form.attr('method')?_form.attr('method'):'post'; //指定提交方法
    var _datas='';
    var other_datas = _btn.data('datas'); //获取额外的参数【键值对】（多个以;隔开）
    var _noreload=_btn.data('noreload');

    //禁止二次提交
    if(_btn.data('loading') || _btn.prop('disabled') || _btn.hasClass('disabled')){
        return false;
    }

    var _inputs='';
    var _others;
    var key_value;
    if(_form.length){
        //添加额外的参数（隐藏的键值对）
        if(other_datas){
            //拆分多组参数
            if(other_datas.indexOf('&') > -1){
                _others=other_datas.split('&');
            }else{
                _others=[other_datas];
            }
            //拆分键值对
            $.each(_others,function (index,info) {
                key_value=info.split('=');
                _form.find('input[name="'+key_value[0]+'"]').remove();
                _inputs +='<input type="hidden" name="'+key_value[0]+'" value="'+key_value[1]+'" />';
            });

            _form.append(_inputs);
        }
        _datas=_form.serialize();
    }else{
        _datas=other_datas;
    }

    //锁定提交按钮
    _btn.data('loading',true).prop('disabled',true).addClass('disabled');
    //表单提交
    ajaxAct(_action,_datas,_method);
    //释放提交按钮
    setTimeout(function () {
        _btn.data('loading',false).prop('disabled',false).removeClass('disabled');
    },1500);
}

$(function () {

    //表头 ==>> 全选、全不选
    $('thead').on('click','input[type=checkbox]',function () {
        var _checked=$(this).prop('checked');
        var _all_tbody_checkbox=$(this).parents('table:first').find('input[type=checkbox]');

        if(_checked){
            _all_tbody_checkbox.prop('checked',true);
        }else{
            _all_tbody_checkbox.prop('checked',false);
        }
    });
    //表体 ==>> 全选或全不选 ==>> 表头跟随变化
    $('tbody').on('click','input[type=checkbox]',function () {
        var _this=$(this);
        var _tbody=_this.parents('tbody:first');
        var _all_tbody_checkbox=_tbody.find('input[type=checkbox]');
        var _all_checked=_tbody.find('input[type=checkbox]:checked');
        var _thead_checkbox=_this.parents('table:first').find('thead input[type=checkbox]');

        if(_all_checked.length == _all_tbody_checkbox.length){
            _thead_checkbox.prop('checked',true);
        }else{
            _thead_checkbox.prop('checked',false);
        }
    }).on('change','input[type=text],input[type=number]',function () { //表体输入框 ==>> 变化 ==>> 复选框选中
        $(this).parents('tr:first').find('input:checkbox').prop('checked',true);
    });


    // 查询条件下分页跳转
    $('ul.pagination').on('click','a',function () {
        var _form=$('#form-search');

        if(_form.length>0){
            _form.attr('action',$(this).attr('href'));
            _form.submit();
            return false;
        }
    });

});

