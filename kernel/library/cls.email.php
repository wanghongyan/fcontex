<?php 
/**
 * email 发送邮件类
 */
class email 
{
	//发件人名称
	public $sendName;
	//发件邮箱
	public $sendEmail;
	//发件服务Host
	public $sendHost;
	//发件验证用户名
	public $sendUser;
	//发件验证密码	
	public $sendPass;
	
	//对像
	public $M;
	
	//错误状态
	public $err;
	
	/**
 	 * 构造函数
 	 * 
 	 */
	public function __construct() 
	{
		//加载发送邮件插件
		include PATH_ROOT.'kernel/tools/phpmailer/class.phpmailer.php';
    
		$this->M = new PHPMailer(); 
	}
	
	/*
	* 发送邮件方法
	* toMail	收件邮件
	* subject	主题
	* body		内容
	*/
	public function mailTo ($toMail, $subject, $body, $toName='')
	{
		$this->M->Host = $this->sendHost;   // SMTP servers  
		$this->M->SMTPAuth = TRUE;           // turn on SMTP authentication  
		$this->M->Username = $this->sendUser;     // SMTP username     注意：普通邮件认证不需要加 @域名 
		$this->M->Password = $this->sendPass;          // SMTP password  
		
		$this->M->From = $this->sendEmail;        // 发件人邮箱 
		$this->M->FromName = $this->sendName;    // 发件人 
		
		$this->M->CharSet = CHARSET;              // 这里指定字符集！ 
		$this->M->Encoding = "base64";  
		
		$this->M->AddAddress($toMail, $toName);    // 收件人邮箱和姓名 
		$this->M->AddReplyTo($this->sendUser, $this->sendName);  
		
		//$mail->WordWrap = 50; // set word wrap  
		//$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment  
		//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");  
		$this->M->IsHTML(TRUE);    // send as HTML  
		// 邮件主题 
		$this->M->Subject = $subject; 
		// 邮件内容  
		$this->M->Body = ' 
		<html><head> 
		<meta http-equiv="Content-Language" content="zh-cn"> 
		<meta http-equiv="Content-Type" content="text/html; charset='.CHARSET.'"></head> 
		<body> 
			'.$body.'
		</body> 
		</html> 
		';                                                                        
		
		$this->M->AltBody ="text/html";  
		if(!$this->M->Send())  
		{  
			 $this->err = $this->M->ErrorInfo;
			 return FALSE;
		}  
		else 
		{ 
			 return TRUE;
		}
	}
}
?>