<?php
/**
* 
*/
class BitPay extends MGatewayCore
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
			//Request
			
		);

		$this->requestUrl 	= 'http://bitpay.ir/payment/gateway-send';
		// $this->requestUrl 	= 'https://bitpay.ir/payment-test/gateway-send';

		$this->verifyUrl 	= 'http://bitpay.ir/payment/gateway-result-second';

		$this->api 			= $gateWayInformation['api'];

	}

	private function connect( $requestUrl ) {

	}

	public function paymentRequest( $orderId, $amount, $description, $name = '', $email = '' ) {
		
		// $this->connect( $this->requestUrl );

		$params 					= array();
		$params['api'] 				= $this->api;
		$params['amount'] 			= $amount;
		$params['factorId'] 		= $orderId;
		$params['description'] 		= $description;
		$params['name'] 			= $name;
		$params['email'] 			= $email;
		$params['redirect'] 		= $this->callBackUrl;
		// die( print_r($params, true) );
		$curl_handle  = curl_init( $this->requestUrl );
		curl_setopt( $curl_handle, CURLOPT_POST, TRUE );
		curl_setopt( $curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $curl_handle, CURLOPT_POSTFIELDS, $params );
		$res = curl_exec( $curl_handle );
        curl_close( $curl_handle );

        if( $res > 0 && is_numeric( $res ) ){
			$this->payPath = "http://bitpay.ir/payment/gateway-" . $res;
			return true;
		}
		return false;

	}


	public function verifyPayment( $amount ){

		if( isset( $_POST['trans_id'] ) && isset( $_POST['id_get'] ) ){

			$this->connect( $this->requestUrl );

			$params 			= array();
			$params['api'] 		= $this->api;
			$params['id_get'] 	= $_POST['id_get'];
			$params['trans_id'] = $_POST['trans_id'];

			$curl_handle = curl_init();
	        curl_setopt( $curl_handle, CURLOPT_URL, $this->verifyUrl );
	        curl_setopt( $curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE );
	        curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER,true );
	        curl_setopt( $curl_handle, CURLOPT_POSTFIELDS, $params );
	        $res = curl_exec($curl_handle);
	        curl_close($curl_handle);
	        
			if( $res == 1 ){
				return true;
			}
			return false;
		}

	}

}