<?php
/***
 * 名称：应用程序类
 * Alan, 2012.03
 * http://www.fcontex.com/
*/
 
final class FCApplication
{
	//编码模式 UTF-8/GBK
	public $charset = CHARSET;
	
	private $_tableEnCode = NULL;
	private $_tableDeCode = NULL;
	
	//单件模式实例
	private static $instance = NULL;
	
	//用户对象实例
	private static $user = NULL;
	
	//数据对象实例
	private static $database = NULL;
	
	//模板对象实例
	private static $template = NULL;
	
	//翻页对象实例
	private static $turnner = NULL;
	
	//路由对象实例
	private static $routing = NULL;
	
	//内容对象实例
	private static $content = NULL;
	
	//系统配置
	public $system;
	
	//站点配置
	public $site;
	
	//构造
	function __construct()
	{
		//强制工厂单件模式
		if (self::$instance)
		{
			die('Error: Call FCApplication::sharedApplication() to get a instance.');
		}
		else
		{
			$this->_start = microtime(TRUE);
		}
		
		if (!get_magic_quotes_gpc())
		{
			$_GET    = $this->fcaddslashes($_GET);
			$_POST   = $this->fcaddslashes($_POST);
			$_COOKIE = $this->fcaddslashes($_COOKIE);
		}
		
		//可逆加密字典
		$arrEnCode = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=');
		$arrDeCode = str_split('MNOPQRSTUVWXYZlmnopqrstuvwxyz~_-0123456789ABCDEFGHIJKLabcdefghijk');
		$this->_tableEnCode = array_combine($arrEnCode, $arrDeCode);
		$this->_tableDeCode = array_combine($arrDeCode, $arrEnCode);
		
		//加载全局配置
		$this->system = $this->loadConfig('system.settings');
		$this->site   = $this->loadConfig('system.site');
	}
	
	//字符串加密
	public function strEnCode($str)
	{
		$encode = '';
		$str = base64_encode($str);
		$end = strlen($str) - 1;
		$dot = ($end+1) / 2 - 1;
		
		for ($i=$dot,$j=$end; $i>=0; $i--,$j--)
		{
			$encode .= $this->_tableEnCode[$str[$i]] . $this->_tableEnCode[$str[$j]];
		}
		
		return $encode;
	}
	
	//字符串解密
	public function strDeCode($str)
	{
		$decodeA = '';
		$decodeB = '';
		$end = strlen($str) - 1;
		
		for ($i=$end; $i>0;)
		{
			$decodeB .= $this->_tableDeCode[$str[$i--]];
			$decodeA .= $this->_tableDeCode[$str[$i--]];
		}
		
		return base64_decode($decodeA.$decodeB);
	}
	
	//单件模式实例化接口
	public static function sharedApplication()
	{
		if (!self::$instance)
		{
			self::$instance = new FCApplication();
		}
		
		return self::$instance;
	}
	
	//共享数据对象实例化接口
	public static function sharedDataBase()
	{
		if (!self::$database)
		{
			self::loadLibrary('database', '', 0);
			$dataArr = self::loadConfig('system.database');
			self::$database = database::connect($dataArr['DB_TYPE'], $dataArr['DB_BASE'], $dataArr['DB_USER'], $dataArr['DB_PASS'], $dataArr['DB_HOST'], $dataArr['DB_CHAR'], $dataArr['DB_HOLD']);
		}
		return self::$database;
	}
	
	//共享模板对象实例化接口
	public static function sharedTemplate()
	{
		if (!self::$template)
		{
			self::$template = self::loadLibrary('template');
		}
		
		return self::$template;
	}
	
	//共享用户对象实例化接口
	public static function sharedUser()
	{
		if (!self::$user)
		{
			self::$user = self::loadLibrary('user');
		}
		
		return self::$user;
	}
	
	//共享内容对象实例化接口
	public static function sharedContent()
	{
		if (!self::$content)
		{
			self::$content = self::loadLibrary('content');
		}
		
		return self::$content;
	}
	
	//共享翻页对象实例化接口
	public static function sharedPageTurnner()
	{
		if (!self::$turnner)
		{
			self::$turnner = self::loadLibrary('pageturnner');
		}
		
		return self::$turnner;
	}
	
