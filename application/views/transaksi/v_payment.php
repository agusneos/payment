<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-scrollview.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-filter.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/accounting/accounting.js')?>"></script>


<!-- Data Grid -->
<table id="grid-transaksi_payment"
    data-options="pageSize:100, multiSort:true, remoteSort:false, rownumbers:true, singleSelect:false, 
                showFooter:false, fit:true, fitColumns:true, toolbar:toolbar_transaksi_payment">
    <thead>
        <tr>           
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'OrderAccount'"         width="80"  align="center" sortable="true">Vendor</th>
            <th data-options="field:'InvoiceId'"            width="150" align="center" sortable="true" >Invoice</th>            
            <th data-options="field:'InvoiceDate'"          width="80"  align="center" sortable="true" >Invoice Date</th>
            <th data-options="field:'PaymentSisa'"          width="80"  align="center" sortable="true" formatter="thousandSep" >DPP Amount</th>
            <th data-options="field:'Ppn'"                  width="80"  align="center" sortable="true" formatter="thousandSep" >PPN</th>   
            <th data-options="field:'InvoiceAmount'"        width="80"  align="center" sortable="true" formatter="thousandSep" >Invoice Amount</th>   
        </tr>
    </thead>
</table>

<script type="text/javascript">
    
    var toolbar_transaksi_payment = [{
        text    :'Pay',
        iconCls :'icon-ok',
        handler :function(){paid();}
    },{
        text    :'Refresh',
        iconCls :'icon-reload',
        handler :function(){$('#grid-transaksi_payment').datagrid('reload');}
    }];
    
    $('#grid-transaksi_payment').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('transaksi/payment/index'); ?>?grid=true'}).datagrid('enableFilter');

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
    
    function thousandSepIDR(value,row,index)
    {
        if (value == 0)
        {
            return "";
        }        
        else
        {
            return accounting.formatMoney(value, "Rp. ", 0, ".", ",");
        }        
    }
    
    function thousandSepRate(value,row,index)
    {
        if (row.CurrencyCode == "IDR")
        {
            return "";
        }        
        else
        {
            return accounting.formatMoney(value, "Rp. ", 0, ".", ",");
        }        
    }
    
    
</script>
        
<!-- Toolbar -->


<!-- Dialog Form -->
<style type="text/css">
    #fm-paid{
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
        width:100px;
    }
</style>
    
<div id="dlg-paid" class="easyui-dialog" style="width:400px; height:230px; padding: 10px 20px" closed="true" buttons="#dlg_buttons-paid">
    <form id="fm-paid" method="post" novalidate>       
        <div class="fitem">
            <label for="type">Tanggal Bayar</label>
            <input id="paiddate" name="paid_date" class="easyui-datebox" required="true" data-options="
                formatter:dateboxFormatter, parser:dateboxParser"/>
        </div>
        <div class="fitem">
            <label for="type">Kode Bayar</label>
            <input id="paidno" name="paid_no" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Total Amount</label>
            <input id="InvoiceAmount" name="InvoiceAmount" class="easyui-numberbox" required="true"
                   data-options="groupSeparator:'.',decimalSeparator:',', precision:2"/>
        </div>
        <div class="fitem">
            <input id="InvoiceAmountTemp" name="InvoiceAmountTemp" class="easyui-numberbox" type="hidden"
                   data-options="groupSeparator:'.',decimalSeparator:',', precision:2"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg_buttons-paid">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="paidSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg-paid').dialog('close')">Batal</a>
</div>

