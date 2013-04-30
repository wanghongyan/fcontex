<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户编辑 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
<meta name="robots" content="nofollow">
<?php if (0) { ?><link rel="stylesheet" type="text/css" href="../../../system/skins/default/style.css" /><?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" /><?php } ?>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript">
function user(mode, args)
{
	switch (mode)
	{
		case 'user.update':
			$$.post('control/?mode=' + mode + '&args=' + args, $('#form_detail').serialize(), function()
			{
				if (args == '<?php echo $_SESSION['userInfo']['us_id']; ?>')
				{
					window.top.loginStatus(args);
				}
				$$.redirect('?mode=user.select');
			});
			break;
		default:
			$$.alert({text:'无效参数 [ '+mode+' ]。'});
			break;
	}
	
	return false;
}

function headerUploadCallback(param)
{
	$('#thumb_img').html('<a href="'+param.url+'" target="_blank"><img src="'+param.url+'" /></a>');
	$('#us_face').val(param.dir+param.name);
	$$.filesUploadLayer.close();
}

function headerClear()
{
	$('#thumb_img').html('');
	$('#us_face').val('');
}
</script>
</head>
<body>

<form id="form_detail" name="form_detail" method="post" action="###" onsubmit="return user('user.update', <?php echo $args; ?>);">
<table class="table_form" width="100%" border="0" cellspacing="0" cellpadding="0">
	<?php
	if (!is_numeric($args))
	{
	?>
	<tr>
		<th>&nbsp;</th>
		<td>无效参数[ <?php echo $args; ?> ]。</td>
	</tr>
	<?php
	}
	else
	{
		$sql = 'select * from FC_User where us_id = '.$args;
		$res = $D->query($sql);
		if ($rst = $D->fetch($res))
		{
	?>
    <tr>
		<th>所属分组</th>
		<td>
        <select name="us_group">
        	<option>=选择用户组=</option>
        	<?php
			$sql = 'select gr_id, gr_name from T[group] order by gr_id asc';
			$resG = $D->query($sql);
			while ($rstG = $D->fetch($resG))
			{
			?>
            <option value="<?php echo $rstG['gr_id']; ?>"<?php if ($rst['us_group'] == $rstG['gr_id'])echo ' selected="selected"'; ?>><?php echo $rstG['gr_name']; ?></option>
			<?php
			}
			?>
        </select>
		
		<span><cite>*</cite> 必填</span>
		</td>
	</tr>
    <tr>
		<th>头像</th>
		<td>
			<div class="thumb">
            	<div class="thumb_img" id="thumb_img"><a target="_blank" href="<?php echo $A->getThumb($rst['us_face']); ?>"><img src="<?php echo $A->getThumb($rst['us_face'], 200, 200); ?>" /></a></div>
                <h1><a href="#" onclick="$$.filesUploadLayer.open({callback:'headerUploadCallback',opener:window});return false;">设置头像</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="headerClear();return false;">清除</a></h1>
            </div>
            <input type="hidden" name="us_face" id="us_face" value="<?php echo $rst['us_face']; ?>">
		</td>
	</tr>
	<tr>
		<th>登录帐户</th>
		<td>
			<input class="text" type="text" name="us_username" id="us_username" autocomplete="off" value="<?php echo $rst['us_username']; ?>" /> <span><cite>*</cite> 必填</span>
			<script type="text/javascript">$(function(){setTimeout(function(){$('#us_username').val('<?php echo $rst['us_username']; ?>');},200)});</script>
		</td>
	</tr>
	<tr>
		<th>登录密码</th>
		<td>
			<input class="text" type="password" name="us_password" id="us_password" autocomplete="off" /> <span>不修改请留空</span>
			<script type="text/javascript">$(function(){setTimeout(function(){$('#us_password').val('');},200)});</script>
		</td>
	</tr>
	<tr>
		<th>确认密码</th>
		<td><input class="text" type="password" name="us_password2" id="us_password2" autocomplete="off" /></td>
	</tr>
	<tr>
		<th>用户姓名</th>
		<td><input class="text" type="text" name="us_name" id="us_name" value="<?php echo $rst['us_name']; ?>" /> <span><cite>*</cite> 必填</span></td>
	</tr>
	<tr>
		<th>邮件地址</th>
		<td><input class="text" type="text" name="us_email" id="us_email" value="<?php echo $rst['us_email']; ?>" /></td>
	</tr>
	<tr>
		<th>电话号码</th>
		<td><input class="text" type="text" name="us_phone" id="us_phone" value="<?php echo $rst['us_phone']; ?>" /></td>
	</tr>
	<tr>
		<th>用户描述</th>
		<td><textarea name="gr_desc" id="gr_desc" cols="45" rows="5"><?php echo $rst['us_desc']; ?></textarea></td>
	</tr>
	<tr class="action"><th>&nbsp;</th><td><input type="submit" class="button" value="确认修改" /><input type="button" class="button cancle" value="放弃修改" onclick="$$.redirect('?mode=user.select');" /></td></tr>
	<?php
		}
	}
	?>
</table>
</form>


</body>
</html>