	//共享路由对象实例化接口
	public static function sharedRouting()
	{
		if (!self::$routing)
		{
			self::$routing = self::loadLibrary('routing');
		}
		
		return self::$routing;
	}
	
	/***
	 * 系统基础接口
	*/
	
	//页面开始运行时的微秒级时间戳
	private $_start;
	
	//返回从页面开始执行到当前的时间差
	public function processTime($point=6)
	{
		return round(microtime(TRUE) - $this->_start, $point);
	}
	
	//加载配置
	public static function loadConfig($file, $key = '', $default = '')
	{
		$path = PATH_CONFIG.$file.'.php';
		static $data = array();
		//判断是否已经加载
		if (isset($data[$file]))
		{
			if (empty($key)) 
			{
				return $data[$file];
			} 
			elseif (isset($data[$file][$key])) 
			{
				return $data[$file][$key];
			} else {
				return $default;
			}
		}
		if (file_exists($path))
		{
			$data[$file] = include $path;
			if (empty($key))
			{
				return $data[$file];
			}
			elseif (isset($data[$file][$key]))
			{
				return $data[$file][$key];
			}
		}
		return $default;
	}
	
	//保存配置
	public static function saveConfig($config, $data)
	{
		$content = "<?php\n/*自动化配置文件*/\nreturn ".var_export($data, TRUE).";\n?>";
		@file_put_contents(PATH_CONFIG.$config.'.php', $content);
		
		return TRUE;
	}
	
	//加载类库
	public static function loadLibrary($library, $module=NULL, $init=TRUE)
	{
		static $class = array();
		if ($module)
		{
			$path = PATH_MODULE.$module.'library/cls.'.$library.'.php';
		}
		else
		{
			$path = PATH_ROOT.'kernel/library/cls.'.$library.'.php';
			
			if (!file_exists($path))
			{
				$path = PATH_MODULE.$library.'/library/cls.'.$library.'.php';
			}
		}
		$key = md5($path);
		if (isset($class[$key]))
		{
			if (!empty($class[$key]))
			{
				return $class[$key];
			}
			else
			{
				return TRUE;
			}
		}
		if (file_exists($path))
		{
			include $path;
			if ($init)
			{
				$class[$key] = class_exists($library) ? new $library : TRUE;
			}
			else $class[$key] = TRUE;
			return $class[$key];
		}
		return FALSE;		
	}
	
	public function hasRightsErr ($str = '')
	{
		include PATH_MODULE.'system/template/error.rights.php';
		exit();
	}
	
	/***
	 * 字符串处理接口
	*/
	
	//获取GET参数值
	public function strGet($key, $isTransform=TRUE, $default='')
	{			
		return isset($_GET[$key]) ? ($isTransform ? (substr(self::sharedDataBase()->name, 0, 6) == 'sqlite' ? $this->strSQL($_GET[$key]) : $_GET[$key]) : $this->fcstripslashes($_GET[$key])) : $default;
	}
	
	//获取POST参数值
	public function strPost($key, $isTransform=TRUE, $default='')
	{
		return isset($_POST[$key]) ? ($isTransform ? (substr(self::sharedDataBase()->name, 0, 6) == 'sqlite' ? $this->strSQL($_POST[$key]) : $_POST[$key]) : $this->fcstripslashes($_POST[$key])) : $default;
	}
	
	//SQL安全字符串
	public function strSQL($str)
	{
		if (is_array($str)) 
		{
			foreach ($str as $key => $val) 
			{
				$str[$key] = $this->strSQL($val);
			}
		} 
		else 
		{
			$str = str_replace(array("'"), array("''"), $this->fcstripslashes($str));
		}
		return $str;
	}
	
	//可递归过滤数组字符
	public function fcaddslashes($string) 
	{
		if (is_array($string)) 
		{
			foreach ($string as $key => $val) 
			{
				$string[$key] = $this->fcaddslashes($val);
			}
		} 
		else 
		{
			$string = addslashes($string);
		}
		return $string;
	}
	
	//可递归清除转义
	public function fcstripslashes($string) 
	{
		if (is_array($string)) 
		{
			foreach ($string as $key => $val) 
			{
				$string[$key] = $this->fcstripslashes($val);
			}
		} 
		else 
		{
			$string = stripslashes($string);
		}
		return $string;
	}
	
