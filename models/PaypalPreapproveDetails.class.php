<?php
require_once('libs/Paypal/AdaptivePayments.php');
require_once('libs/Paypal/Stub/AP/AdaptivePaymentsProxy.php');

class PaypalPreapproveDetails {
	public $preapprovalKey;
	public $response;
	
	function __construct() {
		
	}
	
	function __destruct() {
		
	}
	
	public function preapprove() {
		if (isset($_GET['cs'])) {
			$_SESSION['preapprovalKey'] = '';
		}
		
		try {
			if (isset($_REQUEST["preapprovalKey"])){
				$this->preapprovalKey = $_REQUEST["preapprovalKey"];
			}
			
			if (empty($this->preapprovalKey)) {
				$this->preapprovalKey = $_SESSION['preapprovalKey'];
			}
				
			$PDRequest = new PreapprovalDetailsRequest();
			
			$PDRequest->requestEnvelope = new RequestEnvelope();
			$PDRequest->requestEnvelope->errorLanguage = "en_US";
			$PDRequest->preapprovalKey = $this->preapprovalKey; 
			
			$ap = new AdaptivePayments();
			$this->response = $ap->PreapprovalDetails($PDRequest);
			
			/* Display the API response back to the browser.
			 If the response from PayPal was a success, display the response parameters'
			 If the response was an error, display the errors received using APIError.php. */
			if (strtoupper($ap->isSuccess) == 'FAILURE') {
				$_SESSION['FAULTMSG'] = $ap->getLastError();
				$location = "APIError.php";
				header("Location: $location");
			}
		} catch (Exception $ex) {
			$fault = new FaultMessage();
			$errorData = new ErrorData();
			$errorData->errorId = $ex->getFile() ;
			$errorData->message = $ex->getMessage();
			$fault->error = $errorData;
			$_SESSION['FAULTMSG'] = $fault;
			$location = "APIError.php";
			
			header("Location: $location");
		}
	}
}