<?php
/***
 * 名称：路由分发
 * Joe, 2012.11
 * http://www.fcontex.com/
*/
 
class routing
{
	//URL Query
	public $query;
	
	//参数
	private $param;
	
	//缓存
	public  $cacheopen;
	private $cachetime;
	
	//模块
	public $mode;
	
	//事件
	public $event;
	
	//路由默认配置
	private $config;
	
	//请求处理程序
	public $controller;
	
	//404控件
	private $in404;
	
	function __construct()
	{
		$this->cacheopen = FALSE;
		$this->cachetime = FCApplication::loadConfig('system.site', 'site_cachetime');
		$this->query  = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
		$this->config = FCApplication::loadConfig('system.routing');
		$this->in404  = FALSE;
	}
	
	/***
	 * 请求分发函数
	 */
	public function parse($query='')
	{
		if (empty($query))
		{
			$query = $this->query;
		}
		else $query = 'm-'.$query;
		
		//解析请求
		$this->param = explode('/', preg_replace(array('/^m-/i', '/\.html$/i'), '', $query));
		$this->mode  = $this->param(0);
		$this->event = $this->param(1,0);
		if (empty($this->mode)) $this->mode = $this->config['default']['mode'];
		if (empty($this->event)) $this->event = $this->config['default']['event'];
		
		//控制台跳转
		if ($this->mode=='system' && $this->event=='start')
		{
			header('location: '.URL_MODULE.'system');exit();
		}
		
		//缓存控制
		$path  = PATH_CACHE.'output/';
		if (!file_exists($path)) mkdir($path);
		$path .= md5($this->query);
		if (!file_exists($path) || ($this->cachetime>0 && time()-filemtime($path)>=$this->cachetime))
		{
			//全局公共对象
			$A = FCApplication::sharedApplication();
			$U = FCApplication::sharedUser();
			$D = FCApplication::sharedDataBase();
			$T = FCApplication::sharedTemplate();
			$R = FCApplication::sharedRouting();
			$C = FCApplication::sharedContent();
			
			//模块处理程序
			$this->controller = URL_SITE.DIR_MODULE.'/'.$this->mode.'/control/';
			
			//模块入口地址
			$entry = PATH_MODULE.$this->mode.'/'.$this->config['default']['file'].'.php';
			
			if (!file_exists($entry))
			{
				$this->print404();
			}
			else
			{
				ob_start();
				include $entry;
				$output = ob_get_contents();
				ob_end_clean();
				
				if ($this->cacheopen)
				{
					file_put_contents($path, $output);
				}
				$this->cacheopen = FALSE;
			}
		}
		else $output = file_get_contents($path);
		
		echo($output);
	}

	/* 获取URL参数值 */
	public function param($m=0, $n=-1)
	{
		if (!isset($this->param[$m])) return '';
		$param = $this->param[$m];
		if ($n == -1) return $param;
		
		$param = explode('-', $param);
		return isset($param[$n]) ? $param[$n] : '';
	}
	
	public function getQueryPrefix()
	{
		return FCApplication::loadConfig('system.site', 'site_rewrite') ? 'm-' : '?';
	}
	
	/* 返回URL路径 */
	public function getUrl($data='', $ext='.html', $prefix=URL_SITE)
	{
		if ($data == '/') return $prefix;
		if ($data == '') return $prefix.$this->getQueryPrefix().$this->query;
		return $prefix.$this->getQueryPrefix().$data.$ext;
	}
	
	/* 输出404错误 */
	public function print404()
	{
		if (!$this->in404)
		{
			$this->in404 = TRUE;
			header('HTTP/1.1 404 Not Found');
			$this->parse('content/404');
			exit();
		}
		else
		//404模板文件不存在
		{
			exit('<strong style="font-size:24px;font-variant:small-caps;">Error 404 : Page Not Found.</strong>');
		}
	}
}
?>