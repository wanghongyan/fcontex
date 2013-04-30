<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>错误信息...</title>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" />
</head>
<body>
	<div class="error_no_rights"><?php if ($str == ''){ ?>Sorry, <?php echo ERROR_NO_RIGHTS; } else { echo $str; } ?> <a href="#" onclick="location.reload(); return false;">刷新</a></div>
</body>
</html>