<script type="text/javascript">
    
    function paid()
    {     
        var rows = $('#grid-transaksi_payment').datagrid('getSelections');

        if(rows.length<1)
        {            
            $.messager.alert('Info','Data Belum Dipilih !','info');
        }
        else if(rows.length<2)
        {            
            var rowa = $('#grid-transaksi_payment').datagrid('getSelected');
            if (rowa.CurrencyCode == 'IDR')
            {
                $('#dlg-paid').dialog({modal: true}).dialog('open').dialog('setTitle','Posting Payment');
                $('#fm-paid').form('reset');
                var InvoiceAmount       = 0;

                for(var i=0; i<rows.length; i++)            
                {
                    var row = rows[i];
                    InvoiceAmount       = eval(InvoiceAmount)+eval(row.InvoiceAmount);
                }
                $('#InvoiceAmount').numberbox('setValue',InvoiceAmount);
                $('#InvoiceAmountTemp').numberbox('setValue',InvoiceAmount);
                $('#InvoiceAmount').numberbox({disabled: true});
            }
            else
            {
                $('#dlg-paid').dialog({modal: true}).dialog('open').dialog('setTitle','Posting Payment');
                $('#fm-paid').form('reset');
                var InvoiceAmount       = 0;

                for(var i=0; i<rows.length; i++)            
                {
                    var row = rows[i];
                    InvoiceAmount       = eval(InvoiceAmount)+eval(row.InvoiceAmount);
                }
                $('#InvoiceAmount').numberbox('setValue',InvoiceAmount);
                $('#InvoiceAmountTemp').numberbox('setValue',InvoiceAmount);
                $('#InvoiceAmount').numberbox({disabled: false});
            }
        }
        else
        {
            $('#dlg-paid').dialog({modal: true}).dialog('open').dialog('setTitle','Posting Payment');
            $('#fm-paid').form('reset');
            var InvoiceAmount       = 0;

            for(var i=0; i<rows.length; i++)            
            {
                var row = rows[i];
                InvoiceAmount       = eval(InvoiceAmount)+eval(row.InvoiceAmount);
            }
            $('#InvoiceAmount').numberbox('setValue',InvoiceAmount);
            $('#InvoiceAmountTemp').numberbox('setValue',InvoiceAmount);
            $('#InvoiceAmount').numberbox({disabled: true});
        }
    }
    
    function dateboxFormatter(date)
    {
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
        return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
    }
    function dateboxParser(s)
    {
        if (!s) return new Date();
        var ss = (s.split('-'));
        var y = parseInt(ss[0],10);
        var m = parseInt(ss[1],10);
        var d = parseInt(ss[2],10);
        if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
            return new Date(y,m-1,d);
        } else {
            return new Date();
        }
    }
    
    function paidSave()
    {       
        var validate    = $('#fm-paid').form('validate');        
        if (validate)
        {
            getSelections();
        }      
    }    
    
    function getSelections()
    {
        var paid_date           = $('#paiddate').datebox('getValue');
        var paid_no             = $('#paidno').val();
        var InvoiceAmount       = $('#InvoiceAmount').numberbox('getValue');
        var InvoiceAmountTemp   = $('#InvoiceAmountTemp').numberbox('getValue');
        var rows                = $('#grid-transaksi_payment').datagrid('getSelections');
        
        for(var i=0; i<rows.length; i++)            
        {
            var row = rows[i];
            if (InvoiceAmount == InvoiceAmountTemp)
            {
                $.post('<?php echo site_url('transaksi/payment/update'); ?>',
                {InvoiceId      : row.InvoiceId,
                PaymentSisa     : eval(0)},'json');
            
                $.post('<?php echo site_url('transaksi/payment/createVoucher'); ?>',
                {OrderAccount   : row.OrderAccount, 
                 PaymentDate    : paid_date,
                 PaymentNumber  : paid_no,
                 Note           : row.InvoiceId,
                 InvoiceAmount  : row.InvoiceAmount,
                 CurrencyCode   : row.CurrencyCode, 
                 ExchRate       : row.ExchRate},'json');   
            }
            else
            {
                $.post('<?php echo site_url('transaksi/payment/update'); ?>',
                {InvoiceId      : row.InvoiceId,
                 PaymentSisa    : eval(InvoiceAmountTemp) - eval(InvoiceAmount)},'json');
             
                 $.post('<?php echo site_url('transaksi/payment/createVoucher'); ?>',
                {OrderAccount   : row.OrderAccount, 
                 PaymentDate    : paid_date,
                 PaymentNumber  : paid_no,
                 Note           : row.InvoiceId,
                 InvoiceAmount  : eval(InvoiceAmount),
                 CurrencyCode   : row.CurrencyCode, 
                 ExchRate       : row.ExchRate},'json'); 
            }
                     
        }
        $('#dlg-paid').dialog('close');
        $('#grid-transaksi_payment').datagrid('reload');
    }
    
    function timestamp()
    {
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

<!-- End of file v_payment.php -->
<!-- Location: ./application/views/transaksi/v_payment.php -->