<?php
/***
 * 名称：数据库类库
 * Alan, 2012.03
 * http://www.fcontex.com/
*/

//工厂类实例化具体的驱动对象
abstract class database
{
	//工厂方法
	public static function connect($type=NULL, $database='', $username='', $password='', $hostname='localhost', $charset='utf8', $hold=TRUE)
	{
		$driver = 'DBDriver'.ucfirst(strtolower($type));
		$driver = new $driver();
		$driver->charset = $charset;
		$driver->hold = $hold;
		$driver->connect($database, $username, $password, $hostname);
		return $driver;
	}
}

//数据库驱动层模型
abstract class FCDataBaseDriver
{
	public $conn = NULL;
	public $hold = TRUE;
	public $charset = '';
	public $name = '';
	
	////////具体的驱动需要至少实现以下接口////////
	
	//构造数据库连接
	abstract public function __construct();
	
	//析构数据库连接
	public function __destruct(){$this->close();}
	
	/***
	 * 打开数据库连接
	*/
	abstract public function connect($database, $username='', $password='', $hostname='');
	
	/***
	 * 回收资源并关闭数据库连接
	 * 参数force指定是否强制关闭持久连接
	*/
	abstract public function close($force=FALSE);
	
	/***
	 * 执行任意SQL语句
	 * 查询语句返回查询语句的结果集
	 * 非查询语句返回TRUE/FALSE
	*/
	abstract public function query($sql);
	
	/***
	 * 读取结果集中的一行记录并流动游标
	 * 返回包含一行记录的数组
	 * 未取回记录时返回NULL
	*/
	abstract public function fetch($result);
	
	/***
	 * 移动游标到指定索引位置
	 * 返回TRUE/FALSE
	*/
	abstract public function seek($result, $index);
	
	/***
	 * 统计结果集中记录的行数
	 * 返回大于等于0的整型数
	*/
	abstract public function count($result);
	
	/***
	 * 返回最后插入记录的ID值
	*/
	abstract public function insertid($table);
	
	
	////////具体驱动无需实现的公共继承接口////////
	
	/***
	 * 预处理SQL语句
	*/
	public function praseSQL($sql)
	{
		return preg_replace('/T\[(.+?)\]/i', FCApplication::loadConfig('system.database', 'DB_PFIX').'$1', $sql);
	}
	
	/***
	 * 以多维数组形式返回数据集
	*/
	public function fetchAll($result)
	{
		$array = array();
		if ($result)
		{
			while ($rst = $this->fetch($result))
			{
				$array[] = $rst;
			}
		}
		return $array;
	}
	
	/***
	 * 快速添加数据
	 * 以数组方式输入一条记录
	*/
	public function insert($table, $insertsqlarr)
	{
		$insertkeysql = $insertvaluesql = $dot = '';
		foreach ($insertsqlarr as $insert_key => $insert_value) 
		{
			$insertkeysql .= $dot.$insert_key;
			$insertvaluesql .= $dot.'\''.$insert_value.'\'';
			$dot = ', ';
		}
		return $this->query('insert into '.$table.' ('.$insertkeysql.') values ('.$insertvaluesql.')');
	}
	
	/***
	 * 快速更新数据
	 * 以数组方式输入一条记录
	*/
	public function update($table, $setsqlarr, $wheresqlarr)
	{
		$setsql = $dot = '';
		foreach ($setsqlarr as $set_key => $set_value)
		{
			$setsql .= $dot.$set_key.'=\''.$set_value.'\'';
			$dot = ', ';
		}
		$where = $dot = '';
		if (empty($wheresqlarr)) 
		{
			$where = '1';
		} 
		elseif (is_array($wheresqlarr)) 
		{
			foreach ($wheresqlarr as $key => $value) 
			{
				$where .= $dot.$key.'=\''.$value.'\'';
				$dot = ' and ';
			}
		} 
		else 
		{
			$where = $wheresqlarr;
		}
		return $this->query('update '.$table.' set '.$setsql.' where '.$where);
	}
	
	/***
	 * 快速删除数据
	*/
	public function delete($table, $wheresqlarr)
	{
		return $this->query('delete from '.$table.' where '.$wheresqlarr);
	}
}

//数据库驱动 - MySQL
final class DBDriverMysql extends FCDataBaseDriver
{
	public function __construct()
	{
		$this->name = 'mysql';
	}
	
	public function connect($database, $username='', $password='', $hostname='')
	{
		if ($this->hold)
		{
			$this->conn = mysql_pconnect($hostname, $username, $password);
		}
		else
		{
			$this->conn = mysql_connect($hostname, $username, $password);
		}
		mysql_select_db($database, $this->conn);
		mysql_query('set character_set_connection='.$this->charset.', character_set_results='.$this->charset.', character_set_client=binary', $this->conn);
	}
	
	public function close($force=FALSE)
	{
		if ($this->conn && ($force || !$this->hold))
		{
			mysql_close($this->conn);
			$this->conn = NULL;
		}
	}
	
	public function query($sql)
	{
		return mysql_query($this->praseSQL($sql), $this->conn);
	}
	
