<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<style type="text/css">
    #fm-dialog_voucher{
        margin:0;
        padding:20px 30px;
    }
    #dlg_btn-dialog_voucher{
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
    <form id="fm-dialog_voucher" method="post" novalidate buttons="#dlg_btn-dialog_voucher">
        <div class="fitem">
            <label for="type">Bulan</label>
            <select id="bulan3" name="bulan3" class="easyui-combobox" style="width:100px;" required>
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
            <input id="tahun4" name="tahun4" class="easyui-numberspinner" value="2014" data-options="increment:1,required:true"style="width:100px;" />
        </div>
    </form>

<!-- Dialog Button -->
<div id="dlg_btn-dialog_voucher">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="cetak_voucher()">Cetak</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Batal</a>
</div>

<script type="text/javascript">
    function cetak_voucher()
    {
        var isValid = $('#fm-dialog_voucher').form('validate');
        if (isValid)
        {         
            var bulan3   = $('#bulan3').combobox('getValue');
            var tahun4   = $('#tahun4').numberbox('getValue');            
            var periode = bulan3+'-'+tahun4;
            
            var url = '<?php echo site_url('report/voucher/cetak_voucher'); ?>/' + periode;
            var content = '<iframe scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:100%;"></iframe>';
            var title = 'Voucher ' + periode;
            
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

<!-- End of file v_dialog_voucher.php -->
<!-- Location: ./views/report/voucher/v_dialog_voucher.php -->
