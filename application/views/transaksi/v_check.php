<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-transaksi_check"
    data-options="pageSize:100, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:false, 
                showFooter:false, fit:true, fitColumns:true, toolbar:toolbar_transaksi_check">
    <thead>
        <tr>           
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'Id'"                   width="80"  align="center" sortable="true" >Id</th>
            <th data-options="field:'OrderAccount'"         width="80"  align="center" sortable="true" >Vendor</th>
            <th data-options="field:'InvoiceId'"            width="150" align="center" sortable="true" >Invoice</th>            
            <th data-options="field:'InvoiceDate'"          width="80"  align="center" sortable="true" >Invoice Date</th>
            <th data-options="field:'Qty'"                  width="80"  align="center" sortable="true" formatter="decimalSparator">Quantity</th>
            <th data-options="field:'ExchRate'"             width="80"  align="center" sortable="true" formatter="decimalSparator">Exch. Rate</th>
            <th data-options="field:'SalesBalance'"         width="80"  align="center" sortable="true" formatter="thousandSep" >Invoice Amount</th>   
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

    function check(){
       var rows = $('#grid-transaksi_check').datagrid('getSelections');
        if(rows.length<1){            
            $.messager.alert('Info','Data Belum Dipilih !','info');
        }
        else{
            $('#dlg-check').dialog({modal: true}).dialog('open').dialog('setTitle','Input Tanggal Terima');
            $('#fm-check').form('reset');
        }
    }

    function checkSave(){
        var validate    = $('#fm-check').form('validate');
        if(validate){
            var rows    = $('#grid-transaksi_check').datagrid('getSelections');
            var accept  = $('#accept_date').datebox('getValue');
            for(var i=0; i<rows.length; i++){
                var row = rows[i];
                $.post('<?php echo site_url('transaksi/check/update'); ?>',
                    {InvoiceId      : row.Id,
                     checkdate      : timestamp(),
                     AcptDt         : accept},'json');
                    
                $.post('<?php echo site_url('transaksi/check/createVoucher'); ?>',
                    {Id             : row.Id,
                     OrderAccount   : row.OrderAccount, 
                     PaymentDate    : accept,
                     PaymentNumber  : row.InvoiceId,
                     InvoiceAmount  : row.SalesBalance,
                     CurrencyCode   : row.CurrencyCode, 
                     ExchRate       : row.ExchRate},'json');
            }
            $('#dlg-check').dialog('close');
            $('#grid-transaksi_check').datagrid('reload');            
            $.messager.alert('Info','Check Data Berhasil !','info', function(){
                $('#grid-transaksi_check').datagrid('reload');
            });
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
    
    function decimalSparator(value,row,index){
        if (value == 0){
            return "";
        }        
        else{
            return accounting.formatMoney(value, "", 0, ".", ",");
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

<!-- Dialog Form -->
<style type="text/css">
    #fm-check{
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
    
<div id="dlg-check" class="easyui-dialog" style="width:400px; height:230px; padding: 10px 20px" closed="true" buttons="#dlg_buttons-check">
    <form id="fm-check" method="post" novalidate>       
        <div class="fitem">
            <label for="type">Tanggal Terima</label>
            <input id="accept_date" name="accept_date" class="easyui-datebox" required="true" data-options="
                formatter:dateboxFormatter, parser:dateboxParser"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg_buttons-check">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="checkSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-check').dialog('close')">Batal</a>
</div>

<!-- End of file v_check.php -->
<!-- Location: ./application/views/transaksi/v_check.php -->