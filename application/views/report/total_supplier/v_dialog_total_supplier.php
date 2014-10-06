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
            <label for="type">Bulan</label>
            <select id="bulan" name="bulan" class="easyui-combobox" style="width:100px;" required>
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
            <input id="tahun" name="tahun" class="easyui-numberbox" style="width:100px;" required/>
        </div>
        <div class="fitem">
            <label for="type">Group</label>
            <select id="group" name="group" class="easyui-combobox" style="width:100px;" required>
                <option value=""></option>
                <option value="LOKAL">LOKAL</option>
                <option value="IMPORT">IMPORT</option>
            </select>
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
        if (!isValid)
        {
           // var bulan   = $('#bulan').combobox('getValue');
           // var tahun   = $('#tahun').numberbox('getValue');
           // var group   = $('#group').combobox('getValue');
            
            var bulan   = eval(8);
            var tahun   = eval(2014);
            var group   = 'IMPORT';
            
            var periode = group+'-'+bulan+'-'+tahun;
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

<!-- End of file v_dialog.php -->
<!-- Location: ./views/report/habis_kontrak/v_dialog.php -->
