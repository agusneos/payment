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
    #fm-dialog_hutang_supplier{
        margin:0;
        padding:20px 30px;
    }
    #dlg_btn-dialog_hutang_supplier{
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
    <form id="fm-dialog_hutang_supplier" method="post" novalidate buttons="#dlg_btn-dialog_hutang_supplier">
        <div class="fitem">
            <label for="type">Tanggal</label>
            <input  id="tglTrans" name="tglTrans" class="easyui-datebox" style="width:100px;" required>
        </div>
        <div class="fitem">
            <label for="type">Jenis</label>
            <select id="jenis" name="jenis" class="easyui-combobox" style="width:100px;" required>
                <option value="0"></option>
                <option value="1">Summary</option>
                <option value="2">Detail</option>                
            </select>
        </div>
    </form>

<!-- Dialog Button -->
<div id="dlg_btn-dialog_hutang_supplier">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="cetak_hutang_supplier()">Cetak</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Batal</a>
</div>

<script type="text/javascript">
    function cetak_hutang_supplier()
    {
        var isValid = $('#fm-dialog_hutang_supplier').form('validate');
        if (isValid)
        {           
            var jenis   = $('#jenis').combobox('getValue');
            var tanggal = $('#tglTrans').datebox('getValue');   
            if (jenis == 1)
            {
                var url     = '<?php echo site_url('report/hutang_supplier/cetak_hutang_supplier_summary'); ?>?nilai='+tanggal;
                var content = '<iframe scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:100%;"></iframe>';
                var title   = 'Summary Hutang Supplier';
            }
            else
            {
                var url     = '<?php echo site_url('report/hutang_supplier/cetak_hutang_supplier_detail'); ?>?nilai='+tanggal;
                var content = '<iframe scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:100%;"></iframe>';
                var title   = 'Detail Hutang Supplier';
            }
            
            
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

<!-- End of file v_dialog_hutang_supplier.php -->
<!-- Location: ./views/report/hutang_supplier/v_dialog_hutang_supplier.php -->
