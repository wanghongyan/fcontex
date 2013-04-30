<?php
/***
 * 名称：全局应用程序启动文件
 * Alan, 2012.03
 * http://www.fcontex.com/
*/

//程序错误开关	
ini_set('display_errors', 1);

//输出过滤开关
ini_set('magic_quotes_runtime', 0);

//应用程序信息
define('SYSTEM_INCLUDE', 'YES');
define('SYSTEM_NAME', 'fcontex');
define('SYSTEM_VERSION', '1.0 alpha 3');
define('SYSTEM_MODULE', 'SYSTEM|CONTENT|USER');
define('ERROR_NO_RIGHTS', '没有操作权限。');

//物理根目录
define('PATH_ROOT', dirname(__FILE__).'/../');

//读写存储区
define('DIR_STORE', 'store');
define('PATH_STORE', PATH_ROOT.DIR_STORE.'/');
//配置存储区
define('DIR_CONFIG', 'config');
define('PATH_CONFIG', PATH_STORE.DIR_CONFIG.'/');
//缓存目录
define('DIR_CACHE', 'cache');
define('PATH_CACHE', PATH_STORE.DIR_CACHE.'/');
//插件目录
define('DIR_PLUGIN', 'plugin');
define('PATH_PLUGIN', PATH_STORE.DIR_PLUGIN.'/');
//模块根目录
define('DIR_MODULE', 'module');
define('PATH_MODULE', PATH_ROOT.DIR_MODULE.'/');
//主题目录
define('DIR_THEMES', 'themes');
define('PATH_THEMES', PATH_ROOT.DIR_THEMES.'/');

//字符集
define('CHARSET', 'utf-8');
session_save_path(PATH_CACHE.'session/');
session_start();
header('Cache-Control: private');
header('Content-Type: text/html;charset='.CHARSET);
date_default_timezone_set('PRC');

include PATH_ROOT.'kernel/library/cls.application.php';

//全局公共对象
$A = FCApplication::sharedApplication();
$U = FCApplication::sharedUser();
$D = FCApplication::sharedDataBase();
$T = FCApplication::sharedTemplate();
$R = FCApplication::sharedRouting();
$C = FCApplication::sharedContent();

//网站根目录
define('DIR_SITE', $A->getRootDirectory());

//网站根URL
define('URL_SITE', (isset($_SERVER['HTTP_HOST']) ? 'http://'.$_SERVER['HTTP_HOST'].DIR_SITE : ''));
//皮肤URL
define('URL_SKIN', $U->getSkin());
//脚本URL
define('URL_SCRIPTS', URL_SITE.'kernel/scripts/');
//工具URL
define('URL_TOOLS', URL_SITE.'kernel/tools/');
//模块URL
define('URL_MODULE', URL_SITE.DIR_MODULE.'/');
//主题URL
define('URL_THEME', URL_SITE.DIR_THEMES.'/'.$A->loadConfig('system.site', 'site_theme').'/');
?>