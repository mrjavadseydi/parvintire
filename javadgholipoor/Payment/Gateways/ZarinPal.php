<?php

namespace LaraBase\Payment\Gateways;

use LaraBase\Payment\CoreGateway;

class ZarinPal extends CoreGateway
{

    private $client;

    private $params;

	private $merchantId;

	private $requestWsdl;

	private $payPath;

	private $test = false;

	function __construct()
	{

	    if ($this->test)
            $this->requestWsdl 	= 'https://sandbox.zarinpal.com/pg/services/WebGate/wsdl';
        else
    	    $this->requestWsdl 	= 'https://www.zarinpal.com/pg/services/WebGate/wsdl';

        $this->merchantId 	= getOption('gateway_zarinpal_merchant_id');

        $this->messages     = [
            -1  => "اطلاعات ارسال شده ناقص است",
            -2  => "IP یا کد مرچنت پذیرنده صحیح نیست",
            -3  => "با توجه به محدودیت های شاپرک امکان پرداخت با رقم درخواست شده میسر نمی باشد",
            -4  => "است. اي نقره سطح از تر پايين پذيرنده تاييد سطح",
            -4  => "سطح تایید پذیرنده پایین تر از سطح نقره ای می باشد",
            -11 => "درخواست مورد نظر یافت نشد",
            -12 => "امکان ویرایش درخواست میسر نمی باشد",
            -21 => "برای این تراکنش هیچ عملیات مالی یافت نشد.",
            -22 => "تراکنش ناموفق می باشد",
            -33 => "رقم تراکنش با رقم پرداخت شده مطابقت ندارد",
            -34 => "سقف تقسیم تراکنش از لحاظ تعداد یا رقم عبور نموده است",
            -40 => "اجازه دسترسی به متد مربوطه وجود ندارد",
            -41 => "اطلاعات ارسال شده مربوط به داده های غیرمعتبر می باشد",
            -42 => "مدت زمان معتبر طول عمر شناسه پرداخت باید بین ۳۰ تا ۴۵ روز باشد",
            -51 => "پرداخت لغو شده است",
            -54 => "درخواست مورد نظر آرشیو شده است",
            100 => "عملیات با موفقیت انجام گردید",
            101 => "عملیات پرداخت موفق بوده و قبلا تایید تراکنش انجام شده است",
        ];

    }

    public function price($price) {
        return ceil($price);
    }

	public function request($params = []) {

        $this->params = $params;

		$this->connect( $this->requestWsdl );

		$params 					= array();
		$params['MerchantID'] 		= $this->merchantId;
		$params['Amount'] 			= $this->price($this->params['amount']);
		$params['Description'] 		= $this->params['description'];
		$params['Email'] 			= (isset($this->params['email']) ? $this->params['email'] : "");
		$params['Mobile'] 			= (isset($this->params['mobile']) ? $this->params['mobile'] : "");
		$params['CallbackURL'] 		= $this->params['callbackUrl'];

		$result = $this->client->call( "PaymentRequest", $params );
		$status = $result['Status'];
		$authority = $result['Authority'];

        $this->params['code'] = $status;
        $this->params['message'] = $this->message($status);
        $this->params['authority'] = $authority;

        if($status == 100){

            if ($this->test)
                $this->payPath = 'https://sandbox.zarinpal.com/pg/StartPay/' . $authority;
            else
    			$this->payPath = 'https://zarinpal.com/pg/StartPay/' . $authority;

		    $this->params['status'] = 'success';

		} else {
		    $this->params['status'] = 'error';
        }

        return $this->params;

    }

	public function send($hiddenInputs = []) {

		if( ! headers_sent() ){
			header( 'Location: ' . $this->payPath );
			exit;
		}

        $parts = parse_url( $this->payPath );

		$scheme = $parts['scheme'];
		$host 	= $parts['host'];
		$path 	= $parts['path'];

		$query = ['key' => 'value'];
		if (isset($parts['query'])) {
            parse_str( $parts['query'], $query );
        }
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

	public function verify( $params = [] ){

	    // شرط برابری قیمت را خود زرین پال چک میکند.

        $this->connect( $this->requestWsdl );
        $result = $this->client->call("PaymentVerification", [
            'MerchantID' => $this->merchantId,
            'Authority'  => $_GET['Authority'],
            'Amount'     => $params['amount']
        ]);

        $status = $result['Status'];
        $referenceId = $result['RefID'];

		return [
            'status'      => ($status == 100 ? 'success' : 'error'),
            'code'        => $status,
            'message'     => $this->message($status),
            'referenceId' => $referenceId
        ];

	}

	public function getUnverifiedTransactions() {

        $params 				= array();
        $params['MerchantID'] 	= $this->merchantId;
        $this->connect( $this->requestWsdl );
        $result = $this->client->call("GetUnverifiedTransactions", $params );

        $output = [];

        if ($result['Status'] == 100) {

            $authorities = json_decode($result['Authorities']);
            foreach ($authorities as $authority)
                $output[$authority->Authority] = $authority;

        }

        return $output;

    }

    public function checkPaymentStatus($params)
    {

        $authority = $params['get']['Authority'];
        $price     = $params['price'];

        $transactions = $this->getUnverifiedTransactions();

        if (array_key_exists($authority, $transactions)) {

            $callbackUrl = $transactions[$authority]->CallbackURL;
            $amount      = $transactions[$authority]->Amount;

            parse_str(parse_url($callbackUrl, PHP_URL_QUERY), $urlParams);
            if ($price == $amount) {
                return true;
            }

        }

        return false;

    }

    public function paymentSettle($params = [])
    {
        $amount = $params['price'];

        if( isset( $_GET['Authority'] ) && isset( $_GET['Status'] ) ) {

            if ($_GET['Status'] == 'OK') {

                $this->connect( $this->requestWsdl );

                $params 				= array();
                $params['MerchantID'] 	= $this->merchantId;
                $params['Authority'] 	= $_GET['Authority'];
                $params['Amount'] 		= $amount;

                $result = $this->client->call("PaymentVerification", $params );

                $this->messageCode = $result['Status'];

                if ( $this->messageCode ==  100 ) // شرط برابری قیمت تراکنش رزرو شده و پرداخت شده
                {
                    $this->referID = $result['RefID'];
                    return true;
                }
                return false;

            } else {

                return false;

            }

        }
    }

    public function getVerifyAuthority() {
        return $_GET['Authority'];
    }

    private function connect( $wsdl ) {

        $this->client = new \nusoap_client( $wsdl , 'wsdl' );
        $this->client->soap_defencoding = 'UTF-8';

    }

}
