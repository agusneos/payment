<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-scrollview.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-filter.js')?>"></script>

<!-- Data Grid -->
<table id="grid-master_invoice"
    data-options="pageSize:50, rownumbers:true, singleSelect:true, fit:true, fitColumns:false, toolbar:toolbar_master_invoice">
    <thead>
        <tr>           
            <th data-options="field:'VendorId'"             width="80"  align="center" sortable="true">Vendor</th>
            <th data-options="field:'InvoiceId'"            width="150" align="center" sortable="true" >Invoice</th>            
            <th data-options="field:'InvoiceDate'"          width="80"  align="center" sortable="true" >Invoice Date</th>
            <th data-options="field:'Currency'"             width="50"  align="center" sortable="true" >Currency</th>
            <th data-options="field:'Rate'"                 width="50"  align="center" sortable="true" formatter="thousandSep" >Rate</th>
            <th data-options="field:'ItemId'"               width="80"  align="center" sortable="true" >Item</th>
            <th data-options="field:'Name'"                 width="200" align="left"   sortable="true" halign="center" >Item Name</th>
            <th data-options="field:'PurchUnit'"            width="50"  align="center" sortable="true" >Unit</th>
            <th data-options="field:'Qty'"                  width="80"  align="center" sortable="true" formatter="thousandSep" >Qty</th>
            <th data-options="field:'Price'"                width="80"  align="center" sortable="true" formatter="thousandSep" >Price</th>
            <th data-options="field:'Amount'"               width="100" align="center" sortable="true" formatter="thousandSep" >Amount</th>
            <th data-options="field:'AmountMST'"            width="100" align="center" sortable="true" formatter="thousandSep" >Amount IDR</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_master_invoice = [{
        text:'New',
        iconCls:'icon-new_file',
        handler:function(){$('#grid-master_invoice').datagrid('reload')}
    },{
        text:'Edit',
        iconCls:'icon-edit',
        handler:function(){$('#grid-master_invoice').datagrid('reload')}
    },{
        text:'Delete',
        iconCls:'icon-cancel',
        handler:function(){$('#grid-master_invoice').datagrid('reload')}
    },{
        text:'Upload',
        iconCls:'icon-upload',
        handler:function(){$('#grid-master_invoice').datagrid('reload')}
    },{
        text:'Download',
        iconCls:'icon-download',
        handler:function(){$('#grid-master_invoice').datagrid('reload')}
    },{
        text:'Print',
        iconCls:'icon-print',
        handler:function(){$('#grid-master_invoice').datagrid('reload')}
    },{
        text:'Refresh',
        iconCls:'icon-reload',
        handler:function(){$('#grid-master_invoice').datagrid('reload')}
    }];
    
    $('#grid-master_invoice').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('master/invoice/index'); ?>?grid=true'})
        .datagrid('enableFilter');
    
   
    
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
        if ($('#grid-master_invoice').datagrid('validateRow', editIndex)){
            $('#grid-master_invoice').datagrid('endEdit', editIndex);
            editIndex = undefined;
            return true;
        } else {
            return false;
        }
    }
    function onClickCell(index, field){             
        if (endEditing()){
            var row = $('#grid-master_invoice').datagrid('getSelected'); //tambahan
            if (row){
                $('#grid-master_invoice').datagrid('selectRow', index)
                .datagrid('editCell', {index:index,field:field});
                editIndex = index;
                $.post('<?php echo site_url('master/invoice/update2'); ?>',
                {id:row.id, actual_qty:row.actual_qty, box:row.box, urgent:row.urgent,
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
    
    function thousandSep(value,row,index) {
        return value.toString()
            .replace(".",",")
            .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    function payterm(value,row,index) {
        if (value == 0){
            return value;
        } else {
            return value +' Hari';
        }        
    }
    
</script>


        
<!-- Toolbar -->


<!-- Dialog Form -->


<!-- Dialog Button -->

<!-- End of file v_invoice.php -->
<!-- Location: ./application/views/master/v_invoice.php -->