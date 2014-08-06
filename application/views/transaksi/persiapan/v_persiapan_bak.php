<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-scrollview.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-filter.js')?>"></script>

<style type="text/css">
    .datagrid-cell{        
        font-size:14px;
    }
    #fm-lot-input{
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
<table id="grid-transaksi_persiapan"
    data-options="pageSize:50, onClickCell:onClickCell, singleSelect:true, fit:true, fitColumns:false">
    <thead>
        <tr>            
            <th data-options="field:'picking_route'"        width="100" align="center" sortable="true">Picking Route</th>
            <th data-options="field:'customer_requisition'" width="110" align="center" sortable="true">PO Cust</th>
            <th data-options="field:'name'"                 width="300" align="center" sortable="true">Nama Customer</th>
            <th data-options="field:'delivery_date'"        width="80"  align="center" sortable="true">Delivery Date</th>
            <th data-options="field:'item_name'"            width="200" align="center" sortable="true">Nama Barang</th>
            <th data-options="field:'external'"             width="100" align="center" sortable="true">External</th>
            <th data-options="field:'ca_number'"            width="100" align="center" sortable="true">CA Number</th>
            <th data-options="field:'quantity'"             width="80"  align="center" sortable="true" formatter="qty">Quantity</th>
            <th data-options="field:'lot'"                  width="80"  align="center" formatter="lot">Add Lot</th>
            <th data-options="field:'qty'"                  width="80"  align="center" sortable="true" formatter="act">Actual Qty</th>
            <th data-options="field:'box'"                  width="100" align="center" sortable="true" editor="numberbox">Box</th> 
            <th data-options="field:'urgent'"               width="80"  align="center" sortable="true" editor="{type:'checkbox',options:{on:'URGENT',off:''}}">Urgent</th>
            <th data-options="field:'no_stock'"             width="100" align="center" sortable="true" editor="{type:'checkbox',options:{on:'NO STOK',off:''}}" styler="noStock">No Stock</th>
            <th data-options="field:'picked'"               width="100" align="center" sortable="true" editor="{type:'checkbox',options:{on:'PICKED',off:''}}"  styler="picked">Picked</th>
            <th data-options="field:'close'"                width="100" align="center" sortable="true" editor="{type:'checkbox',options:{on:'CLOSE',off:''}}"   styler="close">Close</th>
                      
        </tr>
    </thead>
</table>

<div id="dlg-lot" toolbar="#toolbar-lot-input">
    
</div>
<!-- Toolbar -->
<div id="toolbar-lot-input">
    <a id="lotadd" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="lotInputCreate()">Tambah Data</a>
    <a id="lotdel" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="lotInputDelete()">Hapus Data</a>
</div>
<!-- Dialog Form -->
<div id="dlg-lot-input" class="easyui-dialog" style="width:400px; height:250px; padding: 10px 20px" closable="false" closed="true" buttons="#dlg-buttons-lot-input">
    <form id="fm-lot-input" method="post" novalidate>        
        <div class="fitem">
            <input id="wid" type="text" name="wmsordertrans_id" class="easyui-numberbox" required="true" readonly="true" hidden="true"/>
        </div>
        <div class="fitem">
            <label for="type">LOT</label>
            <input id="inlot" type="text" name="lot" class="easyui-validatebox" required="true" tabindex="1"/>
        </div>
        <div class="fitem">
            <label for="type">Qty</label>
            <input id="inqty" type="text" name="qty" class="easyui-numberbox" required="true" tabindex="2"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-lot-input">
    <a id="inok" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="lotInputSave()" tabindex="3">Simpan</a>
    <a id="inng" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="lotInputCancel()" >Batal</a>
</div>

