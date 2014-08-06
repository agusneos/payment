<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-scrollview.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-filter.js')?>"></script>

<style type="text/css">
    .datagrid-cell{        
        font-size:14px;
    }
</style>

<!-- Data Grid -->
<table id="grid-master_pickinglist"
    data-options="pageSize:50, onClickCell:onClickCell, singleSelect:true, fit:true, fitColumns:false">
    <thead>
        <tr>              
            <th data-options="field:'id'"                   width="30" align="center" sortable="true">ID</th>
            <th data-options="field:'activation_date'"      width="110" align="center" sortable="true">activation_date</th>
            <th data-options="field:'picking_route'"        width="70" align="center" sortable="true">picking_route</th>
            <th data-options="field:'customer_requisition'" width="100" align="center" sortable="true">customer_requisition</th>
            <th data-options="field:'customer_account'"     width="50" align="center" sortable="true">customer_account</th>
            <th data-options="field:'name'"                 width="300" align="center" sortable="true">Nama Customer</th>
            <th data-options="field:'delivery_date'"        width="80" align="center" sortable="true">delivery_date</th>
            <th data-options="field:'item_number'"          width="80" align="center" sortable="true">item_number</th>
            <th data-options="field:'item_name'"            width="200" align="center" sortable="true">item_name</th>
            <th data-options="field:'external'"             width="100" align="center" sortable="true">external</th>
            <th data-options="field:'ca_number'"            width="100" align="center" sortable="true">ca_number</th>
            <th data-options="field:'quantity'"  formatter="qty"            width="80" align="center" sortable="true">quantity</th>
            <th data-options="field:'actual_qty'" formatter="act" editor="numberbox"          width="80" align="center" sortable="true">actual_qty</th>
            <th data-options="field:'box'" editor="numberbox"                 width="100" align="center" sortable="true">box</th>
            <th data-options="field:'urgent'" editor="{type:'checkbox',options:{on:'URGENT',off:''}}"              width="80" align="center" sortable="true">urgent</th>
            <th data-options="field:'no_stock'" styler="noStock" editor="{type:'checkbox',options:{on:'NO STOK',off:''}}"            width="100" align="center" sortable="true">no_stock</th>
            <th data-options="field:'picked'" styler="picked" editor="{type:'checkbox',options:{on:'PICKED',off:''}}"            width="100" align="center" sortable="true">picked</th>
            <th data-options="field:'close'" styler="close" editor="{type:'checkbox',options:{on:'CLOSE',off:''}}"            width="100" align="center" sortable="true">close</th>
            <th data-options="field:'upload_time'"          width="80" align="center" sortable="true">upload_time</th>            
        </tr>
    </thead>
</table>

<script type="text/javascript">
    $('#grid-master_pickinglist').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('master/pickinglist/index'); ?>?grid=true'})
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
        if ($('#grid-master_pickinglist').datagrid('validateRow', editIndex)){
            $('#grid-master_pickinglist').datagrid('endEdit', editIndex);
            editIndex = undefined;
            return true;
        } else {
            return false;
        }
    }
    function onClickCell(index, field){             
        if (endEditing()){
            var row = $('#grid-master_pickinglist').datagrid('getSelected'); //tambahan
            if (row){
                $('#grid-master_pickinglist').datagrid('selectRow', index)
                .datagrid('editCell', {index:index,field:field});
                editIndex = index;
                $.post('<?php echo site_url('master/pickinglist/update2'); ?>',
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
    
    function qty(value,row,index) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    function act(value,row,index) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    
</script>


        
<!-- Toolbar -->


<!-- Dialog Form -->


<!-- Dialog Button -->

<!-- End of file v_pickinglist.php -->
<!-- Location: ./application/views/master/v_pickinglist.php -->