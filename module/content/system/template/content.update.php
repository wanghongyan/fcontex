<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>内容编辑 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
<meta name="robots" content="nofollow">
<link rel="stylesheet" type="text/css" href="<?php echo URL_TOOLS; ?>kindeditor/themes/default/default.css" />
<?php if (0) { ?><link rel="stylesheet" type="text/css" href="../../../system/skins/default/style.css" /><?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" /><?php } ?>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript" src="<?php echo URL_TOOLS; ?>kindeditor/kindeditor.min.js"></script>
<script type="text/javascript" src="<?php echo URL_TOOLS; ?>kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="<?php echo URL_TOOLS; ?>datepicker/WdatePicker.js"></script>
<script type="text/javascript">
function content(mode, args)
{
	switch (mode)
	{
		case 'content.update':
			window.__editor_summary.sync();
			window.__editor_content.sync();
			$$.post('control/?mode=' + mode + '&args=' + args, $('#form_detail').serialize(), function()
			{
				$$.redirect('?mode=content.select<?php echo $A->strGet('page') ? '&page='.$A->strGet('page') : ''; ?>');
			});
			break;
		default:
			$$.alert({text:'无效参数 [ '+mode+' ]。'});
			break;
	}
	
	return false;
}

function coverUploadCallback(param)
{
	$('#thumb_img').html('<a href="'+param.url+'" target="_blank"><img src="'+param.url+'" /></a>');
	$('#ct_cover').val(param.dir+param.name);
	$$.filesUploadLayer.close();
}

function coverClear()
{
	$('#thumb_img').html('');
	$('#ct_cover').val('');
}

$(function()
{
	window.__editor_summary = $$.editor({target:'#ct_summary',css:['<?php echo URL_THEME.'images/style.css'; ?>','<?php echo URL_TOOLS.'kindeditor/plugins/code//prettify.css'; ?>'],mode:2});
	window.__editor_content  = $$.editor({target:'#ct_content',css:['<?php echo URL_THEME.'images/style.css'; ?>','<?php echo URL_TOOLS.'kindeditor/plugins/code//prettify.css'; ?>']});
});
</script>
</head>
<body>

<form id="form_detail" name="form_detail" method="post" action="###" onsubmit="return content('content.update', <?php echo ($args = $A->strGet('args')); ?>);">
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
		$sql = 'select * from T[content] where ct_id = '.$args;
		$res = $D->query($sql);
		if ($rst = $D->fetch($res))
		{
	?>
	<tr>
		<th>主标题</th>
		<td><input class="text" type="text" name="ct_title" id="ct_title" value="<?php echo $rst['ct_title']; ?>" /> <span><cite>*</cite> 必填</span></td>
	</tr>
	<tr>
		<th>副标题</th>
		<td><input class="text" type="text" name="ct_title2" id="ct_title2" value="<?php echo $rst['ct_title2']; ?>" /></td>
	</tr>
    <tr>
		<th>栏目</th>
		<td>
		<input type="hidden" name="ct_category" id="ct_category" value="<?php echo $rst['ct_category']; ?>" />
        <select name="ct_cid" id="ct_cid" onchange="$('#ct_category').val($.trim($(this).find('option:selected[value!=0]').text()));">
        	<option value="0">=选择栏目=</option>
            <?php
			$sql = 'select cg_id, cg_title from T[category] where cg_pid=0 order by cg_order asc, cg_id asc';
			$resP = $D->query($sql);
			while ($rstP = $D->fetch($resP))
			{
            ?>
            <option value="<?php echo $rstP['cg_id']; ?>"<?php if ($rst['ct_cid'] == $rstP['cg_id'])echo ' selected="selected"'; ?>><?php echo $rstP['cg_title']; ?></option>
			<?php
				$sql = 'select cg_id, cg_title from T[category] where cg_pid='.$rstP['cg_id'].' order by cg_order asc, cg_id asc';
				$resC = $D->query($sql);
				while ($rstC = $D->fetch($resC))
				{
			?>
				<option value="<?php echo $rstC['cg_id']; ?>"<?php if ($rst['ct_cid'] == $rstC['cg_id'])echo ' selected="selected"'; ?>>&nbsp; <?php echo $rstC['cg_title']; ?></option>
			<?php
				}
			}
            ?>
        </select>
		<script type="text/javascript">$('#ct_cid').change();</script>
		</td>
	</tr>
    <tr>
		<th>封面</th>
		<td>
			<div class="thumb">
            	<div class="thumb_img" id="thumb_img"><?php if ($rst['ct_cover']){ ?><a target="_blank" href="<?php echo $A->getThumb($rst['ct_cover']); ?>"><img src="<?php echo $A->getThumb($rst['ct_cover'], 200, 200); ?>" /></a><?php } ?></div>
                <h1><a href="#"  onclick="$$.filesUploadLayer.open({callback:'coverUploadCallback',opener:window});return false;">设置封面</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="coverClear();return false;">清除</a></h1>
            </div>
            <input type="hidden" name="ct_cover" id="ct_cover" value="<?php echo $rst['ct_cover']; ?>">
		</td>
	</tr>
    <tr>
		<th>标签</th>
		<td>
        	<input class="text" type="text" name="ct_tags" id="ct_tags" value="<?php echo $rst['ct_tags']; ?>" /> <span> 多个用逗号分隔</span> 
        </td>
	</tr>
	<tr>
		<th>摘要</th>
		<td>
			留空将自动从正文中截取摘要 &nbsp; <a href="javascript:void(0);" onclick="var _this=$(this);_this.next().toggle();_this.toggleClass('D');" class="E">手动编辑摘要</a>
			<div style="margin-top:6px; display:none;"><textarea name="ct_summary" id="ct_summary" style="width:720px; height:200px;"><?php echo $rst['ct_summary']; ?></textarea></div>
		</td>
	</tr>
	<tr>
		<th>内容</th>
		<td><textarea name="ct_content" id="ct_content" style="width:720px; height:340px;"><?php echo $rst['ct_content']; ?></textarea> <span><cite>*</cite> 必填</span></td>
	</tr>
	<tr>
    	<th>发布时间</th>
		<td><input class="text" type="text" name="ct_inserttime" id="ct_inserttime" value="<?php echo date('Y-m-d H:i:s', $rst['ct_inserttime']); ?>" readonly="readonly" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" /> <span><cite>*</cite> 必填 发布时按此时间倒排序</span> </td>
	</tr>
	<tr>
		<th>附加属性</th>
		<td>
			<input type="checkbox" class="checkbox" value="1" id="ct_check" name="ct_check"<?php if ($rst['ct_check'] == 0)echo ' checked="checked"'; ?> /> <label for="ct_check">草稿</label>&nbsp;&nbsp;&nbsp;
        	<input type="checkbox" class="checkbox" value="1" id="ct_fixed" name="ct_fixed"<?php if ($rst['ct_fixed'] == 1)echo ' checked="checked"'; ?> /> <label for="ct_fixed">置顶</label>&nbsp;&nbsp;&nbsp;
            <input type="checkbox" class="checkbox" value="1" id="ct_quiet" name="ct_quiet"<?php if ($rst['ct_quiet'] == 0)echo ' checked="checked"'; ?> /> <label for="ct_quiet">允许评论</label>&nbsp;&nbsp;&nbsp;
			<input type="checkbox" class="checkbox" name="ct_seo"<?php if ($rst['ct_seo'])echo ' checked="checked"'; ?> value="1" id="ct_seo" onclick="if (this.checked) $('#seo').slideDown(300); else $('#seo').slideUp(300);" /> <label for="ct_seo">自定义页面SEO属性</label>
		</td>
	</tr>
    <tbody id="seo" <?php if (!$rst['ct_seo'])echo 'style="display:none;"'; ?>>
    <tr>
		<th>页面标题</th>
		<td><input class="text" type="text" name="ct_pagetitle" id="ct_pagetitle" value="<?php echo $rst['ct_pagetitle']; ?>" /> </td>
	</tr>
    <tr>
		<th>页面关键字</th>
		<td><textarea name="ct_keywords" id="ct_keywords" style="height:60px;"><?php echo $rst['ct_keywords']; ?></textarea></td>
	</tr>
    <tr>
		<th>页面描述</th>
		<td><textarea name="ct_description" id="ct_description" style="height:60px;"><?php echo $rst['ct_description']; ?></textarea></td>
	</tr>
    </tbody>
	<tr class="action"><th>&nbsp;</th><td><input type="submit" class="button" value="确认修改" /><input type="button" class="button cancle" value="放弃修改" onclick="$$.redirect('?mode=content.select');" /></td></tr>
	<?php
		}
	}
	?>
</table>
</form>

</body>
</html>