	public function fetch($result)
	{
		if ($result)
		{
			return mysql_fetch_array($result);
		}
		else return NULL;
	}
	
	public function seek($result, $index)
	{
		if ($result)
		{
			return mysql_data_seek($result, $index);
		}
		else return FALSE;
	}
	
	public function count($result)
	{
		if ($result)
		{
			return mysql_num_rows($result);
		}
		else return 0;
	}
	
	public function insertid($table)
	{
		return ($id = mysql_insert_id($this->conn)) >= 0 ? $id : $this->fetch($this->query("select last_insert_id()"), 0);
	}
}

//数据库驱动 - SQLite
final class DBDriverSqlite extends FCDataBaseDriver
{
	public function __construct()
	{
		$this->name = 'sqlite';
	}
	
	public function connect($database, $username='', $password='', $hostname='')
	{
		if ($this->hold)
		{
			$this->conn = sqlite_popen($database);
		}
		else
		{
			$this->conn = sqlite_open($database);
		}
	}
	
	public function close($force=FALSE)
	{
		if ($this->conn && ($force || !$this->hold))
		{
			sqlite_close($this->conn);
			$this->conn = NULL;
		}
	}
	
	public function query($sql)
	{
		return sqlite_query($this->conn, $this->praseSQL($sql));
	}
	
	public function fetch($result)
	{
		if ($result)
		{
			return sqlite_fetch_array($result);
		}
		else return NULL;
	}
	
	public function seek($result, $index)
	{
		if (!$result || $index < 0 ||  $index >= $this->count($result))
		{
			return FALSE;
		}
		else return sqlite_seek($result, $index);
	}
	
	public function count($result)
	{
		if ($result)
		{
			return sqlite_num_rows($result);
		}
		else return 0;
	}
	
	public function insertid($table)
	{
		$query = $this->query('select last_insert_rowid() from '.$table);
		$res = $this->fetch($query);
		return $res[0];
	}
}

//数据库驱动 - SQLite3
final class DBDriverSqlite3 extends FCDataBaseDriver
{
	private $cousor = 0;
	
	public function __construct()
	{
		$this->name = 'sqlite3';
	}
	
	public function connect($database, $username='', $password='', $hostname='')
	{
		$this->conn = new PDO('sqlite:'.$database, $username, $password, array(PDO::ATTR_PERSISTENT=>$this->hold));
	}
	
	public function close($force=FALSE){}
	
	public function query($sql)
	{
		$this->cursor = 0;
		
		return $this->conn->query($this->praseSQL($sql));
	}
	
	public function fetch($result)
	{
		if ($result)
		{
			$this->cursor ++;
			return $result->fetch();
		}
		else return NULL;
	}
	
	public function seek($result, $index)
	{
		if (!$result || $index<0)
		{
			return FALSE;
		}
		
		if ($index == $this->cursor)
		{
			return TRUE;
		}
		
		//底层不支持seek故以折中办法实现
		
		$ret = TRUE;
		
		if ($index > $this->cursor)
		{
			for ($i=$this->cursor; $i<$index; $i++)
			{
				if ($this->fetch($result)) continue;
				else {$ret = FALSE; break;}
			}
		}
		else
		{
			$this->cursor = 0;
			
			if (!$result->execute())
			{
				return FALSE;
			}
			
			for ($i=0; $i<$index; $i++)
			{
				if ($this->fetch($result)) continue;
				else {$ret = FALSE; break;}
			}
		}
		
		return $ret;
	}
	
	public function count($result)
	{
		if ($result)
		{
			//底层不支持count故以折中办法实现
			return count($this->conn->query($result->queryString)->fetchAll());
		}
		else
		{
			return 0;
		}
	}
	
	public function insertid($table)
	{
		$query = $this->query('select last_insert_rowid() from '.$table);
		$r = $this->fetch($query);
		return $r[0];
	}
}

//数据库驱动 - Access
final class DBDriverAccess extends FCDataBaseDriver
{
	public function __construct()
	{
		$this->name = 'access';
	}
	
	public function connect($database, $username='', $password='', $hostname='')
	{
		$dsn = 'DRIVER={Microsoft Access Driver (*.mdb)};DBQ='.realpath($database);
		if ($this->hold)
		{
			$this->conn = odbc_pconnect($dsn, $username, $password);
		}
		else
		{
			$this->conn = odbc_connect($dsn, $username, $password);
		}
	}
	
	public function close($force=FALSE)
	{
		if ($this->conn && ($force || !$this->hold))
		{
			odbc_close($this->conn);
			$this->conn = NULL;
		}
	}
	
	public function query($sql)
	{
		return odbc_exec($this->conn, $this->praseSQL($sql));
	}
	
	public function fetch($result)
	{
		if ($result)
		{
			return odbc_fetch_array($result);
		}
		else return NULL;
	}
	
	public function seek($result, $index)
	{
		return FALSE;
	}
	
	public function count($result)
	{
		if ($result)
		{
			return odbc_num_rows($result);
		}
		else return 0;
	}
	
	public function insertid($table)
	{
		return 0;
	}
}
?>