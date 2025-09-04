<?php

namespace App\Models;
class PaymentBcaBillRequest extends \stdClass
{
    private $CompanyCode = "";
    private $CustomerNumber = "";
    private $RequestID = "";
    private $ChannelType = "";
    private $TransactionDate = "";
//    private $AdditionalData = "";

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
    public function getCompanyCode(): string
    {
        return $this->CompanyCode;
    }

    /**
     * @param string $CompanyCode
     */
    public function setCompanyCode(string $CompanyCode): void
    {
        $this->CompanyCode = $CompanyCode;
    }

    /**
     * @return string
     */
    public function getCustomerNumber(): string
    {
        return $this->CustomerNumber;
    }

    /**
     * @param string $CustomerNumber
     */
    public function setCustomerNumber(string $CustomerNumber): void
    {
        $this->CustomerNumber = $CustomerNumber;
    }

    /**
     * @return string
     */
    public function getRequestID(): string
    {
        return $this->RequestID;
    }

    /**
     * @param string $RequestID
     */
    public function setRequestID(string $RequestID): void
    {
        $this->RequestID = $RequestID;
    }

    /**
     * @return string
     */
    public function getChannelType(): string
    {
        return $this->ChannelType;
    }

    /**
     * @param string $ChannelType
     */
    public function setChannelType(string $ChannelType): void
    {
        $this->ChannelType = $ChannelType;
    }

    /**
     * @return string
     */
    public function getTransactionDate(): string
    {
        return $this->TransactionDate;
    }

    /**
     * @param string $TransactionDate
     */
    public function setTransactionDate(string $TransactionDate): void
    {
        $this->TransactionDate = $TransactionDate;
    }

    /**
     * @return string
     */
    public function getAdditionalData(): string
    {
        return $this->AdditionalData;
    }

    /**
     * @param string $AdditionalData
     */
    public function setAdditionalData(string $AdditionalData): void
    {
        $this->AdditionalData = $AdditionalData;
    }
}
