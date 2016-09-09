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
    #fm-dialog_payment{
        margin:0;
        padding:20px 30px;
    }
    #dlg_btn-dialog_payment{
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
    <form id="fm-dialog_payment" method="post" novalidate buttons="#dlg_btn-dialog_payment">
        <div class="fitem">
            <label for="type">Tanggal Pengerjaan</label>
            <input  id="tglDo" name="tglDo" class="easyui-datebox" style="width:100px;" required>
        </div>
    </form>

<!-- Dialog Button -->
<div id="dlg_btn-dialog_payment">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="cetak_payment()">Cetak</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Batal</a>
</div>

<script type="text/javascript">
    function cetak_payment()
    {
        var isValid = $('#fm-dialog_payment').form('validate');
        if (isValid){
            var tanggal = $('#tglDo').datebox('getValue');   
            var url     = '<?php echo site_url('report/payment/cetak_payment'); ?>?nilai='+tanggal;
            var content = '<iframe scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:100%;"></iframe>';
            var title   = 'Summary Hutang Supplier';
            if ($('#tt').tabs('exists', title))
            {
                $('#tt').tabs('select', title);
                $('#dlg').dialog('close');
            } 
            else 
            {
                $('#tt').tabs('add',{
                    title:title+' '+tanggal,
                    content:content,
                    closable:true,
                    iconCls:'icon-print'
                });
                $('#dlg').dialog('close');
            }
                 
        }          
    }
</script>

<!-- End of file v_dialog_payment.php -->
<!-- Location: ./views/report/payment/v_dialog_payment.php -->
