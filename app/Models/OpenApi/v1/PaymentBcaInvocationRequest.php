<?php

namespace App\Models\OpenApi\v1;

use Illuminate\Support\Collection;

class PaymentBcaInvocationRequest extends \stdClass
{
    private $partnerServiceId = "";
    private $customerNo = "";
    private $virtualAccountNo = "";
    private $virtualAccountName = "";
    private $virtualAccountEmail = "";
    private $virtualAccountPhone = "";
    private $trxId = "";
    private $paymentRequestId = "";
    private $channelCode = "";
    private $hashedSourceAccountNo = "";
    private $sourceBankCode = "";
    private $paidAmount = array(
        "value" => "0.00",
        "currency" => "IDR"
    );
    private $cumulativePaymentAmount = "";
    private $paidBills = "";
    private $totalAmount = array(
        "value" => "0.00",
        "currency" => "IDR"
    );
    private $trxDateTime = "";
    private $referenceNo = "";
    private $journalNum = "";
    private $paymentType = "";
    private $flagAdvise = "";
    private $subCompany = "00000";
    private $billDetails = null;
//    private $billDetails = array(
//        "billCode" => "",
//        "billNo" => "",
//        "billName" => "",
//        "billShortName" => "",
//        "billDescription" => array(
//            "english" => "",
//            "indonesia" => ""
//        ),
//        "billSubCompany" => "00000",
//        "billAmount" => array(
//            "value" => "0.00",
//            "currency" => "IDR"
//        ),
//        "additionalInfo" => array(
//            "value" => ""
//        ),
//        "billReferenceNo" => "",
//    );
    private $freeTexts = array();
    private $additionalInfo = array();


    public function __construct($response)
    {
        $has = get_object_vars($this);
        foreach ($has as $name => $oldValue) {
            !array_key_exists($name, $response) ?: $this->$name = $response[$name];
        }
    }

    public function toArray(): array
    {
        $has = get_object_vars($this);
        $response = array();
        foreach ($has as $name => $value) {
            if (gettype($value) === 'object') {
                $response[$name] = $value->toArray();
            } else {
                $response[$name] = $value;
            }
        }
        return $response;
    }

    /**
     * @return string
     */
    public function getpartnerServiceId()
    {
        return $this->partnerServiceId;
    }

    /**
     * @param string $partnerServiceId
     */
    public function setpartnerServiceId($partnerServiceId): void
    {
        $this->partnerServiceId = str_pad($partnerServiceId, 8, " ", STR_PAD_LEFT);
    }

    /**
     * @return string
     */
    public function getcustomerNo()
    {
        return $this->customerNo;
    }

    /**
     * @param string $customerNo
     */
    public function setcustomerNo($customerNo): void
    {
        $this->customerNo = $customerNo;
    }

    /**
     * @return string
     */
    public function getvirtualAccountNo()
    {
        return $this->virtualAccountNo;
    }

    /**
     * @param string $virtualAccountNo
     */
    public function setvirtualAccountNo($virtualAccountNo): void
    {
        $lengthString = 3;
        $lengthVA = strlen($virtualAccountNo);
        $this->virtualAccountNo = str_pad($virtualAccountNo, $lengthVA + $lengthString, " ", STR_PAD_LEFT);
    }

    /**
     * @return string
     */
    public function getvirtualAccountName()
    {
        return $this->virtualAccountName;
    }

    /**
     * @param string $virtualAccountName
     */
    public function setvirtualAccountName($virtualAccountName): void
    {
        $this->virtualAccountName = $virtualAccountName;
    }

    /**
     * @return string
     */
    public function getvirtualAccountEmail()
    {
        return $this->virtualAccountEmail;
    }

    /**
     * @param string $virtualAccountEmail
     */
    public function setvirtualAccountEmail($virtualAccountEmail): void
    {
        $this->virtualAccountEmail = $virtualAccountEmail;
    }

    /**
     * @return string
     */
    public function getvirtualAccountPhone()
    {
        return $this->virtualAccountPhone;
    }

    /**
     * @param string $virtualAccountPhone
     */
    public function setvirtualAccountPhone($virtualAccountPhone): void
    {
        $this->virtualAccountPhone = $virtualAccountPhone;
    }

    /**
     * @return string
     */
    public function gettrxId()
    {
        return $this->trxId;
    }

    /**
     * @param string $PaidAmount
     */
    public function settrxId($trxId): void
    {
        $this->trxId = $trxId;
    }

    /**
     * @return string
     */
    public function getpaymentRequestId()
    {
        return $this->paymentRequestId;
    }

