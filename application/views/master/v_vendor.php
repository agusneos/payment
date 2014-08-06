<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-scrollview.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-filter.js')?>"></script>

<!-- Data Grid -->
<table id="grid-master_vendor"
    data-options="pageSize:50, rownumbers:true, singleSelect:true, fit:true, fitColumns:false, toolbar:toolbar_master_vendor">
    <thead>
        <tr>              
            <th data-options="field:'Id'"                   width="100" align="center" sortable="true">Kode Vendor</th>
            <th data-options="field:'Name'"                 width="300" halign="center" align="left" sortable="true">Nama Vendor</th>
            <th data-options="field:'PayTerm'"              width="100" align="center" sortable="true" formatter="payterm">Payment Term</th>            
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_master_vendor = [{
        text:'New',
        iconCls:'icon-new_file',
        handler:function(){$('#grid-master_vendor').datagrid('reload')}
    },{
        text:'Edit',
        iconCls:'icon-edit',
        handler:function(){$('#grid-master_vendor').datagrid('reload')}
    },{
        text:'Delete',
        iconCls:'icon-cancel',
        handler:function(){$('#grid-master_vendor').datagrid('reload')}
    },{
        text:'Upload',
        iconCls:'icon-upload',
        handler:function(){$('#grid-master_vendor').datagrid('reload')}
    },{
        text:'Download',
        iconCls:'icon-download',
        handler:function(){$('#grid-master_vendor').datagrid('reload')}
    },{
        text:'Print',
        iconCls:'icon-print',
        handler:function(){$('#grid-master_vendor').datagrid('reload')}
    },{
        text:'Refresh',
        iconCls:'icon-reload',
        handler:function(){$('#grid-master_vendor').datagrid('reload')}
    }];
    
    $('#grid-master_vendor').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('master/vendor/index'); ?>?grid=true'})
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
        if ($('#grid-master_vendor').datagrid('validateRow', editIndex)){
            $('#grid-master_vendor').datagrid('endEdit', editIndex);
            editIndex = undefined;
            return true;
        } else {
            return false;
        }
    }
    function onClickCell(index, field){             
        if (endEditing()){
            var row = $('#grid-master_vendor').datagrid('getSelected'); //tambahan
            if (row){
                $('#grid-master_vendor').datagrid('selectRow', index)
                .datagrid('editCell', {index:index,field:field});
                editIndex = index;
                $.post('<?php echo site_url('master/vendor/update2'); ?>',
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

<!-- End of file v_vendor.php -->
<!-- Location: ./application/views/master/v_vendor.php -->