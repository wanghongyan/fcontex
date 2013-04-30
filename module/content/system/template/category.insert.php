<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>栏目添加 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
<meta name="robots" content="nofollow">
<?php if (0) { ?><link rel="stylesheet" type="text/css" href="../../../system/skins/default/style.css" /><?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" /><?php } ?>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>jquery.ui.min.js"></script>
<script type="text/javascript">
function category(mode, args)
{
	switch (mode)
	{
		case 'category.insert':
			$$.post('control/?mode=' + mode, $('#form_detail').serialize(), function()
			{
				top.naviSwitcher('content/mode=category.insert', 'content/mode=category.select');
			});
			break;
		default:
			$$.alert({text:'无效参数 [ '+mode+' ]。'});
			break;
	}
	
	return false;
}
</script>
</head>
<body>

<form id="form_detail" name="form_detail" method="post" action="###" onsubmit="return category('category.insert');">
<table class="table_form" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<th>栏目名称</th>
		<td><input class="text" type="text" name="cg_title" id="cg_title" /> <span><cite>*</cite> 必填</span></td>
	</tr>
	<tr>
		<th>栏目类型</th>
		<td>
			<input type="radio" class="checkbox" name="cg_type" id="cg_type_0" value="0" checked="checked" onclick="$('#cg_target_ui,#cg_url_ui').hide();" /> <label for="cg_type_0">普通栏目</label> &nbsp; &nbsp; &nbsp; &nbsp;
			<input type="radio" class="checkbox" name="cg_type" id="cg_type_1" value="1" onclick="$('#cg_target_ui,#cg_url_ui').show();$('#cg_url').focus();" /> <label for="cg_type_1">链接地址</label>
		</td>
	</tr>
	<tr id="cg_url_ui" style="display:none;">
		<th>链接地址</th>
		<td><input class="text" type="text" name="cg_url" id="cg_url" /></td>
	</tr>
	<tr id="cg_target_ui" style="display:none;">
		<th>打开方式</th>
		<td>
			<input type="radio" class="checkbox" name="cg_target" id="cg_target_0" value="_self" checked="checked" /> <label for="cg_target_0">默认窗口打开</label> &nbsp;
			<input type="radio" class="checkbox" name="cg_target" id="cg_target_1" value="_blank" /> <label for="cg_target_1">新窗口打开</label>
		</td>
	</tr>
	<tr>
		<th>上级栏目</th>
		<td>
        <select name="cg_pid">
            <option value="0">=无上级栏目=</option>
            <?php
            $sql = 'select cg_id, cg_title from T[category] where cg_pid = 0 order by cg_id asc';
            $resG = $D->query($sql);
            while ($rstG = $D->fetch($resG))
            {
            ?>
            <option value="<?php echo $rstG['cg_id']; ?>"><?php echo $rstG['cg_title']; ?></option>
            <?php
            }
            ?>
        </select>
		</td>
	</tr>
	<tr>
		<th>栏目描述</th>
		<td><textarea name="cg_desc" id="cg_desc" cols="45" rows="5"></textarea></td>
	</tr>
	<tr class="action"><th>&nbsp;</th><td><input type="submit" class="button" value="确认添加" /><input type="button" class="button cancle" value="放弃添加" onclick="$$.confirm({text:'放弃添加并关闭窗口？', ok:function(){top.naviShut('content/mode=category.insert');}, icon:'WAR'});" /></td></tr>
</table>
</form>


</body>
</html>
