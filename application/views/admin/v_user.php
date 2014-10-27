<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-scrollview.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-filter.js')?>"></script>
<script type="text/javascript">
var url;

function create_admin_user(){
    $('#dlg-admin_user').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
    $('#fm-admin_user').form('clear');
    url = '<?php echo site_url('admin/user/create'); ?>';
}

function save_admin_user(){
    $('#fm-admin_user').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success: function(result){
            var result = eval('('+result+')');
            if(result.success){
                $('#dlg-admin_user').dialog('close');
                $('#grid-admin_user').datagrid('reload');
                $.messager.show({
                    title: 'Info',
                    msg: 'Input Data Berhasil'
                });
            } else {
                $.messager.show({
                    title: 'Error',
                    msg: 'Input Data Gagal'
                });
            }
        }
    });
}

function update_admin_user(){
    var row = $('#grid-admin_user').datagrid('getSelected');
    if(row){
        $('#dlg_update-admin_user').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
        $('#fm_update-admin_user').form('load',row);
        url = '<?php echo site_url('admin/user/update'); ?>/' + row.id;
    }
    else
    {
         $.messager.alert('Info','Data belum dipilih !','info');
    }
}

function save_update_admin_user(){
    $('#fm_update-admin_user').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success: function(result){
            var result = eval('('+result+')');
            if(result.success){
                $('#dlg_update-admin_user').dialog('close');
                $('#grid-admin_user').datagrid('reload');
                $.messager.show({
                    title: 'Info',
                    msg: 'Ubah Data Berhasil'
                });
            } else {
                $.messager.show({
                    title: 'Error',
                    msg: 'Ubah Data Gagal'
                });
            }
        }
    });
}

function reset_admin_user(){
    var row = $('#grid-admin_user').datagrid('getSelected');
    if(row){
        $('#dlg_reset-admin_user').dialog({modal: true}).dialog('open').dialog('setTitle','Reset Password');
        $('#fm_reset-admin_user').form('reset');
        url = '<?php echo site_url('admin/user/reset'); ?>/' + row.id;
    }
    else
    {
         $.messager.alert('Info','Data belum dipilih !','info');
    }
}

function save_reset_admin_user(){
    $('#fm_reset-admin_user').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success: function(result){
            var result = eval('('+result+')');
            if(result.success){
                $('#dlg_reset-admin_user').dialog('close');
                $('#grid-admin_user').datagrid('reload');
                $.messager.show({
                    title: 'Info',
                    msg: 'Ubah Password Berhasil'
                });
            } else {
                $.messager.show({
                    title: 'Error',
                    msg: 'Ubah Password Gagal'
                });
            }
        }
    });
}

function delete_admin_user(){
    var row = $('#grid-admin_user').datagrid('getSelected');
    if (row){
        $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus data ID '+row.id+' ?',function(r){
            if (r){
                $.post('<?php echo site_url('admin/user/delete'); ?>',{id:row.id},function(result){
                    if (result.success){
                        $('#grid-admin_user').datagrid('reload');
                        $.messager.show({
                            title: 'Info',
                            msg: 'Hapus Data Berhasil'
                        });
                    } else {
                        $.messager.show({
                            title: 'Error',
                            msg: 'Hapus Data Gagal'
                        });
                    }
                },'json');
            }
        });
    }
    else
    {
         $.messager.alert('Info','Data belum dipilih !','info');
    }
}

function refresh()
{
    $('#grid-admin_user').datagrid('reload');
}

</script>
<style type="text/css">
    #fm-admin_user{
        margin:0;
        padding:10px 30px;
    }
    #fm_update-admin_user{
        margin:0;
        padding:10px 30px;
    }
    #fm_reset-admin_user{
        margin:0;
        padding:10px 30px;
    }
    .ftitle{
        font-size:14px;
        font-weight:bold;
        padding:5px 0;
        margin-bottom:10px;
        border-bottom:1px solid #ccc;
    }
    .fitem{
        margin-bottom:5px;
    }
    .fitem label{
        display:inline-block;
        width:80px;
    }
</style>

<!-- Data Grid -->
<table id="grid-admin_user" toolbar="#toolbar-admin_user"
    data-options="pageSize:50, rownumbers:true, singleSelect:true, fit:true, fitColumns:true">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'id'" width="30" align="center" sortable="true">ID</th>
            <th data-options="field:'name'" width="100" halign="center" sortable="true">Nama Lengkap</th>
            <th data-options="field:'username'" width="50" align="center" sortable="true">Username</th>
            <th data-options="field:'password'" formatter="adminUserPassword" width="50" align="center" sortable="true">Password</th>
            <th data-options="field:'level'" width="50" align="center" sortable="true">Level</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    $('#grid-admin_user').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('admin/user/index'); ?>?grid=true'}).datagrid('enableFilter');
    
    function adminUserPassword(value,row,index) {
        return '**********';
    }
</script>
<!-- Toolbar -->
<div id="toolbar-admin_user">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="create_admin_user()">Tambah Data</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="update_admin_user()">Edit Data</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="delete_admin_user()">Hapus Data</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="reset_admin_user()">Reset Password</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="refresh()">Refresh</a>
</div>

<!-- Dialog Input Form -->
<div id="dlg-admin_user" class="easyui-dialog" style="width:400px; height:250px; padding: 10px 20px" closed="true" buttons="#dlg_buttons-admin_user">
    <form id="fm-admin_user" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Nama Lengkap</label>
            <input type="text" name="name" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Username</label>
            <input type="text" name="username" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Password</label>
            <input id="pass" type="password" name="password" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Level</label>
            <input type="text" name="level" class="easyui-textbox" required="true"/>
        </div>
    </form>
</div>
<!-- Dialog Update Form -->
<div id="dlg_update-admin_user" class="easyui-dialog" style="width:400px; height:250px; padding: 10px 20px" closed="true" buttons="#dlg_buttons_update-admin_user">
    <form id="fm_update-admin_user" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Nama Lengkap</label>
            <input type="text" name="name" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Username</label>
            <input type="text" name="username" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Level</label>
            <input type="text" name="level" class="easyui-textbox" required="true"/>
        </div>
    </form>
</div>
<!-- Dialog Reset Form -->
<div id="dlg_reset-admin_user" class="easyui-dialog" style="width:400px; height:150px; padding: 10px 20px" closed="true" buttons="#dlg_buttons_reset-admin_user">
    <form id="fm_reset-admin_user" method="post" novalidate>       
        <div class="fitem">
            <label for="type">New Password</label>
            <input id="pass" type="password" name="password" class="easyui-textbox" required="true"/>
        </div>        
    </form>
</div>

<!-- Dialog Input Button -->
<div id="dlg_buttons-admin_user">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="save_admin_user()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-admin_user').dialog('close')">Batal</a>
</div>
<!-- Dialog Update Button -->
<div id="dlg_buttons_update-admin_user">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="save_update_admin_user()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg_update-admin_user').dialog('close')">Batal</a>
</div>
<!-- Dialog Reset Button -->
<div id="dlg_buttons_reset-admin_user">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="save_reset_admin_user()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg_reset-admin_user').dialog('close')">Batal</a>
</div>

<!-- End of file v_user.php -->
<!-- Location: ./application/views/admin/v_user.php -->