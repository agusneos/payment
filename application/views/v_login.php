<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<html>
    
<meta charset="UTF-8">    
<title>Login</title>    
<link rel="icon" type="image/png" href="<?=base_url('assets/easyui/themes/icons/login.png')?>">
<link rel="stylesheet" type="text/css" href="<?=base_url('assets/easyui/themes/default/easyui.css')?>">
<link rel="stylesheet" type="text/css" href="<?=base_url('assets/easyui/themes/icon.css')?>">
<script type="text/javascript" src="<?=base_url('assets/easyui/jquery.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/jquery.easyui.min.js')?>"></script>

<div id="win" class="easyui-window" collapsible="false" minimizable="false" maximizable="false" closable="false"
     draggable="false" resizable="false" iconCls="icon-login" title="Login" style="width:300px;height:220px;">
    <form id="form-login" method="post" style="padding: 20px;" novalidate onsubmit="return false">
        <p>Username: <input type="text" id="username" name="username" class="easyui-validatebox" required="true" tabindex="1" ></p>
        <p>Password: <input type="password" id="password" name="password" class="easyui-validatebox" required="true" tabindex="2" ></p>
        <div style="padding:5px;text-align:center;">
            <a id="submit-login" class="easyui-linkbutton" tabindex="3" >Login</a>
        </div>
    </form>
</div>   

<script type="text/javascript">
    function progress(){
        var win = $.messager.progress({
            title:'Please waiting',
            msg:'Loading data...'
        });
        setTimeout(function(){
            $.messager.progress('close');
             window.location.assign('<?php echo site_url("")//redirect ke index; ?>');
        },2000)           
    }
    
    $('#username').keypress(function(e)  {
        if ((e.keyCode == '13')){
            $('#password').focus();
        }
    });
    $('#password').keypress(function(e)  {
        if ((e.keyCode == '13')){
            $.post('<?php echo site_url("main/proses_login"); ?>', $('#form-login').serialize(), function(e) {
                if (e.success == true) {                
                    progress();
                }
                else {
                    $('#form-login').form('clear');
                    $.messager.alert('Alert','Maaf Username atau Password Anda Salah!','error');
                }
            });
        }
    });
    
    $('#submit-login').keypress(function() {
        $.post('<?php echo site_url("main/proses_login"); ?>', $('#form-login').serialize(), function(e) {
            if (e.success == true) {                
                progress();
            }
            else {                
                $('#form-login').form('clear');
                $.messager.alert('Alert','Maaf Username atau Password Anda Salah!','error');
            }
        });
    });
    $('#submit-login').click(function() {
        $.post('<?php echo site_url("main/proses_login"); ?>', $('#form-login').serialize(), function(e) {
            if (e.success == true) {                
                progress();
            }
            else {                
                $('#form-login').form('clear');
                $.messager.alert('Alert','Maaf Username atau Password Anda Salah!','error');
            }
        });
    });
    
</script>
 
</html>

<!-- End of file v_login.php -->
<!-- Location: ./application/views/v_login.php -->