<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $SITE['site_title']; ?></title>
<meta name="generator" content="<?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?>" />
<meta name="viewport" content="width=1024" />
<meta name="description" content="<?php echo $SITE['site_description']; ?>" />
<meta name="keywords" content="<?php echo $SITE['site_keywords']; ?>" />
<script type="text/javascript" src="<?php echo URL_THEME; ?>images/jquery.js"></script>
<script type="text/javascript">
$(function()
{
	$("#fly-on-home").animate({top: 50, left: 50}, 300, "", function() {});	
});
</script>
</head>

<body style="background: #ddeded url(<?php echo URL_THEME; ?>images/bg-pattern-plain.jpg) repeat;">

	<a id="fly-on-home" href="<?php echo URL_SITE; ?>" style="position: absolute; top: -1384px; left: -1389px;"><img src="<?php echo URL_THEME; ?>images/404.png" width="389" height="384" alt="could not compute"/></a>	
    
</body>
</html>
