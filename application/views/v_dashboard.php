<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-scrollview.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-filter.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/accounting/accounting.js')?>"></script>

<!-- Data Grid -->
<table id="grid-dashboard_overdue" title="Invoice Overdue" style="height:250px"
    data-options="pageSize:100, multiSort:true, remoteSort:false, rownumbers:true, 
                    singleSelect:true, fit:false, fitColumns:true">
    <thead>
        <tr>
            <th data-options="field:'OrderAccount'"         width="80"  align="center" sortable="true">Vendor</th>
            <th data-options="field:'InvoiceId'"            width="150" align="center" sortable="true" >Invoice</th>            
            <th data-options="field:'InvoiceDate'"          width="80"  align="center" sortable="true" >Invoice Date</th>            
            <th data-options="field:'JatuhTempo'"          width="80"  align="center" sortable="true" >Jatuh Tempo</th>
            <th data-options="field:'PaymentSisa'"         width="80"  align="center" sortable="true" formatter="thousandSep" >DPP Amount</th>
            <th data-options="field:'Ppn'"                  width="80"  align="center" sortable="true" formatter="thousandSep" >PPN</th>   
            <th data-options="field:'InvoiceAmount'"        width="80"  align="center" sortable="true" formatter="thousandSep" >Invoice Amount</th>   
        </tr>
    </thead>
</table>

<div style="margin:20px 0;"></div>

<table id="grid-dashboard_willoverdue" title="Invoice 1 Week Before Overdue" style="height:250px"
    data-options="pageSize:100, multiSort:true, remoteSort:false, rownumbers:true, 
                    singleSelect:true, fit:false, fitColumns:true">
    <thead>
        <tr>
            <th data-options="field:'OrderAccount'"         width="80"  align="center" sortable="true">Vendor</th>
            <th data-options="field:'InvoiceId'"            width="150" align="center" sortable="true" >Invoice</th>
            <th data-options="field:'InvoiceDate'"          width="80"  align="center" sortable="true" >Invoice Date</th>
            <th data-options="field:'JatuhTempo'"          width="80"  align="center" sortable="true" >Jatuh Tempo</th>
            <th data-options="field:'PaymentSisa'"         width="80"  align="center" sortable="true" formatter="thousandSep" >DPP Amount</th>
            <th data-options="field:'Ppn'"                  width="80"  align="center" sortable="true" formatter="thousandSep" >PPN</th>   
            <th data-options="field:'InvoiceAmount'"        width="80"  align="center" sortable="true" formatter="thousandSep" >Invoice Amount</th>   
        </tr>
    </thead>
</table>

<script type="text/javascript">
    $(function() {
        function grid_refresh() {
            $('#grid-dashboard_overdue').datagrid('reload'); 
            $('#grid-dashboard_willoverdue').datagrid('reload');// reload grid
            setTimeout(grid_refresh, 1); // schedule next refresh after 15sec
        }
        $('#grid-dashboard_overdue').datagrid({view:scrollview,remoteFilter:true,
            url:'<?php echo site_url('dashboard/index'); ?>?grid=true'});
         $('#grid-dashboard_willoverdue').datagrid({view:scrollview,remoteFilter:true,
            url:'<?php echo site_url('dashboard/willindex'); ?>?grid=true'});
       // grid_refresh();
    });
    
    function thousandSep(value,row,index)
    {
        if (value == 0)
        {
            return "";
        }
        else if (row.CurrencyCode == "IDR")
        {
            return accounting.formatMoney(value, "Rp. ", 0, ".", ",");
        }
        else
        {
            return accounting.formatMoney(value, "$ ", 2, ".", ",");
        }        
    }   
    
</script>
<!-- End of file v_dashboard.php -->
<!-- Location: ./application/views/v_dashboard.php -->