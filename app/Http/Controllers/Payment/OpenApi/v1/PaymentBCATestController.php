<?php

namespace App\Http\Controllers\Payment\OpenApi\v1;
use App\Http\Controllers\Controller;
use App\Models\ExternalLog;
use App\Models\OpenApi\v1\PaymentBcaBillRequest;
use App\Models\OpenApi\v1\PaymentBcaBillResponse;
use App\Models\OpenApi\v1\PaymentBcaInvocationRequest;
use App\Models\OpenApi\v1\PaymentBcaInvocationResponse;
use App\Models\OpenApi\v1\PaymentVirtualAccountDataFailedResponse;
use App\Models\PPDBUser;
use App\Services\OpenApi\v1\PaymentBCAService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentBCATestController extends Controller
{
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

        $validateHeader['success'] = true;
        if ($validateHeader['success']) {

            $data = new PaymentBcaBillRequest($request->all());
            $result = new PaymentBcaBillResponse($request->all());

            try {
                $inquiryRequestId = $data->getinquiryRequestId();
                $validateField = $this->validateField($request->all(), 'bills');
                if ($validateField['success']) {
                    // $validateRequest = $this->validateRequest($request->all(), 'bills');
                    $validateRequest['success'] = true;
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
                            $orderId = substr($data->getcustomerNo(), 4, 7);
                            $dispensation_type = null;

                            switch ($paymentCode) {
                                case '08':
                                    $data = $paymentBCAService->getPpdbBills($orderId, $unitId, $data, $result);
                                    break;
                                case '07':
                                    $data = $paymentBCAService->getPpdbRegistration($orderId, $unitId, $data, $result);
                                    break;
                                case '03': //Uang Pengembangan
                                    $dispensation_type = 'development';
                                    $data = $paymentBCAService->getBillPaymentDevelopment($orderId, $unitId, $dispensation_type, $data, $result);
                                    break;
                                case '06': //Uang Kegiatan
                                    $dispensation_type = 'activity';
                                    $data = $paymentBCAService->getBillPaymentDevelopment($orderId, $unitId, $dispensation_type, $data, $result);
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
                dd($e);
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
            // $this->destroyToken($headerToken);
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
            // $validateHeader = $this->validateHeader($request, $ClientID, $ChannelID, $partnerServiceId, $relativeUrl);
            $paymentBCAService->log('request_payment', $request->toArray(), '');
            $validateHeader['success'] = true;
            if ($validateHeader['success']) {
                // $this->destroyToken($headerToken);
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
                            $orderId = substr($data->getcustomerNo(), 4, 7);
                            $dispensation_type = null;
                            switch ($paymentCode) {
                                case '08':
                                    $result = $paymentBCAService->flagPaymentPpdb($orderId, $unitId, $data, $result, $request->header('x-external-id'));
                                    break;
                                case '07':
                                    $result = $paymentBCAService->flagPaymentRegistration($orderId, $unitId, $data, $result, $request->header('x-external-id'));
                                    break;
                                case '03': //Uang Pengembangan
                                    $dispensation_type = 'development';
                                    $data = $paymentBCAService->flagPaymentDevelopment($orderId, $unitId, $dispensation_type, $data, $result, $request->header('x-external-id'), $paymentCode);
                                    break;
                                case '06': //Uang Kegiatan
                                    $dispensation_type = 'activity';
                                    $data = $paymentBCAService->flagPaymentDevelopment($orderId, $unitId, $dispensation_type, $data, $result, $request->header('x-external-id'), $paymentCode);
                                    break;
                                default:
                                    // $result->setPaymentFlagStatus('01');
                                    // $result->setPaymentFlagReason(array(
                                    //     'Indonesian' => 'Tagihan tidak ditemukan',
                                    //     'English' => 'Bill not found',
                                    // ));
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
            dd($e);
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

            // if (!empty($param['trxDateTime'])) {
            //     $validateTimestap = $this->validateDate($param['trxDateTime']);
            //     if (!$validateTimestap) {
            //         $message = array(
            //             'indonesia' => 'Format trxDateTime tidak sesuai',
            //             'english' => 'Invalid Field Format trxDateTime'
            //         );
            //         return ['success' => false, 'message' => $message, 'error_code' => '4002501', 'type' => $type];
            //     }
            // }

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

    public function failedResponse($data, $result, $reason)
    {
        $unitId = substr($data->getcustomerNo(), 0, 2);
        $paymentCode = substr($data->getcustomerNo(), 2, 2);
        $orderId = substr($data->getcustomerNo(), 4);

        $ppdbUser = PPDBUser::where('register_number', $orderId)
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
}

