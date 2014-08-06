<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-scrollview.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-filter.js')?>"></script>
<script type="text/javascript">
var url;

function update_number_sequence(){
    var row = $('#grid-number_sequence').datagrid('getSelected');
    if(row){
        $('#dlg-number_sequence').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
        $('#fm-number_sequence').form('load',row);
        url = '<?php echo site_url('setting/number_sequence/update'); ?>/' + row.TABLE_NAME;
    }
}

function save_number_sequence(){
    $('#fm-number_sequence').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success: function(result){
            var result = eval('('+result+')');
            if(result.success){
                $('#dlg-number_sequence').dialog('close');
                $('#grid-number_sequence').datagrid('reload');
            } else {
                $.messager.show({
                    title: 'Error',
                    msg: result.msg
                });
            }
        }
    });
}

</script>
<style type="text/css">
    #fm-number_sequence{
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
<table id="grid-number_sequence" toolbar="#toolbar-number_sequence"
    data-options="pageSize:50, singleSelect:true, fit:true, fitColumns:false">
    <thead>
        <tr>              
            <th data-options="field:'TABLE_NAME'" width="100" align="center" sortable="true">Nama Tabel</th>
            <th data-options="field:'AUTO_INCREMENT'" width="100" align="center" sortable="true">Sequence</th>
            <th data-options="field:'TABLE_COMMENT'" width="150" halign="center" sortable="true">Keterangan</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    $('#grid-number_sequence').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('setting/number_sequence/index'); ?>?grid=true'}).datagrid('enableFilter');
</script>
<!-- Toolbar -->
<div id="toolbar-number_sequence">    
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="update_number_sequence()">Edit Data</a>
</div>

<!-- Dialog Form -->
<div id="dlg-number_sequence" class="easyui-dialog" style="width:400px; height:250px; padding: 10px 20px" closed="true" buttons="#dlg_buttons-number_sequence">
    <form id="fm-number_sequence" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Sequence</label>
            <input type="text" name="AUTO_INCREMENT" class="easyui-numberbox" />
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg_buttons-number_sequence">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="save_number_sequence()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg-number_sequence').dialog('close')">Batal</a>
</div>

<!-- End of file v_number_sequence.php -->
<!-- Location: ./application/views/setting/v_number_sequence.php -->