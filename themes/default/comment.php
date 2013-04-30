<div class="comment_list">
	<h1>查看评论</h1>
    <?php
	foreach ($comments as $rst)
	{
	?>
	<div class="comment_list_1">
		<span class="comment_list_face"><img src="http://www.gravatar.com/avatar/<?php echo md5($rst['cm_email']); ?>?s=40&r=X" /></span>
		<h2 class="comment_list_name"><a target="_blank" href="<?php if ($rst['cm_url']) echo 'http://'.$rst['cm_url']; else echo 'javascript:void(0);'; ?>"><?php echo $rst['cm_name']; ?></a></h2>
			<span class="comment_list_time"><?php echo date('Y-m-d H:i', $rst['cm_time']); ?></span>
			<div class="comment_list_content"><?php echo $rst['cm_content']; ?></div>
			<div class="comment_list_button"><input type="button" value="回复" topid="<?php echo $rst['cm_id']; ?>" toid="<?php echo $rst['cm_id']; ?>" toname="<?php echo $rst['cm_name']; ?>" /></div>
        <?php
		foreach ($M->getComments($rst['cm_id']) as $rst2)
		{
		?>
		<div class="comment_list_2">
			<span class="comment_list_face"><img src="http://www.gravatar.com/avatar/<?php echo md5($rst2['cm_email']); ?>?s=40&r=X" /></span>
			<h2 class="comment_list_name"><a target="_blank" href="<?php if ($rst2['cm_url']) echo 'http://'.$rst2['cm_url']; else echo 'javascript:void(0);'; ?>"><?php echo $rst2['cm_name']; ?></a> @<?php echo $rst2['cm_toname']; ?></h2>
			<span class="comment_list_time"><?php echo date('Y-m-d H:i', $rst2['cm_time']); ?></span>
			<div class="comment_list_content"><?php echo $rst2['cm_content']; ?></div>
			<div class="comment_list_button"><input type="button" value="回复" topid="<?php echo $rst['cm_id']; ?>" toid="<?php echo $rst2['cm_id']; ?>" toname="<?php echo $rst2['cm_name']; ?>" /></div>
		</div>
        <?php
		}
		?>
	</div>
    <?php
	}
	?>
	<?php echo $turnner; ?>
	<h1>发表评论</h1>
    <div id="comment_form_box">
	<?php
	if ($quiet)
	{
		echo '当前页面已关闭评论。';
	}
	else
	{
	?>
		<form onSubmit="comment.submit(); return false;" id="form_comment">
			<input type="hidden" name="cm_control" id="cm_control" value="<?php echo $R->controller; ?>" />
			<input type="hidden" name="cm_cid" id="cm_cid" value="0" />
			<input type="hidden" name="cm_ctitle" id="cm_ctitle" value="" />
			<input type="hidden" name="cm_toid" id="cm_toid" value="0" />
			<input type="hidden" name="cm_topid" id="cm_topid" value="0" />
			<input type="hidden" name="cm_toname" id="cm_toname" value="" />
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td><input type="text" class="text" name="cm_name" id="cm_name" /><label>*称呼</label></td>
				</tr>
				<tr>
					<td><input type="text" class="text" name="cm_email" id="cm_email" /><label>邮箱</label></td>
				</tr>
				<tr>
					<td><input type="text" class="text" name="cm_url" id="cm_url" /><label>网站</label></td>
				</tr>
				<tr>
					<td><textarea class="text" name="cm_content" id="cm_content"></textarea><label>*内容</label></td>
				</tr>
				<tr>
					<td class="submit"><input type="submit" value="提交" /> <input type="button" value="取消" id="cancel" /><label id="comment_info" style="display:none;"></label></td>
				</tr>
			</table>
		</form>
	<?php
	}
	?>
    </div>
</div>