<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-transaksi_payment"
    data-options="pageSize:300, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:false, 
                showFooter:false, fit:true, fitColumns:true, toolbar:toolbar_transaksi_payment">
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
    var toolbar_transaksi_payment = [{
        text    :'Pay',
        iconCls :'icon-ok',
        handler :function(){paid();}
    },{
        text    :'Split',
        iconCls :'icon-split',
        handler :function(){split();}
    },{
        text    :'Delete',
        iconCls :'icon-cancel',
        handler :function(){hapus();}
    },{
        text    :'Refresh',
        iconCls :'icon-reload',
        handler :function(){$('#grid-transaksi_payment').datagrid('reload');}
    }];
    
    $('#grid-transaksi_payment').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('transaksi/payment/index'); ?>?grid=true'}).datagrid('enableFilter');

    function paid(){     
        var rows = $('#grid-transaksi_payment').datagrid('getSelections');
        if(rows.length<1){            
            $.messager.alert('Info','Data Belum Dipilih !','info');
        }
        else {
            var cc = [];
            for(var i=0; i<rows.length; i++){
                    var currency = rows[i]['CurrencyCode'];
                    if ($.inArray(currency, cc) == -1){	// not found
                            cc.push(currency);
                    }
            }
            if (cc.length > 1){
                    $.messager.alert('Info','Currency Berbeda !','info');
            } else {
                $('#dlg-paid').dialog({modal: true}).dialog('open').dialog('setTitle','Posting Payment');
                $('#fm-paid').form('reset');
                var InvoiceAmountSum = 0;
                for(var i=0; i<rows.length; i++){
                    var row = rows[i];
                    InvoiceAmountSum    = eval(InvoiceAmountSum)+eval(row.SalesBalance);               
                }
                $('#InvoiceAmountSum').numberbox('setValue',InvoiceAmountSum);
                $('#InvoiceAmountSum').numberbox({disabled: true});
            }
        }
    }
    
    function paidSave(){       
        var validate    = $('#fm-paid').form('validate');        
        if (validate){
            var paid_date           = $('#paiddate').datebox('getValue');
            var paid_no             = $('#paidno').val();
            var rows                = $('#grid-transaksi_payment').datagrid('getSelections');
            
            for(var i=0; i<rows.length; i++){
                var row = rows[i];
                $.post('<?php echo site_url('transaksi/payment/update'); ?>',
                    {InvoiceId      : row.Id,
                     PayDate        : paid_date},'json');

                $.post('<?php echo site_url('transaksi/payment/createVoucher'); ?>',
                    {Id             : row.Id,
                     OrderAccount   : row.OrderAccount, 
                     PaymentDate    : paid_date,
                     PaymentNumber  : paid_no,
                     Note           : row.InvoiceId,
                     InvoiceAmount  : row.SalesBalance,
                     CurrencyCode   : row.CurrencyCode, 
                     ExchRate       : row.ExchRate},'json');   
            }
            
            $('#dlg-paid').dialog('close');
            $('#grid-transaksi_payment').datagrid('reload');
        }      
    }    
    
    function split(){
        var rows = $('#grid-transaksi_payment').datagrid('getSelections');
        if(rows.length<1){            
            $.messager.alert('Info','Data Belum Dipilih !','info');
        }
        else if(rows.length<2){
            $('#dlg-split').dialog({modal: true}).dialog('open').dialog('setTitle','Split Payment');
            $('#fm-split').form('reset');
            var rowat = $('#grid-transaksi_payment').datagrid('getSelected');            
            if(rowat.CurrencyCode == 'IDR'){
                $('#InvoiceAmountSumSplit').numberbox({min:1,max:eval(rowat.SalesBalance),precision:0});
                $('#InvoiceAmountSumSplit').numberbox('setValue',rowat.SalesBalance);
                $('#InvoiceAmountSisaSplit').numberbox({min:0,precision:0});
                $('#InvoiceAmountSisaSplit').numberbox('setValue', 0);
            }
            else{
                $('#InvoiceAmountSumSplit').numberbox({min:0.01,max:eval(rowat.SalesBalance),precision:2});
                $('#InvoiceAmountSumSplit').numberbox('setValue',rowat.SalesBalance);
                $('#InvoiceAmountSisaSplit').numberbox({min:0,precision:2});
                $('#InvoiceAmountSisaSplit').numberbox('setValue', 0);
            }
            $('#InvoiceAmountSumSplit').numberbox({
                onChange: function(){
                    var awal    = rowat.SalesBalance;
                    var ubah    = $(this).numberbox('getValue');
                    var total   = eval(awal)-eval(ubah);
                    $('#InvoiceAmountSisaSplit').numberbox('setValue', total);
                }
            });
            $('#InvoiceAmountSumSplit').numberbox({disabled: false});
            $('#InvoiceAmountSisaSplit').numberbox({disabled: true});
        }
        else {
            $.messager.alert('Info','Data Tidak Boleh Lebih Dari 2 !','info');
        }
    }
    
    function splitSave(){       
        var validate    = $('#fm-split').form('validate');        
        if (validate){
            var row                = $('#grid-transaksi_payment').datagrid('getSelected');
            var sum                 = $('#InvoiceAmountSumSplit').numberbox('getValue');
            var sisa                = $('#InvoiceAmountSisaSplit').numberbox('getValue');
            if(sisa>0){     // Pecah Payment
                $.post('<?php echo site_url('transaksi/payment/split'); ?>',
                {Id             : row.Id,
                 Sum            : sum,
                 Sisa           : sisa},
                function(result){
                    if (result.success){
                        $.messager.show({
                            title   : 'Info',
                            msg     : '<div class="messager-icon messager-info"></div><div>Split Berhasil</div>'
                        });
                    }
                    else{
                        $.messager.show({
                            title   : 'Error',
                            msg     : '<div class="messager-icon messager-error"></div><div>Split Gagal !</div>'+result.error
                        });
                    }
                },'json');                
            }
            $('#grid-transaksi_payment').datagrid('reload');
            $('#dlg-split').dialog('close');
            $('#grid-transaksi_payment').datagrid('reload');
        }      
    }
    
    function hapus(){
        var ss = [];
        var rows = $('#grid-transaksi_payment').datagrid('getSelections');
        if(rows.length<1){            
            $.messager.alert('Info','Data Belum Dipilihh !','info');
        }
        else {
            for(var i=0; i<rows.length; i++){
                var row = rows[i];
                ss.push('<span>'+row.Id+" : "+row.OrderAccount+" : "+row.InvoiceId+'</span>');
            }
            var win = $.messager.confirm('Konfirmasi', 'Apakah anda yakin menghapus data ?'+('<br/>')+ss.join('<br/>'), function(r){
                if (r){
                    for(var i=0; i<rows.length; i++){
                        var row = rows[i];
                         $.post('<?php echo site_url('transaksi/payment/delete'); ?>',
                            {InvoiceId      : row.Id},'json');
                    }
                }
                $('#grid-transaksi_payment').datagrid('reload');
            });            
            win.find('.messager-icon').removeClass('messager-question').addClass('messager-warning');
            win.window('window').addClass('bg-warning');
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
        
<!-- Toolbar -->


<!-- Dialog Form -->
<style type="text/css">
    #fm-paid{
        margin:0;
        padding:10px 30px;
    }
    #fm-split{
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
            <input id="InvoiceAmountSum" name="InvoiceAmountSum" class="easyui-numberbox" required="true"
                   data-options="groupSeparator:'.',decimalSeparator:',', precision:2"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg_buttons-paid">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="paidSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-paid').dialog('close')">Batal</a>
</div>


<!-- Dialog SPLIT -->
<div id="dlg-split" class="easyui-dialog" style="width:400px; height:230px; padding: 10px 20px" closed="true" buttons="#dlg_buttons-split">
    <form id="fm-split" method="post" novalidate>       
        <div class="fitem">
            <label for="type">Total Amount</label>
            <input id="InvoiceAmountSumSplit" name="InvoiceAmountSumSplit" class="easyui-numberbox" required="true"
                   data-options="groupSeparator:'.',decimalSeparator:','"/>
        </div>
        <div class="fitem">
            <label for="type">Sisa Amount</label>
            <input id="InvoiceAmountSisaSplit" name="InvoiceAmountSisaSplit" class="easyui-numberbox" required="true"
                   data-options="groupSeparator:'.',decimalSeparator:','"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg_buttons-split">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="splitSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-split').dialog('close')">Batal</a>
</div>

<!-- End of file v_payment.php -->
<!-- Location: ./application/views/transaksi/v_payment.php -->