<script type="text/javascript">
    var url;
    
    $('#grid-transaksi_persiapan').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('transaksi/persiapan/index'); ?>?grid=true'})
        .datagrid({
            rowStyler:function(index,row){
                if (row.urgent == 'URGENT'){
                    return 'background-color:#990012;color:#fff;font-weight:bold;height:50px;';
                } else {
                    return 'height:50px;font-weight:bold;';
                }
            }
        }).datagrid('enableFilter');
    
   
    
    $.extend($.fn.datagrid.methods, {
        editCell: function(jq,param){
            return jq.each(function(){
                var opts = $(this).datagrid('options');
                var fields = $(this).datagrid('getColumnFields',true).concat($(this).datagrid('getColumnFields'));
                for(var i=0; i<fields.length; i++){
                    var col = $(this).datagrid('getColumnOption', fields[i]);
                    col.editor1 = col.editor;
                    if (fields[i] != param.field){
                        col.editor = null;
                    }
                }
                $(this).datagrid('beginEdit', param.index);
                for(var i=0; i<fields.length; i++){
                    var col = $(this).datagrid('getColumnOption', fields[i]);
                    col.editor = col.editor1;
                }
            });
        }
    });

    var editIndex = undefined;
    function endEditing(){
        if (editIndex == undefined){return true}
        if ($('#grid-transaksi_persiapan').datagrid('validateRow', editIndex)){
            $('#grid-transaksi_persiapan').datagrid('endEdit', editIndex);
            editIndex = undefined;
            return true;
        } else {
            return false;
        }
    }
    function onClickCell(index, field){             
        if (endEditing()){
            var row = $('#grid-transaksi_persiapan').datagrid('getSelected'); //tambahan
            if (row){
                $('#grid-transaksi_persiapan').datagrid('selectRow', index)
                .datagrid('editCell', {index:index,field:field});
                editIndex = index;
                $.post('<?php echo site_url('transaksi/persiapan/update2'); ?>',
                {id:row.id, box:row.box, urgent:row.urgent, 
                no_stock:row.no_stock, picked:row.picked, close:row.close},'json');
                //alert('index ='+index + 'id ='+row.id); //debug                
            }
        }
    }
    
    function close(value,row,index){
        if (value == 'CLOSE'){
            return 'background-color:#00FF00;color:black;font-weight:bold;';
        }
    }
    
    function noStock(value,row,index){
        if (value == 'NO STOK'){
            return 'background-color:#FFFF00;color:black;font-weight:bold;';
        }
    }
    
    function picked(value,row,index){
        if (value == 'PICKED'){
            return 'background-color:#0000FF;color:white;font-weight:bold;';
        }
    }
    
    function qty(value,row,index) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    function act(value,row,index) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    function lot(row,index){
        return '<a href="javascript:lot_click()" ><img src="<?=base_url('assets/easyui/themes/icons/edit_add.png')?>"></a>';
    }
    
    function lot_click(){
        var row = $('#grid-transaksi_persiapan').datagrid('getSelected');
        var s = '<?php echo site_url('transaksi/persiapan/lot'); ?>/' + row.id;
        if (row)
        {
            $('#dlg-lot').dialog({
                title   : 'ID - '+ row.id,
                width   : 350,
                height  : 300,
                modal   : true,
            });
            $('#dlg-lot').dialog('refresh', s);
            $('#lotadd').focus();
        }
    }
    
    function lotInputCreate(){
        var row = $('#grid-transaksi_persiapan').datagrid('getSelected');
        $('#dlg-lot-input').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-lot-input').form('clear');
        $('#wid').numberbox('setValue',row.id);
        $('#inlot').focus();
        url = '<?php echo site_url('transaksi/persiapan/lot_create'); ?>';
    }
    
    function lotInputSave(){
        $('#fm-lot-input').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-lot-input').dialog('close');
                    $('#dlg-lot').dialog('refresh');
                    $('#lotadd').focus();
                    lotInputCreate();
                } else {
                    $.messager.show({
                        title: 'Error',
                        msg: result.msg
                    });
                }
            }
        });
    }
    
    $('#inlot').keypress(function(e)  {
        if ((e.keyCode == '13')){
            $('#inqty').focus();
        }
    });
    
    $('#inqty').keypress(function(e)  {
        if ((e.keyCode == '13')){
            $('#inok').focus();
        }
    });
        
    function lotInputCancel()   {
        $('#dlg-lot-input').dialog('close');
        $('#lotadd').focus();
    }
    
    $('#dlg-lot-input').keydown(function(e){ //tutup dialog input lot saat menekan tombol esc
	if (e.keyCode == 27){
            lotInputCancel();
	}
    });
    
    $('#dlg-lot').keydown(function(e){ //tutup dialog lot saat menekan tombol esc
	if (e.keyCode == 27){
            $('#dlg-lot').dialog('close');
	}
    });
    
    function lotInputDelete(){
        var row = $('#grid-transaksi_persiapan_lot').datagrid('getSelected');
        if (row){
            $.messager.confirm('Konfirmasi','Hapus LOT '+row.lot+' dengan Qty '+row.qty+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('transaksi/persiapan/lot_delete'); ?>',{id:row.wmsordertrans_id,time:row.time},function(result){
                        if (result.success){
                            $('#dlg-lot').dialog('refresh');
                            $('#lotadd').focus();
                        } else {
                            $.messager.show({
                                title: 'Error',
                                msg: result.msg
                            });
                        }
                    },'json');
                }
            });
        }
    }

</script>


        
<!-- Toolbar -->


<!-- Dialog Form -->


<!-- Dialog Button -->

<!-- End of file v_persiapan.php -->
<!-- Location: ./application/views/transaksi/persiapan/v_persiapan.php -->