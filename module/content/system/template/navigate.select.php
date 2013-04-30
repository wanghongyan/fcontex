<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>导航列表 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
<meta name="robots" content="nofollow">
<?php if (0) { ?><link rel="stylesheet" type="text/css" href="../../../system/skins/default/style.css" /><?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" /><?php } ?>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>jquery.ui.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript">
function content(mode, args, args1)
{
	switch (mode)
	{
		case 'navigate.delete':
			$$.confirm({text:'确定删除导航？', ok:function()
			{
				$$.get('control/?mode=' + mode + '&args=' + args, function(){$$.redirect();});
			}});
			return false;
			break;
		case 'navigate.add':
			$$.post('control/?mode=' + mode, $('#form_navi_add').serialize(), function()
			{
				$$.redirect();
			});
			break;
		case 'navigate.check':
			$$.get('control/?mode=' + mode + '&args=' + args + '&check='+args1);
			break;
		case 'navigate.target':
			$$.get('control/?mode=' + mode + '&args=' + args + '&target='+args1);
			break;
		case 'navigate.update':
			$$.post('control/?mode=' + mode, $('#form_navi').serialize());
			break;
		default:
			$$.alert({text:'请选择操作。'});
	}
	
	return false;
}

$(function()
{
	$('#addNavi input[name=nv_type]').click(function(){
		var index = $('#addNavi input[name=nv_type]').index($(this));
		$('#div_type p').hide();
		$('#div_type p').eq(index).show(300);
	});
	
	$('.table_list tr').hover(function()
	{
		$(this).find('.operate').show();	
	},
	function()
	{
		$(this).find('.operate').hide();
	});
});
</script>
</head>
<body>
    
    <ul class="navigate">
    <form id="form_navi">
    <?php
	$i = 1;
    $query = $D->query('select * from T[navigate] order by nv_order asc, nv_id asc');
	while ($rst = $D->fetch($query))
	{
	?>
	<li>
	<input type="hidden" name="ids[]" value="<?php echo $rst['nv_id']; ?>" />
	排序：<input type="text" value="<?php echo $rst['nv_order']; ?>" class="text" name="nv_order[]" style="width:30px;" />&nbsp;&nbsp;&nbsp;
	标题：<input type="text" value="<?php echo $rst['nv_title']; ?>" class="text" name="nv_title[]" style="width:100px;" />&nbsp;&nbsp;&nbsp;
	URL：<input type="text" value="<?php echo $rst['nv_url']; ?>" class="text" name="nv_url[]" style="width:400px;" />
	<span>
		<input type="checkbox" name="target" value="1" class="checkbox"<?php if ($rst['nv_target'])echo ' checked="checked"'; ?> onclick="content('navigate.target', '<?php echo $rst['nv_id']; ?>', this.checked ? 1 : 0);" />新窗口&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="checkbox" name="check" value="1" class="checkbox"<?php if ($rst['nv_check'])echo ' checked="checked"'; ?> onclick="content('navigate.check', '<?php echo $rst['nv_id']; ?>', this.checked ? 1 : 0);" />启用&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="" onclick="content('navigate.delete', '<?php echo $rst['nv_id']; ?>'); return false;">删除</a>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>
	</li>
    <?php
		$i++;
	}
	if ($i == 1)
	{
		echo '<li>还没有添加导航。</li>';
	}
	else
	{
	?>
    <li>
    	<input type="button" value="保存更改" class="button" onclick="content('navigate.update');" />
    	<input type="button" value="添加" class="button cancle" onclick="if($('#addNavi').css('display') == 'none'){$('#addNavi').stop(true, false).slideDown(300, function(){$('#addButton').focus();});}else{$('#addNavi').stop(true, false).slideUp(300);}" />
    </li>
    <?php
	}
	?>
    </form>
    <li id="addNavi"<?php if ($i > 1)echo ' style="display:none;"'; ?>><b>添加新导航</b>
    	<div class="content">
        <form id="form_navi_add" onsubmit="content('navigate.add');">
        	
        	<p>标题：<input type="text" name="nv_title" id="nv_title" value="" class="text" /></p>
            <p>类型：<input type="radio" value="1" class="checkbox" checked="checked" id="navi_brauch" name="nv_type" /> <label for="navi_brauch">自定义</label>&nbsp;&nbsp;
            <input type="radio" value="2" class="checkbox" id="navi_category" name="nv_type" /> <label for="navi_category">栏目</label>&nbsp;&nbsp;
            <input type="radio" value="3" class="checkbox" id="navi_page" name="nv_type" /> <label for="navi_page">页面</label></p>
            <div id="div_type">
            <p id="navi_brauch">
            	URL：<input type="text" value="http://" name="nv_url" id="nv_url" class="text" style="width:400px;" /> <cite> 如：http://www.fcontex.com</cite>
            </p>
            <p id="navi_category" style="display:none;">
            	栏目：
                <select name="nv_category">
                <?php
                $rescategory = $D->query('select cg_id, cg_title from T[category] order by cg_order asc');
				while ($rstcategory = $D->fetch($rescategory)){
				?>
                	<option value="<?php echo $rstcategory['cg_id']; ?>"><?php echo $rstcategory['cg_title']; ?></option>
                <?php
				}
				?>
                </select>
            </p>
            <p id="navi_page" style="display:none;">
            	页面：
                <select name="nv_page">
                <?php
                $respage = $D->query('select ct_id, ct_title from T[content] where ct_type=1 order by ct_id desc');
				while ($rstpage = $D->fetch($respage)){
				?>
                	<option value="<?php echo $rstpage['ct_id']; ?>"><?php echo $rstpage['ct_title']; ?></option>
                <?php
				}
				?>
                </select>
            </p>
            </div>
            <p>
            	<input type="checkbox" class="checkbox" id="nv_target" name="nv_target" checked="checked" value="1" /> <label for="nv_target">新窗口</label>&nbsp;&nbsp;&nbsp;&nbsp;
            	<input type="checkbox" value="1" name="nv_check" class="checkbox" id="nv_check" checked="checked" /> <label for="nv_check">启用</label>
			</p>
			<br />
			<input type="button" id="addButton" value="确定" class="button" onclick="content('navigate.add');" />
			<input type="button" value="放弃" class="button cancle" onclick="$('#form_navi input.cancle').click();" />
        </form>
        </div>
    </li>
    </ul>

</body>
</html>