    /**
     * @param string $paymentRequestId
     */
    public function setpaymentRequestId($paymentRequestId): void
    {
        $this->paymentRequestId = $paymentRequestId;
    }

    /**
     * @return string
     */
    public function getchannelCode()
    {
        return $this->channelCode;
    }

    /**
     * @param string $SubCompany
     */
    public function setchannelCode($channelCode): void
    {
        $this->channelCode = $channelCode;
    }

    /**
     * @return string
     */
    public function gethashedSourceAccountNo()
    {
        return $this->hashedSourceAccountNo;
    }

    /**
     * @param string $hashedSourceAccountNo
     */
    public function sethashedSourceAccountNo($hashedSourceAccountNo): void
    {
        $this->hashedSourceAccountNo = $hashedSourceAccountNo;
    }

    /**
     * @return string
     */
    public function getsourceBankCode()
    {
        return $this->sourceBankCode;
    }

    /**
     * @param string $sourceBankCode
     */
    public function setsourceBankCode($sourceBankCode): void
    {
        $this->sourceBankCode = $sourceBankCode;
    }

    /**
     * @return string
     */
    public function getpaidAmount(): Collection
    {
        return collect($this->paidAmount);
    }

    /**
     * @param string $paidAmount
     */
    public function setpaidAmount(array $paidAmount): void
    {
        $this->paidAmount = $paidAmount;
    }

    /**
     * @return string
     */
    public function getcumulativePaymentAmount()
    {
        return $this->cumulativePaymentAmount;
    }

    /**
     * @param string $cumulativePaymentAmount
     */
    public function setcumulativePaymentAmount($cumulativePaymentAmount): void
    {
        $this->cumulativePaymentAmount = $cumulativePaymentAmount;
    }

    /**
     * @return string
     */
    public function getpaidBills()
    {
        return $this->paidBills;
    }

    /**
     * @param string $paidBills
     */
    public function setpaidBills($paidBills): void
    {
        $this->paidBills = $paidBills;
    }

    /**
     * @return string
     */
    public function gettotalAmount(): Collection
    {
        return collect($this->totalAmount);
    }

    /**
     * @param string $totalAmount
     */
    public function settotalAmount($totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    /**
     * @return string
     */
    public function gettrxDateTime()
    {
        return $this->trxDateTime;
    }

    /**
     * @param string $trxDateTime
     */
    public function settrxDateTime($trxDateTime): void
    {
        $this->trxDateTime = $trxDateTime;
    }

    /**
     * @return string
     */
    public function getreferenceNo()
    {
        return $this->referenceNo;
    }

    /**
     * @param string $referenceNo
     */
    public function setreferenceNo($referenceNo): void
    {
        $this->referenceNo = $referenceNo;
    }

    /**
     * @return string
     */
    public function getjournalNum()
    {
        return $this->journalNum;
    }

    /**
     * @param string $journalNum
     */
    public function setjournalNum($journalNum): void
    {
        $this->journalNum = $journalNum;
    }

    /**
     * @return string
     */
    public function getpaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @param string $paymentType
     */
    public function setpaymentType($paymentType): void
    {
        $this->paymentType = $paymentType;
    }

    /**
     * @return string
     */
    public function getflagAdvise()
    {
        return $this->flagAdvise;
    }

    /**
     * @param string $flagAdvise
     */
    public function setflagAdvise($flagAdvise): void
    {
        $this->flagAdvise = $flagAdvise;
    }

    /**
     * @return string
     */
    public function getsubCompany()
    {
        return $this->subCompany;
    }

    /**
     * @param string $subCompany
     */
    public function setsubCompany($subCompany): void
    {
        $this->subCompany = $subCompany;
    }

    /**
     * @return Collection
     */
    public function getbillDetails(): Collection
    {
        return collect($this->billDetails);
    }

    /**
     * @param null[] $billDetails
     */
    public function setbillDetails(array $billDetails): void
    {
        $this->billDetails = $billDetails;
    }

    /**
     * @return string
     */
    public function getfreeTexts()
    {
        return $this->subCompany;
    }

    /**
     * @param string $freeTexts
     */
    public function setfreeTexts($freeTexts): void
    {
        $this->freeTexts = $freeTexts;
    }

    /**
     * @return string
     */
    public function getadditionalInfo()
    {
        return $this->additionalInfo;
    }

    /**
     * @param string $additionalInfo
     */
    public function setadditionalInfo($additionalInfo): void
    {
        $this->additionalInfo = $additionalInfo;
    }
}
