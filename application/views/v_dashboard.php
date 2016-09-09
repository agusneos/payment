<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-dashboard_overdue" title="Invoice Overdue" style="height:250px"
    data-options="pageSize:100, multiSort:true, remoteSort:false, rownumbers:true, 
                    singleSelect:true, fit:false, fitColumns:true">
    <thead>
        <tr>
            <th data-options="field:'OrderAccount'"         width="80"  align="center" sortable="true" >Vendor</th>
            <th data-options="field:'InvoiceId'"            width="150" align="center" sortable="true" >Invoice</th>            
            <th data-options="field:'InvoiceDate'"          width="80"  align="center" sortable="true" >Invoice Date</th>
            <th data-options="field:'AcceptDate'"           width="80"  align="center" sortable="true" >Accept Date</th>
            <th data-options="field:'JatuhTempo'"           width="80"  align="center" sortable="true" >Jatuh Tempo</th> 
            <th data-options="field:'SalesBalance'"         width="80"  align="center" sortable="true" formatter="thousandSep" >Invoice Amount</th>   
        </tr>
    </thead>
</table>

<div style="margin:20px 0;"></div>

<table id="grid-dashboard_willoverdue" title="Invoice 1 Week Before Overdue" style="height:250px"
    data-options="pageSize:100, multiSort:true, remoteSort:false, rownumbers:true, 
                    singleSelect:true, fit:false, fitColumns:true">
    <thead>
        <tr>
            <th data-options="field:'OrderAccount'"         width="80"  align="center" sortable="true" >Vendor</th>
            <th data-options="field:'InvoiceId'"            width="150" align="center" sortable="true" >Invoice</th>            
            <th data-options="field:'InvoiceDate'"          width="80"  align="center" sortable="true" >Invoice Date</th>
            <th data-options="field:'AcceptDate'"           width="80"  align="center" sortable="true" >Accept Date</th>
            <th data-options="field:'JatuhTempo'"           width="80"  align="center" sortable="true" >Jatuh Tempo</th> 
            <th data-options="field:'SalesBalance'"         width="80"  align="center" sortable="true" formatter="thousandSep" >Invoice Amount</th>   
        </tr>
    </thead>
</table>

<script type="text/javascript">
    $(function() {
        function grid_refresh() {
            $('#grid-dashboard_overdue').datagrid('reload'); 
            $('#grid-dashboard_willoverdue').datagrid('reload');// reload grid
            setTimeout(grid_refresh, 30000); // schedule next refresh after 30 sec
        }
        $('#grid-dashboard_overdue').datagrid({view:scrollview,remoteFilter:true,
            url:'<?php echo site_url('dashboard/index'); ?>?grid=true'});
         $('#grid-dashboard_willoverdue').datagrid({view:scrollview,remoteFilter:true,
            url:'<?php echo site_url('dashboard/willindex'); ?>?grid=true'});
        grid_refresh();
    });
    
    function thousandSep(value,row,index){
        if (value == 0){
            return "";
        }
        else if (row.CurrencyCode == "IDR"){
            return accounting.formatMoney(value, "Rp. ", 0, ".", ",");
        }
        else{
            return accounting.formatMoney(value, "$ ", 2, ".", ",");
        }        
    }   
    
</script>
<!-- End of file v_dashboard.php -->
<!-- Location: ./application/views/v_dashboard.php -->