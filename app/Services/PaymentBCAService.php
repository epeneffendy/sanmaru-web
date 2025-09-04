<?php

namespace App\Services;

use App\Models\PaymentApiLog;
use App\Models\PaymentBca;
use App\Models\PaymentBcaBillDetailResponse;
use App\Models\PaymentBcaBillRequest;
use App\Models\PaymentBcaBillResponse;
use App\Models\PaymentBcaInvocationDetailResponse;
use App\Models\PaymentBcaInvocationRequest;
use App\Models\PaymentBcaInvocationResponse;
use App\Models\PPDBUser;
use App\Models\ProductOrder;
use App\Models\ProductOrderPayment;
use App\Models\TokenApiLog;
use App\Models\Unit;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleLogMiddleware\LogMiddleware;
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

    public function __construct(ProductOrderService $productOrderService)
    {
        $this->debug = env('PAYMENT_BCA_DEBUG', false);
        $this->productOrderService = $productOrderService;
        $this->settings = array(
            'apiUrl' => env('PAYMENT_BCA_API_URL', 'https://devapi.klikbca.com:9443/'),
            'clientId' => env('PAYMENT_BCA_CLIENT_ID', 'e305a76a-78d3-4f92-b734-c23ae58c97d8'),
            'clientSecret' => env('PAYMENT_BCA_CLIENT_SECRET', '04031743-9645-4bc7-84e5-c8943618c2c8'),
            'companyId' => env('PAYMENT_BCA_API_CORP_ID', 'uatcorp001'),
            'apiKey' => env('PAYMENT_BCA_API_KEY', 'a16c5bb4-49d1-4a12-9194-db3df367d893'),
            'apiSecret' => env('PAYMENT_BCA_API_SECRET', '2ad77de8-7f0e-4379-bce5-71d70529a611'),
        );
        $this->defaultHeaders = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Origin' => request()->getHost(),
            'X-BCA-Key' => $this->settings['apiKey'],
        ];
        $configs = [
            'base_uri' => $this->settings['apiUrl'],
            'headers' => $this->defaultHeaders,
        ];
        if (env('APP_DEBUG', false)) {
            $this->settings['apiUrl'] = 'https://devapi.klikbca.com:9443/';
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
            $request = $this->client->post('api/oauth/token', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->settings['clientId'] . ':' . $this->settings['clientSecret']),
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                ],
            ]);
            $response = json_decode($request->getBody()->getContents());

            if ($response->access_token) {
                $token->token = $response;
                $token->expires_at = Carbon::now()->addSeconds($token->token->expires_in);
                $expires_at = Carbon::now()
                    ->addSeconds(3600)
                    ->format('Y-m-d H:i:s');

                $this->logToken($token->token->access_token, $expires_at);
                session()->put('payment_bca_token', $token);
            }
        } catch (RequestException $e) {
            $token = null;
        }
