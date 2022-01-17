<?php
/**
* 
*/
class Pardano extends MGatewayCore
{

	private $api;

	private $requestWsdl;

	private $verifyWsdl;
	
	function __construct( $gateWayInformation = array() )
	{

		/**
		 * Array of gateway messages for gateway
		 * Note: $this->messages defined in parent class
		 * @var array
		 */
		$this->messages = array(
			//Request
			1	=> 'پرداخت با موفقیت انجام شد',
			2	=> 'اتصال یه درگاه برقرار شد',
			-1	=>	'',
			-2	=>	'',
			-3	=>	'',
			-4	=>	'',
			-6	=>	'',
			-8	=>	'',
			-9	=>	'',
			-10	=>	'',
			-11	=>	'',
			-12	=>	'',
			-13	=>	'',
			-21	=>	'',
			
		);

		$this->api 			= $gateWayInformation['api'];

		$this->requestWsdl 	= 'http://pardano.com/p/webservice/?wsdl';

		$this->verifyWsdl 	= 'http://pardano.com/p/webservice/?wsdl';

	}

	private function connect(){

		$this->client 		= new nusoap_client( $this->requestWsdl, true );

	}

	/**
	 * Request payment for gateway
	 * @param  array $params Prameters that need for payment request
	 * @return [type]         [description]
	 */
	public function paymentRequest( $orderId, $amount, $description = '' ){

		$this->connect();
		
		$parameters = array(
			'api' 			=> $this->api,
			'amount' 		=> $amount,
			'callBackUrl' 	=> $this->callBackUrl,
			'orderId' 		=> $orderId,
			'description' 	=> $description,
		);

		// Call the SOAP method
		$result = $this->client->call( 'requestpayment', array_values( $parameters ) );

		if( $result > 0 ){
			$this->messageCode = 2;
			$this->payPath = 'http://pardano.com/p/payment/' . $result;
			return true;
		}
		return true;
	}

	
	public function verifyPayment( $amount ){
		
		if( isset( $_GET['order_id'] ) && isset( $_GET["au"] ) ){

			$this->connect();

			$parameters = array(
				'api' 		=> $this->api,
				'amount'	=> $amount,
				'au' 		=> $_GET['au'],
			);

			$result = $this->client->call('verification', array_values( $parameters ) );

			$this->messageCode = 1;
			
			if(  ! empty( $result ) && $result == 1 ) {
				return true;
			}

			return false;

		}

	}

}
