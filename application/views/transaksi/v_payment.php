<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-scrollview.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-filter.js')?>"></script>


<!-- Data Grid -->
<table id="grid-transaksi_payment"
    data-options="pageSize:100, multiSort:true, remoteSort:false, rownumbers:true, singleSelect:false, 
                showFooter:true, fit:true, fitColumns:true, toolbar:toolbar_transaksi_payment">
    <thead>
        <tr>           
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'VendorId'"             width="80"  align="center" sortable="true">Vendor</th>
            <th data-options="field:'InvoiceId'"            width="150" align="center" sortable="true" >Invoice</th>            
            <th data-options="field:'InvoiceDate'"          width="80"  align="center" sortable="true" >Invoice Date</th>
            <th data-options="field:'Currency'"             width="50"  align="center" sortable="true" >Currency</th>
            <th data-options="field:'Rate'"                 width="50"  align="center" sortable="true" formatter="thousandSep" >Rate</th>           
            <th data-options="field:'Qty'"                  width="80"  align="center" sortable="true" formatter="thousandSep" >Total Qty</th>
            <th data-options="field:'Dpp'"                  width="100" align="center" sortable="true" formatter="thousandSep" >DPP</th>
            <th data-options="field:'DppIdr'"               width="100" align="center" sortable="true" formatter="thousandSep" >DPP IDR</th>
            <th data-options="field:'PpnIdr'"               width="100" align="center" sortable="true" formatter="thousandSep" >PPN IDR</th>
            <th data-options="field:'AmountIdr'"            width="100" align="center" sortable="true" formatter="thousandSep" >Amount IDR</th>
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
        else
        {
            return value.toString()
            .replace(".",",")
            .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
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
            <label for="type">Total Qty</label>
            <input id="totalqty" name="totalqty" align="center" class="easyui-numberbox" 
                   data-options="groupSeparator:'.',decimalSeparator:',',disabled:true" />
        </div>
        <div class="fitem">
            <label for="type">Total DPP</label>
            <input id="totaldpp" name="totaldpp" class="easyui-numberbox"
                   data-options="groupSeparator:'.',decimalSeparator:',',prefix:'Rp. ',disabled:true"/>
        </div>
        <div class="fitem">
            <label for="type">Total DPP IDR</label>
            <input id="totaldppidr" name="totaldppidr" class="easyui-numberbox" 
                   data-options="groupSeparator:'.',decimalSeparator:',',prefix:'Rp. ',disabled:true"/>
        </div>
        <div class="fitem">
            <label for="type">Total PPN IDR</label>
            <input id="totalppnidr" name="totalppnidr" class="easyui-numberbox" 
                   data-options="groupSeparator:'.',decimalSeparator:',',prefix:'Rp. ',disabled:true"/>
        </div>
        <div class="fitem">
            <label for="type">Total Amount IDR</label>
            <input id="totalamountidr" name="totalamountidr" class="easyui-numberbox" 
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
            var totalqty        = 0;
            var totaldpp        = 0;
            var totaldppidr     = 0;
            var totalppnidr     = 0;
            var totalamountidr  = 0;
            for(var i=0; i<rows.length; i++)            
            {
                var row = rows[i];
                totalqty        = eval(totalqty)+eval(row.Qty);
                totaldpp        = eval(totaldpp)+eval(row.Dpp);
                totaldppidr     = eval(totaldppidr)+eval(row.DppIdr);
                totalppnidr     = eval(totalppnidr)+eval(row.PpnIdr);
                totalamountidr  = eval(totalamountidr)+eval(row.AmountIdr);
            }
            $('#totalqty').numberbox('setValue',totalqty);
            $('#totaldpp').numberbox('setValue',totaldpp);
            $('#totaldppidr').numberbox('setValue',totaldppidr);  
            $('#totalppnidr').numberbox('setValue',totalppnidr);
            $('#totalamountidr').numberbox('setValue',totalamountidr);
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
        var paid_date  = $('#paiddate').datebox('getValue');
        var paid_no    = $('#paidno').val();
        var rows = $('#grid-transaksi_payment').datagrid('getSelections');
        var total = 0;
        for(var i=0; i<rows.length; i++)            
        {
            var row = rows[i];
            total = eval(total)+eval(row.AmountIdr);
           // $.post('<?php echo site_url('transaksi/payment/update'); ?>',
          //      {invoiceid:row.InvoiceId, paymentcreatedate:timestamp(), 
          //      paymentdate:paid_date, paymentnumber:paid_no},'json');
        }
        alert(total);
      //  $('#dlg-paid').dialog('close');
      //  $('#grid-transaksi_payment').datagrid('reload');
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