<?php
/*自动化配置文件*/
return array
(
  'skin' => 'default',
  //支持上传大小
  'uploadSize'	=> 8,
  //上传文件类型
  'uploadSuffix' => array
  (
		'image' => 'jpg,jpeg,gif,png,bmp',
		'file'	=> 'txt,doc,docx,xls,xlsx,pdf,ppt,pptx,zip,rar,tar,7z',
		'video'	=> 'swf,flv,mp4,mp3'
  ),
  //附件目录
  'uploadDir'	=> 'upload',
  //附件上传路径
  'uploadPath'  => PATH_STORE.'upload/',
  //Cookie配置
  'cookie_domain' => '', 	//Cookie 作用域
  'cookie_path' => '/', 	//Cookie 作用路径
  'cookie_pre' => 'fc_', 	//Cookie 前缀
  'cookie_ttl' => 0,		//Cookie 默认生命周期，0 表示随浏览器进程
  
  'system_user'	=> '1'	//系统管理员，多个用逗号分隔
);
?>