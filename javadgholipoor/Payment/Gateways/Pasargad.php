<?php
/**
* 
*/
class Pasargad extends MGatewayCore
{
	
	private $terminalCode;

	private $merchantCode;

	/**
	 * Private key for gateway
	 * @var string|XML format
	 */
	private $priveKey;

	/**
	 * Url that request payment
	 * @var [type]
	 */
	private $requestUrl;

	/**
	 * Url that verify payment
	 * @var string
	 */
	private $verifyUrl;

	/**
	 * Hold parameters for send to gateway form
	 * @var array
	 */
	private $sendFormParams;

	/**
	 * Result message
	 * @var string
	 */
	private $message;

	function __construct( $gateWayInformation = array() )
	{

		/**
		 * Array of gateway messages for gateway
		 * Note: $this->messages defined in parent class
		 * @var array
		 */
		$this->messages = array(
			
		);

		$this->requestUrl 	= 'https://pep.shaparak.ir/gateway.aspx';

		$this->verifyUrl 	= 'https://pep.shaparak.ir/VerifyPayment.aspx';
		
		$this->refundUrl 	= 'https://pep.shaparak.ir/doRefund.aspx';

		$this->terminalCode = $gateWayInformation['terminalCode'];

		$this->merchantCode = $gateWayInformation['merchantCode'];

		$this->privateKey 	= $gateWayInformation['privateKey'];

	}

	private function connect( $requestUrl ) {

	}

	private function getSign( $params ){

		$processor 		= new RSAProcessor( $this->privateKey,RSAKeyType::XMLString);
		
		//$data 			= "#". $params['merchantCode'] ."#". $params['terminalCode'] ."#". $params['invoiceNumber'] ."#". $params['invoiceDate'] ."#". $params['amount'] ."#". $params['redirectAddress'] ."#". $params['action'] ."#". $params['timeStamp'] ."#";
		$data = '#';
		foreach ($params as $value) {
			$data.= $value . '#';
		}

		$data 			= sha1( $data, true );
		$data 			= $processor->sign( $data ); // امضاي ديجيتال 
		$result 		= base64_encode( $data ); // base64_encode 
		return $result;
	}

	public function paymentRequest( $orderId, $amount, $transactionDate ) {
		
		// $this->connect( $this->requestUrl );

		$this->sendFormParams 						= array();
		$this->sendFormParams['merchantCode'] 		= $this->merchantCode;
		$this->sendFormParams['terminalCode'] 		= $this->terminalCode;
		$this->sendFormParams['invoiceNumber'] 		= $orderId;
		$this->sendFormParams['invoiceDate'] 		= $transactionDate;
		$this->sendFormParams['amount'] 			= $amount;
		$this->sendFormParams['redirectAddress'] 	= $this->callBackUrl;
		$this->sendFormParams['action'] 			= '1003';
		$this->sendFormParams['timeStamp'] 			= date("Y/m/d H:i:s");
		$this->sendFormParams['sign'] 				= $this->getSign( $this->sendFormParams );

		return true;

	}

	public function sendToGateway( $hiddenInputs = array() ) {
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="UTF8"/>
			<title>در حال اتصال به درگاه پرداخت</title>
		</head>
		<body>
			<form action="<?php echo $this->requestUrl;?>" method="POST" id="gatewayForm">
				<?php foreach( $this->sendFormParams as $key => $value ):?>
					<?php echo $this->hiddenInput( $key, $value );?>
				<?php endforeach;?>
			</form>
			<script type="text/javascript">
				var gateWayForm = document.getElementById( 'gatewayForm' );
				gatewayForm.submit();
			</script>
		</body>
		</html>
		<?php
		exit;
	}

	public function verifyPayment( $orderId, $amount, $transactionDate ){

		if( isset( $_GET['tref'] ) && isset( $_GET['iN'] ) && isset( $_GET['iD'] ) ){

			//$this->connect( $this->requestUrl );

			$this->referID = $_GET['tref'];

			$this->sendFormParams 						= array();
			$this->sendFormParams['MerchantCode'] 		= $this->merchantCode;
			$this->sendFormParams['TerminalCode'] 		= $this->terminalCode;
			$this->sendFormParams['InvoiceNumber'] 		= $orderId;
			$this->sendFormParams['InvoiceDate'] 		= $transactionDate;
			$this->sendFormParams['amount'] 			= $amount;
			$this->sendFormParams['TimeStamp'] 			= date("Y/m/d H:i:s");
			$this->sendFormParams['sign'] 				= $this->getSign( $this->sendFormParams );

			$curl_handle = curl_init();
	        curl_setopt( $curl_handle, CURLOPT_URL, $this->verifyUrl );
	        curl_setopt( $curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE );
	        curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER,true );
	        curl_setopt( $curl_handle, CURLOPT_POSTFIELDS, $this->sendFormParams );
	        $res = curl_exec($curl_handle);
	        curl_close($curl_handle);
	        
	        $result = simplexml_load_string( $res );

	        if( $result !== FALSE ){
	        	// die( print_r($result, true) );
	        	$this->message = $result->resultMessage;
	        	return $result->result;
	        }

	        return false;

		}

	}

	public function doRefund( $orderId, $amount, $transactionDate ){

		$this->sendFormParams 						= array();
		$this->sendFormParams['MerchantCode'] 		= $this->merchantCode;
		$this->sendFormParams['TerminalCode'] 		= $this->terminalCode;
		$this->sendFormParams['InvoiceNumber'] 		= $orderId;
		$this->sendFormParams['InvoiceDate'] 		= $transactionDate;
		$this->sendFormParams['amount'] 			= $amount;
		$this->sendFormParams['Action'] 			= '1004';
		$this->sendFormParams['TimeStamp'] 			= date("Y/m/d H:i:s");
		$this->sendFormParams['sign'] 				= $this->getSign( $this->sendFormParams );

		$curl_handle = curl_init();
        curl_setopt( $curl_handle, CURLOPT_URL, $this->refundUrl );
        curl_setopt( $curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER,true );
        curl_setopt( $curl_handle, CURLOPT_POSTFIELDS, $this->sendFormParams );
        $result = curl_exec($curl_handle);
        curl_close($curl_handle);
        
        	die( print_r($result, true) );
        $result = simplexml_load_string( $res );

        if( $result !== FALSE ){
        	$this->message = $result->resultMessage;
        	return $result->result;
        }

        return false;
	}

	public function getMessage(){
		return $this->message;
	}

}