<?php
/**
* 聚合短信接口 
* 2017.11.10
*/
namespace juhe;
class Juhe{
	private $sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL
	public  $smsConf = [
	    'key'   => '4f947d29c482967df06c74e1a07a7b58', //您申请的APPKEY
	    'mobile'    => '15539728747', //接受短信的用户手机号码
	    'tpl_id'    => '11099', //您申请的短信模板ID，根据实际情况修改
	    'tpl_value' =>'#code#=1234' //您设置的模板变量，根据实际情况修改
	];

	function __construct($conf) {
        if(!empty($conf) && is_array($conf)){
        	$this->smsConf = array_merge($this->smsConf, $conf);
        }
    }

    function send(){
    	header('content-type:text/html;charset=utf-8');
    	$content = $this->juhecurl($this->sendUrl,$this->smsConf,1); //请求发送短信
		if($content){
		    $result = json_decode($content,true);
		    $error_code = $result['error_code']; //状态为0，说明短信发送成功 //状态非0，说明失败
		    if($error_code != '0'){
		    	return $error_code.'错误信息='.$result['reason'];
		    }
		    return $error_code;
		}else{
			return false;
		}
    }
	/**
	 * 请求接口返回内容
	 * @param  string $url [请求的URL地址]
	 * @param  string $params [请求的参数]
	 * @param  int $ipost [是否采用POST形式]
	 * @return  string
	 */
	function juhecurl($url,$params=false,$ispost=0){
	    $httpInfo = array();
	    $ch = curl_init();
	    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
	    curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
	    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
	    curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
	    if( $ispost )
	    {
	        curl_setopt( $ch , CURLOPT_POST , true );
	        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
	        curl_setopt( $ch , CURLOPT_URL , $url );
	    }
	    else
	    {
	        if($params){
	            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
	        }else{
	            curl_setopt( $ch , CURLOPT_URL , $url);
	        }
	    }
	    $response = curl_exec( $ch );
	    if ($response === FALSE) {
	        //echo "cURL Error: " . curl_error($ch);
	        return false;
	    }
	    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
	    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
	    curl_close( $ch );
	    return $response;
	}
}