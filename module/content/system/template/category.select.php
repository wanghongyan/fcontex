<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>栏目查看 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
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
		case 'category.delete':
			$$.confirm({text:'确定删除[ #'+args+' ]？', ok:function()
			{
				$$.get('control/?mode=' + mode + '&args=' + args, function(){$$.redirect();});
			}});
			break;
		case 'category.order':
			$$.get('control/?mode=' + mode + '&args=' + args);
			break;
		default:
			$$.alert({text:'无效参数 [ '+mode+' ]。'});
	}
	
	return false;
}

function dlOrder()
{
	var order = '', dot = '';
	$('dl[id]').each(function(){order+=dot+$(this).attr('id').replace('category_','');dot=',';});
	
	return order;
}

function ddOrder(dl)
{
	var selector = dl ? '#category_'+dl+' dd' : 'dd';
	var order = '', dot = '';
	$(selector).each(function(){order+=dot+$(this).attr('id').replace('category_','');dot=',';});
	
	return order;
}

$(function()
{
	window.__order_dl = dlOrder();
	window.__order_dd = ddOrder();
	
	$('body').sortable(
	{
		containment:'window',
		helper:'clone',
		items:'dl',
		axis:'y',
		start: function(){clearTimeout(window.__sort_dl_timer||0);},
		stop: function()
		{
			var order = dlOrder();
			if (window.__order_dl != order)
			{
				window.__sort_dl_timer = setTimeout(function(){category('category.order', order); window.__order_dl = order;}, 1500);
			}
		}
	});
	
	$('dl').sortable(
	{
		containment:'parent',
		items:'dd',
		axis:'y',
		start: function(){clearTimeout(window.__sort_dd_timer||0);},
		stop: function()
		{
			var order = ddOrder();
			var dl = $(this).attr('id').replace('category_','');
			if (window.__order_dd != order)
			{
				window.__sort_dd_timer = setTimeout(function(){category('category.order', ddOrder(dl)); window.__order_dd = order;}, 1500);
			}
		}
	});
});
</script>
</head>
<body>

<?php
$sql = 'select * from T[category] where cg_pid = 0 order by cg_order asc, cg_id asc';
$resP = $D->query($sql);
$n = 0;
while ($rstP = $D->fetch($resP))
{
	$sql = 'select * from T[category] where cg_pid = '.$rstP['cg_id'].' order by cg_order asc, cg_id asc';
	$res = $D->query($sql);
	$rst = $D->fetch($res);
?>
<dl class="category" id="category_<?php echo $rstP['cg_id']; ?>">
	<dt ondblclick="$(this).find('.B a').click();">
		<span class="A"># <?php echo $rstP['cg_id']; ?></span>
		<span class="B"><a href="javascript:void(0);" onclick="$('#category_<?php echo $rstP['cg_id']; ?> dd').toggle();$(this).toggleClass('d');">子栏目</a></span>
		<span class="C"><cite class="type">[<?php echo $rstP['cg_type'] == 0 ? '栏目' : '链接'; ?>]</cite> <?php echo $rstP['cg_title']; ?></span>
		<span class="D"><?php echo $rstP['cg_desc']; ?></span>
		<span class="E">
		<a class="update" href="?mode=category.update&args=<?php echo $rstP['cg_id']; ?>">修改</a> &nbsp; 
		<?php
		if ($rst)
		{
		?>
		<a class="delete" href="javascript:void(0);" style="opacity:0.5; filter:alpha(opacity=50); color:#ccc;">删除</a>
		<?php
		}
		else
		{
		?>
		<a class="delete" href="javascript:void(0);" onclick="category('category.delete', <?php echo $rstP['cg_id']; ?>);">删除</a>
		<?php
		}
		?>
		</span>
	</dt>
	<?php
	if ($rst) do
	{
	?>
	<dd id="category_<?php echo $rst['cg_id']; ?>">
		<span class="A"># <?php echo $rst['cg_id']; ?></span>
		<span class="B"></span>
		<span class="C"><cite class="type">[<?php echo $rst['cg_type'] == 0 ? '栏目' : '链接'; ?>]</cite> <?php echo $rst['cg_title']; ?></span>
		<span class="D"><?php echo $rst['cg_desc']; ?></span>
		<span class="E">
			<a class="update" href="?mode=category.update&args=<?php echo $rst['cg_id']; ?>">修改</a> &nbsp; 
			<a class="delete" href="javascript:void(0);" onclick="category('category.delete', <?php echo $rst['cg_id']; ?>);">删除</a>
		</span>
	</dd>
	<?php
	}
	while ($rst = $D->fetch($res));
	?>
</dl>
<?php
	$n++;
}
if ($n == 0)
{
?>
	<dl class="category">
		<dt><span>还没有添加栏目。&nbsp;&nbsp;<a href="#" onclick="top.naviSwitcher('content/mode=category.select', 'content/mode=category.insert');return false;">立即添加</a></span></dt>
	</dl>
<?php
}
?>

</body>
</html>
