<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-scrollview.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-filter.js')?>"></script>

<!-- Start Data Grid -->
<table id="grid-transaksi_suratjalan"
    data-options="pageSize:50, onClickCell:onClickCell, singleSelect:true, fit:true, fitColumns:false, toolbar:toolbar_transaksi_suratjalan">
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
            <th data-options="field:'lot'"                  width="50"  align="center" formatter="lotsj">Lot</th>
            <th data-options="field:'qty'"                  width="80"  align="center" sortable="true" formatter="act">Actual Qty</th>
            <th data-options="field:'box'"                  width="50"  align="center" sortable="true" >Box</th>
            <th data-options="field:'urgent'"               width="80"  align="center" sortable="true" hidden="true" >Urgent</th>
            <th data-options="field:'close'"                width="100" align="center" sortable="true" editor="{type:'checkbox',options:{on:'CLOSE',off:''}}"   styler="close">Close</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">      
    var url;
    
    var toolbar_transaksi_suratjalan = [{
        text:'Refresh',
        iconCls:'icon-reload',
        handler:function(){$('#grid-transaksi_suratjalan').datagrid('reload')}
    }];
    
    $('#grid-transaksi_suratjalan').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('transaksi/suratjalan/index'); ?>?grid=true'})
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
        if ($('#grid-transaksi_suratjalan').datagrid('validateRow', editIndex)){
            $('#grid-transaksi_suratjalan').datagrid('endEdit', editIndex);
            editIndex = undefined;
            return true;
        } else {
            return false;
        }
    }
    function onClickCell(index, field){             
        if (endEditing()){
            var row = $('#grid-transaksi_suratjalan').datagrid('getSelected'); //tambahan
            if (row){
                $('#grid-transaksi_suratjalan').datagrid('selectRow', index)
                .datagrid('editCell', {index:index,field:field});
                editIndex = index;
                $.post('<?php echo site_url('transaksi/suratjalan/update'); ?>',
                {id:row.id, close:row.close},'json');
               // $('#grid-transaksi_suratjalan').datagrid('reload');
                //alert('index ='+index + 'id ='+row.id); //debug                
            }
        }
    }
    
    function close(value,row,index){
        if (value == 'CLOSE'){
            return 'background-color:#00FF00;color:black;font-weight:bold;';
        }
    }
       
    function qty(value,row,index) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    function act(value,row,index) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    function lotsj(row,index){
        return '<a href="javascript:lotsj_click()" ><img src="<?=base_url('assets/easyui/themes/icons/edit_add.png')?>"></a>';
    }
    
    function lotsj_click(){
        var row = $('#grid-transaksi_suratjalan').datagrid('getSelected');
        var s = '<?php echo site_url('transaksi/suratjalan/lotsj'); ?>/' + row.id;
        if (row)
        {
            $('#dlg-lotsj').dialog({
                title   : 'Id - '+ row.id,
                width   : 430,
                height  : 300,
                modal   : true
            });
            $('#dlg-lotsj').dialog('refresh', s);
        }
    }
    
</script>
<!-- End Data Grid -->

<!-- Start Data LOT -->
<div id="dlg-lotsj">
    
</div>
<!-- End Data LOT -->




        
<!-- Toolbar -->


<!-- Dialog Form -->


<!-- Dialog Button -->

<!-- End of file v_suratjalan.php -->
<!-- Location: ./application/views/transaksi/suratjalan/v_suratjalan.php -->