<?php
/**
* 
*/
class Pay extends MGatewayCore
{
	
	private $api;

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
			1 			=> 'آماده اتصال به درگاه هستید',
			2 			=> 'پرداخت با موفقیت انجام شد',
			//Request
			//Request result code must less from -5
			-6			=> 'ارسال api الزامی می باشد',
			-7			=> 'ارسال amount ( مبلغ تراکنش ) الزامی می باشد',
			-8			=> 'amount ( مبلغ تراکنش )باید به صورت عددی باشد',
			-9			=> 'amount نباید کمتر از 1000 باشد',
			-10			=> 'ارسال redirect الزامی می باشد',
			-11			=> 'درگاه پرداختی با api ارسالی یافت نشد و یا غیر فعال می باشد',
			-12			=> 'فروشنده غیر فعال می باشد',
			-13			=> 'آدرس بازگشتی با آدرس درگاه پرداخت ثبت شده همخوانی ندارد',
			'failed'	=> 'تراکنش با خطا مواجه شد',
			//verify
			-1			=> 'ارسال api الزامی می باشد',
			-2			=> 'ارسال transId الزامی می باشد',
			-3			=> 'درگاه پرداختی با api ارسالی یافت نشد و یا غیر فعال می باشد',
			-4			=> 'فروشنده غیر فعال می باشد',
			-5			=> 'تراکنش با خطا مواجه شده است',
		);

		$this->requestUrl 	= 'https://pay.ir/payment/send';

		$this->verifyUrl 	= 'https://pay.ir/payment/verify';

		$this->api 			= $gateWayInformation['api'];

	}

	private function connect( $requestUrl ) {

	}

	public function paymentRequest( $orderId, $amount ) {
		
		// $this->connect( $this->requestUrl );

		$params 					= array();
		$params['api'] 				= $this->api;
		$params['amount'] 			= $amount;
		$params['factorNumber'] 	= $orderId;
		$params['redirect'] 		= $this->callBackUrl;
		
		$curl_handle  = curl_init( $this->requestUrl );
		curl_setopt( $curl_handle, CURLOPT_POST, TRUE );
		curl_setopt( $curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $curl_handle, CURLOPT_POSTFIELDS, $params );
		$res = curl_exec( $curl_handle );
        curl_close( $curl_handle );
        
        $result = json_decode( $res );

        if( $result->status ){
        	$this->messageCode = 1;
			$this->payPath = "https://pay.ir/payment/gateway/" . $result->transId;
			return true;
		}else{
			$this->messageCode = is_numeric( $result->errorCode ) ? ( $result->errorCode - 5 ) : $result->errorCode;
			return false;
		}

	}

	public function verifyPayment( $amount ){

		if( isset( $_POST['transId'] ) ){

			//$this->connect( $this->requestUrl );

			$params 			= array();
			$params['api'] 		= $this->api;
			$params['transId'] 	= $_POST['transId'];

			$curl_handle = curl_init();
	        curl_setopt( $curl_handle, CURLOPT_URL, $this->verifyUrl );
	        curl_setopt( $curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE );
	        curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER, TRUE );
	        curl_setopt( $curl_handle, CURLOPT_POSTFIELDS, $params );
	        $res = curl_exec($curl_handle);
	        curl_close($curl_handle);
	        
	        $result = json_decode( $res );

			if( $result->status ){
				$this->messageCode = 2;
				return true;
			}else{
				$this->messageCode = $result->errorCode;
				return false;
			}
		}

	}

}