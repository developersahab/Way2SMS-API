<?php
# -*- coding: utf-8 -*-
# @ScriptName: Way2Sms.php
# @Author: Devendra Singh
# @Date:   2018-03-23 11:28:12
# @Email:   devrajput317@gmail.com
# @Last modified by:   Devendra Singh
# @Last Modified time: 2018-03-23 11:28:12
# @Decription: Use this code on your own risk, author is not responsible.
# -*- coding: utf-8 -*-
class Way2SmsApi{
    private $way2smsHost;
    private $token;
	private $timeout = 30;
    private $cUrl;
	 /**
     * login user using way2sms Credentials.
     *
     * @param  string  $username
     * @param  string  $password
     * @return Response
     */
    function atuhenticate($username, $password){
		$result = array('success'=>0,'msg'=>'');
        $this->curl = curl_init();
        $uid = urlencode($username);
        $pwd = urlencode($password);
        curl_setopt($this->curl, CURLOPT_URL, "http://way2sms.com");
        curl_setopt($this->curl, CURLOPT_HEADER, true);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
        $a = curl_exec($this->curl);
        if (preg_match('#Location: (.*)#', $a, $r))
            $this->way2smsHost = trim($r[1]);
        // Setup for login With the curl
        curl_setopt($this->curl, CURLOPT_URL, $this->way2smsHost . "Login1.action");
        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, "username=" . $uid . "&password=" . $pwd . "&button=Login");
        curl_setopt($this->curl, CURLOPT_COOKIESESSION, 1);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, "cookie_way2sms");
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->curl, CURLOPT_MAXREDIRS, 20);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36");
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($this->curl, CURLOPT_REFERER, $this->way2smsHost);
        $text = curl_exec($this->curl);
        if (curl_errno($this->curl)){
			$result = array('success'=>0,'msg'=>curl_error($this->curl));
			return $result;
		}
        $pos = stripos(curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL), "main.action");
        if ($pos === "FALSE" || $pos == 0 || $pos == ""){
			$result = array('success'=>0,'msg'=>'Login credentials you have entered is not valid. Please try again');
			return $result;
		}
        $this->cUrl = curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL);
        $tokenLocation = strpos($this->cUrl, "Token");
        $this->token = substr($this->cUrl, $tokenLocation + 6, 37);
        return true;
    }
    /**
     * send message to the provided number.
     *
     * @param  string  $integer
     * @param  string  $msg
     * @return Response
     */
    function send($phone, $msg){
        $result = array();
        // Check the message
        if (trim($msg) == "" || strlen($msg) == 0){
            $result = array('success'=>0,'msg'=>"You can't send blank message");
			return $result;
		}
        // Take only the first 140 characters of the message
        $msg = substr($msg, 0, 140);
        $phoneArr = explode(",", $phone);
        // Send SMS to each number
        foreach ($phoneArr as $val) {
            if (strlen($val) != 10 || !is_numeric($val) || strpos($val, ".") != false) {
                $result[] = array('success'=>0,'msg'=>'invalid number','phone' => $val, 'message_content' => $msg);
                continue;
            }
            curl_setopt($this->curl, CURLOPT_URL, $this->way2smsHost . 'smstoss.action');
            curl_setopt($this->curl, CURLOPT_REFERER, curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL));
            curl_setopt($this->curl, CURLOPT_POST, 1);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, "ssaction=ss&Token=" . $this->token . "&mobile=" . $val . "&message=" . $msg . "&button=Login");
            $contents = curl_exec($this->curl);
            //Check Message Status
            $pos = strpos($contents, 'Message has been submitted successfully');
            if($pos !== false){
				$result[] = array('success'=>1,'msg'=>'Message has been submitted successfully ','phone' => $val, 'message_content' => $msg);
			}
			else{
				$result[] = array('success'=>0,'msg'=>'Oops! Something went wrong','phone' => $val, 'message_content' => $msg);
			}
        }
        return $result;
    }
	/**
	 * logout of current session of way2sms.
	 */
    function logout(){
        curl_setopt($this->curl, CURLOPT_URL, $this->way2smsHost . "LogOut");
        curl_setopt($this->curl, CURLOPT_REFERER, $this->cUrl);
        curl_exec($this->curl);
        curl_close($this->curl);
    }

}
/**
 * Function to send to sms to single/multiple number via way2sms
 *
 * @param  string  $username
 * @param  string  $pwd
 * @param  integer $phone
 * @param  string  $msg
 * @example sendMessage ( 'username' , 'password' , 'Mobile Numbers(With comma seperate)' , 'Your message')
 * @return Response
 */
function sendMessage($username,$pwd,$phone,$msg){
	//Check if curl is enabled
	if (!function_exists('curl_version')){
		$result = array('success'=>0,'msg'=>'cURL is disabled');
		return $result;
	}
	$client = new Way2SmsApi();
	$client->atuhenticate($username, $pwd);
	$result = $client->send($phone,$msg);
	$client->logout();
	return $result;
}

