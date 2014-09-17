<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-scrollview.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-filter.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/accounting/accounting.js')?>"></script>


<!-- Data Grid -->
<table id="grid-transaksi_payment"
    data-options="pageSize:1000, multiSort:true, remoteSort:false, rownumbers:true, singleSelect:false, 
                showFooter:true, fit:true, fitColumns:true, toolbar:toolbar_transaksi_payment">
    <thead>
        <tr>           
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'OrderAccount'"         width="80"  align="center" sortable="true">Vendor</th>
            <th data-options="field:'InvoiceId'"            width="150" align="center" sortable="true" >Invoice</th>            
            <th data-options="field:'InvoiceDate'"          width="80"  align="center" sortable="true" >Invoice Date</th>
            <th data-options="field:'CurrencyCode'"         width="50"  align="center" sortable="true" >Currency</th>
            <th data-options="field:'ExchRate'"             width="80" align="center" sortable="true" formatter="thousandSepRate" >Rate</th>
            <th data-options="field:'InvoiceAmount'"        width="80"  align="center" sortable="true" formatter="thousandSep" >Invoice Amount</th>            
            <th data-options="field:'InvoiceAmountMST'"     width="100" align="center" sortable="true" formatter="thousandSepIDR" >Invoice Amount IDR</th>
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
    
<div id="dlg-paid" class="easyui-dialog" style="width:400px; height:330px; padding: 10px 20px" closed="true" buttons="#dlg_buttons-paid">
    <form id="fm-paid" method="post" validate>       
        <div class="fitem">
            <label for="type">Tanggal Bayar</label>
            <input id="paiddate" name="paid_date" class="easyui-datebox" required="true" data-options="
                formatter:dateboxFormatter, parser:dateboxParser"/>
        </div>
        <div class="fitem">
            <label for="type">Kode Bayar</label>
            <input id="paidno" name="paid_no" class="easyui-validatebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Rate Bayar</label>
            <input id="rate" name="rate" class="easyui-numberbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Total Amount</label>
            <input id="InvoiceAmount" name="InvoiceAmount" class="easyui-numberbox" 
                   data-options="groupSeparator:'.',decimalSeparator:',', precision:2,disabled:true"/>
        </div>
        <div class="fitem">
            <label for="type">Total Amount IDR</label>
            <input id="InvoiceAmountMST" name="InvoiceAmountMST" class="easyui-numberbox" 
                   data-options="groupSeparator:'.',decimalSeparator:',',prefix:'Rp. ',disabled:true"/>
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
        if(rows.length>0)
        {
            $('#dlg-paid').dialog({modal: true}).dialog('open').dialog('setTitle','Posting Payment');
            $('#fm-paid').form('reset');
            var InvoiceAmountMST    = 0;
            var InvoiceAmount       = 0;
            for(var i=0; i<rows.length; i++)            
            {
                var row = rows[i];
                InvoiceAmountMST    = eval(InvoiceAmountMST)+eval(row.InvoiceAmountMST);
                InvoiceAmount       = eval(InvoiceAmount)+eval(row.InvoiceAmount);
            }           
            $('#InvoiceAmountMST').numberbox('setValue',InvoiceAmountMST);
            $('#InvoiceAmount').numberbox('setValue',InvoiceAmount);
        }
        else
        {
            $.messager.alert('Info','Data Belum Dipilih!','info');
        }
    }
    
    function dateboxFormatter(date){
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
        return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
    }
    function dateboxParser(s){
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
        var paid_date  = $('#paiddate').datebox('getValue');
        var paid_no    = $('#paidno').val();
        
        if (paid_date == '')
        {
            $.messager.alert('Info','Tanggal Pembayaran Belum Diisi','info');
        }
        else if (paid_no == '')
        {
            $.messager.alert('Info','Kode Pembayaran Belum Diisi','info');
        }
        else
        {
            getSelections();
        }
    }    
    
    function getSelections()
    {
        var paid_date   = $('#paiddate').datebox('getValue');
        var paid_no     = $('#paidno').val();
        var amount      = $('#InvoiceAmount').numberbox('getValue');
        var amountmst   = $('#InvoiceAmountMST').numberbox('getValue');
        var rows        = $('#grid-transaksi_payment').datagrid('getSelections');
       // var total       = 0;
        for(var i=0; i<rows.length; i++)            
        {
            var row = rows[i];
            //total = eval(total)+eval(row.InvoiceAmountMST);
            $.post('<?php echo site_url('transaksi/payment/update'); ?>',
                {InvoiceId:row.InvoiceId, paymentcreatedate:timestamp(), 
                paymentdate:paid_date, paymentnumber:paid_no},'json');
        }
        $.post('<?php echo site_url('transaksi/payment/createVoucher'); ?>',
                {paymentnumber:paid_no, paymentdate:paid_date, invoiceamount:amount,
                invoiceamountmst:amountmst, paymentcreatedate:timestamp()},'json');
       // alert(amount);
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