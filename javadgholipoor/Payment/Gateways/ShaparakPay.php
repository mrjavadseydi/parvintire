<?php
/**
* 
*/
class ShaparakPay extends MGatewayCore
{
	
	private $pin;

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
			1		=> 'آماده انتقال به درگاه',
			2 		=> 'پرداخ با موفقیت انجام شد',
			-1 		=> '',
			-2 		=> '',
			-3 		=> '',
			-4 		=> '',
			-5 		=> '',
			-6 		=> '',
			-7 		=> '',
			-8 		=> '',
			-9 		=> '',
			-10 	=> '',
			-11 	=> '',
			-12 	=> '',
		);

		$this->requestUrl 	= 'http://panel.shaparakpay.com/api/create/';

		$this->verifyUrl 	= 'http://panel.shaparakpay.com/api/verify/';

		$this->pin 			= $gateWayInformation['pin'];

	}

	private function connect( $requestUrl ) {

	}

	public function paymentRequest( $amount, $bank = 'mellat', $description = '' ) {
		
		// $this->connect( $this->requestUrl );

		$params 					= array();
		$params['pin'] 				= $this->pin;
		$params['amount'] 			= $amount;
		$params['bank'] 			= $bank;
		$params['description'] 		= $description;
		$params['callback'] 		= $this->callBackUrl;

		$ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $this->requestUrl );
        curl_setopt( $ch, CURLOPT_POST, TRUE );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $params );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        $res = curl_exec( $ch );
        curl_close($ch);
		
        if( strlen( $res ) > 5 && strlen( $res ) < 20 ){
        	$this->messageCode = 1;
			$this->payPath = "http://panel.shaparakpay.com/startpay/" . $res;
			return true;
		}else{
			$this->messageCode = $res;
			return false;
		}

	}

	public function verifyPayment( $amount ){

		if( isset( $_POST['transid'] ) ){

			//$this->connect( $this->requestUrl );

			$params 			= array();
			$params['pin'] 		= $this->pin;
			$params['transid'] 	= $_POST['transid'];
			$params['amount'] 	= $amount;

			$ch = curl_init();
	        curl_setopt( $ch, CURLOPT_URL, $this->verifyUrl );
	        curl_setopt( $ch, CURLOPT_POST, TRUE );
	        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
	        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
	        curl_setopt( $ch, CURLOPT_POSTFIELDS, $params );
	        $res = curl_exec($ch);
	        curl_close($ch);

	        die( print_r( $res, true ) );

			if( ! empty( $res ) && $res == 1 ){
				$this->messageCode = 2;
				return true;
			}else{
				$this->messageCode = $res;
				return false;
			}
		}

	}

}