<?php
/**
* 
*/
class AsemanPay extends MGatewayCore
{
	
	private $MerchantID;

	private $requestUrl;

	private $verifyUrl;

	function __construct( $gateWayInformation = array() )
	{

		/**
		 * Array of gateway messages for gateway
		 * Note: $this->messages defined in parent class
		 * @var array
		 */
		$this->messages = array(
			//Request
			100 		=> 'آماده اتصال به درگاه هستید',
			-1			=> 'پارامترهای ارسال ناقص میباشد',
			-2			=> 'مرچنت کد ارسال شده صحیح نمیباشد',
			-3			=> 'مبلغ وارد شده با مقدار ثبت و پرداخت شده مغایرت دارد',
			-4			=> 'تراکنش موفق بوده است, اما این تراکنش قبلاً Verify شده است, هر تراکنش را فقط یک بار میتوان Verify کرد',
			-5			=> 'خطا در Verify کردن تراکنش',
			-6			=> 'خطای سیستمی, این موضوع را به بخش پشتیبانی آسمان پی اطلاع دهید',
			//verify
			-7 			=> 'پارامترهای ارسال ناقص میباشد',
			-8 			=> 'مرچنت کد ارسال شده صحیح نمیباشد',
			-9 			=> 'مبلغ وارد شده با مقدار ثبت و پرداخت شده مغایرت دارد',
			-10 		=> 'تراکنش موفق بوده است, اما این تراکنش قبلاً Verify شده است, هر تراکنش را فقط یک بار میتوان Verify کرد',
			-11 		=> 'خطا در Verify کردن تراکنش',
			-12			=> 'خطای سیستمی, این موضوع را به بخش پشتیبانی آسمان پی اطلاع دهید',
		);

		$this->requestUrl 	= 'http://shaparkgateway.com/service/paymentRequest.php';

		$this->verifyUrl 	= 'http://shaparkgateway.com/service/paymentVerify.php';

		$this->MerchantID 	= $gateWayInformation['MerchantID'];

	}

	private function connect( $requestUrl ) {

	}

	public function paymentRequest( $orderId, $amount, $description = '' ) {
		
		// $this->connect( $this->requestUrl );

		$params 					= array();
		$params['MerchantID'] 		= $this->MerchantID;
		$params['Price'] 			= $amount;
		$params['Description'] 		= $description;
		$params['InvoiceNumber'] 	= $orderId;
		$params['CallbackURL'] 		= $this->callBackUrl;

		$curl_handle  = curl_init( 'http://shaparkgateway.com/service/paymentRequest.php' );
		curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/json'));
		curl_setopt( $curl_handle, CURLOPT_POST, TRUE );
		curl_setopt( $curl_handle, CURLOPT_POSTFIELDS, $params );
		curl_setopt($curl_handle, CURLOPT_TIMEOUT, 400);
		curl_setopt( $curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER, TRUE );
		$res = curl_exec( $curl_handle );
        curl_close( $curl_handle );
        
        $result = json_decode( $res );
 
        if( $result && $result->status == 100 ){
        	$this->messageCode 	= $result->status;
			$this->payPath 		= 'http://shaparkgateway.com/service/startPayment.php?au='. $result->Authority;
			return true;
		}
		return false;

	}

	public function verifyPayment( $amount ){

		if( isset( $_POST['InvoiceNumber'] ) && isset( $_POST['authority'] ) ){

			//$this->connect( $this->requestUrl );

			if( $_POST['authority'] > 0 ){

				$params 					= array();
				$params['MerchantID'] 		= $this->MerchantID;
				$params['Authority '] 		= $_POST['authority'];
				$params['Price  '] 			= $amount;

				$curl_handle = curl_init();
		        curl_setopt( $curl_handle, CURLOPT_URL, $this->verifyUrl );
		        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/json'));
		        curl_setopt( $curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE );
		        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 400);
		        curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER, TRUE );
		        curl_setopt( $curl_handle, CURLOPT_POSTFIELDS, $params );
		        $res = curl_exec($curl_handle);
		        curl_close($curl_handle);
		        
		        $result = json_decode( $res );

				if( $result->Status == 100 ){
					$this->messageCode = $result->Status;
					return true;
				}
				$this->messageCode = ( $result->Status - 6 );
				return false;

			}

		}

	}

}