<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<style type="text/css">
    #fm-dialog_total_supplier{
        margin:0;
        padding:20px 30px;
    }
    #dlg_btn-dialog_total_supplier{
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
    <form id="fm-dialog_total_supplier" method="post" novalidate buttons="#dlg_btn-dialog_total_supplier">
        <div class="fitem">
            <label for="type">Vendor</label>
            <input id="vendor" name="vendor" class="easyui-combobox" data-options="
                url:'<?php echo site_url('report/saldo_supplier/get_supp'); ?>',
                method:'get', valueField:'Id', textField:'Name', panelHeight:200" style="width:200px;" required/>
        </div>
        <div class="fitem">
            <label for="type">Tahun</label>
            <input id="tahun" name="tahun" class="easyui-numberspinner" value="2014" data-options="increment:1,required:true" style="width:100px;" />
        </div>
    </form>

<!-- Dialog Button -->
<div id="dlg_btn-dialog_total_supplier">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="cetak_total_supplier()">Cetak</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Batal</a>
</div>

<script type="text/javascript">
    function cetak_total_supplier()
    {
        var isValid = $('#fm-dialog_total_supplier').form('validate');
        if (isValid)
        {         
            var bulan   = $('#bulan').combobox('getValue');
            var tahun   = $('#tahun').numberbox('getValue');            
            var periode = bulan+'-'+tahun;
            
            var url = '<?php echo site_url('report/total_supplier/cetak_total_supplier'); ?>/' + periode;
            var content = '<iframe scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:100%;"></iframe>';
            var title = 'Periode ' + periode;
            
            if ($('#tt').tabs('exists', title))
            {
                $('#tt').tabs('select', title);
                $('#dlg').dialog('close');
            } 
            else 
            {
                $('#tt').tabs('add',{
                    title:title,
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
