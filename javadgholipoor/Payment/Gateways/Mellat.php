<?php

namespace LaraBase\Payment\Gateways;

use LaraBase\Payment\CoreGateway;

class Mellat extends CoreGateway
{
    private $client;

    private $params;

    private $terminalId;

    private $userName;

    private $userPassword;

    private $namespace;

    private $referenceId;

    function __construct()
    {

        $this->userName 	= getOption('gateway_mellat_username');
        $this->userPassword = getOption('gateway_mellat_password');
        $this->terminalId 	= getOption('gateway_mellat_terminal_id');

        $this->messages = [
            -2 	=> "شکست در ارتباط با بانک",
            -1 	=> "شکست در ارتباط با بانک",
            0 	=> "تراکنش با موفقیت انجام شد",
            11 	=> "شماره کارت معتبر نیست",
            12 	=> "موجودی کافی نیست",
            13 	=> "رمز دوم شما صحیح نیست",
            14 	=> "دفعات مجاز ورود رمز بیش از حد است",
            15 	=> "کارت معتبر نیست",
            16 	=> "دفعات برداشت وجه بیش از حد مجاز است",
            17 	=> "شما از انجام تراکنش منصرف شد اید",
            18 	=> "تاریخ انقضای کارت گذشته است",
            19 	=> "مبلغ برداشت وجه بیش از حد مجاز است",
            111 => "صادر کننده کارت نامعتبر است",
            112 => "خطای سوییچ صادر کننده کارت",
            113 => "پاسخی از صادر کننده کارت دریافت نشد",
            114 => "دارنده کارت مجاز به انجام این تراکنش نمی باشد",
            21 	=> "پذیرنده معتبر نیست",
            23 	=> "خطای امنیتی رخ داده است",
            24 	=> "اطلاعات کاربری پذیرنده معتبر نیست",
            25 	=> "مبلغ نامعتبر است",
            31 	=> "پاسخ نامعتبر است",
            32 	=> "فرمت اطلاعات وارد شده صحیح نیست",
            33 	=> "حساب نامعتبر است",
            34 	=> "خطای سیستمی",
            35 	=> "تاریخ نامعتبر است",
            41 	=> "شماره درخواست تکراری است",
            42 	=> "تراکنش Sale یافت نشد",
            43 	=> "قبلا درخواست Verify داده شده است",
            44 	=> "درخواست Verify یافت نشد",
            45 	=> "تراکنش Settle شده است",
            46 	=> "تراکنش Settle نشده است",
            47 	=> "تراکنش Settle یافت نشد",
            48 	=> "تراکنش Reverse شده است",
            49 	=> "تراکنش Refund یافت نشد",
            412 => "شناسه قبض نادرست است",
            413 => "شناسه پرداخت نادرست است",
            414 => "سازمان صادر کننده قبض معتبر نیست",
            415 => "زمان جلسه کاری به پایان رسیده است",
            416 => "خطا در ثبت اطلاعات",
            417 => "شناسه پرداخت کننده نامعتبر است",
            418 => "اشکال در تعریف اطلاعات مشتری",
            419 => "تعداد دفعات ورود اطلاعات بیش از حد مجاز است",
            421 => "IP معتبر نیست",
            51 	=> "تراکنش تکراری است",
            54 	=> "تراکنش مرجع موجود نیست",
            55 	=> "تراکنش نامعتبر است",
            61 	=> "خطا در واریز",
        ];

    }

