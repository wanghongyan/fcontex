<?php if (!defined('SYSTEM_INCLUDE')) die('Access Denied.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $SITE['site_title']; ?></title>
<meta name="generator" content="<?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?>" />
<meta name="viewport" content="width=1136" />
<meta name="description" content="<?php echo $SITE['site_description']; ?>" />
<meta name="keywords" content="<?php echo $SITE['site_keywords']; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo URL_THEME; ?>images/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo URL_TOOLS; ?>kindeditor/plugins/code/prettify.css" />
<script type="text/javascript" src="<?php echo URL_THEME; ?>images/jquery.js"></script>
<script type="text/javascript" src="<?php echo URL_THEME; ?>images/scrolltop.js"></script>
<script type="text/javascript" src="<?php echo URL_TOOLS; ?>kindeditor/plugins/code/prettify.js"></script>
<script type="text/javascript">
var comment = 
{
	//读取评论
	fetch : function(url)
	{
		$.get(url, function(data)
		{
			$('#comment_box').html(data);
			$('#cm_cid').val('<?php echo $page['ct_id']; ?>');
			$('#cm_ctitle').val('<?php echo $page['ct_title']; ?>');
			$('.comment_list_button input').click(function()
			{
				$('#comment_info').removeClass().html('');
				var form = $('#form_comment').clone(true);
				$('#form_comment').remove();
				$(this).parent().after(form);
				$('#cm_topid').val($(this).attr('topid'));
				$('#cm_toid').val($(this).attr('toid'));
				$('#cm_toname').val($(this).attr('toname'));
				$('#cancel').css('display', 'inline').bind('click', function()
				{
					$('#comment_info').removeClass().html('');
					$('#form_comment').remove();
					$('#comment_form_box').after(form);
					$('#cm_topid').val('0');
					$('#cm_toid').val('0');
					$(this).css('display', 'none');
				});
			});
		});
		
	},
	
	//提交评论
	submit : function()
	{
		$('#comment_info').removeClass().html('<img src="<?php echo URL_THEME; ?>images/loading.gif" />').show();
		$.ajax
		({
			type    : 'post',
			url     : $('#cm_control').val()+'?mode=comment.insert',
			cache   : false,
			data    : $('#form_comment').serialize(),
			success : function(data, textStatus)
			{
				var a = data ? data.split('|') : ['无效的服务器响应。'];
				if (a[0] == 'YES')
				{
					$('#comment_info').addClass('comm_yes').html(a[1]);
					setTimeout(comment.loader, 800);
				}
				else if (a[0] == 'ERR')
				{
					$('#comment_info').addClass('comm_err').html(a[1]);
					if (a[2]) $('#'+a[2]).focus();
				}
				else
				{
					$('#comment_info').html(data);
				}
			},
			error  : function(XMLHttpRequest, textStatus, errorThrown)
			{
				$('#comment_info').addClass('comm_err').html('超求超时.');
			}
		});	
	},
	
	loader : function()
	{
		comment.fetch('<?php echo $R->getUrl('message/comment-'.$page['ct_id'].'-1'); ?>?'+Math.random());
	}
};

$(function()
{
	comment.loader();
	
	prettyPrint();
	
	scrolltotop.init();
	
	$('.list_img a').hover(function()
	{
		$(this).animate({'opacity':'0.1'}, 300);
	}
	, function()
	{
		$(this).stop(true,false).animate({'opacity':'0'}, 300);
	});
	
	var atts = '', dot = '', fcatts = $('#content .fcattached');
	fcatts.each(function(){atts += dot+$(this).attr('key'); dot=',';});
	$.get('<?php echo $R->controller.'?mode=count&id='.$page['ct_id']; ?>&atts='+atts, null, function(data)
	{
		eval('var obj = ' + data);
		if (typeof obj == 'object')
		{
			$('#hits').html(obj.hits || 0);
			$('#talks').html(obj.talks || 0);
			if (typeof obj.atts == 'object' && obj.atts.length == fcatts.length)
			{
				var i = 0;
				fcatts.each(function(){$(this).find('span').html(' (已下载'+(obj.atts[i++])+'次)');});
			}
		}
	});
});
</script>
</head>
<body>

    <div class="top_bg"></div>
    
    <div class="container">
    
    	<?php include 'inc.header.php'; ?>
		
		<?php
		if ($position)
		{
		?>
		<div class="center position">
			<a href="<?php echo URL_SITE; ?>">首页</a>
			<?php
			foreach ($position as $pos)
			{
				if ($pos['link'])
				{
			?>
			<a href="<?php echo $pos['link']; ?>"><?php echo $pos['text']; ?></a>
			<?php
				}
				else echo $pos['text'];
			}
			?>
		</div>
        <?php
		}
		?>
        
        <div class="center">
        	<div class="left">
				<?php
				if (empty($page))
				{
					echo '<div>信息不存在或已被删除。</div>';
				}
				else
				{
                ?>
            	<div class="list">
                    <?php
                    if ($page['ct_type'] == 0)
					{
					?>
					<div class="simplemodal-love hits" id="hits"><?php echo $page['ct_hits']; ?></div>
					<h1><?php echo $page['ct_title']; ?></h1>
                    <ul>
                    	<li class="author"><?php echo $page['ct_username']; ?></li>
                        <li class="time"><?php echo date('Y-m-d', $page['ct_inserttime']); ?></li>
                        <li class="comments"><a href="#comment_box" id="talks"><?php echo $page['ct_talks']; ?></a></li>
                        <li class="litag"><span class="b_tag"></span>
						<?php
						$tags = explode(',', $page['ct_tags']);
						foreach ($tags as $tag)
						{
						?>
						<a href="<?php echo $R->getUrl('content/tag/'.urlencode($tag), ''); ?>"><?php echo $tag; ?></a>&nbsp;
						<?php
						}
						?>
						</li>
                    </ul>
                    <?php
					}
					?>
                    <div class="list_content" id="content"><?php echo $page['ct_content']; ?></div>
					
					<div class="share">
						<!-- JiaThis Button BEGIN -->
						<div class="jiathis_style"><span class="jiathis_txt">分享到：</span>
						<a class="jiathis_button_tsina"></a>
						<a class="jiathis_button_tqq"></a>
						<a class="jiathis_button_tsohu"></a>
						<a class="jiathis_button_t163"></a>
						<a class="jiathis_button_renren"></a>
						<a class="jiathis_button_kaixin001"></a>
						<a class="jiathis_button_qzone"></a>
						<a class="jiathis_button_xiaoyou"></a>
						<a class="jiathis_button_cqq"></a>
						<a class="jiathis_button_weixin"></a>
						<a class="jiathis_button_miliao"></a>
						<a class="jiathis_button_douban"></a>
						<a class="jiathis_button_copy"></a>
						<a href="http://www.jiathis.com/share?uid=1767744" class="jiathis jiathis_txt jiathis_separator jtico jtico_jiathis" target="_blank"></a>
						<a class="jiathis_counter_style"></a>
						</div>
						<script type="text/javascript" >
						var jiathis_config =
						{
							data_track_clickback:true,
							summary:'<?php echo $A->trim($page['ct_summary'], TRUE); ?>',
							title:'<?php echo $page['ct_title']; ?> #<?php echo $SITE['site_name']; ?>#',
							pic:'<?php if ($page['ct_cover']) echo URL_SITE.DIR_STORE.'/'.$A->system['uploadDir'].'/'.$page['ct_cover']; ?>',
							hideMore:false
						}
						</script>
						<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jia.js?uid=1767744" charset="utf-8"></script>
						<!-- JiaThis Button END -->
					</div>
                </div>
                <?php
				}
				?>
				
				<p class="delimiter"></p>
                
				<div id="comment_box"><b style="padding:20px 0; display:block;">正在加载评论...</b></div>
            </div>
            
            <?php include 'inc.sider.php'; ?>
            
        </div>
        
        <div class="clear"></div>
    	
        <?php include 'inc.footer.php'; ?>
    </div>
    
</body>
</html>
