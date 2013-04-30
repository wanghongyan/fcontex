<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>系统登录</title>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" />
<script src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js" type="text/javascript"></script>
<script src="<?php echo URL_SCRIPTS; ?>lib.system.js" type="text/javascript"></script>
<script type="text/javascript">
function loginDo ()
{
	var message = $('#loginMessage');
	var username = $('#username');
	var password = $('#password');
	var subbutton = $('.table_form .button');
	if (username.val() == '')
	{
		username.focus();
		message.html('请输入登录帐户。');
		message.fadeIn(100);
	}
	else if (password.val() == '')
	{
		password.focus();
		message.html('请输入登录密码。');
		message.fadeIn(100);
	}
	else 
	{
		$.ajax
		({
			type    : 'post',
			url     : 'control/?mode=user.login',
			cache   : false,
			data    : $('#form_login').serialize(),
			beforeSend : function(XMLHttpRequest)
			{
				subbutton.addClass('loginLoad');
				subbutton.attr({'value': '', 'disabled' : 'disabled'});
			},
			success : function(data, textStatus)
			{
				var arr = data.split('|');
				if (arr[0] == 'YES')
				{
					message.html('验证通过，加载控制台...');
					message.fadeIn(100);
					window.setTimeout(function()
					{
						var layerWidth = window.top.loginLayerObj.content.width();
						window.top.loginLayerObj.content.animate({left: -(layerWidth+100)+'px'}, 500, '', function()
						{
							// 更新为用户皮肤 
							if (arr[1] != '<?php echo URL_SKIN; ?>' && arr[1])
							{
								window.top.skinSwitch(arr[1]);
							}
							// 更新用户状态信息
							window.top.loginStatus(arr[2]);
							// 关闭遮罩， 移除登录层
							window.top.$('#dialog_mask').remove();
							$$.dialogs.close(window.top.loginLayerObj);
					});}, 1000);
				}
				else
				{
					message.html(arr[1]);
					message.fadeIn(100);
					loginOk();
				}
			},
			error : function(XMLHttpRequest, textStatus, errorThrown)
			{
				message.html('未知错误，请重试。');
				loginOk();
			}
			
		});
	}
	
	function loginOk ()
	{
		subbutton.removeClass('loginLoad');
		subbutton.attr({'value' : '登录'});
		subbutton.removeAttr('disabled');
	}
}

function tipTool(selector)
{
	var td = $(selector);
	if (td.find('input').val() == '')
	{
		td.find('span').show();
	}
	else
	{
		td.find('span').hide();;
	}
}

$(function()
{
	setTimeout(function()
	{
		tipTool('#form_login .login_user');
		tipTool('#form_login .login_pass');
	}, 200);
	
	$('#username').focus();
});
</script>
</style>
</head>
<body>
<div class="login_box">
	<h1><?php echo SYSTEM_NAME; ?> 内容管理系统</h1>
    <form onsubmit="loginDo(); return false;" id="form_login">
    <table width="100%" border="0" class="table_form">
        <tr>
          <td class="login_user"><span>登录账号</span><input type="text" class="text" name="username" id="username" autocomplete="off" onkeyup="tipTool('#form_login .login_user');" /></td>
        </tr>
        <tr>
          <td class="login_pass"><span>登录密码</span><input type="password" class="text" name="password" id="password" onkeyup="tipTool('#form_login .login_pass');" /></td>
        </tr>
        <tr>
          <td><div id="loginMessage"></div><input type="submit" class="button" value="登录" /><a href="#" onclick="window.top.retrieveCode(); return false;">忘记密码？</a>&nbsp;&nbsp;<a href="../../../" target="_blank">返回首页</a></td>
        </tr>
    </table>
    </form>
    <div class="login_right">
    	<span>了解 <?php echo SYSTEM_NAME; ?> 及获取最新动态请</span>
        <a class="login_link" href="http://www.fcontex.com/" target="_blank" id="go">访问官方网站</a>
    </div>
    <div class="clear"></div>
</div>
<script type="text/javascript">
$('.table_form span').click(function(){
	$(this).parent().find('input').focus();	
});
</script>
</body>
</html>
