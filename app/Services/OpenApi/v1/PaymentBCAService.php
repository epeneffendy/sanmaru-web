<?php

namespace App\Services\OpenApi\v1;

use App\Models\OpenApi\v1\PaymentBcaInvocationDetailBillsResponse;
use App\Models\OpenApi\v1\PaymentBcaInvocationDetailFailedResponse;
use App\Models\OpenApi\v1\PaymentVirtualAccountDataResponse;
use App\Models\PaymentApiLog;
use App\Models\ExternalLog;
use App\Models\OpenApi\v1\PaymentBca;
use App\Models\OpenApi\v1\PaymentBcaBillRequest;
use App\Models\OpenApi\v1\PaymentBcaBillResponse;
use App\Models\OpenApi\v1\PaymentBcaBillDetailResponse;
use App\Models\OpenApi\v1\PaymentBcaInvocationDetailResponse;
use App\Models\OpenApi\v1\PaymentBcaInvocationRequest;
use App\Models\OpenApi\v1\PaymentBcaInvocationResponse;
use App\Models\PPDBUser;
use App\Models\ProductOrder;
use App\Models\ProductOrderPayment;
use App\Models\TokenApiLog;
use App\Models\Unit;
use App\Services\ProductOrderService;
use App\Services\PPDBUserService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleLogMiddleware\LogMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class PaymentBCAService
{
    protected $debug;
    protected $settings;
    protected $client;
    protected $defaultHeaders = [];
    private $productOrderService;
    public const PAYMENT_BCA_CHANNEL_TYPE = array(
        6010 => 'Teller/Branch',
        6011 => 'ATM',
        6012 => 'POS/EDC',
        6013 => 'AutoDebit',
        6014 => 'Internet Banking',
        6015 => 'Kiosk',
        6016 => 'Phone Banking',
        6017 => 'Mobile Banking',
        6018 => 'LLG / Kiriman Uang (KU)',
        6019 => 'Branchless Banking',
        6020 => 'Shared Biller',
    );

    public function __construct(ProductOrderService $productOrderService, PPDBUserService $ppdbUserService)
    {
        $this->debug = env('PAYMENT_BCA_DEBUG', false);
        $this->productOrderService = $productOrderService;
        $this->ppdbUserService = $ppdbUserService;
        $this->settings = array(
            'apiUrl' => env('PAYMENT_BCA_API_URL', 'https://devapi.klikbca.com:443/'),
            'clientId' => env('PAYMENT_BCA_CLIENT_ID', 'e305a76a-78d3-4f92-b734-c23ae58c97d8'),
            'clientSecret' => env('PAYMENT_BCA_CLIENT_SECRET', '04031743-9645-4bc7-84e5-c8943618c2c8'),
            'companyId' => env('PAYMENT_BCA_API_CORP_ID', 'uatcorp001'),
            'apiKey' => env('PAYMENT_BCA_API_KEY', 'a16c5bb4-49d1-4a12-9194-db3df367d893'),
            'apiSecret' => env('PAYMENT_BCA_API_SECRET', '2ad77de8-7f0e-4379-bce5-71d70529a611'),
            'channelId' => env('PAYMENT_BCA_CHANNEL_ID', '95231')
        );
        $this->defaultHeaders = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Origin' => request()->getHost(),
//            'X-BCA-Key' => $this->settings['apiKey'],
        ];
        $configs = [
            'base_uri' => $this->settings['apiUrl'],
            'headers' => $this->defaultHeaders,
        ];
        if (env('APP_DEBUG', false)) {
            $this->settings['apiUrl'] = 'https://devapi.klikbca.com:443/';
            $stack = HandlerStack::create();
            $stack->push(
                new LogMiddleware(
                    with(new Logger('guzzle-log'))->pushHandler(
                        new RotatingFileHandler(storage_path('logs/guzzle-log.log'))
                    )
                )
            );
            $configs = array_merge($configs, ['handler' => $stack]);
        }
        $this->client = new Client($configs);
    }

    public function log($type, $request, $response)
    {
        $log = PaymentApiLog::create([
            'type' => $type,
            'request' => json_encode($request),
            'response' => json_encode($response),
        ]);
        return $log;
    }

    public function getAuthToken()
    {
        $token = session()->get('payment_bca_token');

        $isExpired = !$token || Carbon::parse($token->expires_at)->isPast();

//        if ($isExpired) {
        $token = new \stdClass();
        try {

            if ((ENV("PAYMENT_BCA_API_URL") == "https://devapi.klikbca.com:443")) {
                $clientID = '8effa5d7-d3be-4c79-9fc7-d2d22c615413';
            }else{
                $clientID = env('PAYMENT_BCA_CLIENT_ID_INBOUND', '7aff11d8-08c0-4ab2-9a67-9c23a519450a');
            }

            $privateKey = Storage::disk('local')->get('/private-key.pem');
            $isoTime = date('o-m-d') . 'T' . date('H:i:s') . date('P');

            $signatureToken = $this->signatureToken($privateKey, $clientID, $isoTime);
            $data = [
                'grantType' => 'client_credentials'
            ];

            $request = $this->client->post('openapi/v1.0/access-token/b2b', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->settings['clientId'] . ':' . $this->settings['clientSecret']),
                    'X-TIMESTAMP' => $isoTime,
                    'X-SIGNATURE' => $signatureToken['signature'],
                    'X-CLIENT-KEY' => $clientID
                ],
                'body' => \GuzzleHttp\json_encode($data)
            ]);

            $response = json_decode($request->getBody()->getContents());
            $header= [
                'Authorization' => 'Basic ' . base64_encode($this->settings['clientId'] . ':' . $this->settings['clientSecret']),
                'TIMESTAMP' => $isoTime,
                'SIGNATURE' => $signatureToken['signature'],
                'CLIENT' => $clientID
            ];
            $this->log('v1.0/access-token/b2b-inbound', \GuzzleHttp\json_encode($header), $response);

            if ($response->accessToken) {
                $token->token = $response;
                $token->expires_at = Carbon::now()->addSeconds($token->token->expiresIn);
                $expires_at = Carbon::now()
                    ->addSeconds(900)
                    ->format('Y-m-d H:i:s');

                $this->logToken($token->token->accessToken, $expires_at);
                session()->put('payment_bca_token', $token);
            }
        } catch (RequestException $e) {
            $token = null;
        }
