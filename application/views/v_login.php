<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<html>
    
<meta charset="UTF-8">    
<title>Login</title>    
<link rel="icon" type="image/png" href="<?=base_url('assets/easyui/themes/icons/login.png')?>">
<link rel="stylesheet" type="text/css" href="<?=base_url('assets/easyui/themes/default/easyui.css')?>">
<link rel="stylesheet" type="text/css" href="<?=base_url('assets/easyui/themes/icon.css')?>">
<script type="text/javascript" src="<?=base_url('assets/easyui/jquery.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/jquery.easyui.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/jquery.easyui.patch.js')?>"></script>

<div data-options="region:'center'">
    <div class="easyui-panel" title="Login to system" data-options="style:{margin:'15% auto'}" style="width:400px;padding:30px 70px 20px 70px">
        <form id="form-login" method="post" novalidate onsubmit="return false">
            <div style="margin-bottom:10px">
                <input id="username" name="username" class="easyui-textbox" style="width:100%;height:40px;padding:12px" data-options="prompt:'Username',iconCls:'icon-man',iconWidth:38" tabindex="1">
            </div>
            <div style="margin-bottom:20px">
                <input id="password" name="password" class="easyui-textbox" type="password" style="width:100%;height:40px;padding:12px" data-options="prompt:'Password',iconCls:'icon-lock',iconWidth:38" tabindex="2">
            </div>
            <div>
                <a id="submit-login" class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="padding:5px 0px;width:100%;" tabindex="3">
                    <span style="font-size:14px;">Login</span>
                </a>
            </div>
        </form>
    </div>

</div>

<script type="text/javascript">
    $(function(){	
        $('#username').next().find('input').focus();
    });
    
    $(function(){
	$('#username').textbox('textbox').keypress(function(e){
            if (e.keyCode == 13){
                $('#password').next().find('input').focus();
            }
	});
    });
    
    $(function(){    
        $('#password').textbox('textbox').keypress(function(e){
            if (e.keyCode == 13){
                $('#submit-login').focus();
            }
        });
    });   
    
    $('#submit-login').keyup(function() {
        login();
    });
    
    $('#submit-login').click(function() {
        login();
    });
    
    function login()
    {
        $.post('<?php echo site_url("main/proses_login"); ?>', $('#form-login').serialize(), function(e) {
            if (e.success) {                
                progress();
            }
            else {  
                $('#form-login').form('clear');
                $.messager.alert('Alert','Maaf Username atau Password Anda Salah!','error',function(){
                    $('#username').next().find('input').focus();
                }); 
            }
        });
    }
    
    function progress()
    {
        $.messager.progress({
            title:'Please wait',
            msg:'Loading data...'
        });
        setTimeout(function(){
            $.messager.progress('close');
             window.location.assign('<?php echo site_url("")//redirect ke index; ?>');
        },1000)           
    }
</script>
 
</html>

<!-- End of file v_login.php -->
<!-- Location: ./application/views/v_login.php -->