	//按宽度截取字符串(utf-8)
	public function strLeft($str, $width, $ext='')
	{
		if (strtolower($this->charset) == 'gbk')
		{
			return $this->strLeftGbk($str, $width, $ext);
		}
		else
		{
			return $this->strLeftUtf8($str, $width, $ext);
		}
	}
	
	private function strLeftUtf8($str, $width, $ext='')
	{
		$ret = '';
		$i = 0; //字节计数器
		$n = 0; //宽度计数器
		
		$len = strlen($str);
		
		while ($i<=$len && $n<$width)
		{
			$asc = ord(substr($str, $i, 1));
			
			//utf-8核心规则：首字节高位连续1的个数
			//表示该字符utf-8编码所需要的字节数
			
			if ($asc >= 224)
			{//1110 0000
				$ret .= substr($str, $i, 3);
				$i += 3; //字节滚动3
				$n += 2; //宽度滚动2
			}
			elseif ($asc >= 192)
			{//1100 0000
				$ret .= substr($str, $i, 2);
				$i += 2; //字节滚动2
				$n += 2; //宽度滚动2
			}
			/*elseif ($asc>=65 && $asc<=90)
			{//大写字母
				$ret .= substr($str, $i, 1);
				$i += 1; //字节滚动1
				$n += 2; //宽度滚动2
			}*/
			else
			{//其它半角
				$ret .= substr($str, $i, 1);
				$i += 1; //字节滚动1
				$n += 1; //宽度滚动1
			}
		}
		
		//超出宽度才加后缀
		if ($n >= $width) $ret .= $ext;
		
		return $ret;
	}
	
	private function strLeftGbk($str, $width, $ext='')
	{
		return $str;
	}
	
	//文件名后辍
	public function fileSuffix($files)
	{
		return strtolower(substr(strrchr($files, '.'), 1));
	}
    
    //是否为图片
    public function isImage($fileSuffix)
    {
        return in_array(strtolower($fileSuffix), array('jpg','jpeg','gif','png','bmp'));
    }
	
	//获取缩略图
	public function getThumb($pic, $w='', $h='')
	{
		if (empty($pic)) return '';
		
		//返回原图
		if ($w == '' || $h == '') return URL_SITE.DIR_STORE.'/'.$this->system['uploadDir'].'/'.$pic;
		
		//缩略图目录/路径/名称
		$thumbDir   = 'thumb/';
		$thumbPath  = PATH_CACHE.$thumbDir;
		if (!file_exists($thumbPath)) mkdir($thumbPath);
		$thumbDir  .= $w.'x'.$h.'/';
		$thumbPath  = PATH_CACHE.$thumbDir;
		if (!file_exists($thumbPath)) mkdir($thumbPath);
		$thumbName  = basename($pic);
		
		//缩略图不存在则创建
		if (!file_exists($thumbPath.$thumbName))
		{
			$clsPic = self::loadLibrary('picdeal');
			$clsPic->picPath = PATH_STORE.$this->system['uploadDir'].'/'.$pic;
			$clsPic->picNewPath = $thumbPath.$thumbName;
			$clsPic->thumb($w, $h);
		}
		
		//返回缩略图完整URL
		return URL_SITE.DIR_STORE.'/cache/'.$thumbDir.$thumbName;
	}
	
	//根据后辍返回配置文件中的类型
	public function suffixType($fileName='')
	{
		$suffix = $this->fileSuffix($fileName);
		$config = $this->system['uploadSuffix'];
		foreach ($config as $key => $v)
		{
			if (in_array($suffix, explode(',', $v)))
			{
				return $key;
			}	
		}
		return 'file';
	}
	
    //根据后缀返回文件图标
    public function fileIcon($suffix)
	{
		switch ($suffix)
		{
			case 'rar':case 'zip':
			case 'tar':case '7z':
				$icon = 'file_zip.png';
				break;
			case 'doc':case 'docx':
				$icon = 'file_doc.png';
				break;
			case 'ppt':case 'pptx':
				$icon = 'file_ppt.png';
				break;
			case 'xls':case 'xlsx':
				$icon = 'file_xls.png';
				break;
			case 'bmp':case 'png':case 'gif':
			case 'jpg':case 'jpeg':
				$icon = 'file_bmp.png';
				break;
			case 'mp3':case 'wmv':
				$icon = 'file_mp3.png';
				break;
			case 'txt':case 'log':
				$icon = 'file_txt.png';
				break;
			default:
				$icon = 'file_unknown.png';
				break;
		}
		return URL_SKIN.$icon;
	}
	
