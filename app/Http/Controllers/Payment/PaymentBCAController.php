<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentBcaBillRequest;
use App\Models\PaymentBcaBillResponse;
use App\Models\PaymentBcaInvocationFailedResponse;
use App\Models\PaymentBcaInvocationRequest;
use App\Models\PaymentBcaInvocationResponse;
use App\Models\PPDBUser;
use App\Models\ProductOrder;
use App\Models\TokenApiLog;
use App\Services\PaymentBCAService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentBCAController extends Controller
{

    public function authToken(PaymentBCAService $paymentBCAService, Request $request)
    {
        $credential = $request->header('Authorization');
        if (!empty($credential)) {
            $header = explode(' ', $credential);
            $content = $request->header('Content-Type');
            $header_type = $header[0];
            $header_auth = $header[1];
            $auth_secret = 'ZTMwNWE3NmEtNzhkMy00ZjkyLWI3MzQtYzIzYWU1OGM5N2Q4OjA0MDMxNzQzLTk2NDUtNGJjNy04NGU1LWM4OTQzNjE4YzJjOA==';

            if (($header_type == 'Basic') && ($content == 'application/x-www-form-urlencoded')) {
                if ($header_auth == $auth_secret) {
                    $token = $paymentBCAService->getAuthToken();
                    if ($token) {
                        return response()->json($token->token, 200);
                    } else {
                        return response()->json(['error' => 'Failed to get token'], 500);
                    }
                } else {
                    $response = [
                        'ErrorCode' => 'ESB-14-001',
                        'ErrorMessage' => [
                            'Indonesian' => 'HMAC tidak cocok',
                            'English' => 'HMAC mismatch'
                        ]
                    ];
                    return response()->json($response);
                }
            } else {
                $response = [
                    'ErrorCode' => 'ESB-14-009',
                    'ErrorMessage' => [
                        'Indonesian' => 'Tidak Berhak',
                        'English' => 'Unauthorized'
                    ]
                ];
                return response()->json($response);
            }
        } else {
            $response = [
                'ErrorCode' => 'ESB-14-009',
                'ErrorMessage' => [
                    'Indonesian' => 'Tidak Berhak',
                    'English' => 'Unauthorized'
                ]
            ];
            return response()->json($response);
        }
    }

    public function inquiryStatus(Request $request, PaymentBCAService $paymentBCAService)
    {
        $validator = validator($request->all(), [
            'CompanyCode' => ['string'],
            'CustomerNumber' => ['required_without:RequestID', 'string', 'max:18'],
            'RequestID' => ['required_without:CustomerNumber', 'string', 'max:30'],
        ], [], [
            'CompanyCode' => 'Company Code',
            'CustomerNumber' => 'Customer Number',
            'RequestID' => 'Request ID',
        ]);
        try {
            $validator->validate();
            $params = $validator->validated();
            $token = $paymentBCAService->getAuthToken();
            return response()->json($paymentBCAService->inquiryStatus($token->token, $params));

        } catch (ValidationException $e) {
            return response()
                ->json([
                    'error' => $validator->errors(),
                    'code' => 400
                ], 400);
        } catch (\Exception $e) {
            return response()
                ->json([
                    'error' => $e->getMessage(),
                    'code' => 400
                ], 400);
        }
    }

    public function inquiryList(Request $request, PaymentBCAService $paymentBCAService)
    {
        $companyCode = env('PAYMENT_BCA_API_CORP_ID', 'uatcorp001');
        $bcaKey = env('PAYMENT_BCA_API_KEY');
        $validator = validator($request->all(), [
            'CompanyCode' => ['required', 'string', 'in:' . $companyCode],
            'CustomerNumber' => ['required', 'string', 'max:18'],
            'RequestID' => ['required', 'string', 'max:30'],
            'ChannelType' => ['required', 'string', 'size:4'],
            'TransactionDate' => ['required', 'date_format:d/m/Y H:i:s', 'size:19'],
//            'AdditionalData' => ['string', 'max:999'],
        ], [], [
            'CompanyCode' => 'Company Code',
            'CustomerNumber' => 'Customer Number',
            'RequestID' => 'Request ID',
            'ChannelType' => 'Channel Type',
            'TransactionDate' => 'Transaction Date',
        ]);
        $headerToken = $request->bearerToken();
        $getToken = $this->authenticationToken($headerToken);
        if ($getToken) {

            $validateRequest = $this->validateRequest($request->all(), 'bills');
            if ($validateRequest) {
                $data = new PaymentBcaBillRequest($request->all());
                $result = new PaymentBcaBillResponse($request->all());
                try {
                    $validator->validate();
                    if ($result->getCompanyCode() !== env('PAYMENT_BCA_API_CORP_ID')) {
                        $result->setInquiryStatus('01');
                        $result->setInquiryReason(array(
                            'Indonesian' => 'Company code tidak sesuai',
                            'English' => 'Invalid company code',
                        ));
                        $response = $result->toArray();
                        $paymentBCAService->log('va/bills', $request->toArray(), $response);
                        return response()->json($response);
                    } else {
                        $unitId = substr($data->getCustomerNumber(), 0, 2);
                        $paymentCode = substr($data->getCustomerNumber(), 2, 2);
                        $orderId = substr($data->getCustomerNumber(), 4);
                        switch ($paymentCode) {
                            case '08':
                                $data = $paymentBCAService->getPpdbBills($orderId, $unitId, $data, $result);
                                break;
                            default:
                                $result->setInquiryStatus('01');
                                $result->setInquiryReason(array(
                                    'Indonesian' => 'Jenis pembayaran salah',
                                    'English' => 'Invalid payment type',
                                ));
                                $response = $result->toArray();
                                $paymentBCAService->log('va/bills', $request->toArray(), $response);
                                return response()->json($response);
                                break;
                        }
                    }
                    $response = $data->toArray();
                    $response['AdditionalData'] = '';
                    $paymentBCAService->log('va/bills', $request->toArray(), $response);
                    $this->destroyToken($headerToken);
                    return response()
                        ->json($response);
                } catch (ValidationException $e) {
                    $result->setInquiryStatus('01');
                    $result->setInquiryReason(array(
                        'Indonesian' => 'Request data tidak valid.',
                        'English' => 'Invalid request data',
                    ));
                    $response = $result->toArray();
                    $response['AdditionalData'] = "";
                    $paymentBCAService->log('va/bills', $request->toArray(), $response);
                    $this->destroyToken($headerToken);
                    return response()->json($response, 400);
                } catch (\Exception $e) {
                    $result->setInquiryStatus('01');
                    $result->setInquiryReason(array(
                        'Indonesian' => 'Kesalahan pada server',
                        'English' => 'Internal server error',
                    ));
                    $response = $result->toArray();
                    $response['AdditionalData'] = "";
                    $paymentBCAService->log('va/bills', $request->toArray(), $response);
                    $this->destroyToken($headerToken);
                    return response()->json($response, 400);
                }
            } else {
                $result = new PaymentBcaBillResponse($request->all());
                $result->setInquiryStatus('01');
                $result->setInquiryReason(array(
                    'Indonesian' => 'Request data tidak valid.',
                    'English' => 'Invalid request data',
                ));
                $response = $result->toArray();
                $response['CompanyCode'] = (!empty($request->all()['CompanyCode']) ? $request->all()['CompanyCode'] : '');
                $response['CustomerNumber'] = (!empty($request->all()['CustomerNumber']) ? $request->all()['CustomerNumber'] : '');
                $response['RequestID'] = (!empty($request->all()['RequestID']) ? $request->all()['RequestID'] : '');
//            $response['ChannelType'] = (!empty($request->all()['ChannelType']) ? $request->all()['ChannelType'] : '');
//            $response['TransactionDate'] = (!empty($request->all()['TransactionDate']) ? $request->all()['TransactionDate'] : '');
                $response['AdditionalData'] = "";
                $paymentBCAService->log('va/bills', $request->toArray(), $response);
                $this->destroyToken($headerToken);
                return response()->json($response, 400);
            }
        } else {
            $response = [
                'ErrorCode' => 'ESB-14-001',
                'ErrorMessage' => [
                    'Indonesian' => 'HMAC tidak cocok',
                    'English' => 'HMAC mismatch'
                ]
            ];
            $paymentBCAService->log('va/bills', $request->toArray(), $response);
            return response()->json($response);
        }

    }

    public function paymentFlag(Request $request, PaymentBCAService $paymentBCAService)
    {
        $companyCode = env('PAYMENT_BCA_API_CORP_ID', 'uatcorp001');
        $bcaKey = env('PAYMENT_BCA_API_KEY');
        $validator = validator($request->all(), [
            'CompanyCode' => ['required', 'string', 'in:' . $companyCode],
            'CustomerNumber' => ['required', 'string', 'max:18'],
            'RequestID' => ['required', 'string', 'max:30'],
            'ChannelType' => ['required', 'string', 'size:4'],
            'CurrencyCode' => ['required', 'string', 'size:3'],
            'PaidAmount' => ['required', 'string', 'max:15'],
            'TotalAmount' => ['required', 'string', 'max:15'],
            'SubCompany' => ['required', 'string', 'max:5'],
            'TransactionDate' => ['required', 'date_format:d/m/Y H:i:s', 'size:19'],
            'Reference' => ['string', 'max:15'],
            'DetailBills' => ['array'],
            'FlagAdvice' => ['required', 'string', 'size:1', 'in:Y,N'],
//            'AdditionalData' => ['string', 'max:999'],
        ]);

        $headerToken = $request->bearerToken();
        $getToken = $this->authenticationToken($headerToken);
        $getToken = true;
        if ($getToken) {
            $validateRequest = $this->validateRequest($request->all(), 'payment');
            if ($validateRequest) {
                try {
                    $data = new PaymentBcaInvocationRequest($request->all());
                    $result = new PaymentBcaInvocationResponse($request->all());
                    dd($result);
                    $validator->validate();
                    if ($data->getCompanyCode() !== env('PAYMENT_BCA_API_CORP_ID')) {
                        $result->setPaymentFlagStatus('01');
                        $result->setPaymentFlagReason(array(
                            'Indonesian' => 'Company code tidak sesuai',
                            'English' => 'Invalid company code',
                        ));
                    } else {
                        $unitId = substr($data->getCustomerNumber(), 0, 2);
                        $paymentCode = substr($data->getCustomerNumber(), 2, 2);
                        $orderId = substr($data->getCustomerNumber(), 4);


                        switch ($paymentCode) {
                            case '08':
                                $result = $paymentBCAService->flagPaymentPpdb($orderId, $unitId, $data, $result);
                                break;
                            default:
                                $result->setPaymentFlagStatus('01');
                                $result->setPaymentFlagReason(array(
                                    'Indonesian' => 'Jenis pembayaran salah',
                                    'English' => 'Invalid payment type',
                                ));
                                break;
                        }
                    }
                    $response = $result->toArray();
                    $response['AdditionalData'] = '';
                    $paymentBCAService->log('va/payments', $request->toArray(), $response);
                    dd(response()->json($response));
                    $this->destroyToken($headerToken);
                    return response()
                        ->json($response);
                } catch (ValidationException $e) {
                    $result = new PaymentBcaInvocationFailedResponse($request->all());
                    $result->setPaymentFlagStatus('01');
                    $result->setPaymentFlagReason(array(
                        'Indonesian' => 'Request data tidak valid.',
                        'English' => 'Invalid request data',
                    ));
                    $response = $result->toArray();
                    $response['AdditionalData'] = '';
                    $paymentBCAService->log('va/payments', $request->toArray(), $response);
                    $this->destroyToken($headerToken);
                    return response()
                        ->json($response, 400);
                } catch (\Exception $e) {
                    $result = new PaymentBcaInvocationFailedResponse($request->all());
                    $result->setPaymentFlagStatus('01');
                    $result->setPaymentFlagReason(array(
                        'Indonesian' => 'Kesalahan pada server' . $e->getMessage(),
                        'English' => 'Internal server error',
                    ));
                    $response = $result->toArray();
                    $response['AdditionalData'] = '';
                    $paymentBCAService->log('va/payments', $request->toArray(), $response);
                    $this->destroyToken($headerToken);
                    return response()
                        ->json($response, 400);
                }
            } else {
                $result = new PaymentBcaInvocationResponse($request->all());
                $result->setPaymentFlagStatus('01');
                $result->setPaymentFlagReason(array(
                    'Indonesian' => 'Request data tidak valid.',
                    'English' => 'Invalid request data',
                ));
                $response = $result->toArray();
                $response['CompanyCode'] = (!empty($request->all()['CompanyCode']) ? $request->all()['CompanyCode'] : '');
                $response['CustomerNumber'] = (!empty($request->all()['CustomerNumber']) ? $request->all()['CustomerNumber'] : '');
                $response['CustomerName'] = (!empty($request->all()['CustomerName']) ? $request->all()['CustomerName'] : '');
                $response['CurrencyCode'] = (!empty($request->all()['CurrencyCode']) ? $request->all()['CurrencyCode'] : '');
                $response['PaidAmount'] = (!empty($request->all()['PaidAmount']) ? $request->all()['PaidAmount'] : '');
                $response['TotalAmount'] = (!empty($request->all()['TotalAmount']) ? $request->all()['TotalAmount'] : '');
                $response['RequestID'] = (!empty($request->all()['RequestID']) ? $request->all()['RequestID'] : '');
//                $response['SubCompany'] = (!empty($request->all()['SubCompany']) ? $request->all()['SubCompany'] : '');
//                $response['ChannelType'] = (!empty($request->all()['ChannelType']) ? $request->all()['ChannelType'] : '');
                $response['TransactionDate'] = (!empty($request->all()['TransactionDate']) ? $request->all()['TransactionDate'] : '');
                $response['AdditionalData'] = '';
                $paymentBCAService->log('va/bills', $request->toArray(), $response);
                $this->destroyToken($headerToken);
                return response()->json($response, 400);
            }
        } else {
            $response = [
                'ErrorCode' => 'ESB-14-001',
                'ErrorMessage' => [
                    'Indonesian' => 'HMAC tidak cocok',
                    'English' => 'HMAC mismatch'
                ]
            ];
            $paymentBCAService->log('va/bills', $request->toArray(), $response);
            return response()->json($response);
        }

    }

    public function authenticationToken($token)
    {
        $success = false;
        $accessToken = TokenApiLog::where('access_token', $token)->first();

        if (isset($accessToken)) {
            $dateNow = Carbon::now()->format('Y-m-d H:i:s');

            if ($accessToken->expires_at > $dateNow) {
                $success = true;
            }
        }
        return $success;
    }

    public function destroyToken($token)
    {
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');
        $token = TokenApiLog::where('access_token', $token)->delete();
        $tokenExp = TokenApiLog::where('access_token', '>', $dateNow)->delete();
    }

    public function validateRequest($param, $type)
    {

        if (empty($param['CompanyCode'])) {
            return false;
        }

        if (empty($param['CustomerNumber'])) {
            return false;
        }

        if (empty($param['RequestID'])) {
            return false;
        }

        if (empty($param['ChannelType'])) {
            return false;
        }

        if (empty($param['TransactionDate'])) {
            return false;
        }

        if ($type == 'payment') {
            if (empty($param['CustomerName'])) {
                return false;
            }

            if (empty($param['CurrencyCode'])) {
                return false;
            }

            if (empty($param['PaidAmount'])) {
                return false;
            }

            if (empty($param['TotalAmount'])) {
                return false;
            }

            if (empty($param['SubCompany'])) {
                return false;
            }

            if (empty($param['Reference'])) {
                return false;
            }
        }
        return true;
    }



}
