<?php

namespace App\Models\OpenApi\v1;
class PaymentBcaBillRequest extends \stdClass
{
    private $partnerServiceId = "";
    private $customerNo = "";
    private $virtualAccountNo = "";
    private $trxDateInit = "";
    private $channelCode = "";
    private $language = "";
    private $amount = null;
    private $hashedSourceAccountNo = "";
    private $sourceBankCode = "";
    private $additionalInfo = array(
        "value" => ""
    );
    private $passApp = "";
    private $inquiryRequestId = "";

    public function __construct($response)
    {
        $has = get_object_vars($this);
        foreach ($has as $name => $oldValue) {
            !array_key_exists($name, $response) ?: $this->{'set' . $name}($response[$name]);
        }
    }

    public function toArray()
    {
        $has = get_object_vars($this);
        $response = array();
        foreach ($has as $name => $value) {
            $response[$name] = $value;
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
     * @param string $CompanyCode
     */
    public function setpartnerServiceId($partnerServiceId): void
    {
        $this->partnerServiceId = $partnerServiceId;
    }

    /**
     * @return string
     */
    public function getcustomerNo()
    {
        return $this->customerNo;
    }

    /**
     * @param string $CustomerNumber
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
     * @param string $RequestID
     */
    public function setvirtualAccountNo($virtualAccountNo): void
    {
        $this->virtualAccountNo = $virtualAccountNo;
    }

    /**
     * @return string
     */
    public function getchannelCode()
    {
        return $this->channelCode;
    }

    /**
     * @param string $ChannelType
     */
    public function setchannelCode($channelCode): void
    {
        $this->channelCode = $channelCode;
    }

    /**
     * @return string
     */
    public function gettrxDateInit()
    {
        return $this->trxDateInit;
    }

    /**
     * @param string $TransactionDate
     */
    public function settrxDateInit($trxDateInit): void
    {
        $this->trxDateInit = $trxDateInit;
    }

    /**
     * @return string
     */
    public function getlanguage()
    {
        return $this->language;
    }

    /**
     * @param string $Language
     */
    public function setlanguage($language): void
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getamount()
    {
        return $this->amount;
    }

    /**
     * @param string $Amount
     */
    public function setamount($amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function gethashedSourceAccountNo()
    {
        return $this->hashedSourceAccountNo;
    }

    /**
     * @param string $HashedSourceAccountNo
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
     * @param string $SourceBankCode
     */
    public function setsourceBankCode($sourceBankCode): void
    {
        $this->sourceBankCode = $sourceBankCode;
    }

    /**
     * @return string[]
     */
    public function getadditionalInfo(): array
    {
        return $this->additionalInfo;
    }

    /**
     * @param string[] $AdditionalInfo
     */
    public function setadditionalInfo($additionalInfo): void
    {
        $this->additionalInfo = $additionalInfo;
    }

    /**
     * @return string
     */
    public function getpassApp()
    {
        return $this->passApp;
    }

    /**
     * @param string $passApp
     */
    public function setpassApp($passApp): void
    {
        $this->passApp = $passApp;
    }

    /**
     * @return string
     */
    public function getinquiryRequestId()
    {
        return $this->inquiryRequestId;
    }

    /**
     * @param string $inquiryRequestId
     */
    public function setinquiryRequestId($inquiryRequestId): void
    {
        $this->inquiryRequestId = $inquiryRequestId;
    }
}