	//返回用户IP
	public function getUserIP()
	{
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown'))
		{
			$ip = getenv('HTTP_CLIENT_IP');
		} 
		elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) 
		{
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		} 
		elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) 
		{
			$ip = getenv('REMOTE_ADDR');
		} 
		elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) 
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
	}
	
	//cookie设置
	public function setCookie($var, $value, $life=0) 
	{
		setcookie($this->system['cookie_pre'].$var, $value, $life?$life:$this->system['cookie_ttl'], $this->system['cookie_path'], $this->system['cookie_domain'], $_SERVER['SERVER_PORT']==443?1:0);
	}
	
	//cookie获取
	public function getCookie($var) 
	{
		return isset($_COOKIE[$this->system['cookie_pre'].$var]) ? $_COOKIE[$this->system['cookie_pre'].$var] : '';
	}
	
	//返回根目录
	public function getRootDirectory()
	{
		//获取完整盘符路径
		$path = dirname(__FILE__);
		//获取 DocumentRoot 根目录设置，并替换为统一斜杠
		$DOCUMENT_ROOT = str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT']);
		//从完整路径中替换掉根目录路径
		$path = str_ireplace($DOCUMENT_ROOT, '', $path);
		//替换掉当前文件目录
		$path = str_replace('kernel'.DIRECTORY_SEPARATOR.'library', '', $path);
		//最后替换为可用斜杠
		$path = str_replace('\\', '/', $path);
		return $path ? $path : '/';
	}
	
	//清空目录 危险函数请勿滥用
	public function cleanDirectory($dir, $del=FALSE)
	{
		if(!is_dir($dir)) return FALSE;
		
		$handle = opendir($dir);
		while (($name=readdir($handle)) !== FALSE)
		{
			if ($name == "." || $name == "..") continue;
			
			$name = $dir.'/'.$name;
			if (is_dir($name))
			{
				$this->cleanDirectory($name, TRUE);
			}
			elseif (is_file($name))
			{
				@unlink($name);
			}
		}
		closedir($handle);
		if ($del) @rmdir($dir);
		
		return TRUE;
	}
	
	//清除连续空白及HTML标签
	public function trim($str, $strip=FALSE)
	{
		return preg_replace('/[\s]+/i', '', $strip?strip_tags($str):$str);
	}
	
	/**
	* 转换字节数为其他单位
	* @param	string	$filesize	字节大小
	* @return	string	返回大小
	*/
	public function transSize($filesize)
	{
		if ($filesize >= 1073741824)
		{
			$filesize = round($filesize / 1073741824 * 100) / 100 .' GB';
		} 
		elseif ($filesize >= 1048576)
		{
			$filesize = round($filesize / 1048576 * 100) / 100 .' MB';
		}
		elseif ($filesize >= 1024)
		{
			$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
		}
		else
		{
			$filesize = $filesize.' Bytes';
		}
		return $filesize;
	}
	
	//格式化时间为天， 超过3天显示日期
	public function transDate($date, $format='Y-m-d H:i:s')
	{
		if ($date >= strtotime(date("Y-m-d")))
		{
			return '今天 '.date('(H:i)', $date);
		}
		elseif ($date >= strtotime('-2 day'))
		{
			return '昨天 '.date('(H:i)', $date);
		}
		elseif ($date >= strtotime('-3 day'))
		{
			return '前天 '.date('(H:i)', $date);
		}
		else 
		{
			return date($format, $date);	
		}
	}
	
	//添加操作日志
	public function logInsert($event, $uid=0)
	{
		$uid  = $uid ? $uid : $_SESSION['userInfo']['us_id'];
		$time = time();
		$ip   = $this->getUserIP();
		$iparea = $this->loadLibrary('iparea');
		$area = $iparea->dataMini($ip);
		$insertarr = array
		(
			'lg_uid'	=> $uid,
			'lg_time'	=> $time,
			'lg_event'	=> $event,
			'lg_ip'		=> $ip
		);
		if ($area && $area != 'Unknown') $insertarr['lg_iparea'] = $area;
		self::$database->insert('T[logs]', $insertarr);
	}
}
?>