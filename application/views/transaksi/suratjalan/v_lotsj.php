<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-scrollview.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-filter.js')?>"></script>



<!-- Data Grid -->
<table id="grid-transaksi_suratjalan_lotsj"
    data-options="pageSize:50, singleSelect:true, rownumbers:true, showFooter:true, fit:true, fitColumns:false">
    <thead>
        <tr>            
            <th data-options="field:'box'"                  width="40"  align="center" sortable="true" >BOX</th>
            <th data-options="field:'lot'"                  width="140" align="center" sortable="true" >LOT</th>
            <th data-options="field:'qty'"                  width="140" align="center" sortable="true" formatter="qty2" >Qty</th>              
        </tr>
    </thead>
</table>

<!-- Form -->

<!-- Dialog Button -->


<script type="text/javascript">
    var f = <?php echo $nilai;?>;
    $('#grid-transaksi_suratjalan_lotsj').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('transaksi/suratjalan/lotsj'); ?>?grid=true&nilai='+f})
        .datagrid('enableFilter');
        
    function qty2(value,row,index) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }   
    
</script>

<!-- End of file v_lotsjsj.php -->
<!-- Location: ./application/views/transaksi/suratjalan/v_lotsjsj.php -->