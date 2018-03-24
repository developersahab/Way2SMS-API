# Way2SMS-API
An simple unofficial API to send messages using service provided by way2sms

In this [repo](https://github.com/developersahab/Way2SMS-API), we currently have:


* [PHP](https://developersahab.github.io/Way2SMS-API/): 
-------

```php
<?php
include('Way2Sms.php');
sendMessage ( 'Way2SMS UserName' , 'Way2SMS password' , '8386123456' , 'Hello World');
sendMessage ( 'Way2SMS UserName' , 'Way2SMS password' , '8386123456,9414123456' , 'Lorem Ipsum is simply dummy text of the printing and typesetting industry');
?>
```

GET/POST API
Send SMS just making GET or POST Requests.

If you want to send message than you need to pass following parameter in your GET or POST Request

For single number:
------------------
http://www.example.com/send.php?username=USER_NAME&pwd=PASSWORD&phone=8386123456&msg=Lorem Ipsum is simply dummy text of the printing and typesetting industry.

For Multiple numbers:
---------------------
http://www.example.com/send.php?username=USER_NAME&pwd=PASSWORD&phone=8386123456,9414123456&msg=Lorem Ipsum is simply dummy text of the printing and typesetting industry.

Parameters
----------
username = USER_NAME ( Your MSG91 username )

pwd = PASSWORD ( Your MSG91 Password )

phone = 10 Digit Mobile number. Incase of multiple numbers then numbers separated by comma (,)

msg = Your Message.
