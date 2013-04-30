<?php
if ($U->hasRights('system.login'))
{
?>
<img id="face" src="<?php echo $U->getFace($_SESSION['userInfo']['us_face']); ?>" title="<?php echo $_SESSION['userInfo']['us_name'] != '' ? $_SESSION['userInfo']['us_name'] : $_SESSION['userInfo']['us_username']; ?>" />
<a class="homepage" href="<?php echo URL_SITE; ?>" target="_blank" title="网站首页"></a><a href="javascript:void(0);" class="loginout" title="退出系统"></a>
<?php 
}
else 
{
?>
<img src="<?php echo $U->getFace(); ?>" />游客...
<?php
}
?>