<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-scrollview.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-filter.js')?>"></script>
<script type="text/javascript">
var url;

function create_admin_menu(){
    $('#dlg-admin_menu').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
    $('#fm-admin_menu').form('clear');
    url = '<?php echo site_url('admin/menu/create'); ?>';
}

function update_admin_menu(){
    var row = $('#grid-admin_menu').datagrid('getSelected');
    if(row){
        $('#dlg-admin_menu').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
        $('#fm-admin_menu').form('load',row);
        url = '<?php echo site_url('admin/menu/update'); ?>/' + row.id;
    }
    else
    {
         $.messager.alert('Info','Data belum dipilih !','info');
    }
}

function save_admin_menu(){
    $('#fm-admin_menu').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success: function(result){
            var result = eval('('+result+')');
            if(result.success){
                $('#dlg-admin_menu').dialog('close');
                $('#grid-admin_menu').datagrid('reload');
                $.messager.show({
                    title: 'Info',
                    msg: 'Input/Ubah Data Berhasil'
                });
            } else {
                $.messager.show({
                    title: 'Error',
                    msg: 'Input/Ubah Data Gagal'
                });
            }
        }
    });
}

function delete_admin_menu(){
    var row = $('#grid-admin_menu').datagrid('getSelected');
    if (row){
        $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus data ID '+row.id+' ?',function(r){
            if (r){
                $.post('<?php echo site_url('admin/menu/delete'); ?>',{id:row.id},function(result){
                    if (result.success){
                        $('#grid-admin_menu').datagrid('reload');
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
    $('#grid-admin_menu').datagrid('reload');
}

</script>
<style type="text/css">
    #fm-admin_menu{
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
<table id="grid-admin_menu" toolbar="#toolbar-admin_menu"
    data-options="pageSize:50, rownumbers:true, singleSelect:true, fit:true, fitColumns:true">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'id'" width="30" align="center" sortable="true">ID</th>            
            <th data-options="field:'parentId'" width="50" align="center" sortable="true">Parent Menu</th>
            <th data-options="field:'name'" width="100" halign="center" sortable="true">Nama Menu</th>
            <th data-options="field:'uri'" width="100" halign="center" sortable="true">URI</th>
            <th data-options="field:'allowed'" width="100" halign="center" sortable="true">Allowed</th>
            <th data-options="field:'iconCls'" width="100" halign="center" sortable="true">Icon</th>
            <th data-options="field:'type'" width="30" halign="center" sortable="true">Type</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    $('#grid-admin_menu').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('admin/menu/index'); ?>?grid=true'}).datagrid('enableFilter');
</script>
<!-- Toolbar -->
<div id="toolbar-admin_menu">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="create_admin_menu()">Tambah Data</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="update_admin_menu()">Edit Data</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="delete_admin_menu()">Hapus Data</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="refresh()">Refresh</a>
</div>

<!-- Dialog Form -->
<div id="dlg-admin_menu" class="easyui-dialog" style="width:400px; height:300px; padding: 10px 20px" closed="true" buttons="#dlg_buttons-admin_menu">
    <form id="fm-admin_menu" method="post" novalidate>
        <div class="fitem">
            <label for="type">Parent Menu</label>
            <input class="easyui-combobox" name="parentId" data-options="
                url:'<?php echo site_url('admin/menu/getParent'); ?>',
                method:'get', valueField:'id', textField:'name', panelHeight:'auto'">
        </div>
        <div class="fitem">
            <label for="type">Nama Menu</label>
            <input type="text" name="name" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">URI</label>
            <input type="text" name="uri" class="easyui-textbox" />
        </div>
        <div class="fitem">
            <label for="type">Allowed</label>
            <input type="text" name="allowed" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Icon</label>
            <input type="text" name="iconCls" class="easyui-textbox" />
        </div>
        <div class="fitem">
            <label for="type">Type</label>
            <input class="easyui-combobox" name="type" data-options="
                url:'<?php echo site_url('admin/menu/enumType'); ?>',
                method:'get', valueField:'data', textField:'data', panelHeight:'auto'" />
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg_buttons-admin_menu">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="save_admin_menu()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-admin_menu').dialog('close')">Batal</a>
</div>

<!-- End of file v_menu.php -->
<!-- Location: ./application/views/admin/v_menu.php -->