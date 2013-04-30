<?php
/***
 * 名称：模板类
 * Joe, 2012.03
 * http://www.fcontex.com/
*/
 
final class template
{
	//模板路径
	public $pathTo = 'template/';
	
	//模板文件后缀
	public $ext = '.php';
	
	public $contents;
	
	private $vars = array();
	
	public function parse($file)
	{
		//全局公共对象
		$A = FCApplication::sharedApplication();
		$U = FCApplication::sharedUser();
		$D = FCApplication::sharedDataBase();
		$T = FCApplication::sharedTemplate();
		$R = FCApplication::sharedRouting();
		$C = FCApplication::sharedContent();
		
		$SITE = $A->site;
		
		foreach ($this->vars as $key=>$value)
		{
			$$key = $value;
			unset($this->vars[$key]);
		}
		
		ob_start();
		if (!file_exists($this->pathTo.$file.$this->ext))
		{
			$R->print404();
		}
		else
		{
			include $this->pathTo.$file.$this->ext;
			$this->contents = ob_get_contents();
		}
		ob_end_clean();
	}
	
	public function show($file)
	{
		$this->parse($file);
		echo $this->contents;
	}
	
	public function display($file)
	{
		$this->pathTo = PATH_ROOT.'themes/'.FCApplication::loadConfig('system.site', 'site_theme').'/';
		$this->parse($file);
		echo $this->contents;
	}
	
	public function saveHtml ($file, $savePath)
	{
		$this->parse($file);
		file_put_contents($savePath, $this->contents);
	}
	
	public function bind($key, $value)
	{
		$this->vars[$key] = $value;
	}
}
?>