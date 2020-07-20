<?php
/**
* 
*/
class Parsian extends MGatewayCore
{

	/**
	 * Parsian bank server
	 * @var string
	 */
	private $server;

	/**
	 * seller pin code
	 * @var string
	 */
	private $pin;
	
	function __construct( $gateWayInformation = array() )
	{

		$this->messages = array(
			0	=> 'موفق',
			20	=> 'پین فروشنده درست نمی باشد',
			22	=> 'پین فروشنده درست نمی باشد',
			30	=> 'عملیات قبلاً ثبت شده است',
			34	=> 'شماره تراکنش فروشنده درست نمی باشد',
		);

		$this->payPath 		= '';

		$this->server 	= $gateWayInformation['server'];
		$this->pin 		= $gateWayInformation['pin'];

	}

	private function connect(){

		$this->client = new nusoap_client( 'https://'. $this->server .'/pecpaymentgateway/eshopservice.asmx?wsdl', 'wsdl' );

	}

	/**
	 * Request payment for gateway
	 * @param  array 	$params Prameters that need for payment request
	 * @return boolean  True if payment request has been successfully|False if an error accurc
	 */
	public function paymentRequest( $orderId, $amount ){

		$this->connect();

		$parameters = array(
			'pin' 			=> $this->pin,
			'amount' 		=> $amount,
			'orderId' 		=> $orderId,
			'callbackUrl' 	=> $this->callBackUrl,
			'authority' 	=> 0,
			'status' 		=> 1
	    );

		$res 		= $this->client->call( 'PinPaymentRequest', array( $parameters ) );

		$this->messageCode = $res['status'];
		
		$authority 	= $res['authority'];
		$status 	= $res['status'];

		if( $authority && $status == 0 ){
			$this->payPath = 'https://'. $this->server .'/pecpaymentgateway/?au='.$authority;
			return true;
		}
		return false;
	}

	public function sendToGateway(){

		if( ! headers_sent() ){
			header( 'Location: ' . $this->payPath );
			exit;
		}

		$parts = parse_url( $this->payPath );

		$scheme = $parts['scheme'];
		$host 	= $parts['host'];
		$path 	= $parts['path'];

		parse_str( $parts['query'], $query );

		?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="UTF8"/>
			<title>در حال اتصال به درگاه پرداخت</title>
		</head>
		<body>
			<form action="<?php echo $scheme . '://' . $host . $path ;?>" method="GET" id="gatewayForm">
				<?php foreach( $query as $key => $value ):?>
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

	public function verifyPayment( $price ){
		
		$this->connect();

		if( isset( $_GET['au'] ) && isset( $_GET['rs'] ) ){
			$this->messageCode 	= $_GET['rs'];
			$authority 			= $_GET['au'];
			
			if( ( $this->messageCode == 0 ) && $authority ){
				$params 	= array(
			        'pin' 		=> $this->pin ,
					'authority' => $authority,
			        'status' 	=> 1,
			    );
			    
			    $res = $this->client->call('PinPaymentEnquiry', array( $params ) );
			    
			    $this->messageCode = $res['status'];
			    if( $this->messageCode == 0 ){
			        die('yes');
			    	return true;
			    }
			    return false;
			}
		}

	}

}
