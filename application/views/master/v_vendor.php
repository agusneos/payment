<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-master_vendor"
    data-options="pageSize:50, rownumbers:true, singleSelect:true, fit:true, fitColumns:false, toolbar:toolbar_master_vendor">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'Id'"                   width="100" align="center" sortable="true">Kode Vendor</th>
            <th data-options="field:'Name'"                 width="300" halign="center" align="left" sortable="true">Nama Vendor</th>
            <th data-options="field:'PayTerm'"              width="100" align="center" sortable="true" formatter="payterm">Payment Term</th>
            <th data-options="field:'VendGroup'"            width="100" align="center" sortable="true" >Grup</th>
            <th data-options="field:'Tax'"                  width="100" align="center" sortable="true" >Pajak</th>
            <th data-options="field:'Round'"                width="100" align="center" sortable="true" >Pembulatan</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_master_vendor = [{
        text:'New',
        iconCls:'icon-new_file',
        handler:function(){masterVendorCreate();}
    },{
        text:'Edit',
        iconCls:'icon-edit',
        handler:function(){masterVendorUpdate();}
    },{
        text:'Delete',
        iconCls:'icon-cancel',
        handler:function(){masterVendorHapus();}
    },{
        text:'Refresh',
        iconCls:'icon-reload',
        handler:function(){$('#grid-master_vendor').datagrid('reload');}
    }];
    
    $('#grid-master_vendor').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('master/vendor/index'); ?>?grid=true'})
        .datagrid('enableFilter');
    
    function masterVendorCreate() {
        $('#dlg-master_vendor').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-master_vendor').form('clear');
        url = '<?php echo site_url('master/vendor/create'); ?>';
    }
    
    function masterVendorUpdate() {
        var row = $('#grid-master_vendor').datagrid('getSelected');
        if(row){
            $('#dlg-master_vendor-edit').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_vendor-edit').form('load',row);
            url = '<?php echo site_url('master/vendor/update'); ?>/' + row.Id;            
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function masterVendorSave(){
        $('#fm-master_vendor').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_vendor').dialog('close');
                    $('#grid-master_vendor').datagrid('reload');
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
    
    function masterVendorSaveEdit(){
        $('#fm-master_vendor-edit').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_vendor-edit').dialog('close');
                    $('#grid-master_vendor').datagrid('reload');
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
    
    function masterVendorHapus(){
        var row = $('#grid-master_vendor').datagrid('getSelected');
        if (row){
            $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Vendor '+row.Name+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/vendor/delete'); ?>',{Id:row.Id},function(result){
                        if (result.success){
                            $('#grid-master_vendor').datagrid('reload');
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

    function payterm(value,row,index) {
        if (value == 0){
            return '';
        } else {
            return value +' Hari';
        }        
    }
    
</script>

<style type="text/css">
    #fm-master_vendor{
        margin:0;
        padding:10px 30px;
    }
     #fm-master_vendor-edit{
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

<div id="dlg-master_vendor" class="easyui-dialog" style="width:400px; height:300px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_vendor">
    <form id="fm-master_vendor" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Kode Vendor</label>
            <input type="text" id="Id" name="Id" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Vendor</label>
            <input type="text" id="Name" name="Name" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Payment</label>
            <input type="text" id="PayTerm" name="PayTerm" class="easyui-numberbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Group</label>
            <input id="VendGroup" name="VendGroup" class="easyui-combobox"  data-options="
                url:'<?php echo site_url('master/vendor/enumVendGroup'); ?>',
                method:'get', valueField:'data', textField:'data', panelHeight:'auto'" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Pajak</label>
            <input id="Tax" name="Tax" class="easyui-combobox"  data-options="
                url:'<?php echo site_url('master/vendor/enumTax'); ?>',
                method:'get', valueField:'data', textField:'data', panelHeight:'auto'" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Pembulatan</label>
            <input id="Round" name="Round" class="easyui-combobox"  data-options="
                url:'<?php echo site_url('master/vendor/enumRound'); ?>',
                method:'get', valueField:'data', textField:'data', panelHeight:'auto'" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_vendor">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterVendorSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_vendor').dialog('close')">Batal</a>
</div>

<div id="dlg-master_vendor-edit" class="easyui-dialog" style="width:400px; height:300px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_vendor-edit">
    <form id="fm-master_vendor-edit" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Nama Vendor</label>
            <input type="text" id="Name" name="Name" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Payment</label>
            <input type="text" id="PayTerm" name="PayTerm" class="easyui-numberbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Group</label>
            <input id="VendGroup" name="VendGroup" class="easyui-combobox"  data-options="
                url:'<?php echo site_url('master/vendor/enumVendGroup'); ?>',
                method:'get', valueField:'data', textField:'data', panelHeight:'auto'" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Pajak</label>
            <input id="Tax" name="Tax" class="easyui-combobox"  data-options="
                url:'<?php echo site_url('master/vendor/enumTax'); ?>',
                method:'get', valueField:'data', textField:'data', panelHeight:'auto'" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Pembulatan</label>
            <input id="Round" name="Round" class="easyui-combobox"  data-options="
                url:'<?php echo site_url('master/vendor/enumRound'); ?>',
                method:'get', valueField:'data', textField:'data', panelHeight:'auto'" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_vendor-edit">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterVendorSaveEdit()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_vendor-edit').dialog('close')">Batal</a>
</div>
<!-- End of file v_vendor.php -->
<!-- Location: ./application/views/master/v_vendor.php -->