<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-scrollview.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-filter.js')?>"></script>

<!-- Data Grid -->
<table id="grid-transaksi_check"
    data-options="pageSize:100, multiSort:true, remoteSort:false, rownumbers:true, singleSelect:false, 
                showFooter:true, fit:true, fitColumns:true, toolbar:toolbar_transaksi_check">
    <thead>
        <tr>           
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'VendorId'"             width="80"  align="center" sortable="true">Vendor</th>
            <th data-options="field:'InvoiceId'"            width="150" align="center" sortable="true" >Invoice</th>            
            <th data-options="field:'InvoiceDate'"          width="80"  align="center" sortable="true" >Invoice Date</th>
        <!--    <th data-options="field:'CheckDate'"            width="110" align="center" sortable="true" >Check Date</th> -->
            <th data-options="field:'Currency'"             width="50"  align="center" sortable="true" >Currency</th>
            <th data-options="field:'Rate'"                 width="50"  align="center" sortable="true" formatter="thousandSep" >Rate</th>
            <th data-options="field:'ItemId'"               width="70"  align="center" sortable="true" >Item</th>
            <th data-options="field:'Name'"                 width="190" align="left"   sortable="true" halign="center" >Item Name</th>
            <th data-options="field:'PurchUnit'"            width="50"  align="center" sortable="true" >Unit</th>
            <th data-options="field:'Qty'"                  width="80"  align="center" sortable="true" formatter="thousandSep" >Qty</th>
            <th data-options="field:'Price'"                width="80"  align="center" sortable="true" formatter="thousandSep" >Price</th>
            <th data-options="field:'Amount'"               width="100" align="center" sortable="true" formatter="thousandSep" >Amount</th>
            <th data-options="field:'AmountMST'"            width="100" align="center" sortable="true" formatter="thousandSep" >Amount IDR</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    
    var toolbar_transaksi_check = [{
        text:'Check',
        iconCls:'icon-ok',
        handler:function(){getSelections();}
    },{
        text:'Refresh',
        iconCls:'icon-reload',
        handler:function(){$('#grid-transaksi_check').datagrid('reload');}
    }];
    
    $('#grid-transaksi_check').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('transaksi/check/index'); ?>?grid=true'}).datagrid('enableFilter');

    function getSelections(){
        // var ss = [];
        var rows = $('#grid-transaksi_check').datagrid('getSelections');
        for(var i=0; i<rows.length; i++){
            var row = rows[i];
            //ss.push('<span>'+row.Qty+":"+row.Qty+":"+row.Qty+'</span>');
            $.post('<?php echo site_url('transaksi/check/update'); ?>',
                {id:row.Id, checkdate:timestamp()},'json');
            //$.messager.alert('Info', row.Id+' '+timestamp());
        }
        // $.messager.alert('Info', ss.join('<br/>'));
        $('#grid-transaksi_check').datagrid('reload');
    }
            
    function thousandSep(value,row,index) {
        if (value == 0)
        {
            return "";
        }
        else
        {
            return value.toString()
            .replace(".",",")
            .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        
    }    
    
    function timestamp(){
        var today   = new Date();
        var dd      = today.getDate();
        var mm      = today.getMonth()+1; //January is 0!
        var yyyy    = today.getFullYear();
        var hh      = today.getHours();
        var min     = today.getMinutes();
        var ss      = today.getSeconds();

        today = yyyy+'-'+mm+'-'+dd+' '+hh+':'+min+':'+ss;
        return today;
        
    }
    
</script>


        
<!-- Toolbar -->


<!-- Dialog Form -->


<!-- Dialog Button -->

<!-- End of file v_check.php -->
<!-- Location: ./application/views/transaksi/v_check.php -->