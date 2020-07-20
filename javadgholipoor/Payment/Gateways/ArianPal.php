<?php
/**
* 
*/
class ArianPal extends MGatewayCore
{

	public $title 			= 'درگاه آرین پال';

	public $description 	= 'توضیحات درگاه آرین پال';

	/**
	 * Merchant ID
	 * @var string
	 */
	private $merchantID;

	/**
	 * Merchant Password
	 * @var string
	 */
	private $password;
	
	function __construct( $gateWayInformation = array() )
	{

		$this->messages = array(
			"Success"				=> "پرداخت با موقیت انجام شد",
			"Ready" 				=> "هیچ عملیاتی انجام نشده است .",
            "GatewayUnverify" 		=> "درگاه شما غیر فعال می باشد .",
            "GatewayIsExpired" 		=> "درگاه شما فاقد اعتبار می باشد .",
			"GatewayIsBlocked" 		=> "درگاه شما مسدود شده است .",
			"GatewayInvalidInfo"	=> "مرچنت یا رمز عبور اشتباه وارد شده است .",
			"UserNotActive" 		=> "کاربر غیرفعال شده است .",
            "InvalidServerIP" 		=> "IP سرور نامعتبر می باشد .",
			"Failed" 				=> "عملیات با مشکل مواجه شد .",
			"NotMatchMoney" 		=> "مبلغ واریزی با مبلغ درخواستی یکسان نمی باشد .",
			"Verifyed" 				=> "قبلا پرداخت شده است .",
			"InvalidRef" 			=> "شماره رسید قابل قبول نمی باشد .",
		);

		$this->payPath 		= '';

		$this->merchantID 	= $gateWayInformation['merchantID'];
		$this->password 	= $gateWayInformation['password'];

	}

	//1
	private function connect(){

		//$this->client = new SoapClient('http://merchant.arianpal.com/WebService.asmx?wsdl');
		$this->client = new SoapClient('http://sandbox.arianpal.com/WebService.asmx?wsdl');

	}

	/**
	 * Request payment for gateway
	 * @param  array 	$params Prameters that need for payment request
	 * @return boolean  True if payment request has been successfully|False if an error accurc
	 */
	public function paymentRequest( $params ){

		$this->connect();

		$parameters = array(
				"MerchantID" 	=> $this->merchantID,
				"Password" 		=> $this->password,
				"Price" 		=> $params['amount'],
				"ReturnPath" 	=> $this->callBackUrl,
				"ResNumber" 	=> $params['orderId'],
				"Description" 	=> $params['description'],
				"Paymenter" 	=> $params['paymenter'],
				"Email" 		=> $params['email'],
				"Mobile" 		=> $params['mobile'],
			);


		$res = $this->client->RequestPayment( $parameters );

		$PayPath 			= $res->RequestPaymentResult->PaymentPath;
		$Status 			= $res->RequestPaymentResult->ResultStatus;

		$this->messageCode 	= $Status;

		if( $Status == 'Succeed' ){
			$this->payPath = $PayPath;
			return true;
		}else{
			return false;
		}

	}

	public function verifyPayment( $price ){
		
		$Status 	= $_POST['status'];

		$Refnumber 	= $_POST['refnumber'];

		$Resnumber 	= $_POST['resnumber'];


		$this->connect();

		$parameters = array(
				"MerchantID" 	=> $this->merchantID,
				"Password" 		=> $this->password,
				"Price" 		=> $price,
				"RefNum" 		=> $Refnumber,
			);

		$res = $this->client->VerifyPayment( $parameters);

		$Status 	= $res->verifyPaymentResult->ResultStatus;
		$PayPrice 	= $res->verifyPaymentResult->PayementedPrice;

		$this->messageCode = $Status;
		
		if( $Status == 'Success' )// Your Peyment Code Only This Event
		{
			return true;
		}
		return false;
	}

}
