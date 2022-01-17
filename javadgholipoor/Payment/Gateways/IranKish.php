<?php
/**
* 
*/
class IranKish extends MGatewayCore
{
	
	private $merchantId;

	private $sha1Key;

	private $resultCode;

	private $token;

	private $requestWsdl;

	private $verifyWsdl;

	function __construct( $gateWayInformation = array() )
	{

		$this->title 		= 'درگاه ایرانکیش';

		$this->description 	= 'توضیحات درگاه پرداخت اران کیش'; 	

		/**
		 * Array of gateway messages for gateway
		 * Note: $this->messages defined in parent class
		 * @var array
		 */
		$this->messages = array(
			//Request
			110 	=> "انصراف دارنده کارت",
			120 	=> "موجودی کافی نیست",
			130 	=> "اطلاعات کارت اشتباه است",
			131 	=> "اطلاعات کارت اشتباه است",
			160 	=> "اطلاعات کارت اشتباه است",
			132 	=> "کارت مسدود یا منقضی می باشد",
			133 	=> "کارت مسدود یا منقضی می باشد",
			140 	=> "زمان مورد نظر به پایان رسیده است",
			200 	=> "مبلغ بیش از سقف مجاز",
			201 	=> "مبلغ بیش از سقف مجاز",
			202 	=> "مبلغ بیش از سقف مجاز",
			166 	=> "بانک صادر کننده مجوز انجام  تراکنش را صادر نکرده",
			150 	=> "خطای نامشخص",
			100 	=> "پرداخت با موفقیت انجام شد",
			//Verify
			-90 	=> "تراکنش قبلاً تأیید شده است",
			-30 	=> "تراکنش قبلاً برگشت خورده است",
			-80 	=> "تراکنس مورد نظر یافت نشد",
			-81 	=> "خطای داخل بانکی",
			-51 	=> "خطای در درخواست",
			-20 	=> "وجود کاراکترهای غیر مجاز در درخواست",
			-50 	=> "طول رشته درخواست غیر مجاز است",
		);

		$this->requestWsdl 	= 'https://ikc.shaparak.ir/XToken/Tokens.xml';

		$this->verifyWsdl 	= 'https://ikc.shaparak.ir/XVerify/Verify.xml';

		$this->merchantId 	= $gateWayInformation['merchantId'];
		$this->sha1Key 		= $gateWayInformation['sha1Key'];

	}

	private function connect( $wsdl ) {

		$this->client = new nusoap_client( $wsdl , true );
		$this->client->soap_defencoding = 'UTF-8';

	}

	public function paymentRequest( $orderId, $amount, $additionalData = '' ) {
		
		$this->connect( $this->requestWsdl );
		$params 					= array();
		$params['merchantId'] 		= $this->merchantId;
		$params['amount'] 			= $amount;
		$params['invoiceNo'] 		= $orderId;
		$params['paymentId'] 		= $orderId;
		$params['specialPaymentId'] = $orderId;
		$params['revertURL'] 		= $this->callBackUrl;

		$result = $this->client->call( "MakeToken", array( $params ) );

		if( $result['MakeTokenResult']['result'] == 'true' ){
			$this->token = $result['MakeTokenResult']['token'];
			return true;
		}
		return false;
	}

	public function sendToGateway( $hiddenInputs = array() ) {
		echo '<form id="kicapeyment" action="https://ikc.shaparak.ir/tpayment/payment/Index" method="POST" >
			<input type="hidden" name="token" value="' . $this->token . '">
			<input type="hidden" name="merchantId" value="' . $this->merchantId . '">
		</form>
		<script>document.forms["kicapeyment"].submit()</script>';
		exit;

	}

	public function verifyPayment(){

		if( isset( $_POST['InvoiceNumber'] ) && isset( $_POST['token'] ) ){

			$this->messageCode = $_POST['resultCode'];

			if( isset( $_POST['cardNo'] ) ){
				$this->cardNumber = $_POST['cardNo'];
			}

			if( isset( $_POST['referenceId'] ) ){
				$this->referID = $_POST['referenceId'];
			}

			$this->connect( $this->verifyWsdl );

			$params['token'] 			= $_POST['token'];
			$params['merchantId'] 		= $_POST['merchantId'];
			$params['referenceNumber'] 	= $_POST['referenceId'];
			$params['sha1Key'] 			= $this->sha1Key;

			$result = $this->client->call("KicccPaymentsVerification", array( $params ) );

			$this->messageCode = $result['KicccPaymentsVerificationResult'];

			if ( $this->messageCode ==  '100' || $this->messageCode ==  '1000' ) // شرط برابری قیمت تراکنش رزرو شده و پرداخت شده
			{	
				return true;
			}
			return false;
		}

	}

}