    private function connect(){

        $this->client 		= new \nusoap_client('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
        $this->namespace 	= 'http://interfaces.core.sw.bps.com/';

    }

    public function price($price) {
        // تبدیل مبلغ به ریال
        return ceil($price);
    }

    public function request( $params ){

        $this->params = $params;

        $this->connect();

        $amount			= toIRR($this->price($params['amount']));
        $description	= isset( $params['description'] ) ? $params['description'] : '';

        $parameters = [
            'terminalId' 		=> $this->terminalId,
            'userName' 			=> $this->userName,
            'userPassword' 		=> $this->userPassword,
            'orderId' 			=> $this->params['orderId'],
            'amount' 			=> $amount,
            'localDate' 		=> date('Ydm'),
            'localTime' 		=> date('His'),
            'additionalData' 	=> $description,
            'callBackUrl' 		=> $this->params['callbackUrl'],
            'payerId' 			=> 0
        ];

        $result = $this->client->call('bpPayRequest', $parameters, $this->namespace);

        $parseResult = explode(',', $result);

        $authority = null;
        $code = $parseResult[0];

        if (isset($parseResult[1]))
            $authority = trim($parseResult[1]);

        $this->referenceId = $authority;

        $this->params['status'] = ($code == 0 ? 'success' : 'error');
        $this->params['code'] = $code;
        $this->params['message'] = $this->message($code);
        $this->params['authority'] = $authority;

        return $this->params;

    }

    public function send($hiddenInputs = []){
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF8"/>
            <title>در حال اتصال به درگاه پرداخت</title>
        </head>
        <body>
        <form action="https://bpm.shaparak.ir/pgwchannel/startpay.mellat" method="POST" id="gatewayForm">
            <?php echo $this->hiddenInput('RefId', $this->referenceId);?>
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

    private function settle( $parameters ){

        $result = $this->client->call( 'bpSettleRequest', $parameters, $this->namespace);

    }

    public function verify( $params ){

        $post       = $params['request'];
        $resultCode = $post['ResCode'];
        if ($resultCode == 0) {

            $orderId 			= $params['orderId'];
            $saleOrderId 		= $post['SaleOrderId'];
            $saleReferenceId 	= $post['SaleReferenceId'];

            $this->connect();

            $parameters = array(
                'terminalId' 		=> $this->terminalId,
                'userName' 			=> $this->userName,
                'userPassword' 		=> $this->userPassword,
                'orderId' 			=> $orderId,
                'saleOrderId' 		=> $saleOrderId,
                'saleReferenceId' 	=> $saleReferenceId
            );

            if( isset( $_POST['CardHolderPan'] ) ){
                $this->cardNumber = $_POST['CardHolderPan'];
            }

            if( isset( $_POST['SaleReferenceId'] ) ){
                $this->referID = $_POST['SaleReferenceId'];
            }

            if ($post['ResCode'] != 0) {
                $this->messageCode = $post['ResCode'];
                return false;
            }

            $result = $this->client->call('bpVerifyRequest', $parameters, $this->namespace );

            // Check for a fault
            if ($this->client->fault) {
                echo '<h2>Fault</h2><pre>';
                print_r($result);
                echo '</pre>';
                die();
            }

            $err = $this->client->getError();
            if ($err) {
                // Display the error
                echo '<h2>Error</h2><pre>' . $err . '</pre>';
                die();
            }

            $this->messageCode = $result;
            if( $result == '0' ) {
                $this->settle( $parameters );
                return [
                    'status'      => 'success',
                    'code'        => $resultCode,
                    'message'     => $this->message($resultCode),
                    'referenceId' => $post['SaleReferenceId']
                ];
            }else{
                $this->messageCode = $result;
                return [
                    'status'      => 'false',
                    'code'        => $resultCode,
                    'message'     => $this->message($resultCode),
                    'referenceId' => $post['SaleReferenceId']
                ];
            }

        } else {
            $this->messageCode = $resultCode;
            return [
                'status'      => 'error',
                'code'        => $resultCode,
                'message'     => $this->message($resultCode),
                'referenceId' => null
            ];
        }

    }

    public function checkPaymentStatus($params)
    {

        $post       = $params['post'];
        $resultCode = $post['ResCode'];
        if ($resultCode == 0) {

            $orderId 			= $params['orderId'];
            $saleOrderId 		= $post['SaleOrderId'];
            $saleReferenceId 	= $post['SaleReferenceId'];

            $this->connect();

            $parameters = array(
                'terminalId' 		=> $this->terminalId,
                'userName' 			=> $this->userName,
                'userPassword' 		=> $this->userPassword,
                'orderId' 			=> $orderId,
                'saleOrderId' 		=> $saleOrderId,
                'saleReferenceId' 	=> $saleReferenceId
            );

            if( isset( $_POST['CardHolderPan'] ) ){
                $this->cardNumber = $_POST['CardHolderPan'];
            }

            if( isset( $_POST['SaleReferenceId'] ) ){
                $this->referID = $_POST['SaleReferenceId'];
            }

            if ($post['ResCode'] != 0) {
                $this->messageCode = $post['ResCode'];
                return false;
            }

            $result = $this->client->call('bpVerifyRequest', $parameters, $this->namespace );

            // Check for a fault
            if ($this->client->fault) {
                echo '<h2>Fault</h2><pre>';
                print_r($result);
                echo '</pre>';
                die();
            }

            $err = $this->client->getError();
            if ($err) {
                // Display the error
                echo '<h2>Error</h2><pre>' . $err . '</pre>';
                die();
            }

            $this->messageCode = $result;
            if( $result == '0' ) {
                return true;
            }else{
                $this->messageCode = $result;
                return false;
            }

        } else {
            $this->messageCode = $resultCode;
            return false;
        }

    }

    public function paymentSettle($params)
    {
        $post               = $params['post'];
        $orderId 			= $params['orderId'];
        $saleOrderId 		= $post['SaleOrderId'];
        $saleReferenceId 	= $post['SaleReferenceId'];

        $parameters = array(
            'terminalId' 		=> $this->terminalId,
            'userName' 			=> $this->userName,
            'userPassword' 		=> $this->userPassword,
            'orderId' 			=> $orderId,
            'saleOrderId' 		=> $saleOrderId,
            'saleReferenceId' 	=> $saleReferenceId
        );

        if( isset( $_POST['CardHolderPan'] ) ){
            $this->cardNumber = $_POST['CardHolderPan'];
        }

        if( isset( $_POST['SaleReferenceId'] ) ){
            $this->referID = $_POST['SaleReferenceId'];
        }

        if ($post['ResCode'] != 0) {
            $this->messageCode = $post['ResCode'];
            return false;
        }

        $this->settle( $parameters );

        return true;

    }

    public function getVerifyAuthority() {
        return $_POST['RefId'];
    }

}