//        }
        return $token;
    }

    public function getAuthTokenBearer()
    {
        $token = session()->get('payment_bca_token');

        $strToken = Str::random(60);
        $dataToken = [
            //'token' => hash('sha256', $strToken),
            'token' => $strToken,
            'expires_at' => Carbon::now()
                ->addSeconds(900)
                ->format('Y-m-d H:i:s'),
            'tokenType' => 'bearer'
        ];
        $this->logToken($dataToken['token'], $dataToken['expires_at']);

        return $dataToken;
    }

    public function getAccessToken($isoTime)
    {
        if ((ENV("PAYMENT_BCA_API_URL") == "https://devapi.klikbca.com:443")) {
            $clientID = env('PAYMENT_BCA_CLIENT_ID_INBOUND', '7aff11d8-08c0-4ab2-9a67-9c23a519450a');
        }else{
            $clientID = env('PAYMENT_BCA_CLIENT_ID_INBOUND', '7aff11d8-08c0-4ab2-9a67-9c23a519450a');
        }

        $privateKey = Storage::disk('local')->get('/private-key_prod.pem');
        if (env('APP_ENV_SANMARU') == 'staging') {
            $publicKey = Storage::disk('local')->get('/private-key_Dev.pem');
        }

        $signatureToken = $this->signatureToken($privateKey, $clientID, $isoTime);

        $data = [
            'grantType' => 'client_credentials'
        ];

        $clientInbound = '8effa5d7-d3be-4c79-9fc7-d2d22c615413';
        $secretInbound = '36c42cd1-30cd-4bcc-8f9b-fa4a396db67c';

        try {
            $request = $this->client->post('openapi/v1.0/access-token/b2b', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($clientInbound . ':' . $secretInbound),
                    'X-TIMESTAMP' => $isoTime,
                    'X-SIGNATURE' => $signatureToken['signature'],
                    'X-CLIENT-KEY' => $clientID
                ],
                'body' => \GuzzleHttp\json_encode($data)
            ]);

            $response = json_decode($request->getBody()->getContents());
        } catch (RequestException $e) {
            $response = null;
        }

        return $response;
    }

    public function signatureToken($privateKey, $clientID, $isotime)
    {
        $stringToSign = $clientID . '|' . $isotime;
        $algo = "SHA256";
        $binary_signature = "";
        openssl_sign($stringToSign, $binary_signature, $privateKey, OPENSSL_ALGO_SHA256);
        return ['signature' => base64_encode($binary_signature), 'timestamp' => $isotime];
    }


    public function generateSignatureInquiry($method, $url, $auth_token, $secret_key, $isoTime, $bodyToHash = [])
    {
//        $auth_token = 'J3vOZqd8EZvaXYVwZFaoOCVg6T3y6ZCsFLkE7cgFG1fuKGYi7UlXO4';
//        $isoTime ='2023-01-03T17:46:22+07:00';
        $hash = hash("SHA256", "");
        if (is_array($bodyToHash)) {
//            ksort($bodyToHash);
            $encoderData = json_encode($bodyToHash, JSON_UNESCAPED_SLASHES);
            $hash = hash("sha256", $encoderData);
        }

        $stringToSign = $method . ":" . $url . ":" . $auth_token . ":" . $hash . ":" . $isoTime;
        // $stringToSign = "POST:/openapi/v1.0/transfer-va/status:JYi8LULuaHeJzUl1anoDWj5TRhTotok0aeuXY0GSBACgBX5wPwxI7P:b43e659d620da84430310e0938808713217e458b54cd9059231d6abf8da6373b:2023-12-15T14:26:36+07:00";

        return [
            'signature' => base64_encode(hash_hmac('SHA512', $stringToSign, $secret_key, true)),
            'hash' => $hash,
            'stringToSign' => $stringToSign,
            'requestBody' => \GuzzleHttp\json_encode($bodyToHash)
        ];
    }

    public function generateSignature($method, $url, $auth_token, $secret_key, $isoTime, $bodyToHash = [])
    {
        $hash = hash("sha256", "");
        if (is_array($bodyToHash)) {
            ksort($bodyToHash);
            $encoderData = json_encode($bodyToHash, JSON_UNESCAPED_SLASHES);
            $hash = hash("sha256", $encoderData);
        }
        $hash = strtolower($hash);

        $stringToSign = $method . ":" . $url . ":" . $auth_token . ":" . $hash . ":" . $isoTime;
        return hash_hmac('sha256', $stringToSign, $secret_key);
    }

    public function inquiryStatus($param, $token, $isoTime)
    {
        try {
            if ((ENV("PAYMENT_BCA_API_URL") == "https://devapi.klikbca.com:443")) {
                $url = 'https://devapi.klikbca.com:443/openapi/v1.0/transfer-va/status';
            } else {
                $url = 'https://api.klikbca.com/openapi/v1.0/transfer-va/status';
            }

            $ClientSecret = env('PAYMENT_BCA_CLIENT_SECRET_INBOUND', '6fc93751-131d-4c5c-8880-75cc05835437');

            $relativeUrl = '/openapi/v1.0/transfer-va/status';
            $signature = $this->generateSignatureInquiry('POST', $relativeUrl, $token->accessToken, $ClientSecret, $isoTime, $param);

            $request = $this->client->post($url, [
                'headers' => [
                    'CHANNEL-ID' => $this->settings['channelId'],
                    'X-PARTNER-ID' => '13977',
                    'X-TIMESTAMP' => $isoTime,
                    'X-SIGNATURE' => $signature['signature'],
                    'Authorization' => 'Bearer ' . $token->accessToken,
                    'X-EXTERNAL-ID' => strtotime($isoTime) . strtotime(Carbon::now()),
                    'Origin' =>''
                ],
                'body' => \GuzzleHttp\json_encode($param),
            ]);
            $response = json_decode($request->getBody()->getContents());
            $this->log('va/status', $param, $response);
        } catch (RequestException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            $this->log('va/status', $param, $e->getResponse()->getBody()->getContents());
        }

        return $response;
    }

    public function getPpdbBills($ppdbId, $unitId, PaymentBcaBillRequest $data, PaymentBcaBillResponse $result)
    {
        $unit = Unit::where('unit_code', $unitId)->first();
        if (!$unit) {
            $result->setresponseCode("4042412");
            $result->setresponseMessage("Invalid Bill/Virtual Account [Not Found]");
            $reason = array(
                'english' => 'Invalid Bill/Virtual Account [Not Found]',
                'indonesia' => 'Tagihan/Akun Virtual Tidak Valid [Tidak Ditemukan]',
            );
            $failedResponse = $this->failedResponse($data, $result, $reason);
            $result->setvirtualAccountData($failedResponse->toArray());
        } else {
            $ppdbUser = PpdbUser::where('register_number', $ppdbId)
                ->with([
                    'orders' => function ($query) {
                        $query->where('status', 'new_order');
                    },
                    'orders.productOrderDetails',
                ])
                ->first();
            if (!$ppdbUser) {
                $result->setresponseCode("4042412");
                $result->setresponseMessage("Invalid Bill/Virtual Account [Not Found]");
                $reason = array(
                    'english' => 'Invalid Bill/Virtual Account [Not Found]',
                    'indonesia' => 'Tagihan/Akun Virtual Tidak Valid [Tidak Ditemukan]',
                );
                $failedResponse = $this->failedResponse($data, $result, $reason);
                $result->setvirtualAccountData($failedResponse->toArray());
            } else {
                if (($data->getpartnerServiceId() . $data->getcustomerNo()) == $data->getvirtualAccountNo()) {
                    $orders = $ppdbUser->orders;
                    if ($orders->count() > 0) {
                        $totalAmount = 0;
                        $orders->each(function (ProductOrder $order) use (&$totalAmount, &$data) {
                            $totalAmount += $order->total_payment;
                            $this->inquiryRequestBill($order->id, $data->getinquiryRequestId(),'uniform');
                        });

                        $virtualAccount = new PaymentVirtualAccountDataResponse();
                        $virtualAccount->setinquiryStatus("00");
                        $virtualAccount->setinquiryReason(array(
                            "english" => "Success",
                            "indonesia" => "Sukses"
                        ));
                        $virtualAccount->setpartnerServiceId($data->getpartnerServiceId());
                        $virtualAccount->setcustomerNo($data->getcustomerNo());
                        $virtualAccount->setvirtualAccountNo($data->getvirtualAccountNo());
                        $virtualAccount->setvirtualAccountName($ppdbUser->name);
                        $virtualAccount->setvirtualAccountEmail($ppdbUser->user->email);
                        $virtualAccount->setvirtualAccountPhone((string)$ppdbUser->user->mobile_phone);
                        $virtualAccount->setinquiryRequestId($data->getinquiryRequestId());
                        $virtualAccount->settotalAmount(array(
                            "value" => (string)number_format($totalAmount, 2, '.', ''),
                            "currency" => "IDR"
                        ));
                        $virtualAccount->setsubCompany("00000");
                        $virtualAccount->setbillDetails(array());
                        $virtualAccount->setfreeTexts(array());
                        $virtualAccount->setvirtualAccountTrxType("C");
                        $virtualAccount->setfeeAmount(null);
                        $virtualAccount->setadditionalInfo((object)array());

                        $result->setvirtualAccountData($virtualAccount->toArray());
                    } else {
                        $orders = ProductOrder::where('user_id', $ppdbUser->user_id)->orderBy('id', 'DESC')->firstOrFail();
                        if ($orders->status == 'cancel') {
                            $result->setresponseCode("4042419");
                            $result->setresponseMessage("Bill expired");
                            $reason = array(
                                'english' => 'Bill expired',
                                'indonesia' => 'Tagihan kadarluwarsa',
                            );
                        } else if ($orders->status == 'confirmed') {
                            $result->setresponseCode("4042414");
                            $result->setresponseMessage("Paid Bill");
                            $reason = array(
                                'english' => 'Paid Bill',
                                'indonesia' => 'Tagihan telah dibayar',
                            );
                        }

                        $failedResponse = $this->failedResponse($data, $result, $reason);
                        $result->setvirtualAccountData($failedResponse->toArray());
                    }
                } else {
                    $result->setresponseCode("4002401");
                    $result->setresponseMessage("Invalid Field Format virtualAccountNo");
                    $reason = array(
                        'english' => 'Invalid Field Format virtualAccountNo',
                        'indonesia' => 'Format tidak valid virtualAccountNo',
                    );
                    $failedResponse = $this->failedResponse($data, $result, $reason);
                    $result->setvirtualAccountData($failedResponse->toArray());
                }
            }
        }
        return $result;
    }

    public function flagPaymentPpdb($ppdbId, $unitId, PaymentBcaInvocationRequest $data, PaymentBcaInvocationResponse $result, $external_id)
    {
        $reason = array(
            'english' => '',
            'indonesia' => '',
        );
        $unit = Unit::where('unit_code', $unitId)->first();
        if (!$unit) {
            $status = '01';
            $reason = array(
                'english' => 'Invalid Bill/Virtual Account [Not Found]',
                'indonesia' => 'Tagihan/Akun Virtual Tidak Valid [Tidak Ditemukan]',
            );
            $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', $reason['english'], $reason['indonesia'], '01');

            $result->setresponseCode("4042512");
            $result->setresponseMessage("Invalid Bill/Virtual Account [Not Found]");
            $result = $this->paymentFailedResponse($data, $result, $status, $reason);
        } else {
            $ppdbUser = PpdbUser::where('register_number', $ppdbId)
                ->with([
                    'orders' => function ($query) {
                        $query->where('status', 'new_order');
                    },
                    'orders.productOrderDetails',
                ])
                ->first();

            if (!$ppdbUser) {
                $status = '01';
                $reason = array(
                    'english' => 'Invalid Bill/Virtual Account [Not Found]',
                    'indonesia' => 'Tagihan/Akun Virtual Tidak Valid [Tidak Ditemukan]',
                );
                $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', $reason['english'], $reason['indonesia'], '01');

                $result->setresponseCode("4042512");
                $result->setresponseMessage("Invalid Bill/Virtual Account [Not Found]");
                $result = $this->paymentFailedResponse($data, $result, $status, $reason);
            } else {
                if (($data->getpartnerServiceId() . $data->getcustomerNo()) == $data->getvirtualAccountNo()) {
                    $ppdbUser->orders->each(function (ProductOrder $_order) use (&$order, &$data) {
//                            if ($_order->invoice_no == $bill['billNo']) {
//                                $order = $_order;
//                            });
                        if ($_order->payment_inquiry_id == $data->getpaymentRequestId()) {
                            $order = $_order;
                        }
                    });

                    if (isset($order)) {
                        $totalAmount = $this->totalAmount($ppdbId);
                        if (((substr($totalAmount, 0, -3)) == (substr($data->getpaidAmount()['value'], 0, -3))) && (substr($totalAmount, 0, -3)) == (substr($data->gettotalAmount()['value'], 0, -3))) {
                            if ($data->getflagAdvise() == 'N') {

                                $validateExternal = $this->ExternalID($external_id, $data->getpaymentRequestId(), 'payments', 1);
                                if ($validateExternal['success']) {
                                    $confirmed = $this->debug || $this->productOrderService->confirmPayment($order->id, $ppdbUser->user);
                                    if ($confirmed) {
                                        $detail = new PaymentBcaInvocationDetailResponse();
                                        $detail->setpartnerServiceId($data->getpartnerServiceId());

                                        $detail->setcustomerNo($data->getcustomerNo());
                                        $detail->setvirtualAccountNo($data->getvirtualAccountNo());
                                        $detail->setvirtualAccountName($data->getvirtualAccountName());
                                        $detail->setvirtualAccountEmail($data->getvirtualAccountEmail());
                                        $detail->setvirtualAccountPhone($data->getvirtualAccountPhone());
                                        $detail->settrxId(($data->gettrxId() == null) ? "" : $data->gettrxId());
                                        $detail->setpaymentRequestId($data->getpaymentRequestId());
                                        $detail->setpaidAmount($data->getpaidAmount());
                                        $detail->setpaidBills(($data->getpaidBills() == null) ? "" : $data->getpaidBills());
                                        $detail->settotalAmount($data->gettotalAmount());
                                        $detail->settrxDateTime($data->gettrxDateTime());
                                        $detail->setreferenceNo($data->getreferenceNo());
                                        $detail->setjournalNum(($data->getjournalNum() == null) ? "" : $data->getjournalNum());
                                        $detail->setpaymentType(($data->getpaymentType() == null) ? "" : $data->getpaymentType());
                                        $detail->setflagAdvise($data->getflagAdvise());
                                        $detail->setpaymentFlagStatus("00");
                                        $detail->setbillDetails(array());
                                        $detail->setfreeTexts(array());
                                        $result->setvirtualAccountData($detail);
                                        $result->setadditionalInfo((object)array());
                                        $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', 'Success', 'Sukses', '00');

                                    } else {
                                        $status = '01';
                                        $reason = array(
                                            'english' => 'Failed, order cannot be confirmed',
                                            'indonesia' => 'Gagal, order tidak dapat dikonfirmasi',
                                        );
                                        $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', $reason['english'], $reason['indonesia'], '01');

                                        $result->setresponseCode("4002501");
                                        $result->setresponseMessage("Failed, order cannot be confirmed");
                                        $result = $this->paymentFailedResponse($data, $result, $status, $reason);
                                    }
                                }
                            } else {
                                $status = '01';
                                $reason = array(
                                    'english' => 'Failed, order cannot be confirmed',
                                    'indonesia' => 'Gagal, order tidak dapat dikonfirmasi',
                                );
                                $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', $reason['english'], $reason['indonesia'], '01');

                                $result->setresponseCode("4002501");
                                $result->setresponseMessage("Failed, order cannot be confirmed");
                                $result = $this->paymentFailedResponse($data, $result, $status, $reason);
                            }
                        } else {
                            $status = '01';
                            $reason = array(
                                'english' => 'Invalid Amount',
                                'indonesia' => 'Jumlah tidak valid',
                            );
                            $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', $reason['english'], $reason['indonesia'], '01');

                            $result->setresponseCode("4042513");
                            $result->setresponseMessage("Invalid Amount");
                            $result = $this->paymentFailedResponse($data, $result, $status, $reason);
                        }
                    } else {
                        $orders = ProductOrder::where('user_id', $ppdbUser->user_id)->orderBy('id', 'DESC')->firstOrFail();
                        if (((substr($orders->total_payment, 0, -3)) == (substr($data->getpaidAmount()['value'], 0, -3))) && (substr($orders->total_payment, 0, -3)) == (substr($data->gettotalAmount()['value'], 0, -3))) {
                            if ($orders->status == 'cancel') {
                                $status = '01';
                                $reason = array(
                                    'english' => 'Bill expired',
                                    'indonesia' => 'Tagihan kadarluwasa',
                                );
                                $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', $reason['english'], $reason['indonesia'], '01');
                                $result->setresponseCode("4042519");
                                $result->setresponseMessage("Bill expired");

                                $result = $this->paymentFailedResponse($data, $result, $status, $reason);

                            } else if ($orders->status == 'confirmed') {
                                $status = '01';
                                $reason = array(
                                    'english' => 'Paid Bill',
                                    'indonesia' => 'Tagihan telah di bayar',
                                );
                                $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', $reason['english'], $reason['indonesia'], '01');
                                $result->setresponseCode("4042514");
                                $result->setresponseMessage("Paid Bill");
                                $result = $this->paymentFailedResponse($data, $result, $status, $reason);

                            } else {
                                $status = '01';
                                $reason = array(
                                    'english' => 'Invalid Bill/Virtual Account [Not Found]',
                                    'indonesia' => 'Tagihan/Akun Virtual Tidak Valid [Tidak Ditemukan]',
                                );
                                $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', $reason['english'], $reason['indonesia'], '01');
                                $result->setresponseCode("4042512");
                                $result->setresponseMessage("Invalid Bill/Virtual Account [Not Found]");
                                $result = $this->paymentFailedResponse($data, $result, $status, $reason);
                            }
                        } else {
                            $status = '01';
                            $reason = array(
                                'english' => 'Invalid Amount',
                                'indonesia' => 'Jumlah tidak valid',
                            );
                            $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', $reason['english'], $reason['indonesia'], '01');

                            $result->setresponseCode("4042513");
                            $result->setresponseMessage("Invalid Amount");
                            $result = $this->paymentFailedResponse($data, $result, $status, $reason);
                        }
                    }
                } else {
                    $status = '01';
                    $reason = array(
                        'english' => 'Invalid Field Format virtualAccountNo',
                        'indonesia' => 'Format tidak valid virtualAccountNo',
                    );
                    $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', $reason['english'], $reason['indonesia'], '01');

                    $result->setresponseCode('4002501');
                    $result->setresponseMessage('Invalid Field Format virtualAccountNo');
                    $result = $this->paymentFailedResponse($data, $result, $status, $reason);
                }
            }
        }
        $validateExternal = $this->ExternalID($external_id, $data->getpaymentRequestId(), 'payments');

        if ($validateExternal['success'] == false) {
            if ($validateExternal['count'] > 0) {
                $status = $validateExternal['code'];
                $reason = array(
                    'english' => $validateExternal['english'],
                    'indonesia' => $validateExternal['indonesia'],
                );

                $result->setresponseCode($validateExternal['error_code']);
                $result->setresponseMessage($validateExternal['message']['english']);
                $result = $this->paymentFailedResponse($data, $result, $status, $reason);
            }
        }
        return $result;
    }

    public function addPaymentBca(ProductOrder $productOrder, PaymentBcaInvocationRequest $request, $data): PaymentBca
    {
        $paymentBca = PaymentBca::firstOrCreate([
            'company_code' => $request->getCompanyCode(),
            'customer_number' => $request->getCustomerNumber(),
            'request_id' => $request->getRequestId(),
            'bill_number' => $data['bill_number'],
        ], [
            'company_code' => $request->getCompanyCode(),
            'channel_type' => $request->getChannelType(),
            'request_id' => $request->getRequestId(),
            'customer_number' => $request->getCustomerNumber(),
            'sub_company' => $data['sub_company'],
            'currency' => $request->getCurrencyCode(),
            'reference' => $data['reference'],
            'bill_number' => $data['bill_number'],
            'status' => $data['status'],
            'transaction_date' => Carbon::now(),
            'total_amount' => $request->getTotalAmount(),
            'paid_amount' => $request->getPaidAmount(),
        ]);
        $productOrderPayment = ProductOrderPayment::firstOrCreate([
            'product_order_id' => $productOrder->id,
            'bank' => 'BCA',
            'payment_bca_id' => $paymentBca->id,
        ], [
            'product_order_id' => $productOrder->id,
            'bank' => 'BCA',
            'payment_bca_id' => $paymentBca->id,
            'total_payment' => $request->getTotalAmount(),
            'status' => 'confirmed',
            'payment_date' => Carbon::now(),
        ]);
        return $paymentBca;
    }

    /**
     * @return mixed
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * @param mixed $debug
     */
    public function setDebug($debug): void
    {
        $this->debug = $debug;
    }

    public function logToken($token, $expires_at)
    {
        $log = TokenApiLog::create([
            'access_token' => $token,
            'expires_at' => $expires_at,
        ]);
        return $log;
    }

    public function totalAmount($ppdbId)
    {
        $totalAmount = 0;
        $ppdbUser = PpdbUser::where('register_number', $ppdbId)
            ->with([
                'orders' => function ($query) {
                    $query->where('status', 'new_order');
                },
                'orders.productOrderDetails',
            ])
            ->first();
        if ($ppdbUser) {
            $orders = $ppdbUser->orders;
            if ($orders->count() === 1) {
                $orders->each(function (ProductOrder $order) use (&$reason, &$totalAmount) {
                    $totalAmount = $order->total_payment;
                });
            } else {
                $detailBills = collect();
                $orders->each(function (ProductOrder $order) use ($detailBills, &$totalAmount) {
                    $totalAmount += $order->total_payment;
                });
            }
        }
        $totalAmount = number_format($totalAmount, 2, '.', '');
        return $totalAmount;
    }

    public function paymentFailedResponse(PaymentBcaInvocationRequest $data, PaymentBcaInvocationResponse $result, $status, $reason)
    {
        $detail = new PaymentBcaInvocationDetailFailedResponse();
        $status = (!empty($status)) ? $status : '01';
        $lengthString = 3;
        $lengthVA = strlen($data->getvirtualAccountNo());

        $patnerId = ($data->getpartnerServiceId() == null) ? "" : str_pad($data->getpartnerServiceId(), 8, " ", STR_PAD_LEFT);
        $virtualAccountNo = ($data->getvirtualAccountNo() == null) ? "" : str_pad($data->getvirtualAccountNo(), $lengthVA + $lengthString, " ", STR_PAD_LEFT);

        $detail->setpaymentFlagReason($reason);
        $detail->setpartnerServiceId($patnerId);
        $detail->setcustomerNo(($data->getcustomerNo() == null) ? "" : $data->getcustomerNo());
        $detail->setvirtualAccountNo($virtualAccountNo);
        $detail->setvirtualAccountName(($data->getvirtualAccountName() == null) ? "" : $data->getvirtualAccountName());
        $detail->setvirtualAccountEmail(($data->getvirtualAccountEmail() == null) ? "" : $data->getvirtualAccountEmail());
        $detail->setvirtualAccountPhone(($data->getvirtualAccountPhone() == null) ? "" : $data->getvirtualAccountPhone());
        $detail->settrxId(($data->gettrxId() == null) ? "" : $data->gettrxId());
        $detail->setpaymentRequestId($data->getpaymentRequestId());
        $detail->setpaidAmount(array(
            "value" => ($data->getpaidAmount()['value'] != null) ? $data->getpaidAmount()['value'] : "",
            "currency" => ($data->getpaidAmount()['currency'] != null) ? $data->getpaidAmount()['currency'] : ""
        ));
        $detail->setpaidBills(($data->getpaidBills() == null) ? "" : $data->getpaidBills());
        $detail->settotalAmount(array(
            "value" => ($data->gettotalAmount()['value'] != null) ? $data->gettotalAmount()['value'] : "",
            "currency" => ($data->gettotalAmount()['currency'] != null) ? $data->gettotalAmount()['currency'] : ""
        ));
        $detail->settrxDateTime($data->gettrxDateTime());
        $detail->setreferenceNo($data->getreferenceNo());
        $detail->setjournalNum(($data->getjournalNum() == null) ? "" : $data->getjournalNum());
        $detail->setpaymentType($data->getpaymentType());
        $detail->setflagAdvise($data->getflagAdvise());
        $detail->setpaymentFlagStatus($status);
        $detail->setbillDetails(array());
        $detail->setfreeTexts(array());
        $result->setvirtualAccountData($detail);
        $result->setadditionalInfo((object)array());

        return $result;
    }

    public function inquiryRequestBill($orderId, $inquiryId, $flag = '')
    {
        if($flag == 'registrations'){
            $ppdbUser = PpdbUser::where([
                        'id' => $orderId,
                        'payment_date' => null
                    ])->firstOrFail();

            $ppdbUser->payment_inquiry_id = $inquiryId;
            $ppdbUser->save();
        }else{
            $productOrder = ProductOrder::where([
                        'id' => $orderId,
                        'status' => ProductOrder::STATUS_NEW_ORDER
                    ])->firstOrFail();

            if ($productOrder->update([
                'payment_inquiry_id' => $inquiryId
            ]));
        }


    }

    public function failedResponse($data, $result, $reason)
    {
        $unitId = substr($data->getcustomerNo(), 0, 2);
        $paymentCode = substr($data->getcustomerNo(), 2, 2);
        $orderId = substr($data->getcustomerNo(), 4);

        $ppdbUser = PpdbUser::where('register_number', $orderId)
            ->with([
                'orders' => function ($query) {
                    $query->where('status', 'new_order');
                },
                'orders.productOrderDetails',
            ])
            ->first();

        $lengthString = 3;
        $lengthVA = strlen($data->getvirtualAccountNo());
        $patnerId = ($data->getpartnerServiceId() == null) ? "" : str_pad($data->getpartnerServiceId(), 8, " ", STR_PAD_LEFT);
        $virtualAccountNo = ($data->getvirtualAccountNo() == null) ? "" : str_pad($data->getvirtualAccountNo(), $lengthVA + $lengthString, " ", STR_PAD_LEFT);

        $virtualAccount = new PaymentVirtualAccountDataResponse();
        $virtualAccount->setinquiryStatus("01");
        $virtualAccount->setinquiryReason(array(
            "english" => $reason['english'],
            "indonesia" => $reason['indonesia'],
        ));
        $virtualAccount->setpartnerServiceId($patnerId);
        $virtualAccount->setcustomerNo($data->getcustomerNo());
        $virtualAccount->setvirtualAccountNo($virtualAccountNo);
        $virtualAccount->setvirtualAccountName((isset($ppdbUser) ? $ppdbUser->name : ""));
        $virtualAccount->setvirtualAccountEmail((isset($ppdbUser) ? $ppdbUser->user->email : ""));
        $virtualAccount->setvirtualAccountPhone((isset($ppdbUser) ? (string)$ppdbUser->user->mobile_phone : ""));
        $virtualAccount->setinquiryRequestId($data->getinquiryRequestId());
        $virtualAccount->settotalAmount(array(
            "value" => "0",
            "currency" => "IDR"
        ));
        $virtualAccount->setsubCompany("00000");
        $virtualAccount->setbillDetails(array());
        $virtualAccount->setfreeTexts(array());
        $virtualAccount->setvirtualAccountTrxType("");
        $virtualAccount->setfeeAmount(null);
        $virtualAccount->setadditionalInfo((object)array());

        return $virtualAccount;

    }

     public function logExternalID($external_id, $request_id, $flag, $message_response = null)
    {
        $success = true;
        $message = array(
            'indonesia' => '',
            'english' => ''
        );
        $error_code = '';
        $count = '';

        $date = Carbon::now()->toDateString();
//        if (env('APP_ENV') == 'local') {
//            return ['success' => $success, 'message' => $message, 'error_code' => $error_code];
//        }
        $external = ExternalLog::where(['external_id' => $external_id, 'date' => $date, 'flag' => $flag])->first();
        $requestID = ExternalLog::where(['request_id' => $request_id, 'date' => $date, 'flag' => $flag])->get();


        if ($message_response == 1) {
            $count = 0;
            $count += $message_response;
        }

        if (!isset($external)) {
            $log = ExternalLog::create([
                'external_id' => $external_id,
                'request_id' => $request_id,
                'date' => $date,
                'flag' => $flag,
                'message' => $count
            ]);
        }

        if (isset($external)) {
            if ($flag == 'payments') {
                if (($external->external_id == $external_id) && ($external->request_id == $request_id)) {
                    $success = false;
                    $message = array(
                        'indonesia' => 'Permintaan tidak konsisten',
                        'english' => 'Inconsistent Request'
                    );
                    return ['success' => false, 'message' => $message, 'error_code' => '4042518', 'count' => $count];
                }

                if (($external->external_id == $external_id) && ($external->request_id != $request_id)) {
                    $success = false;
                    $message = array(
                        'indonesia' => 'Konflik',
                        'english' => 'Conflict'
                    );
                    return ['success' => false, 'message' => $message, 'error_code' => '4092500'];
                }

                if (($external->external_id != $external_id) && (count($requestID) > 1 || $external->request_id != $request_id)) {
                    return ['success' => $success, 'message' => $message, 'error_code' => $error_code, 'count' => $count];
                }
            }

            if ($flag == 'bills') {
                if (($external->external_id == $external_id)) {
                    $success = false;
                    $message = array(
                        'indonesia' => 'Konflik',
                        'english' => 'Conflict'
                    );
                    return ['success' => false, 'message' => $message, 'error_code' => '4092500', 'count' => $count];
                }
            }
        }
        return ['success' => $success, 'message' => $message, 'error_code' => $error_code, 'count' => $count];
    }

    public function ExternalID($external_id, $request_id, $flag, $message_response = null)
    {
        $success = true;
        $message = array(
            'indonesia' => '',
            'english' => ''
        );
        $error_code = '';
        $count = 0;
        $code = $english = $indonesia = '';

        $date = Carbon::now()->toDateString();

        $external = ExternalLog::where(['external_id' => $external_id, 'date' => $date, 'flag' => $flag])->first();
        $requestID = ExternalLog::where(['request_id' => $request_id, 'date' => $date, 'flag' => $flag])->get();

        if (isset($external)) {
            if ($flag == 'payments') {
                if (($external->external_id == $external_id) && ($external->request_id == $request_id)) {
                    $success = false;
                    $message = array(
                        'indonesia' => 'Permintaan tidak konsisten',
                        'english' => 'Inconsistent Request'
                    );
                    return ['success' => false, 'message' => $message, 'error_code' => '4042518', 'code' => $external->status_code, 'english' => $external->english, 'indonesia' => $external->indonesia, 'count' => $external->count];
                }

                if (($external->external_id == $external_id) && ($external->request_id != $request_id)) {
                    $success = false;
                    $message = array(
                        'indonesia' => 'Konflik',
                        'english' => 'Conflict'
                    );
                    return ['success' => false, 'message' => $message, 'error_code' => '4092500', 'code' => $external->status_code, 'english' => $external->english, 'indonesia' => $external->indonesia, 'count' => $external->count];
                }

                if (($external->external_id != $external_id) && (count($requestID) > 1 || $external->request_id != $request_id)) {
                    return ['success' => $success, 'message' => $message, 'error_code' => $error_code, 'code' => $external->status_code, 'english' => $external->english, 'indonesia' => $external->indonesia, 'count' => $external->count];
                }

                if ($flag == 'bills') {
                    if (($external->external_id == $external_id)) {
                        $success = false;
                        $message = array(
                            'indonesia' => 'Konflik',
                            'english' => 'Conflict'
                        );
                        return ['success' => false, 'message' => $message, 'error_code' => '4092500', 'code' => $external->status_code, 'english' => $external->english, 'indonesia' => $external->indonesia, 'count' => $external->count];
                    }
                }
            }
        }
        return ['success' => $success, 'message' => $message, 'error_code' => $error_code, 'code' => $code, 'english' => $english, 'indonesia' => $indonesia, 'count' => $count];
    }

    public function insertLogExternal($external_id, $request_id, $flag, $english, $indonesia, $status_code)
    {
        $count = 0;
        $date = Carbon::now()->toDateString();
        $external = ExternalLog::where(['external_id' => $external_id, 'date' => $date, 'flag' => $flag])->first();
        $requestID = ExternalLog::where(['request_id' => $request_id, 'date' => $date, 'flag' => $flag])->get();


        if (!isset($external)) {
            $log = ExternalLog::create([
                'external_id' => $external_id,
                'request_id' => $request_id,
                'date' => $date,
                'flag' => $flag,
                'english' => $english,
                'indonesia' => $indonesia,
                'status_code' => $status_code,
                'count' => $count
            ]);
        } else {
            $count = $external->count + 1;
            $external->count = $count;
            $external->save();
        }
    }

     public function getPpdbRegistration($ppdbId, $unitId, PaymentBcaBillRequest $data, PaymentBcaBillResponse $result)
    {
        $unit = Unit::where('unit_code', $unitId)->first();
        if (!$unit) {
            $result->setresponseCode("4042412");
            $result->setresponseMessage("Invalid Bill/Virtual Account [Not Found]");
            $reason = array(
                'english' => 'Invalid Bill/Virtual Account [Not Found]',
                'indonesia' => 'Tagihan/Akun Virtual Tidak Valid [Tidak Ditemukan]',
            );
            $failedResponse = $this->failedResponse($data, $result, $reason);
            $result->setvirtualAccountData($failedResponse->toArray());
        } else {
            $ppdbUser = PpdbUser::where('register_number', $ppdbId)->first();
            if (!$ppdbUser) {
                $result->setresponseCode("4042412");
                $result->setresponseMessage("Invalid Bill/Virtual Account [Not Found]");
                $reason = array(
                    'english' => 'Invalid Bill/Virtual Account [Not Found]',
                    'indonesia' => 'Tagihan/Akun Virtual Tidak Valid [Tidak Ditemukan]',
                );
                $failedResponse = $this->failedResponse($data, $result, $reason);
                $result->setvirtualAccountData($failedResponse->toArray());
            } else {
                if (($data->getpartnerServiceId() . $data->getcustomerNo()) == $data->getvirtualAccountNo()) {
                    $currentDateTime = Carbon::now();

                    if($currentDateTime > $ppdbUser->expired_at){
                        $result->setresponseCode("4042419");
                        $result->setresponseMessage("Bill expired");
                        $reason = array(
                            'english' => 'Bill expired',
                            'indonesia' => 'Tagihan kadarluwarsa',
                        );
                        $failedResponse = $this->failedResponse($data, $result, $reason);
                        $result->setvirtualAccountData($failedResponse->toArray());
                    }else{
                        if(empty($ppdbUser->payment_date)){
                            $totalAmount = $ppdbUser->total_payment_form;
                            $this->inquiryRequestBill($ppdbUser->id, $data->getinquiryRequestId(), 'registrations');

                            $virtualAccount = new PaymentVirtualAccountDataResponse();
                            $virtualAccount->setinquiryStatus("00");
                            $virtualAccount->setinquiryReason(array(
                                "english" => "Success",
                                "indonesia" => "Sukses"
                            ));
                            $virtualAccount->setpartnerServiceId($data->getpartnerServiceId());
                            $virtualAccount->setcustomerNo($data->getcustomerNo());
                            $virtualAccount->setvirtualAccountNo($data->getvirtualAccountNo());
                            $virtualAccount->setvirtualAccountName($ppdbUser->name);
                            $virtualAccount->setvirtualAccountEmail($ppdbUser->user->email);
                            $virtualAccount->setvirtualAccountPhone((string)$ppdbUser->user->mobile_phone);
                            $virtualAccount->setinquiryRequestId($data->getinquiryRequestId());
                            $virtualAccount->settotalAmount(array(
                                "value" => (string)number_format($totalAmount, 2, '.', ''),
                                "currency" => "IDR"
                            ));
                            $virtualAccount->setsubCompany("00000");
                            $virtualAccount->setbillDetails(array());
                            $virtualAccount->setfreeTexts(array());
                            $virtualAccount->setvirtualAccountTrxType("C");
                            $virtualAccount->setfeeAmount(null);
                            $virtualAccount->setadditionalInfo((object)array());

                            $result->setvirtualAccountData($virtualAccount->toArray());
                        }else{
                            $result->setresponseCode("4042414");
                            $result->setresponseMessage("Paid Bill");
                            $reason = array(
                                'english' => 'Paid Bill',
                                'indonesia' => 'Tagihan telah dibayar',
                            );
                            $failedResponse = $this->failedResponse($data, $result, $reason);
                            $result->setvirtualAccountData($failedResponse->toArray());
                        }
                    }

                } else {
                    $result->setresponseCode("4002401");
                    $result->setresponseMessage("Invalid Field Format virtualAccountNo");
                    $reason = array(
                        'english' => 'Invalid Field Format virtualAccountNo',
                        'indonesia' => 'Format tidak valid virtualAccountNo',
                    );
                    $failedResponse = $this->failedResponse($data, $result, $reason);
                    $result->setvirtualAccountData($failedResponse->toArray());
                }
            }
        }
        return $result;
    }

    public function flagPaymentRegistration($ppdbId, $unitId, PaymentBcaInvocationRequest $data, PaymentBcaInvocationResponse $result, $external_id)
    {
        $reason = array(
            'english' => '',
            'indonesia' => '',
        );
        $unit = Unit::where('unit_code', $unitId)->first();

        if (!$unit) {
            $status = '01';
            $reason = array(
                'english' => 'Invalid Bill/Virtual Account [Not Found]',
                'indonesia' => 'Tagihan/Akun Virtual Tidak Valid [Tidak Ditemukan]',
            );
            $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', $reason['english'], $reason['indonesia'], '01');

            $result->setresponseCode("4042512");
            $result->setresponseMessage("Invalid Bill/Virtual Account [Not Found]");
            $result = $this->paymentFailedResponse($data, $result, $status, $reason);
        } else {

            $ppdbUser = PpdbUser::where('register_number', $ppdbId)->first();

            if (!$ppdbUser) {
                $status = '01';
                $reason = array(
                    'english' => 'Invalid Bill/Virtual Account [Not Found]',
                    'indonesia' => 'Tagihan/Akun Virtual Tidak Valid [Tidak Ditemukan]',
                );
                $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', $reason['english'], $reason['indonesia'], '01');

                $result->setresponseCode("4042512");
                $result->setresponseMessage("Invalid Bill/Virtual Account [Not Found]");
                $result = $this->paymentFailedResponse($data, $result, $status, $reason);
            } else {
                if (($data->getpartnerServiceId() . $data->getcustomerNo()) == $data->getvirtualAccountNo()) {

                    $currentDateTime = Carbon::now();

                    if($currentDateTime > $ppdbUser->expired_at){
                        $status = '01';
                        $reason = array(
                            'english' => 'Bill expired',
                            'indonesia' => 'Tagihan kadarluwasa',
                        );
                        $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', $reason['english'], $reason['indonesia'], '01');
                        $result->setresponseCode("4042519");
                        $result->setresponseMessage("Bill expired");

                        $result = $this->paymentFailedResponse($data, $result, $status, $reason);
                    }else{
                        if(empty($ppdbUser->payment_date)){
                            $totalAmount = $ppdbUser->total_payment_form;
                            if (((substr($totalAmount, 0, -3)) == (substr($data->getpaidAmount()['value'], 0, -3))) && (substr($totalAmount, 0, -3)) == (substr($data->gettotalAmount()['value'], 0, -3))) {
                                if ($data->getflagAdvise() == 'N') {

                                    $validateExternal = $this->ExternalID($external_id, $data->getpaymentRequestId(), 'payments', 1);
                                    if ($validateExternal['success']) {
                                        $confirmed = $this->ppdbUserService->confirmRegistrations($ppdbUser->id);
                                        if ($confirmed) {
                                            $detail = new PaymentBcaInvocationDetailResponse();
                                            $detail->setpartnerServiceId($data->getpartnerServiceId());

                                            $detail->setcustomerNo($data->getcustomerNo());
                                            $detail->setvirtualAccountNo($data->getvirtualAccountNo());
                                            $detail->setvirtualAccountName($data->getvirtualAccountName());
                                            $detail->setvirtualAccountEmail($data->getvirtualAccountEmail());
                                            $detail->setvirtualAccountPhone($data->getvirtualAccountPhone());
                                            $detail->settrxId(($data->gettrxId() == null) ? "" : $data->gettrxId());
                                            $detail->setpaymentRequestId($data->getpaymentRequestId());
                                            $detail->setpaidAmount($data->getpaidAmount());
                                            $detail->setpaidBills(($data->getpaidBills() == null) ? "" : $data->getpaidBills());
                                            $detail->settotalAmount($data->gettotalAmount());
                                            $detail->settrxDateTime($data->gettrxDateTime());
                                            $detail->setreferenceNo($data->getreferenceNo());
                                            $detail->setjournalNum(($data->getjournalNum() == null) ? "" : $data->getjournalNum());
                                            $detail->setpaymentType(($data->getpaymentType() == null) ? "" : $data->getpaymentType());
                                            $detail->setflagAdvise($data->getflagAdvise());
                                            $detail->setpaymentFlagStatus("00");
                                            $detail->setbillDetails(array());
                                            $detail->setfreeTexts(array());
                                            $result->setvirtualAccountData($detail);
                                            $result->setadditionalInfo((object)array());
                                            $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', 'Success', 'Sukses', '00');

                                        } else {
                                            $status = '01';
                                            $reason = array(
                                                'english' => 'Failed, order cannot be confirmed',
                                                'indonesia' => 'Gagal, order tidak dapat dikonfirmasi',
                                            );
                                            $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', $reason['english'], $reason['indonesia'], '01');

                                            $result->setresponseCode("4002501");
                                            $result->setresponseMessage("Failed, order cannot be confirmed");
                                            $result = $this->paymentFailedResponse($data, $result, $status, $reason);
                                        }
                                    }
                                } else {
                                    $status = '01';
                                    $reason = array(
                                        'english' => 'Failed, order cannot be confirmed',
                                        'indonesia' => 'Gagal, order tidak dapat dikonfirmasi',
                                    );
                                    $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', $reason['english'], $reason['indonesia'], '01');

                                    $result->setresponseCode("4002501");
                                    $result->setresponseMessage("Failed, order cannot be confirmed");
                                    $result = $this->paymentFailedResponse($data, $result, $status, $reason);
                                }
                            } else {
                                $status = '01';
                                $reason = array(
                                    'english' => 'Invalid Amount',
                                    'indonesia' => 'Jumlah tidak valid',
                                );
                                $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', $reason['english'], $reason['indonesia'], '01');

                                $result->setresponseCode("4042513");
                                $result->setresponseMessage("Invalid Amount");
                                $result = $this->paymentFailedResponse($data, $result, $status, $reason);
                            }
                        }else{
                            $status = '01';
                            $reason = array(
                                'english' => 'Paid Bill',
                                'indonesia' => 'Tagihan telah di bayar',
                            );
                            $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', $reason['english'], $reason['indonesia'], '01');
                            $result->setresponseCode("4042514");
                            $result->setresponseMessage("Paid Bill");
                            $result = $this->paymentFailedResponse($data, $result, $status, $reason);
                        }
                    }

                } else {
                    $status = '01';
                    $reason = array(
                        'english' => 'Invalid Field Format virtualAccountNo',
                        'indonesia' => 'Format tidak valid virtualAccountNo',
                    );
                    $logExternal = $this->insertLogExternal($external_id, $data->getpaymentRequestId(), 'payments', $reason['english'], $reason['indonesia'], '01');

                    $result->setresponseCode('4002501');
                    $result->setresponseMessage('Invalid Field Format virtualAccountNo');
                    $result = $this->paymentFailedResponse($data, $result, $status, $reason);
                }
            }
        }
        $validateExternal = $this->ExternalID($external_id, $data->getpaymentRequestId(), 'payments');

        if ($validateExternal['success'] == false) {
            if ($validateExternal['count'] > 0) {
                $status = $validateExternal['code'];
                $reason = array(
                    'english' => $validateExternal['english'],
                    'indonesia' => $validateExternal['indonesia'],
                );

                $result->setresponseCode($validateExternal['error_code']);
                $result->setresponseMessage($validateExternal['message']['english']);
                $result = $this->paymentFailedResponse($data, $result, $status, $reason);
            }
        }
        return $result;
    }

}