//        }

        return $token;
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

    public function inquiryStatus($auth_token, $data)
    {
        $url = '/va/payments';
        if (!isset($data['CompanyCode']))
            $data['CompanyCode'] = $this->settings['companyId'];
        ksort($data);
        $relativeUrl = $url . "?" . http_build_query($data);
        $isoTime = date('o-m-d') . 'T' . date('H:i:s') . '.' . substr(date('u'), 0, 3) . date('P');
        $signature = $this->generateSignature('GET', $relativeUrl, $auth_token->access_token, $this->settings['apiSecret'], $isoTime, "");

        try {
            $request = $this->client->get($url, [
                'headers' => array_merge(
                    $this->defaultHeaders,
                    [
                        'Authorization' => $auth_token->token_type . ' ' . $auth_token->access_token,
                        'X-BCA-Key' => $this->settings['apiKey'],
                        'X-BCA-Timestamp' => $isoTime,
                        'X-BCA-Signature' => $signature,
                    ]
                ),
                'query' => $data,
            ]);

            $response = json_decode($request->getBody()->getContents());
            $this->log('va/status', $data, $response);
        } catch (RequestException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            $this->log('va/status', $data, $e->getResponse()->getBody()->getContents());
        }

        return $response;
    }

    public function getPpdbBills($ppdbId, $unitId, PaymentBcaBillRequest $data, PaymentBcaBillResponse $result)
    {
        $unit = Unit::where('unit_code', $unitId)->first();
        if (!$unit) {
            $result->setInquiryStatus('01');
            $result->setInquiryReason(array(
                'Indonesian' => 'Unit tidak ditemukan',
                'English' => 'Unit not found',
            ));
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
                $result->setInquiryStatus('01');
                $result->setInquiryReason(array(
                    'Indonesian' => 'PPDB tidak ditemukan',
                    'English' => 'PPDB not found',
                ));
            } else {
                $result->setCustomerName($ppdbUser->name);
                $result->setInquiryStatus('01');
                $result->setInquiryReason(array(
                    'Indonesian' => 'Tagihan anda telah lunas',
                    'English' => 'Your bill has been paid',
                ));
                $orders = $ppdbUser->orders;
                if ($orders->count() === 1) {
                    $reason = array();
                    $totalAmount = 0;
                    $orders->each(function (ProductOrder $order) use (&$reason, &$totalAmount) {
                        $reason = array(
//                            'Indonesian' => 'Pembayaran invoice ' . $order->invoice_no,
                            'Indonesian' => 'Sukses',
//                            'English' => 'Payment for invoice ' . $order->invoice_no,
                            'English' => 'Success',
                        );
//                        $order->productOrderDetails->each(function ($productOrderDetail) use (&$totalAmount) {
//                            $totalAmount += $productOrderDetail->total_price;
//                        });
                        $totalAmount = $order->total_payment;
                    });
                    $result->setCustomerName($ppdbUser->name);
                    $result->setInquiryStatus('00');
                    $result->setInquiryReason(array(
                        'Indonesian' => 'Sukses',
                        'English' => 'Success',
                    ));
                    $result->setInquiryReason($reason);
                    $result->setSubCompany('00000');
                    $result->setTotalAmount(number_format($totalAmount, 2, '.', ''));
                } else if ($orders->count() > 0) {
                    $detailBills = collect();
                    $totalAmount = 0;
                    $orders->each(function (ProductOrder $order) use ($detailBills, &$totalAmount) {
                        $bill = new PaymentBcaBillDetailResponse();
                        $bill->setBillSubCompany('00000');
                        $bill->setBillNumber($order->invoice_no);
                        $bill->setBillDescription(array(
                            'Indonesian' => 'Pembelian invoice ' . $order->invoice_no,
                            'English' => 'Purchase invoice ' . $order->invoice_no,
                        ));
                        $billAmount = 0;
                        $order_amount = $order->total_payment;
                        $order->productOrderDetails->each(function ($productOrderDetail) use (&$billAmount, &$order_amount) {
                            $billAmount = $order_amount;
                        });
                        $bill->setBillAmount(number_format($billAmount, 2, '.', ''));
                        $detailBills->push($bill->toArray());
                        $totalAmount += $order->total_payment;
                    });
                    $result->setCustomerName($ppdbUser->name);
                    $result->setInquiryStatus('00');
                    $result->setInquiryReason(array(
                        'Indonesian' => 'Sukses',
                        'English' => 'Success',
                    ));
                    $result->setSubCompany('00000');
                    if ($totalAmount > 0) {
                        $result->setDetailBills($detailBills->all());
                    }
                    $result->setTotalAmount(number_format($totalAmount, 2, '.', ''));
                };
            }
        }
        return $result;
    }

    public function flagPaymentPpdb($ppdbId, $unitId, PaymentBcaInvocationRequest $data, PaymentBcaInvocationResponse $result)
    {
        $unit = Unit::where('unit_code', $unitId)->first();

        if (!$unit) {
            $result->setPaymentFlagStatus('01');
            $result->setPaymentFlagReason(array(
                'Indonesian' => 'Unit tidak ditemukan',
                'English' => 'Unit not found',
            ));
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
                $result->setPaymentFlagStatus('01');
                $result->setPaymentFlagReason(array(
                    'Indonesian' => 'PPDB tidak ditemukan',
                    'English' => 'PPDB not found',
                ));
            } else {

                $singleTransaction = true;
                if ($data->getDetailBills()->count() > 0) {
                    $singleTransaction = false;
                    if (empty($data->getDetailBills()[0])) {
                        $singleTransaction = true;
                    }
                }
                $totalAmount = $this->totalAmount($ppdbId);
                if ($singleTransaction) {
                    $order = $ppdbUser->orders->first();
                    if ($order) {
                        $totalAmount = $this->totalAmount($ppdbId);
                        if (((substr($totalAmount, 0, -3)) == (substr($data->getTotalAmount(), 0, -3))) && ((substr($totalAmount, 0, -3)) == (substr($data->getPaidAmount(), 0, -3)))) {
                            $markAsFail = false;
                            $confirmed = $this->debug || $this->productOrderService->confirmPayment($order->id, $ppdbUser->user);
                            if ($confirmed || ($order->status == 'confirmed' && $data->getFlagAdvice() == 'Y')) {
                                $result->setPaymentFlagStatus('00');
                                $result->setPaymentFlagReason(array(
                                    'Indonesian' => 'Sukses',
                                    'English' => 'Success',
                                ));
                                $this->addPaymentBca($order, $data, [
                                    'status' => '00',
                                    'sub_company' => $data->getSubCompany(),
                                    'bill_number' => $order->invoice_no,
                                    'reference' => $data->getReference(),
                                ]);
                            } else {
                                $result->setPaymentFlagStatus('01');
                                $result->setPaymentFlagReason(array(
                                    'Indonesian' => 'Gagal, order tidak dapat dikonfirmasi',
                                    'English' => 'Failed, order cannot be confirmed',
                                ));
                            }
                        } else {
                            $result->setPaymentFlagStatus('01');
                            $result->setPaymentFlagReason(array(
                                'Indonesian' => 'Nominal tidak sesuai dengan tagihan',
                                'English' => 'Paid amount does not match the bill',
                            ));
                        }
                    } else {
                        $result->setCustomerName($ppdbUser->name);
                        $result->setPaymentFlagStatus('01');
                        $result->setPaymentFlagReason(array(
                            'Indonesian' => 'Tagihan anda telah lunas',
                            'English' => 'Your bill has been paid',
                        ));
                        $result->setPaidAmount('0.00');
                        $result->setTotalAmount('0.00');
                    }
                } else {
                    if ($data->getDetailBills()->count() > 0) {
                        $result->setPaymentFlagStatus('00');
                        $result->setPaymentFlagReason(array(
                            'Indonesian' => 'Sukses',
                            'English' => 'Success',
                        ));
                        $data->getDetailBills()->each(function ($bill) use (&$result, $ppdbUser, $unit, $data, $ppdbId) {
                            $order = null;
                            $ppdbUser->orders->each(function (ProductOrder $_order) use ($bill, &$order) {
                                if ($_order->invoice_no == $bill['BillNumber']) {
                                    $order = $_order;
                                }
                            });
                            if (isset($order)) {
                                $totalAmount = $this->totalAmount($ppdbId);
                                if ((($totalAmount) == (substr($data->getTotalAmount(), 0, -3))) && ($totalAmount) == (substr($data->getPaidAmount(), 0, -3))) {
                                    if ($data->getFlagAdvice() == 'N') {
                                        $confirmed = $this->debug || $this->productOrderService->confirmPayment($order->id, $ppdbUser->user);
                                        $status = '00';
                                        $reason = array(
                                            'Indonesian' => 'Sukses',
                                            'English' => 'Success',
                                        );
                                        if (!$confirmed) {
                                            $status = '01';
                                            $reason = array(
                                                'Indonesian' => 'Gagal',
                                                'English' => 'Failed',
                                            );
                                        }
                                        $result->getDetailBills()->map(function (PaymentBcaInvocationDetailResponse $bill) use ($reason, $status, $order) {
                                            if ($bill->getBillNumber() == $order->invoice_no) {
                                                $bill->setStatus($status);
                                                $bill->setReason($reason);
                                            }
                                            return $bill;
                                        });
                                    } else {
                                        $confirmed = $this->debug || $this->productOrderService->confirmPayment($order->id, $ppdbUser->user);
                                        $status = '00';
                                        $reason = array(
                                            'Indonesian' => 'Sukses',
                                            'English' => 'Success',
                                        );
                                        if (!$confirmed && $order->status == 'confirmed') {
                                            $status = '01';
                                            $reason = array(
                                                'Indonesian' => 'Gagal, order tidak dapat dikonfirmasi',
                                                'English' => 'Failed, order cannot be confirmed',
                                            );
                                        }
                                        $result->getDetailBills()->map(function (PaymentBcaInvocationDetailResponse $bill) use ($reason, $status, $order) {
                                            if ($bill->getBillNumber() == $order->invoice_no) {
                                                $bill->setStatus($status);
                                                $bill->setReason($reason);
                                            }
                                            return $bill;
                                        });
                                    }
                                }else{
                                    $result->setPaymentFlagStatus('01');
                                    $result->setPaymentFlagReason(array(
                                        'Indonesian' => 'Nominal tidak sesuai dengan tagihan',
                                        'English' => 'Paid amount does not match the bill',
                                    ));
                                    $status = '01';
                                    $reason = array(
                                        'Indonesian' => 'Nominal tidak sesuai dengan tagihan',
                                        'English' => 'Paid amount does not match the bill',
                                    );

                                    $result->getDetailBills()->map(function (PaymentBcaInvocationDetailResponse $bill) use ($reason, $status) {
                                        $bill->setStatus($status);
                                        $bill->setReason($reason);
                                        return $bill;
                                    });
                                }
                            } else {
                                $result->setPaidAmount('0.00');
                                $result->setTotalAmount('0.00');
                                $result->setPaymentFlagStatus('01');
                                $result->setPaymentFlagReason(array(
                                    'Indonesian' => 'Tagihan anda telah lunas',
                                    'English' => 'Your bill has been paid',
                                ));
                                $status = '01';
                                $reason = array(
                                    'Indonesian' => 'Tagihan anda telah lunas',
                                    'English' => 'Your bill has been paid',
                                );

                                $result->getDetailBills()->map(function (PaymentBcaInvocationDetailResponse $bill) use ($reason, $status) {
                                    $bill->setStatus($status);
                                    $bill->setReason($reason);
                                    return $bill;
                                });
                            }
                        });
                    }
                }

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
        return $totalAmount;
    }
}
