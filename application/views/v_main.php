<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>   
    <meta charset="UTF-8">
    <title>Aplikasi Pemantauan Pembayaran</title>
    <link rel="icon" type="image/png" href="<?=base_url('assets/easyui/themes/icons/money.png')?>">
    <link rel="stylesheet" type="text/css" href="<?=base_url('assets/easyui/themes/default/easyui.css')?>">
    <link rel="stylesheet" type="text/css" href="<?=base_url('assets/easyui/themes/icon.css')?>">
    <script type="text/javascript" src="<?=base_url('assets/easyui/jquery.min.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('assets/easyui/jquery.easyui.min.js')?>"></script>
    <script type="text/javascript">
        var url;        
        function add_tab(title, url, iconCls, type){
            if (type == "tabs")
            {
                if ($('#tt').tabs('exists', title)){
                    $('#tt').tabs('select', title);
                } else {
                    if ( url != "kosong")
                    {
                        $('#tt').tabs('add',{
                            title:title,                    
                            href:url,
                            closable:true,
                            iconCls:iconCls,
                        })
                    }
                }
            }
            else if (type == "dialog")
            {
                if ( url != "kosong")
                    {
                        $('#dlg').dialog({
                            title: title,
                            width: 400,
                            height: 250,
                            closed: true,
                            href: url,
                            modal: true
                        });
                        $('#dlg').dialog('open');
                    }
            }
            else if (type == "messager")
            {
                $.messager.confirm('Konfirmasi','Anda ingin mencetak '+title,function(r){
                    if (r){                        
                        var content = '<iframe scrolling="auto" frameborder="0"  \n\
                                        src="'+url+'" style="width:100%;height:100%;"></iframe>'
                        if ($('#tt').tabs('exists', title)){
                            $('#tt').tabs('select', title);
                        } else {
                            $('#tt').tabs('add',{
                                title:title,
                                content:content,
                                closable:true,
                                iconCls:iconCls
                            });
                        }
                    }
                });
            }
            else if (type == "window")
            {
                alert('window');
            }
            else
            {
                
            }
                        
        }
        
        function dashboardTab(title){
            if ($('#tt').tabs('exists', title)){
                $('#tt').tabs('select', title);
            } 
        }
        
        function mainReset(){
            var row = <?php echo $this->session->userdata('id');?>;
            if(row){        
                $('#dlg-reset_main').dialog({modal: true}).dialog('open').dialog('setTitle','Reset Password');
                $('#fm-reset_main').form('reset');
                url = '<?php echo site_url('admin/user/reset'); ?>/' + row;
            }
        }
        
        function mainResetSave(){
            $('#fm-reset_main').form('submit',{
                url: url,
                onSubmit: function(){
                    return $(this).form('validate');
                },
                success: function(result){
                    var result = eval('('+result+')');
                    if(result.success){
                        $('#dlg-reset_main').dialog('close');
                        $.messager.show({
                            title: 'Info',
                            msg: 'Ubah Password Berhasil'
                        });
                    } else {
                        $.messager.show({
                            title: 'Error',
                            msg: 'Ubah Password Gagal'
                        });
                    }
                }
            });
        }
    </script>
    
    <style type="text/css">
        #fm-reset_main{
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
            width:80px;
        }
    </style>
    
</head>
<body>    
    
    <div class="easyui-layout" fit="true" style="width:700px;height:350px;">
        <!-- Top -->
        <div data-options="region:'north',border:true,split:false" style="height:34px;height:28px;" > 
            <div class="easyui-layout" data-options="fit:true" >
                <div data-options="region:'east',split:false,border:false" style="width:250px;background-color:#daeef5">
                    <div align='right' >        
                        <a href="javascript:void(0)" class="easyui-linkbutton" id="clock" data-options="plain:true,iconCls:'icon-time'">                            
                        </a>           
                    </div>
                </div>
                <div data-options="region:'center',split:false,border:false" style="width:150px;background-color:#daeef5">                   
                    <div align='left' >
                        <a href="javascript:void(0)" class="easyui-menubutton" menu="#mm2" data-options="plain:true,iconCls:'icon-user'" >
                            <?php echo $this->session->userdata('nama');?>                            
                        </a>
                        <div id="mm2" style="width:200px;">
                            <div iconCls="icon-undo" onclick="mainReset()">Reset Password</div>
                            <div class="menu-sep"></div>
                            <div href="<?php echo site_url('main/logout'); ?>" iconCls="icon-logout">Logout</div>
                        </div>
                    </div>            
                </div>
            </div>
            
        </div> 
        <!-- bottom -->
        <div data-options="region:'south',split:false" style="height:100px;">
            <h1 align="center">Aplikasi Pemantauan Pembayaran Ver. 1.0</h1>
            <center>©2014 PT. Sagateknindo Sejati. All Rights Reserved.</center>
        </div>
        
        <!-- left -->
        <div data-options="region:'west',split:true" title="Main Menu" iconCls="icon-menu" style="width:200px;">            
            <ul id="ttr" class="easyui-tree" lines="true" animate="true" style="padding:5px"></ul>                
        </div>
        
        <!-- center -->
        <div data-options="region:'center'">
            <div id="tt" class="easyui-tabs" data-options="fit:true,border:false,plain:true" >
                <div title="Dashboard" data-options="closable:false,href:'dashboard',iconCls:'icon-dashboard'" style="padding:10px"></div>
            </div>
            <div id="dlg">      
                
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
        $('#ttr').tree({
        url:'<?php echo site_url('menu/index'); ?>',
        onClick: function(node){
            add_tab(node.text, node.uri, node.iconCls, node.type);
	}
    });
    
    function startTime() {
        var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum&#39;at', 'Sabtu'];
        var date = new Date();
        var day = date.getDate();
        var month = date.getMonth();
        var thisDay = date.getDay(),
            thisDay = myDays[thisDay];
        var yy = date.getYear();
        var year = (yy < 1000) ? yy + 1900 : yy;

        var today=new Date(),
        curr_hour=today.getHours(),
        curr_min=today.getMinutes(),
        curr_sec=today.getSeconds();
        curr_hour=checkTime(curr_hour);
        curr_min=checkTime(curr_min);
        curr_sec=checkTime(curr_sec);
        $('#clock').linkbutton({text:thisDay + ', ' + day + ' ' + months[month] + ' ' + year + ' - ' + curr_hour+":"+curr_min+":"+curr_sec});
    }
    function checkTime(i) {
        if (i<10) {
            i="0" + i;
        }
        return i;
    }
    setInterval(startTime, 500);
    </script>
    <!-- Dialog Reset Form -->
    <div id="dlg-reset_main" class="easyui-dialog" style="width:400px; height:150px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-reset_main">
        <form id="fm-reset_main" method="post" novalidate>       
            <div class="fitem">
                <label for="type">New Password</label>
                <input id="pass" type="password" name="password" class="easyui-textbox" required="true"/>
            </div>        
        </form>
    </div>
    <!-- Dialog Reset Button -->
    <div id="dlg-buttons-reset_main">
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="mainResetSave()">Simpan</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-reset_main').dialog('close')">Batal</a>
    </div>
    
</body>
</html>

<!-- End of file v_main.php -->
<!-- Location: ./application/views/v_main.php -->