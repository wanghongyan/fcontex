<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>系统控制台 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
<meta name="robots" content="nofollow">
<?php if (0) { ?><link rel="stylesheet" type="text/css" href="../skins/default/style.css" /><?php }else{ ?>
<link id="style0" rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" /><?php } ?>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>jquery.ui.min.js"></script>
<script type="text/javascript">
function naviOpen(target)
{
	$$.target(target);
	
	target = location.href.replace(/^[^#]+#(.+?)$/, '$1').split('/');
	
	if (target.length == 2)
	{
		$('#navs li[module='+target[0]+']').removeClass('C').click();
		$('#menus dd[navigate="'+target[0]+'/'+target[1]+'"]').click();
	}
}

function naviShut(target)
{
	if (target = $$.dialogs.find(target))
	{
		target.close();
	}
}

function naviSwitcher(targetA, targetB)
{
	naviShut(targetA);
	naviShut(targetB);
	naviOpen(targetB);
}

function naviUpdate()
{
	$$.get('?mode=menus', function(text)
	{
		$('#menus').slideUp(100, function(){$('#navs ul').slideUp(100, function()
		{
			$(this).remove();
			$('#navs').append(text).slideDown(100);
			$('#navs li').easyTooltip();
			naviOpen();
		});});
	}, false);
}

function naviClick(navi)
{
	var _this = $(navi);
		
	if (_this.hasClass('C'))
	{
		$('#menus').slideUp(100); _this.removeClass('C');
	}
	else
	{
		$('#navs li').removeClass(); _this.addClass('C');
		$('#menus .menus').empty().append(_this.find('dl').clone(true));
		menuPosition();
		$('#menus').slideDown(100);
	}
}

//二级菜单定位
function menuPosition()
{
	var menusHeight = $('#menus').height();
	var menus = $('#menus dd');
	var Top = T = L = 0, Left = 10;
	for (var i = 0; i < menus.length; i++)
	{
		Top = T * 102;
		if (menusHeight < (T+1)*102)
		{
			L++;
			T = 1,Top = 0; Left = L * 120;
		}
		else T++;
		menus.eq(i).css({'margin-top': Top+'px', 'margin-left': Left+'px'});
	}
}

//打开菜单弹出层
function menuClick(menu)
{
	var _this = $(menu),
	title = _this.find('.L').text(),
	taskid = 'task_id_'+$$.dialogs.zmax,
	url = _this.find('.T').text(),
	icon = _this.find('.icon').html();
	$$.dialogs.open(
	{
		name   : _this.attr('navigate'),
		title  : title,
		ticon  : icon,
		url    : url,
		create : function(layer)
		{
			$('#tasks .tasks').append($('<li id='+taskid+'>'+title+'</li>').prepend(icon));
			$('#'+taskid).bind('click', function()
			{
				if (layer.content.is(':hidden'))
				{
					layer.show();
				}
				else
				{
					if (layer == $$.dialogs.toper) layer.min();
					else layer.focus();
				}
			});
		},
		focus  : function(layer)
		{
			$('#'+taskid).addClass('C');
			$$.target(_this.attr('navigate'));
		},
		blur   : function(layer)
		{
			$('#'+taskid).removeClass('C');
		},
		min    : function(layer)
		{
			$('#'+taskid).removeClass('C');
		},
		close  : function(layer)
		{
			$('#'+taskid).hide(500, function(){$(this).remove();});
			$$.target('###');
		}
	});
}

//登录层
function loginLayer()
{
	
	$$.dialogs.open
	({
		name: 'login',
		url: '<?php echo  DIR_SITE.DIR_MODULE; ?>/user/system/?mode=user.login',
		width: 550,
		height : 240,
		model: true,
		ctrl : [0, 0, 0],
		create : function(layer){window.top.loginLayerObj = layer;}
	});
}

//退出登录
function loginOut()
{
	
	$$.confirm({text:'确定退出登录？', ok:function()
	{
		$$.get('<?php echo DIR_SITE; ?>module/user/system/control/?mode=login.out', function()
		{
			loginLayer();
			loginStatus();
		});	
	}});
	return false;
}

//找回密码层
function retrieveCode()
{
	$$.dialogs.open
	({
		name: 'retrieveCode',
		url: '<?php echo  DIR_SITE.DIR_MODULE; ?>/user/system/?mode=user.retrievecode',
		width: 550,
		height : 240,
		model: true,
		ctrl : [0, 0, 1],
		create : function(layer){window.top.retrieveCodeLayer = layer;}
	});
}

//开始菜单
function startLayer()
{
	$$.dialogs.open
	({
		name: 'start',
		title : '开始菜单',
		ticon : ($('.start .icon').html()),
		url: '<?php echo  DIR_SITE.DIR_MODULE; ?>/system/?mode=config.themes',
		width: 500,
		height : 500,
		model: true,
		ctrl : [0, 0, 1]
	});
}

//壁纸加载完成后显示
function backShow()
{
	if ($('.backimage').length > 1)
	{
		$$.fullscreen($('.backimage').eq(1));
		$('.backimage').eq(0).remove();
		$('.backimage').eq(1).fadeIn(600);
	}
	else
	{
		$$.fullscreen('.backimage');
		$('.backimage').fadeIn(600);
	}
	
	$('#back_thumb').fadeOut(500);
}
//显示壁纸缩略图，并加载壁纸
function loadBack(skin)
{
	$('#back_thumb div').html('<img src="'+skin+'thumb.jpg" />');
	$('#back_thumb').show();
	$('#back_box').append('<img class="backimage" src="'+skin+'back.jpg" height="100%" style=" display:none;" onload="backShow();" />');
}

//切换皮肤{ 1: 更新样式, 2: 更新壁纸 }
var skinId = 1;
function skinSwitch(skin)
{
	skin = skin || '<?php echo URL_SKIN; ?>';
	//加载新样式
	$('#style'+(skinId-1)).after('<link id="style'+skinId+'" rel="stylesheet" type="text/css" href="'+skin+'style.css" onload="$(\'#style'+(skinId-1)+'\').remove();" />');
	skinId++;
	//加载壁纸
	loadBack(skin);
}

//打开用户编辑窗口
function editUser(uid)
{
	$$.dialogs.open
	(
		{
			name: 'login.status',
			title : '编辑用户',
			ticon : '',
			url: '<?php echo  DIR_SITE.DIR_MODULE; ?>/user/system/?mode=user.update&args='+uid,
			model: true,
			ctrl : [0,0,1]
		}
	);
}

//加载登录状态
function loginStatus(uid)
{
	uid = uid ? uid : 0;
	$.get('?mode=login.status', function(text)
	{
		$('.tasks_user').html(text);
		
		if ($('.tasks_user .loginout')) $('.tasks_user .loginout').bind('click', function(){loginOut();});
		if ($('.tasks_user #face')) $('.tasks_user #face').bind('click', function(){editUser(uid);});
		$('.tasks_user a').easyTooltip({xOffset : -35, yOffset : 50});
	});
}

$(function()
{	
	//菜单提示
	$('#navs li').easyTooltip();
	$('.tasks_Desktop').easyTooltip({xOffset : -83, yOffset : 50});
	$('.tasks_user a').easyTooltip({xOffset : -35, yOffset : 50});
	
	//打开锚点层
	naviOpen();
	
	//提示IE6
	if (!window.XMLHttpRequest)
	{
		$('#Ie6Error').slideDown(600);
	}
	
	//显示桌面
	$('.tasks_Desktop').click(function()
	{
		for (var i = 0; i < $$.dialogs.stack.length; i++)
		{
			$$.dialogs.min($$.dialogs.stack[i]);
		}	
	});
	
	//隐藏主界面loading
	$('#preloader, #preloader_mark').fadeOut(300);
	
	//加载默认壁纸
	loadBack('<?php echo URL_SKIN; ?>');
	
	//判断登录 {false: 显示登录层, true: 绑定用户头像和退出登录事件}
	<?php 
	if (!$U->hasRights('system.login'))
	{
	?>
	loginLayer();
	<?php
	}
	else 
	{
	?>
	$('.tasks_user .loginout').bind('click', function(){loginOut();});
	$('.tasks_user #face').bind('click', function(){editUser(<?php echo $_SESSION['userInfo']['us_id']; ?>);});
	<?php
	}
	?>
	
	//窗口改变大小，二级菜单重新定位
	$(window).resize(function(){menuPosition();});
});
</script>
<style type="text/css">html,body {width:100%; height:100%; overflow:hidden; margin:0; padding:0;}</style>
</head>
<body style="background:#D7F0FF;">

<div id="preloader"></div><div id="preloader_mark"></div>

<div id="container">

	<div id="back_box"></div>
    <div id="back_thumb"><span></span><div></div></div>
    
	<div id="navs">
		<div class="back"></div>
		<?php include 'menus.php'; ?>
	</div>
	
	<div id="menus"><div class="back"></div><div class="menus"></div></div>
	
	<div id="tasks">
		<div class="back"></div>
		<div class="start" onclick="startLayer();"><span class="icon" style="display:none;"><img src="<?php echo URL_SKIN; ?>gear.png" /></span><span><img src="<?php echo URL_SKIN; ?>gear.png" /></span></div>
		<ul class="tasks"></ul>
        <div class="tasks_right">
        	<div class="tasks_user"><?php include 'login.status.php'; ?></div>
        	<div class="tasks_Desktop" title="显示桌面"></div>
        </div>
	</div>
	
	<div id="Ie6Error">您使用的浏览器版本过低，影响网页性能，建议您换用<a href="http://info.msn.com.cn/ie9/" target="_blank">IE9</a>、<a href="http://www.google.cn/chrome/intl/zh-CN/landing_chrome.html" target="_blank">谷歌</a>、或<a href="http://www.firefox.com.cn/download/" target="_blank">火狐</a>浏览器。</div>
</div>

</body>
</html>
