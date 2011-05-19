<?php
require_once('libs/Paypal/AdaptivePayments.php');

class PaypalPayReceipt {
	function __construct() {
		
	}
	
	function __destruct() {
		
	}
	
	public function pay($senderEmail, $receiverEmail, $amount, $pKey) {
		try {
			/* The servername and serverport tells PayPal where the buyer
			should be directed back to after authorizing payment.
			In this case, its the local webserver that is running this script
			Using the servername and serverport, the return URL is the first
			portion of the URL that buyers will return to after authorizing payment	*/
			
			$serverName = $_SERVER['SERVER_NAME'];
			$serverPort = $_SERVER['SERVER_PORT'];
			$url = dirname('http://'.$serverName.':'.$serverPort.$_SERVER['REQUEST_URI']);
			
			/* The returnURL is the location where buyers return when a
			payment has been succesfully authorized.
			The cancelURL is the location buyers are sent to when they hit the
			cancel button during authorization of payment during the PayPal flow */
			
			$returnURL = $url."/PaymentDetails.php";
			$cancelURL = $url. "/SetPay.php" ;
			$currencyCode = "USD";
			$preapprovalKey = $pKey;
													 
			$payRequest = new PayRequest();
			$payRequest->actionType = "PAY";
			$payRequest->cancelUrl = $cancelURL;
			$payRequest->returnUrl = $returnURL;
			$payRequest->clientDetails = new ClientDetailsType();
			$payRequest->clientDetails->applicationId = "APP-1JE4291016473214C";
			$payRequest->clientDetails->deviceId = DEVICE_ID;
			$payRequest->clientDetails->ipAddress = "127.0.0.1";
			$payRequest->currencyCode = $currencyCode;
			$payRequest->senderEmail = $senderEmail;
			$payRequest->requestEnvelope = new RequestEnvelope();
			$payRequest->requestEnvelope->errorLanguage = "en_US";
			if ($preapprovalKey != "") {
				$payRequest->preapprovalKey = $preapprovalKey ;
			}          	
			$receiver1 = new receiver();
			$receiver1->email = $receiverEmail;
			$receiver1->amount = $amount;
			
			$payRequest->receiverList = new ReceiverList();
			$payRequest->receiverList = array($receiver1);
			
			/* Make the call to PayPal to get the Pay token
			If the API call succeded, then redirect the buyer to PayPal
			to begin to authorize payment.  If an error occured, show the
			resulting errors */
			$ap = new AdaptivePayments();
			$response = $ap->Pay($payRequest);
		
			if (strtoupper($ap->isSuccess) == 'FAILURE') {
				$_SESSION['FAULTMSG']=$ap->getLastError();
				$location = "APIError.php";
				header("Location: $location");
			} else {
				$_SESSION['payKey'] = $response->payKey;
				// SUCCESSFUL
				if ($response->paymentExecStatus == "COMPLETED") {
					$location = "PaymentDetails.php";
				} else {
					$token = $response->payKey;
					$payPalURL = PAYPAL_REDIRECT_URL.'_ap-payment&paykey='.$token;
					header("Location: ".$payPalURL);
				}
			}
		} catch(Exception $ex) {	
			$fault = new FaultMessage();
			$errorData = new ErrorData();
			$errorData->errorId = $ex->getFile() ;
			$errorData->message = $ex->getMessage();
			$fault->error = $errorData;
			$_SESSION['FAULTMSG']=$fault;
			$location = "APIError.php";
			header("Location: $location");
		}
	}
}