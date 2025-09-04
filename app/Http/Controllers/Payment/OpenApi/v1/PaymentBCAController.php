<?php

namespace App\Http\Controllers\Payment\OpenApi\v1;

use App\Http\Controllers\Controller;
use App\Models\ExternalLog;
use App\Models\OpenApi\v1\PaymentBcaBillRequest;
use App\Models\OpenApi\v1\PaymentBcaBillResponse;
use App\Models\OpenApi\v1\PaymentBcaInvocationDetailBillsResponse;
use App\Models\OpenApi\v1\PaymentBcaInvocationFailedResponse;
use App\Models\OpenApi\v1\PaymentBcaInvocationRequest;
use App\Models\OpenApi\v1\PaymentBcaInvocationResponse;
use App\Models\OpenApi\v1\PaymentVirtualAccountDataFailedResponse;
use App\Models\OpenApi\v1\PaymentVirtualAccountDataResponse;
use App\Models\PPDBUser;
use App\Models\TokenApiLog;
use App\Services\OpenApi\v1\PaymentBCAService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class PaymentBCAController extends Controller
{

    public function authToken(PaymentBCAService $paymentBCAService, Request $request)
    {
        Log::info($request);
        $header= [
            'signature' => $request->header('x-signature'),
            'timestamps' => $request->header('x-timestamp'),
            'client' => $request->header('x-client-key')
        ];
       $validateHeader = $this->validateHeaderToken($request);
       Log::info($validateHeader);
        if ($validateHeader['success']) {
            $token = $paymentBCAService->getAccessToken($request->header('x-timestamp'));
            if ($token) {
                $response = [
                    'responseCode' => '2007300',
                    'responseMessage' => 'Successful',
                    'accessToken' => $token->token->accessToken,
                    'tokenType' => $token->token->tokenType,
                    'expiresIn' => 900,
                ];
                $paymentBCAService->log('v1.0/access-token/b2b', \GuzzleHttp\json_encode($header), $response);
                return response()->json($response, 200);
            } else {
                $response = [
                    'responseCode' => '5047300',
                    'responseMessage' => 'Timeout',
                ];
                $paymentBCAService->log('v1.0/access-token/b2b', \GuzzleHttp\json_encode($header), $response);
                return response()->json($response, 500);
            }
        } else {
            $response = [
                'responseCode' => $validateHeader['error_code'],
                'responseMessage' => $validateHeader['error_message'],
            ];
            $paymentBCAService->log('v1.0/access-token/b2b', \GuzzleHttp\json_encode($header), $response);
            return response()->json($response, $validateHeader['http_code']);
        }
    }

    public function authTokenNew(PaymentBCAService $paymentBCAService, Request $request)
    {
        $header= [
            'signature' => $request->header('x-signature'),
            'timestamps' => $request->header('x-timestamp'),
            'client' => $request->header('x-client-key')
        ];
        $validateHeader = $this->validateHeaderToken($request);
        if ($validateHeader['success']) {
            $token = $paymentBCAService->getAuthTokenBearer();
            if ($token) {
                $response = [
                    'responseCode' => '2007300',
                    'responseMessage' => 'Successful',
                    'accessToken' => $token['token'],
                    'tokenType' => $token['tokenType'],
                    'expiresIn' => '900',
                ];
                $paymentBCAService->log('v1.0/access-token/b2b', \GuzzleHttp\json_encode($header), $response);
                return response()->json($response, 200);
            } else {
                $response = [
                    'responseCode' => '5047300',
                    'responseMessage' => 'Timeout',
                ];
                $paymentBCAService->log('v1.0/access-token/b2b', \GuzzleHttp\json_encode($header), $response);
                return response()->json($response, 500);
            }
        } else {
            $response = [
                'responseCode' => $validateHeader['error_code'],
                'responseMessage' => $validateHeader['error_message'],
            ];
            $paymentBCAService->log('v1.0/access-token/b2b', \GuzzleHttp\json_encode($header), $response);
            return response()->json($response, $validateHeader['http_code']);
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
        // Test
        $partnerServiceId = env('PAYMENT_BCA_API_CORP_ID', 'uatcorp001');
        $ClientID = env('PAYMENT_BCA_CLIENT_ID');
        $ChannelID = env('PAYMENT_BCA_CHANNEL_ID');
        $bcaKey = env('PAYMENT_BCA_API_KEY');
        $relativeUrl = '/payment/v1.0/transfer-va/inquiry';

        $validator = validator($request->all(), [
            'partnerServiceId' => ['required', 'string', 'in:' . $partnerServiceId],
            'customerNo' => ['required', 'string', 'max:20'],
            'virtualAccountNo' => ['required', 'string', 'max:28'],
            'channelCode' => ['required', 'string', 'size:4'],
            'trxDateInit' => ['required', 'date_format:Y-m-d\TH:i:sP', 'size:25'],
            'language' => ['nullable', 'string', 'max:2'],
            'amount' => ['nullable', 'string'],
            'hashedSourceAccountNo' => ['nullable', 'string', 'size:32'],
            'sourceBankCode' => ['required', 'string', 'size:3'],
            'additionalInfo' => ['nullable', 'array', 'max:999'],
            'passApp' => ['nullable', 'string', 'size:64'],
            'inquiryRequestId' => ['nullable', 'string', 'max:128'],
        ], [], [
            'partnerServiceId' => 'Patner Request ID',
            'customerNo' => 'Customer Number',
            'virtualAccountNo' => 'Virtual Account',
            'channelCode' => 'Channel Code',
            'trxDateInit' => 'Date',
            'language' => 'Language',
            'amount' => 'Amount',
            'hashedSourceAccountNo' => 'Has Head Source Account',
            'sourceBankCode' => 'Source Bank Account',
            'additionalInfo' => 'Additional Info',
            'passApp' => 'Pass App',
            'inquiryRequestId' => 'Inquiry Request ID'
        ]);

        $headerToken = $request->bearerToken();
        $validateHeader = $this->validateHeader($request, $ClientID, $ChannelID, $partnerServiceId, $relativeUrl);
        if ($validateHeader['success']) {
            $this->destroyToken($headerToken);
            $data = new PaymentBcaBillRequest($request->all());
            $result = new PaymentBcaBillResponse($request->all());
            try {
                $inquiryRequestId = $data->getinquiryRequestId();
                $validateField = $this->validateField($request->all(), 'bills');
                if ($validateField['success']) {
                    $validateRequest = $this->validateRequest($request->all(), 'bills');
                    if ($validateRequest['success']) {
                        if (str_pad($data->getpartnerServiceId(), 8, " ", STR_PAD_LEFT) !== str_pad(env('PAYMENT_BCA_API_CORP_ID'), 8, " ", STR_PAD_LEFT)) {
                            $result->setresponseCode("4002401");
                            $result->setresponseMessage("Invalid Field Format partnerServiceId");
                            $reason = array(
                                'english' => 'Invalid Field Format partnerServiceId',
                                'indonesia' => 'Format tidak valid partnerServiceId',
                            );
                            $failedResponse = $this->failedResponse($data, $result, $reason);
                            $result->setvirtualAccountData($failedResponse->toArray());
                            $response = $result->toArray();
                            $paymentBCAService->log('v1.0/transfer-va/inquiry', $request->toArray(), $response);
                            return response()->json($response);
                        } else {
                            $unitId = substr($data->getcustomerNo(), 0, 2);
                            $paymentCode = substr($data->getcustomerNo(), 2, 2);
                            $orderId = substr($data->getcustomerNo(), 4);
                            switch ($paymentCode) {
                                case '08':
                                    $data = $paymentBCAService->getPpdbBills($orderId, $unitId, $data, $result);
                                    break;
                                case '07':
                                    $data = $paymentBCAService->getPpdbRegistration($orderId, $unitId, $data, $result);
                                    break;
                                default:
                                    $result->setresponseCode("4042412");
                                    $result->setresponseMessage("Invalid Bill/Virtual Account [Not Found]");
                                    $reason = array(
                                        'english' => 'Invalid Bill/Virtual Account [Not Found]',
                                        'indonesia' => 'Tagihan/Akun Virtual Tidak Valid [Tidak Ditemukan]',
                                    );
                                    $failedResponse = $this->failedResponse($data, $result, $reason);
                                    $result->setvirtualAccountData($failedResponse->toArray());
                                    $response = $result->toArray();
                                    $paymentBCAService->log('v1.0/transfer-va/inquiry', $request->toArray(), $response);
                                    return response()->json($response);
                                    break;
                            }
                        }
                        $response = $data->toArray();
                        $validateExternal = $this->logExternalID($request->header('x-external-id'), $inquiryRequestId, 'bills');
                        if ($validateExternal['success'] == false) {
                            $response['responseCode'] = $validateExternal['error_code'];
                            $response['responseMessage'] = $validateExternal['message']['english'];
                        }

                        $paymentBCAService->log('v1.0/transfer-va/inquiry', $request->toArray(), $response);
                        return response()
                            ->json($response);

                    } else {
                        $error_code = '4002402';
                        if (!empty($validateRequest['error_code'])) {
                            $error_code = $validateRequest['error_code'];
                        }

                        $result->setresponseCode($error_code);
                        $result->setresponseMessage($validateRequest['message']['english']);
                        $reason = array(
                            'english' => $validateRequest['message']['english'],
                            'indonesia' => $validateRequest['message']['indonesia'],
                        );
                        $failedResponse = $this->failedResponse($data, $result, $reason);
                        $result->setvirtualAccountData($failedResponse->toArray());

                        $response = $result->toArray();
                        $paymentBCAService->log('v1.0/transfer-va/inquiry', $request->toArray(), $response);
                        return response()
                            ->json($response);
                    }
                } else {
                    $result->setresponseCode($validateField['error_code']);
                    $result->setresponseMessage($validateField['message']['english']);
                    $reason = array(
                        'english' => $validateField['message']['english'],
                        'indonesia' => $validateField['message']['indonesia'],
                    );
                    $failedResponse = $this->failedResponse($data, $result, $reason);
                    $result->setvirtualAccountData($failedResponse->toArray());

                    $response = $result->toArray();
                    $paymentBCAService->log('v1.0/transfer-va/inquiry', $request->toArray(), $response);
                    return response()
                        ->json($response);
                }

            } catch (ValidationException $e) {
                $result->setresponseCode("4002402");
                $result->setresponseMessage("Invalid request data");
                $reason = array(
                    'english' => 'Invalid request data',
                    'indonesia' => 'Request data tidak valid',
                );
                $failedResponse = $this->failedResponse($data, $result, $reason);
                $result->setvirtualAccountData($failedResponse->toArray());
                $response = $result->toArray();
                $paymentBCAService->log('v1.0/transfer-va/inquiry', $request->toArray(), $response);
                return response()->json($response, 400);
            } catch (\Exception $e) {
                $result->setresponseCode("4002402");
                $result->setresponseMessage("Internal server error");
                $reason = array(
                    'english' => 'Internal server error',
                    'indonesia' => 'Kesalahan pada server',
                );
                $failedResponse = $this->failedResponse($data, $result, $reason);
                $result->setvirtualAccountData($failedResponse->toArray());

                $response = $result->toArray();
                $paymentBCAService->log('v1.0/transfer-va/inquiry', $request->toArray(), $response);
                return response()->json($response, 400);
            }
        } else {
            $response = [
                'responseCode' => $validateHeader['error_code'],
                'responseMessage' => $validateHeader['error_message'],
            ];
            $this->destroyToken($headerToken);
            return response()->json($response);
        }
    }

    public function paymentFlag(Request $request, PaymentBCAService $paymentBCAService)
    {
        $partnerServiceId = env('PAYMENT_BCA_API_CORP_ID', 'uatcorp001');
        $ClientID = env('PAYMENT_BCA_CLIENT_ID');
        $ChannelID = env('PAYMENT_BCA_CHANNEL_ID');
        $bcaKey = env('PAYMENT_BCA_API_KEY');
        $relativeUrl = '/payment/v1.0/transfer-va/payment';

        $headerToken = $request->bearerToken();
        $validator = validator($request->all(), [
            'partnerServiceId' => ['required', 'string', 'in:' . $partnerServiceId],
            'customerNo' => ['required', 'string', 'max:20'],
            'virtualAccountNo' => ['required', 'string', 'max:28'],
            'virtualAccountName' => ['required', 'string', 'max:255'],
            'virtualAccountEmail' => ['nullable', 'string', 'max:255'],
            'virtualAccountPhone' => ['nullable', 'string', 'max:30'],
            'trxId' => ['nullable', 'string', 'size:64'],
            'paymentRequestId' => ['required', 'string', 'size:128'],
            'channelCode' => ['required', 'numeric', 'max:4'],
            'hashedSourceAccountNo' => ['nullable', 'string', 'max:32'],
            'sourceBankCode' => ['nullable', 'string', 'max:3'],
            'paidAmount' => ['nullable', 'string'],
            'cumulativePaymentAmount' => ['nullable', 'string'],
            'paidBills' => ['nullable', 'string', 'max:6'],
            'totalAmount' => ['nullable', 'string'],
            'trxDateTime' => ['required', 'date_format:Y-m-d\TH:i:sP', 'size:25'],
            'referenceNo' => ['string', 'max:64'],
            'journalNum' => ['string', 'max:6'],
            'paymentType' => ['string', 'max:1'],
            'flagAdvise' => ['required', 'string', 'size:1', 'in:Y,N'],
            'subCompany' => ['string', 'max:5'],
            'billDetails' => ['array'],
            'freeTexts' => ['array'],
            'additionalInfo' => ['nullable', 'string'],
        ]);
        try {
            $data = new PaymentBcaInvocationRequest($request->all());
            $result = new PaymentBcaInvocationResponse($request->all());
            $validateHeader = $this->validateHeader($request, $ClientID, $ChannelID, $partnerServiceId, $relativeUrl);
            if ($validateHeader['success']) {
                $this->destroyToken($headerToken);
                $validateField = $this->validateField($request->all(), 'payment');
                if ($validateField['success']) {
                    $validateRequest = $this->validateRequest($request->all(), 'payment');
                    if ($validateRequest['success']) {
                        if ($data->getpartnerServiceId() !== env('PAYMENT_BCA_API_CORP_ID')) {
                            $status = '01';
                            $reason = array(
                                'english' => 'Invalid Field Format partnerServiceId',
                                'indonesia' => 'Format tidak valid partnerServiceId',
                            );

                            $result->setresponseCode("4002501");
                            $result->setresponseMessage("Invalid Field Format partnerServiceId");
                            $result = $paymentBCAService->paymentFailedResponse($data, $result, $status, $reason);
                            $response = $result->toArray();
                            $paymentBCAService->log('v1.0/transfer-va/payment', $request->toArray(), $response);
                            return response()->json($response);
                        } else {
                            $unitId = substr($data->getcustomerNo(), 0, 2);
                            $paymentCode = substr($data->getcustomerNo(), 2, 2);
                            $orderId = substr($data->getcustomerNo(), 4);
                            switch ($paymentCode) {
                                case '08':
                                    $result = $paymentBCAService->flagPaymentPpdb($orderId, $unitId, $data, $result, $request->header('x-external-id'));
                                    break;
                                case '07':
                                    $result = $paymentBCAService->flagPaymentRegistration($orderId, $unitId, $data, $result, $request->header('x-external-id'));
                                    break;
                                default:
                                    $result->setPaymentFlagStatus('01');
                                    $result->setPaymentFlagReason(array(
                                        'Indonesian' => 'Tagihan tidak ditemukan',
                                        'English' => 'Bill not found',
                                    ));
                                    break;
                            }
                        }
                        $response = $result->toArray();
                        $paymentBCAService->log('v1.0/transfer-va/payment', $request->toArray(), $response);
                        return response()->json($response);
                    } else {
                        $error_code = '4002502';
                        if (!empty($validateRequest['error_code'])) {
                            $error_code = $validateRequest['error_code'];
                        }
                        $status = '01';
                        $reason = array(
                            'english' => $validateRequest['message']['english'],
                            'indonesia' => $validateRequest['message']['indonesia'],
                        );
                        $result->setresponseCode($error_code);
                        $result->setresponseMessage($validateRequest['message']['english']);
                        $result = $paymentBCAService->paymentFailedResponse($data, $result, $status, $reason);
                        $response = $result->toArray();
                        $paymentBCAService->log('v1.0/transfer-va/payment', $request->toArray(), $response);
                        return response()->json($response);
                    }
                } else {
                    $status = '01';
                    $reason = array(
                        'english' => $validateField['message']['english'],
                        'indonesia' => $validateField['message']['indonesia'],
                    );
                    $result->setresponseCode($validateField['error_code']);
                    $result->setresponseMessage($validateField['message']['english']);
                    $result = $paymentBCAService->paymentFailedResponse($data, $result, $status, $reason);
                    $response = $result->toArray();
                    $paymentBCAService->log('v1.0/transfer-va/payment', $request->toArray(), $response);
                    return response()->json($response);
                }
            } else {
                $response = [
                    'responseCode' => $validateHeader['error_code'],
                    'responseMessage' => $validateHeader['error_message'],
                ];
                $this->destroyToken($headerToken);
                return response()->json($response);
            }
        } catch (ValidationException $e) {
            $status = '01';
            $reason = array(
                'english' => 'Invalid request data',
                'indonesia' => 'Request data tidak valid',
            );
            $result->setresponseCode("4002400");
            $result->setresponseMessage("Invalid request data");
            $result = $paymentBCAService->paymentFailedResponse($data, $result, $status, $reason);
            $response = $result->toArray();
            $paymentBCAService->log('v1.0/transfer-va/payment', $request->toArray(), $response);
            return response()
                ->json($response, 400);
        } catch (\Exception $e) {
            $status = '01';
            $reason = array(
                'english' => 'Internal server error',
                'indonesia' => 'Kesalahan pada server' . $e->getMessage(),
            );
            $result->setresponseCode("4002400");
            $result->setresponseMessage("Internal server error");
            $result = $paymentBCAService->paymentFailedResponse($data, $result, $status, $reason);
            $response = $result->toArray();
            $paymentBCAService->log('v1.0/transfer-va/payment', $request->toArray(), $response);
            return response()->json($response, 400);
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
        if (env('APP_ENV') == 'local') {
            $success = true;
        }
        return $success;
    }

    public function destroyToken($token)
    {
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');
//        $token = TokenApiLog::where('access_token', $token)->delete();
        $tokenExp = TokenApiLog::where('expires_at', '<', $dateNow)->delete();
    }

    public function validateRequest($param, $type)
    {
        $success = true;
        $message = array(
            'indonesia' => '',
            'english' => ''
        );
        $error_code = '';

        if (empty($param['partnerServiceId'])) {
            $message = array(
                'indonesia' => 'Field wajib PatnerServiceId kosong',
                'english' => 'Missing mandatory field PatnerServiceId'
            );
            return ['success' => false, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
        }

        if (empty($param['customerNo'])) {
            $message = array(
                'indonesia' => 'Field wajib customerNo kosong',
                'english' => 'Missing mandatory field customerNo'
            );
            return ['success' => false, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
        }

        if (empty($param['virtualAccountNo'])) {
            $message = array(
                'indonesia' => 'Field wajib virtualAccountNo kosong',
                'english' => 'Missing mandatory field virtualAccountNo'
            );
            return ['success' => false, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
        }

        if (empty($param['channelCode'])) {
            $message = array(
                'indonesia' => 'Field wajib channelCode kosong',
                'english' => 'Missing mandatory field channelCode'
            );
            return ['success' => false, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
        }
        if ($type == 'bills') {
            if (empty($param['trxDateInit'])) {
                $message = array(
                    'indonesia' => 'Field wajib trxDateInit kosong',
                    'english' => 'Missing mandatory field trxDateInit'
                );
                return ['success' => false, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
            }

            if (empty($param['inquiryRequestId'])) {
                $message = array(
                    'indonesia' => 'Field wajib inquiryRequestId kosong',
                    'english' => 'Missing mandatory field inquiryRequestId'
                );
                return ['success' => false, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
            }

            if (!empty($param['trxDateInit'])) {
                $validateTimestap = $this->validateDate($param['trxDateInit']);
                if (!$validateTimestap) {
                    $message = array(
                        'indonesia' => 'Format trxDateInit tidak sesuai',
                        'english' => 'Invalid Field Format trxDateInit'
                    );
                    return ['success' => false, 'message' => $message, 'error_code' => '4002401', 'type' => $type];
                }
            }
        }


        if ($type == 'payment') {
            if (empty($param['virtualAccountName'])) {
                $message = array(
                    'indonesia' => 'Field wajib virtualAccountName kosong',
                    'english' => 'Missing mandatory field virtualAccountName'
                );
                return ['success' => false, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
            }

            if (empty($param['paidAmount']['value'])) {
                $message = array(
                    'indonesia' => 'Field wajib paidAmount[value] kosong',
                    'english' => 'Missing mandatory field paidAmount[value]'
                );
                return ['success' => false, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
            }

            if (empty($param['paidAmount']['currency'])) {
                $message = array(
                    'indonesia' => 'Field wajib paidAmount[currency] kosong',
                    'english' => 'Missing mandatory field paidAmount[currency]'
                );
                return ['success' => false, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
            }

            if (empty($param['totalAmount']['value'])) {
                $message = array(
                    'indonesia' => 'Field wajib totalAmount[value] kosong',
                    'english' => 'Missing mandatory field totalAmount[value]'
                );
                return ['success' => false, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
            }

            if (empty($param['totalAmount']['currency'])) {
                $message = array(
                    'indonesia' => 'Field wajib totalAmount[currency] kosong',
                    'english' => 'Missing mandatory field totalAmount[currency]'
                );
                return ['success' => false, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
            }

            if (empty($param['subCompany'])) {
                $message = array(
                    'indonesia' => 'Field wajib subCompany kosong',
                    'english' => 'Missing mandatory subCompanypaidAmount'
                );
                return ['success' => false, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
            }

            if (empty($param['referenceNo'])) {
                $message = array(
                    'indonesia' => 'Field wajib referenceNo kosong',
                    'english' => 'Missing mandatory field referenceNo'
                );
                return ['success' => false, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
            }

            if (empty($param['sourceBankCode'])) {
                $message = array(
                    'indonesia' => 'Field wajib sourceBankCode kosong',
                    'english' => 'Missing mandatory field sourceBankCode'
                );
                return ['success' => false, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
            }

            if (empty($param['trxDateTime'])) {
                $message = array(
                    'indonesia' => 'Field wajib trxDateTime kosong',
                    'english' => 'Missing mandatory field trxDateTime'
                );
                return ['success' => false, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
            }

            if (!empty($param['trxDateTime'])) {
                $validateTimestap = $this->validateDate($param['trxDateTime']);
                if (!$validateTimestap) {
                    $message = array(
                        'indonesia' => 'Format trxDateTime tidak sesuai',
                        'english' => 'Invalid Field Format trxDateTime'
                    );
                    return ['success' => false, 'message' => $message, 'error_code' => '4002501', 'type' => $type];
                }
            }

            if (empty($param['referenceNo'])) {
                $message = array(
                    'indonesia' => 'Field wajib referenceNo kosong',
                    'english' => 'Missing mandatory field referenceNo'
                );
                return ['success' => false, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
            }

            if (empty($param['paymentRequestId'])) {
                $message = array(
                    'indonesia' => 'Field wajib paymentRequestId kosong',
                    'english' => 'Missing mandatory field paymentRequestId'
                );
                return ['success' => false, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
            }

            if (empty($param['flagAdvise'])) {
                $message = array(
                    'indonesia' => 'Field wajib flagAdvise kosong',
                    'english' => 'Missing mandatory field flagAdvise'
                );
                return ['success' => false, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
            }

        }
        return ['success' => true, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
    }

    public function isoTime()
    {
        $isoTime = date('o-m-d') . 'T' . date('H:i:s') . date('P');

        return $isoTime;
    }

    public function validateOauthSignature($public_key_str, $client_id, $iso_time, $signature)
    {
        $is_valid = false;
        $algo = "SHA256";
        $dataToSign = $client_id . "|" . $iso_time;
        $is_valid = openssl_verify($dataToSign, base64_decode($signature), $public_key_str, OPENSSL_ALGO_SHA256);
        //$is_valid = openssl_verify($dataToSign, hex2bin($signature), $public_key, $algo);
        if ($is_valid == 1) {
            $is_valid = true;
        }
        return $is_valid;
    }

    function validateDate($date)
    {
        $arrDate = explode('+', $date);
        $arrisoTime = explode('+', $this->isoTime());

        if (count($arrDate) > 1) {
            $date = $arrDate[0];
            $zoneTime = '+' . $arrDate['1'];
            if ($zoneTime == date('P')) {
                if (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})/', $date, $parts) == true) {
                    $arr_datetime = explode('T', $date);
                    $arr_iso_time = explode('T', $arrisoTime[0]);
                    $arr_date = explode('-', $arr_datetime[0]);
                    $arr_time = explode(':', $arr_datetime[1]);
                    $date_start = date_create($arr_datetime[0] . ' '. $arr_datetime[1]);
                    $date_end = date_create($arr_iso_time[0] . ' '. $arr_iso_time[1]);
                    $date_diff = date_diff($date_start, $date_end);

                    if ($arr_time[0] > 24) {
                        return false;
                    }
                    if ($arr_time[1] > 60) {
                        return false;
                    }
                    if ($arr_time[2] > 60) {
                        return false;
                    }
                    if ($arr_date[1] > 12) {
                        return false;
                    }
                    if ($arr_date[2] > 31) {
                        return false;
                    }

                    if (($date_diff->i > 10) && ($date_diff->h > 0)){
                        return true;
                    }

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function validateHeader($request, $ClientID, $ChannelID, $PatnerID, $relativeUrl)
    {
        $success = true;
        $error_message = "";
        $error_code = "";
        if(ENV('APP_ENV_SANMARU') == 'staging'){
            $publicKey = Storage::disk('local')->get('/snap_sign.devapi.klikbca.com.pem');
        }else{
            $publicKey = Storage::disk('local')->get('/snap_sign.api.klikbca.com.pem');
        }
        $headerClientKey = $request->header('x-client-key');
        $headerTimestamp = $request->header('x-timestamp');
        $headerSignature = $request->header('x-signature');
        $headerChannelID = $request->header('channel-id');
        $headerPatnerID = $request->header('x-partner-id');
        $headerExternalID = $request->header('x-external-id');
        $ClientSecret = env('PAYMENT_BCA_CLIENT_SECRET');
        $ApiSecret = env('PAYMENT_BCA_API_SECRET');

        $isBills = false;
        if ($relativeUrl == '/payment/v1.0/transfer-va/inquiry') {
            $isBills = true;
        }
        $headerToken = $request->bearerToken();
        $arrFill = $this->fillParams($request->toArray(), $isBills);
        $signature = $this->generateSignature('POST', $relativeUrl, $headerToken, $ClientSecret, $headerTimestamp, $arrFill);
        $getToken = $this->authenticationToken($headerToken);
        if ($getToken) {
            if ($signature['signature'] == $headerSignature) {
                $validateTimestap = $this->validateDate($headerTimestamp);

                if ($validateTimestap) {
                    if ($headerChannelID != $ChannelID) {
                        $success = false;
                        $error_code = "4012500";
                        if ($isBills) {
                            $error_code = "4012400";
                        }
                        $error_message = "Unauthorized. [Unknown client]";

                        return ['success' => $success, 'error_message' => $error_message, 'error_code' => $error_code];
                    }
                    if ($headerPatnerID != $PatnerID) {
                        $success = false;
                        $error_code = "4012500";
                        if ($isBills) {
                            $error_code = "4012400";
                        }
                        $error_message = "Unauthorized. [Unknown client]";

                        return ['success' => $success, 'error_message' => $error_message, 'error_code' => $error_code];
                    }
                } else {
                    $success = false;
                    $error_code = "4007301";
                    $error_message = "invalid timestamp format [X-TIMESTAMP]";

                    return ['success' => $success, 'error_message' => $error_message, 'error_code' => $error_code];
                }
            } else {
                $success = false;
                $error_code = "4012500";
                if ($isBills) {
                    $error_code = "4012400";
                }
                $error_message = "Unauthorized. [Signature]";

                return ['success' => $success, 'error_message' => $error_message, 'error_code' => $error_code];
            }
        } else {
            $success = false;
            $error_code = "4012501";
            if ($isBills) {
                $error_code = "4012401";
            }
            $error_message = "Invalid Token (B2B)";

            return ['success' => $success, 'error_message' => $error_message, 'error_code' => $error_code];
        }


        return ['success' => $success, 'error_message' => $error_message, 'error_code' => $error_code];
    }

    function validateHeaderToken($request)
    {
        $success = true;
        $error_message = "";
        $error_code = "";
        $http_code = "";

        $headerSignature = $request->header('x-signature');
        $headerTimestamp = $request->header('x-timestamp');
        $headerClientKey = $request->header('x-client-key');

        $ClientID = env('PAYMENT_BCA_CLIENT_ID');


        if (env('APP_ENV_SANMARU') == 'staging') {
            $publicKey = Storage::disk('local')->get('/snap_sign.devapi.klikbca.com.pem');
        }else{
            $publicKey = Storage::disk('local')->get('/snap_sign.api.klikbca.com.pem');
        }

        $validateTimestap = $this->validateDate($headerTimestamp);
        if ($validateTimestap) {
            if ($request->grantType == 'client_credentials') {
                if (!empty($headerClientKey)) {
                    if ($headerClientKey == $ClientID) {
                            $validateSignature = $this->validateOauthSignature($publicKey, $ClientID, $headerTimestamp, $headerSignature);
                            if (!$validateSignature) {
                                $success = false;
                                $error_code = "4017300";
                                $error_message = "Unauthorized. [Signature]";
                                return ['success' => $success, 'error_message' => $error_message, 'error_code' => $error_code, 'http_code' => 401];
                            }

                    }else{
                        $success = false;
                        $error_code = "4017300";
                        $error_message = "Unauthorized. [Unknown client]";
                        return ['success' => $success, 'error_message' => $error_message, 'error_code' => $error_code, 'http_code' => 401];
                    }
                } else {
                    $success = false;
                    $error_code = "4007302";
                    $error_message = "Invalid mandatory field Client";
                    return ['success' => $success, 'error_message' => $error_message, 'error_code' => $error_code, 'http_code' => 400];
                }
            }else{
                $success = false;
                $error_code = "4007301";
                $error_message = "Invalid field format [clientId/clientSecret/grantType]";
                return ['success' => $success, 'error_message' => $error_message, 'error_code' => $error_code, 'http_code' => 400];
            }
            return ['success' => $success, 'error_message' => $error_message, 'error_code' => $error_code, 'http_code' => 200];
        } else {
            $success = false;
            $error_code = "4007301";
            $error_message = "invalid timestamp format [X-TIMESTAMP]";
            return ['success' => $success, 'error_message' => $error_message, 'error_code' => $error_code, 'http_code' => 400];
        }

    }

    public function generateSignature($method, $url, $auth_token, $secret_key, $isoTime, $bodyToHash = [])
    {

        $hash = hash("SHA256", "");
        if (is_array($bodyToHash)) {
//            ksort($bodyToHash);
            $encoderData = json_encode($bodyToHash, JSON_UNESCAPED_SLASHES);
            $hash = hash("sha256", $encoderData);
        }

        $stringToSign = $method . ":" . $url . ":" . $auth_token . ":" . $hash . ":" . $isoTime;
//        $stringToSign = "POST:/payment/va/bills:mWYnherSfK1Zzj1Pi6GfZc4iucXbnUU43CwCZgrF2L6f2wrQ6R6tdK:0e77b151c1499b6c4ace01bcea8d7e120cab23aee789137bbec835b809ac1db2:2022-10-17T14:46:45+07:00";

        return [
            'signature' => base64_encode(hash_hmac('SHA512', $stringToSign, $secret_key, true)),
            'hash' => $hash,
            'stringToSign' => $stringToSign,
            'requestBody' => \GuzzleHttp\json_encode($bodyToHash)
        ];
    }

    public function getSignature(PaymentBCAService $paymentBCAService, Request $request)
    {
        $headerTimestamp = $request->header('x-timestamp');
        $ClientSecret = env('PAYMENT_BCA_CLIENT_SECRET');
        $headerToken = $request->bearerToken();
        $relativeUrl = $request->header('x-link');

        if ($relativeUrl == '/payment/v1.0/transfer-va/inquiry') {
            $arrFill = $this->fillParams($request->toArray(), true);
        } else {
            $arrFill = $this->fillParams($request->toArray(), false);
        }

        $signature = $this->generateSignature('POST', $relativeUrl, $headerToken, $ClientSecret, $headerTimestamp, $arrFill);
        return response()->json($signature);
    }

    public function fillParams($params, $isBills)
    {
        $lengthString = 8 - (strlen(env('PAYMENT_BCA_API_CORP_ID')));
        $lengthVA = isset($params['virtualAccountNo']) ? strlen($params['virtualAccountNo']) : '';

        if ($isBills) {
            $param = [];
            foreach ($params as $key => $item) {
                $item = !empty($params[$key]) ? $params[$key] : "";
                if ($key == 'partnerServiceId') {
//                    $item = !empty($params['partnerServiceId']) ? str_pad($params['partnerServiceId'], 8, " ", STR_PAD_LEFT) : "";
                    $item = !empty($params['partnerServiceId']) ? '   ' . $params['partnerServiceId'] : "";
                }
                if ($key == 'virtualAccountNo') {
                    $item = !empty($params['virtualAccountNo']) ? str_pad($params['virtualAccountNo'], $lengthVA + $lengthString, " ", STR_PAD_LEFT) : "";
                }
                if ($key == 'amount') {
                    $item = $params['amount'];
                }
                if ($key == 'additionalInfo') {
                    // if (empty($params['additionalInfo']['value'])) {
                    //     $item = (object)array();
                    // } else {
                        $item = [
                            'value' => !empty($params['additionalInfo']['value']) ? $params['additionalInfo']['value'] : ""
                        ];
                    // }
                }
                $param[$key] = $item;
            }
        } else {
            $param = [];
            foreach ($params as $key => $item) {
                $item = !empty($params[$key]) ? $params[$key] : "";
                if ($key == 'partnerServiceId') {
                    $item = !empty($params['partnerServiceId']) ? str_pad($params['partnerServiceId'], 8, " ", STR_PAD_LEFT) : "";
                }
                if ($key == 'virtualAccountNo') {
                    $item = !empty($params['virtualAccountNo']) ? str_pad($params['virtualAccountNo'], $lengthVA + $lengthString, " ", STR_PAD_LEFT) : "";
                }
                if ($key == 'paidAmount') {
                    $item = [
                        'value' => ($params['paidAmount']['value'] != null) ? $params['paidAmount']['value'] : "",
                        'currency' => ($params['paidAmount']['currency'] != null) ? $params['paidAmount']['currency'] : ""
                    ];
                }
                if ($key == 'cumulativePaymentAmount') {
                    $item = $params['cumulativePaymentAmount'];
                }
                if ($key == 'totalAmount') {
                    $item = [
                        'value' => ($params['totalAmount']['value']) ? $params['totalAmount']['value'] : "",
                        'currency' => ($params['totalAmount']['currency'] != null) ? $params['totalAmount']['currency'] : ""
                    ];
                }
                if ($key == 'billDetails') {
                    $item = array(null);
                }
                if ($key == 'freeTexts') {
                    $item = array();
                }
                if ($key == 'additionalInfo') {
                    // if (empty($params['additionalInfo']['value'])) {
                    //     $item = (object)array();
                    // } else {
                        $item = [
                            'value' => !empty($params['additionalInfo']['value']) ? $params['additionalInfo']['value'] : ""
                        ];
                    // }
                }
                $param[$key] = $item;
            }
        }
        return $param;
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


        $virtualAccount = new PaymentVirtualAccountDataFailedResponse();
        $virtualAccount->setinquiryStatus("01");
        $virtualAccount->setinquiryReason(array(
            "english" => $reason['english'],
            "indonesia" => $reason['indonesia'],
        ));

        $lengthString = 3;
        $lengthVA = strlen($data->getvirtualAccountNo());
        $patnerId = ($data->getpartnerServiceId() == null) ? "" : str_pad($data->getpartnerServiceId(), 8, " ", STR_PAD_LEFT);
        $virtualAccountNo = ($data->getvirtualAccountNo() == null) ? "" : str_pad($data->getvirtualAccountNo(), $lengthVA + $lengthString, " ", STR_PAD_LEFT);

        $virtualAccount->setpartnerServiceId($patnerId);
        $virtualAccount->setcustomerNo(($data->getcustomerNo() != null) ? $data->getcustomerNo() : "");
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

    public function logExternalID($external_id, $request_id, $flag)
    {
        $success = true;
        $message = array(
            'indonesia' => '',
            'english' => ''
        );
        $error_code = '';
        $date = Carbon::now()->toDateString();
//        if (env('APP_ENV') == 'local') {
//            return ['success' => $success, 'message' => $message, 'error_code' => $error_code];
//        }
        $external = ExternalLog::where(['external_id' => $external_id, 'date' => $date, 'flag'=>$flag])->first();
        $requestID = ExternalLog::where(['request_id' => $request_id, 'date' => $date, 'flag'=>$flag])->get();

        if (!isset($external)) {
            $log = ExternalLog::create([
                'external_id' => $external_id,
                'request_id' => $request_id,
                'date' => $date,
                'flag' => $flag
            ]);
        }

        if (isset($external)) {
            if ($flag == 'payments'){
                if (($external->external_id == $external_id) && ($external->request_id == $request_id)) {
                    $success = false;
                    $message = array(
                        'indonesia' => 'Permintaan tidak konsisten',
                        'english' => 'Inconsistent Request'
                    );
                    return ['success' => false, 'message' => $message, 'error_code' => '4042518'];
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
                    return ['success' => $success, 'message' => $message, 'error_code' => $error_code];
                }
            }

            if ($flag == 'bills'){
                if (($external->external_id == $external_id)) {
                    $success = false;
                    $message = array(
                        'indonesia' => 'Konflik',
                        'english' => 'Conflict'
                    );
                    return ['success' => false, 'message' => $message, 'error_code' => '4092400'];
                }
            }



        }
        return ['success' => $success, 'message' => $message, 'error_code' => $error_code];
    }

    public function getSignatureToken(Request $request, PaymentBCAService $paymentBCAService)
    {
        $isoTime = date('o-m-d') . 'T' . date('H:i:s') . date('P');

        if ((ENV("APP_ENV") == "local") || (ENV("APP_ENV") == "staging")) {
            $clientID = '5f43cd2b-877c-4327-94c2-fd370398141c';
            $clientSecret = '3ee102b3-970d-42e4-8421-291f624e569c';
        }else{
            $clientID = env('PAYMENT_BCA_CLIENT_ID_INBOUND', '7aff11d8-08c0-4ab2-9a67-9c23a519450a');
            $clientSecret = env('PAYMENT_BCA_CLIENT_SECRET_INBOUND', '6fc93751-131d-4c5c-8880-75cc05835437');
        }


        $privateKey = Storage::disk('local')->get('/private-key.pem');

        $signature = $paymentBCAService->signatureToken($privateKey, $clientID, $isoTime);
        return response()->json($signature);
    }

    public function verifSignatureToken(Request $request)
    {
        $headerSignature = $request->header('x-signature');
        $headerTimestamp = $request->header('x-timestamp');
        $headerClientKey = $request->header('x-client-key');

        $publicKey = Storage::disk('local')->get('/public-key_Prod.pem');
        if (env('APP_ENV_SANMARU') == 'staging') {
            $publicKey = Storage::disk('local')->get('public-key_Dev.pem');
        }


        $validateSignature = $this->validateOauthSignature($publicKey, $headerClientKey, $headerTimestamp, $headerSignature);
        $data = [
            'status' => $validateSignature,
        ];
        return response()->json($data);

    }

    public function authTokenTest(PaymentBCAService $paymentBCAService, Request $request)
    {
        $validateHeader = $this->validateHeaderTokenTest($request);
        if ($validateHeader['success']) {
            $token = $paymentBCAService->getAuthToken();
            if ($token) {
                $response = [
                    'responseCode' => '2007300',
                    'responseMessage' => 'Successful',
                    'accessToken' => $token->token->access_token,
                    'tokenType' => $token->token->token_type,
                    'expiresIn' => 900,
                ];
                return response()->json($response, 200);
            } else {
                return response()->json(['error' => 'Failed to get token'], 500);
            }
        } else {
            $response = [
                'responseCode' => $validateHeader['error_code'],
                'responseMessage' => $validateHeader['error_message'],
            ];
            return response()->json($response, $validateHeader['http_code']);
        }
    }

    function validateHeaderTokenTest($request)
    {
        $success = true;
        $error_message = "";
        $error_code = "";
        $http_code = "";

        $headerSignature = $request->header('x-signature');
        $headerTimestamp = $request->header('x-timestamp');
        $headerClientKey = $request->header('x-client-key');

        if ((ENV("APP_ENV") == "local") || (ENV("APP_ENV") == "staging")) {
            $ClientID = '5f43cd2b-877c-4327-94c2-fd370398141c';
        }else{
            $ClientID = env('PAYMENT_BCA_CLIENT_ID_INBOUND', '7aff11d8-08c0-4ab2-9a67-9c23a519450a');
        }
        $publicKey = Storage::disk('local')->get('/public-key.pem');

        if (!empty($headerClientKey)) {
            $validateTimestap = $this->validateDate($headerTimestamp);
            if ($validateTimestap) {
                $validateSignature = $this->validateOauthSignature($publicKey, $ClientID, $headerTimestamp, $headerSignature);
                if ($validateSignature) {
                    if ($headerClientKey != $ClientID) {
                        $success = false;
                        $error_code = "4017300";
                        $error_message = "Unauthorized Client";
                        return ['success' => $success, 'error_message' => $error_message, 'error_code' => $error_code, 'http_code' => 401];
                    }
                } else {
                    $success = false;
                    $error_code = "4017300";
                    $error_message = "Unauthorized. [Signature]";
                    return ['success' => $success, 'error_message' => $error_message, 'error_code' => $error_code, 'http_code' => 401];
                }
            } else {
                $success = false;
                $error_code = "4007301";
                $error_message = "invalid timestamp format [X-TIMESTAMP]";
                return ['success' => $success, 'error_message' => $error_message, 'error_code' => $error_code, 'http_code' => 400];
            }
        } else {
            $success = false;
            $error_code = "4007302";
            $error_message = "Invalid mandatory field Client";
            return ['success' => $success, 'error_message' => $error_message, 'error_code' => $error_code, 'http_code' => 400];
        }
        return ['success' => $success, 'error_message' => $error_message, 'error_code' => $error_code, 'http_code' => 200];
    }

    public function getInquiryStatus(Request $request, PaymentBCAService $paymentBCAService)
    {
        $isoTime = date('o-m-d') . 'T' . date('H:i:s') . date('P');
        $token = $paymentBCAService->getAccessToken($isoTime);

        $customerNo = substr($request->virtualAccountNo, 5, 16);
        $lengthString = 8 - (strlen(env('PAYMENT_BCA_API_CORP_ID')));
        $lengthVA = strlen($request->virtualAccountNo);

        $param = [
            'partnerServiceId' => str_pad(env('PAYMENT_BCA_API_CORP_ID', '13977'), 8, " ", STR_PAD_LEFT),
            'customerNo' => $request->customerNo,
            'virtualAccountNo' => str_pad($request->virtualAccountNo, $lengthVA + $lengthString, " ", STR_PAD_LEFT),
            'inquiryRequestId' => $request->inquiryRequestId,
            'paymentRequestId' => $request->paymentRequestId,
            'additionalInfo' => (object)array(),
        ];

        if ((ENV("APP_ENV") == "local") || (ENV("APP_ENV") == "staging")) {
            $url = 'https://devapi.klikbca.com:443/openapi/v1.0/transfer-va/status';
            $ClientSecret = '3ee102b3-970d-42e4-8421-291f624e569c';
        } else {
            $url = 'https://api.klikbca.com/openapi/v1.0/transfer-va/status';
            $ClientSecret = env('PAYMENT_BCA_CLIENT_SECRET_INBOUND', '6fc93751-131d-4c5c-8880-75cc05835437');
        }
        $relativeUrl = '/openapi/v1.0/transfer-va/status';


        $signature = $paymentBCAService->generateSignatureInquiry('POST', $relativeUrl, $token->accessToken, $ClientSecret, $isoTime, $param);

        $data = [
            'token' => $token->accessToken,
            'timestamps' => $isoTime,
            'signature' => $signature['signature'],
            'generate-signature' => $signature
        ];
        return response()->json($data, 200);
    }

    public function validateField($request, $type)
    {
        $success = true;
        $message = array(
            'indonesia' => '',
            'english' => ''
        );
        $error_code = '';
        $arr_req = [];
        foreach ($request as $key => $req) {
            $arr_req[$key] = $key;
        }
        if ($type == 'bills') {
            $data = array(
                'partnerServiceId' => 'partnerServiceId',
                'customerNo' => 'customerNo',
                'virtualAccountNo' => 'virtualAccountNo',
                'trxDateInit' => 'trxDateInit',
                'channelCode' => 'channelCode',
                'language' => 'language',
                'amount' => 'amount',
                'hashedSourceAccountNo' => 'hashedSourceAccountNo',
                'sourceBankCode' => 'sourceBankCode',
                'additionalInfo' => 'additionalInfo',
                'passApp' => 'passApp',
                'inquiryRequestId' => 'inquiryRequestId',
            );
        } else {
            $data = array(
                'partnerServiceId' => 'partnerServiceId',
                'customerNo' => 'customerNo',
                'virtualAccountNo' => 'virtualAccountNo',
                'virtualAccountName' => 'virtualAccountName',
                'virtualAccountEmail' => 'virtualAccountEmail',
                'virtualAccountPhone' => 'virtualAccountPhone',
                'trxId' => 'trxId',
                'paymentRequestId' => 'paymentRequestId',
                'channelCode' => 'channelCode',
                'hashedSourceAccountNo' => 'hashedSourceAccountNo',
                'sourceBankCode' => 'sourceBankCode',
                'paidAmount' => 'paidAmount',
                'cumulativePaymentAmount' => 'cumulativePaymentAmount',
                'paidBills' => 'paidBills',
                'totalAmount' => 'totalAmount',
                'trxDateTime' => 'trxDateTime',
                'referenceNo' => 'referenceNo',
                'journalNum' => 'journalNum',
                'paymentType' => 'paymentType',
                'flagAdvise' => 'flagAdvise',
                'subCompany' => 'subCompany',
                'billDetails' => 'billDetails',
                'freeTexts' => 'freeTexts',
                'additionalInfo' => 'additionalInfo',
            );
        }

        foreach ($data as $key => $req) {
            if (!in_array($key, $arr_req)) {
                $message = array(
                    'indonesia' => 'Format Tidak Valid ' . $req,
                    'english' => 'Invalid Field Format ' . $req
                );
                return ['success' => false, 'message' => $message, 'error_code' => '4002401', 'type' => $type];
            }
        }

        return ['success' => true, 'message' => $message, 'error_code' => $error_code, 'type' => $type];
    }
}
