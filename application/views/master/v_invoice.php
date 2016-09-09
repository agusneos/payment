<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>


<!-- Data Grid -->
<table id="grid-master_invoice"
    data-options="pageSize:100, multiSort:true, remoteSort:false, rownumbers:true, singleSelect:true, 
                showFooter:false, fit:true, fitColumns:true, toolbar:toolbar_master_invoice">
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
    
    var toolbar_master_invoice = [{
        text:'Upload',
        iconCls:'icon-upload',
        handler:function(){upload();}
    },{
        id      :'master_invoice-edit',
        text    :'Edit',
        iconCls :'icon-edit',
        handler :function(){masterInvoiceEdit();}
    },{
        id      :'master_invoice-delete',
        text    :'Delete',
        iconCls :'icon-cancel',
        handler :function(){masterInvoiceDelete();}
    },{
        text:'Update Rate',
        iconCls:'icon-rate',
        handler:function(){updateRate();}
    },{
        text:'Refresh',
        iconCls:'icon-reload',
        handler:function(){$('#grid-master_invoice').datagrid('reload');}
    }];
    
    $('#grid-master_invoice').datagrid({    
        onClickRow      : function(index,row){
            if (row.CheckDate != '0000-00-00 00:00:00')
            {
                $('#master_invoice-edit').linkbutton('disable');
                $('#master_invoice-delete').linkbutton('disable');
            }
            else
            {
                $('#master_invoice-edit').linkbutton('enable');
                $('#master_invoice-delete').linkbutton('enable');
            }
        },
        view:scrollview,
        remoteFilter:true,
        url:'<?php echo site_url('master/invoice/index'); ?>?grid=true'})
    .datagrid({
        rowStyler:function(index,row){
            if (row.CheckDate != '0000-00-00 00:00:00'){
                return 'background-color:#990012;color:#fff;';
            }
        }
    }).datagrid('enableFilter');
            
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
    
    function thousandSepIDR(value,row,index){
        if (value == 0){
            return "";
        }        
        else{
            return accounting.formatMoney(value, "Rp. ", 0, ".", ",");
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
    
    function payterm(value,row,index) {
        if (value == 0){
            return value;
        } 
        else {
            return value +' Hari';
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
    
    function masterInvoiceDelete()
    {
        var row = $('#grid-master_invoice').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Invoice \n'+row.InvoiceId+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/invoice/delete'); ?>',{InvoiceId:row.InvoiceId},function(result){
                        if (result.success)
                        {
                            $('#grid-master_invoice').datagrid('reload');
                            $.messager.show({
                                title   : 'Info',
                                msg     : '<div class="messager-icon messager-info"></div><div>Data Berhasil Dihapus</div>'
                    });
                        }
                        else
                        {
                            $.messager.show({
                                title   : 'Error',
                                msg     : '<div class="messager-icon messager-error"></div><div>Data Gagal Dihapus !</div>'+result.error
                            });
                        }
                    },'json');
                }
            });
            win.find('.messager-icon').removeClass('messager-question').addClass('messager-warning');
            win.window('window').addClass('bg-warning');
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function updateRate()
    {
        var date = new Date();
        var yy = date.getYear();
        var year = (yy < 1000) ? yy + 1900 : yy;
        
        $('#dlg-updateRate').dialog({modal: true}).dialog('open').dialog('setTitle','Update Rate');
        $('#fm-updateRate').form('reset');
        $('#tahun1').numberspinner('setValue',year);
        
        urls = '<?php echo site_url('master/invoice/updateRate'); ?>/';
    }
    
    function updateRateSave()
    {
        $('#fm-updateRate').form('submit',{
            url: urls,
            onSubmit: function(){   
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success)
                {                    
                    $('#dlg-updateRate').dialog('close');
                    $('#grid-master_invoice').datagrid('reload');
                    $.messager.show({
                        title: 'Info',
                        msg: 'Ubah Rate Berhasil'
                    });
                } 
                else 
                {
                    $.messager.show({
                    title: 'Error',
                    msg: 'Ubah Rate Gagal'
                });
                }
            }
        });
    }
    
    function upload()
    {
        $('#dlg-upload').dialog({modal: true}).dialog('open').dialog('setTitle','Upload File');
        $('#fm-upload').form('reset');
        urls = '<?php echo site_url('master/invoice/upload'); ?>/';
    }
    
    function uploadSave()
    {
        $.messager.progress({
            title   :'Please waiting',
            msg     :'Uploading data...'
        });
        $('#fm-upload').form('submit',{
            url: urls,
            onSubmit: function(){   
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success)
                {
                    
                    $('#dlg-upload').dialog('close');
                    $('#grid-master_invoice').datagrid('reload');
                    $.messager.show({
                            title: 'Info',
                            msg: result.total + ' ' +result.ok + ' ' + result.ng
                            });
                    $.messager.progress('close');
                } 
                else 
                {
                    $.messager.show({
                        title: 'Error',
                        msg: 'Upload Data Gagal'
                    });
                    $.messager.progress('close');
                }
            }
        });
    }
</script>


<!-- Dialog Form -->
<style type="text/css">
    #fm-upload{
        margin:0;
        padding:10px 30px;
    }
    #fm-updateRate{
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
    
<div id="dlg-upload" class="easyui-dialog" style="width:400px; height:150px; padding: 10px 20px" closed="true" buttons="#dlg_buttons-upload">
    <form id="fm-upload" method="post" enctype="multipart/form-data" novalidate>       
        <div class="fitem">
            <label for="type">File</label>
            <input id="file" name="file" class="easyui-filebox" required="true"/>
        </div> 
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg_buttons-upload">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="uploadSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-upload').dialog('close')">Batal</a>
</div>
<!-- ----------- -->
<div id="dlg-updateRate" class="easyui-dialog" style="width:400px; height:250px; padding: 10px 20px" closed="true" buttons="#dlg_buttons-updateRate">
    <form id="fm-updateRate" method="post" novalidate>       
        <div class="fitem">
            <label for="type">Bulan</label>
            <select id="bulan1" name="bulan1" class="easyui-combobox" style="width:100px;" required>
                <option value="0"></option>
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
        </div>
        <div class="fitem">
            <label for="type">Tahun</label>
            <input id="tahun1" name="tahun1" class="easyui-numberspinner" data-options="increment:1,required:true"style="width:100px;" />
        </div> 
        <div class="fitem">
            <label for="type">Rate</label>
            <input id="rate1" name="rate1" class="easyui-numberbox" required="true"
                   data-options="groupSeparator:'.',decimalSeparator:',', precision:0"/>
        </div>
        <div class="fitem">
            <label for="type" style="width:300px;">Update Rate hanya berlaku untuk currency USD dan belum dilakukan Cek invoice</label>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg_buttons-updateRate">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="updateRateSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-updateRate').dialog('close')">Batal</a>
</div>

<!-- Toolbar -->


<!-- Dialog Form -->


<!-- Dialog Button -->

<!-- End of file v_invoice.php -->
<!-- Location: ./application/views/master/v_invoice.php -->