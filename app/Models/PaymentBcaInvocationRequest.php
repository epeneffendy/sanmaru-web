<?php

namespace App\Models;

use Illuminate\Support\Collection;

class PaymentBcaInvocationRequest extends \stdClass
{
    private $CompanyCode = "";
    private $CustomerNumber = "";
    private $RequestID = "";
    private $ChannelType = "";
    private $CustomerName = "";
    private $CurrencyCode = "IDR";
    private $PaidAmount = "0.00";
    private $TotalAmount = "0.00";
    private $SubCompany = "00000";
    private $TransactionDate = "";
    private $Reference = "";
    private $DetailBills = array(null);
    private $FlagAdvice = "";
//    private $AdditionalData = "";

    public function __construct($response)
    {
        $has = get_object_vars($this);
        foreach ($has as $name => $oldValue) {
            !array_key_exists($name, $response) ?:  $this->$name = $response[$name];
        }
    }

    public function toArray() : array
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
    public function getCustomerName(): string
    {
        return $this->CustomerName;
    }

    /**
     * @param string $CustomerName
     */
    public function setCustomerName(string $CustomerName): void
    {
        $this->CustomerName = $CustomerName;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->CurrencyCode;
    }

    /**
     * @param string $CurrencyCode
     */
    public function setCurrencyCode(string $CurrencyCode): void
    {
        $this->CurrencyCode = $CurrencyCode;
    }

    /**
     * @return string
     */
    public function getPaidAmount(): string
    {
        return $this->PaidAmount;
    }

    /**
     * @param string $PaidAmount
     */
    public function setPaidAmount(string $PaidAmount): void
    {
        $this->PaidAmount = $PaidAmount;
    }

    /**
     * @return string
     */
    public function getTotalAmount(): string
    {
        return $this->TotalAmount;
    }

    /**
     * @param string $TotalAmount
     */
    public function setTotalAmount(string $TotalAmount): void
    {
        $this->TotalAmount = $TotalAmount;
    }

    /**
     * @return string
     */
    public function getSubCompany(): string
    {
        return $this->SubCompany;
    }

    /**
     * @param string $SubCompany
     */
    public function setSubCompany(string $SubCompany): void
    {
        $this->SubCompany = $SubCompany;
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
    public function getReference(): string
    {
        return $this->Reference;
    }

    /**
     * @param string $Reference
     */
    public function setReference(string $Reference): void
    {
        $this->Reference = $Reference;
    }

    /**
     * @return Collection
     */
    public function getDetailBills(): Collection
    {
        return collect($this->DetailBills);
    }

    /**
     * @param null[] $DetailBills
     */
    public function setDetailBills(array $DetailBills): void
    {
        $this->DetailBills = $DetailBills;
    }

    /**
     * @return string
     */
    public function getFlagAdvice(): string
    {
        return $this->FlagAdvice;
    }

    /**
     * @param string $FlagAdvice
     */
    public function setFlagAdvice(string $FlagAdvice): void
    {
        $this->FlagAdvice = $FlagAdvice;
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
