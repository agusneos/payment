<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript">
    $.extend($.fn.datebox.defaults,{
        formatter:function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
        },
        parser:function(s){
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
    });
</script>
<style type="text/css">
    #fm-dialog_saldo_supplier{
        margin:0;
        padding:20px 30px;
    }
    #dlg_btn-dialog_saldo_supplier{
        margin:0;
        padding:10px 100px;
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
<!-- Form -->
    <form id="fm-dialog_saldo_supplier" method="post" novalidate buttons="#dlg_btn-dialog_saldo_supplier">
        <div class="fitem">
            <label for="type">Vendor</label>
            <input id="vendor" name="vendor" class="easyui-combobox" data-options="
                url:'<?php echo site_url('report/saldo_supplier/get_supp'); ?>',
                method:'get', valueField:'Id', textField:'Name', panelHeight:200" style="width:300px;" required/>
        </div>
        <div class="fitem">
            <label for="type">Dari</label>
            <input id="dari" name="dari" class="easyui-datebox" data-options="required:true" style="width:100px;" />
        </div>
        <div class="fitem">
            <label for="type">Sampai</label>
            <input id="sampai" name="sampai" class="easyui-datebox" data-options="required:true" style="width:100px;" />
        </div>
    </form>

<!-- Dialog Button -->
<div id="dlg_btn-dialog_saldo_supplier">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="cetak_saldo_supplier()">Cetak</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Batal</a>
</div>

<script type="text/javascript">
    function cetak_saldo_supplier()
    {
        var isValid = $('#fm-dialog_saldo_supplier').form('validate');
        if (isValid)
        {           
            //var tahun2  = $('#tahun2').numberbox('getValue');            
            var vendor  = $('#vendor').combobox('getValue');
            var from    = $('#dari').datebox('getValue');
            var to      = $('#sampai').datebox('getValue');
            //var vt      = vendor+'-'+tahun2;
            //var url     = '<?php echo site_url('report/saldo_supplier/cetak_saldo_supplier'); ?>/' + vt;
            var url     = '<?php echo site_url('report/saldo_supplier/cetak_saldo_supplier'); ?>?from='+from+'&to='+to+'&vend='+vendor;
            var content = '<iframe scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:100%;"></iframe>';
            var title   = vendor;
            
            if ($('#tt').tabs('exists', title))
            {
                $('#tt').tabs('select', title);
                $('#dlg').dialog('close');
            } 
            else 
            {
                $('#tt').tabs('add',{
                    title:vendor,
                    content:content,
                    closable:true,
                    iconCls:'icon-print'
                });
                $('#dlg').dialog('close');
            }
                 
        }          
    }
</script>

<!-- End of file v_dialog_saldo_supplier.php -->
<!-- Location: ./views/report/saldo_supplier/v_dialog_saldo_supplier.php -->
