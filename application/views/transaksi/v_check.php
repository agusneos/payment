<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-scrollview.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-filter.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/accounting/accounting.js')?>"></script>

<!-- Data Grid -->
<table id="grid-transaksi_check"
    data-options="pageSize:100, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:false, 
                showFooter:false, fit:true, fitColumns:true, toolbar:toolbar_transaksi_check">
    <thead>
        <tr>           
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'OrderAccount'"         width="80"  align="center" sortable="true">Vendor</th>
            <th data-options="field:'InvoiceId'"            width="150" align="center" sortable="true" >Invoice</th>            
            <th data-options="field:'InvoiceDate'"          width="80"  align="center" sortable="true" >Invoice Date</th>
            <th data-options="field:'SalesBalance'"         width="80"  align="center" sortable="true" formatter="thousandSep" >DPP Amount</th>
            <th data-options="field:'Ppn'"                  width="80"  align="center" sortable="true" formatter="thousandSep" >PPN</th>   
            <th data-options="field:'InvoiceAmount'"        width="80"  align="center" sortable="true" formatter="thousandSep" >Invoice Amount</th>   
        </tr>
    </thead>
</table>

<script type="text/javascript">
    
    var toolbar_transaksi_check = [{
        text:'Check',
        iconCls:'icon-ok',
        handler:function(){check();}
    },{
        text:'Refresh',
        iconCls:'icon-reload',
        handler:function(){$('#grid-transaksi_check').datagrid('reload');}
    }];
    
    $('#grid-transaksi_check').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('transaksi/check/index'); ?>?grid=true'}).datagrid('enableFilter');

    function check()    
    {
        var rows = $('#grid-transaksi_check').datagrid('getSelections');
        if(rows.length>0)
        {
            for(var i=0; i<rows.length; i++)
            {
                
                var row = rows[i];
                $.post('<?php echo site_url('transaksi/check/update'); ?>',
                    {InvoiceId      : row.InvoiceId,
                     SalesBalance   : row.SalesBalance,
                     checkdate      : timestamp()},'json');
                    
                $.post('<?php echo site_url('transaksi/check/createVoucher'); ?>',
                    {OrderAccount:row.OrderAccount, 
                     PaymentDate:row.InvoiceDate,
                     PaymentNumber:row.InvoiceId,
                     InvoiceAmount:row.InvoiceAmount,
                     CurrencyCode:row.CurrencyCode, 
                     ExchRate:row.ExchRate},'json');
            }
            $('#grid-transaksi_check').datagrid('reload');
            //$('#grid-transaksi_check').datagrid('reload');
            
             $.messager.alert('Info','Check Data Berhasil !','info', function(){
               // $('#grid-transaksi_check').datagrid('reload');
                $('#grid-transaksi_check').datagrid('reload');
            });
        }
        else
        {
            $.messager.alert('Info','Data Belum Dipilih!','info');
        }        
    }
            
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