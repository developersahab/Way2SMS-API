<?php
# -*- coding: utf-8 -*-
# @ScriptName: Way2Sms.php
# @Author: Devendra Singh
# @Date:   2018-03-23 11:28:12
# @Email:   devrajput317@gmail.com
# @Last modified by:   Devendra Singh
# @Last Modified time: 2018-03-23 11:28:12
# @Decription: Way2SMS Rest API for sending sms using way2sms.
# @Param username = Way2SMS Username
# @Param pwd = Way2SMS Password
# @Param phone = Number to send to. Multiple Numbers separated by comma (,). 
# @Param msg = Your Message ( Upto 140 Chars)
# -*- coding: utf-8 -*-
include('Way2Sms.php');
if (isset($_REQUEST['phone']) && isset($_REQUEST['msg'])) {
    $response = sendMessage($_REQUEST['username'],$_REQUEST['pwd'],$_REQUEST['phone'],$_REQUEST['msg']);
    //$response = sendMessage('way2sms username','way2sms username',$_REQUEST['phone'],$_REQUEST['msg']);
	print_r($response);
    exit;
}
