<?php
class AWSConfig {
	// *** Access Credentials *** 
	//###########Log in to http://aws.amazon.com/ and go to "Your Webservices Account > AWS Access Identifiers" to get these keys####### 
	public static $AWSAccessKeyID = 'AKIAIIL3QTICJPNAOJ5A';
	public static $AWSSecretAccessKey = 'BJmeh36QQaiL3YB+iMXgk185kl5LI7X+yjYmU/Gj';
	
	// *** Web Service URLs ***
	// FPS
	public static $amazonfpsURL = 'https://fps.sandbox.amazonaws.com/';
	
	// Cobranded pipeline
	public static $piPipelineUrl = 'https://authorize.payments-sandbox.amazon.com/cobranded-ui/actions/start';

	// FPS service version
	public static $serviceVersion = "2007-01-08";
	
	// *** Local files *** 

	// the thank you page Eg: http://localhost/FPSPHPHelloWorld/thankYou.php
	public static $returnURL = '';
}