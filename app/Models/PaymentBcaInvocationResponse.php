<?php

namespace App\Models;

use Illuminate\Support\Collection;

class PaymentBcaInvocationResponse extends \stdClass
{
    private $CompanyCode = "";
    private $CustomerNumber = "";
    private $RequestID = "";
    private $PaymentFlagStatus = "00";
    private $PaymentFlagReason = array(
        "Indonesian" => "",
        "English" => "",
    );
    private $CustomerName = "";
    private $CurrencyCode = "IDR";
    private $PaidAmount = "0.00";
    private $TotalAmount = "0.00";
//    private $SubCompany = "00000";
//    private $Reference = "00000";
    private $TransactionDate = "00000";
    private $DetailBills = array();
    private $FreeTexts = array();
//    private $AdditionalData = "";

    public function __construct($response = null)
    {
        if ($response !== null) {
            $has = get_object_vars($this);
            foreach ($has as $name => $oldValue) {
                !array_key_exists($name, $response) ?: $this->{'set' . $name}($response[$name]);
            }
        }
    }


    public function toArray() : array
    {
        $has = get_object_vars($this);
        $response = array();
        foreach ($has as $name => $value) {
            if ($value instanceof Collection) {
                $response[$name] = array();
                $value->each(function ($item) use (&$response, $name) {
                    if (gettype($item) === 'object') {
                        $response[$name][] = $item->toArray();
                    } else {
                        $response[$name][] = $item;
                    }
                });
            } else if (gettype($value) === 'array') {
                $response[$name] = array();
                foreach ($value as $i => $arrValue) {
                    if (gettype($arrValue) === 'object') {
                        $response[$name][$i] = $arrValue->toArray();
                    } else {
                        $response[$name][$i] = $arrValue;
                    }
                }
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
    public function setCompanyCode($CompanyCode): void
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
    public function setCustomerNumber($CustomerNumber): void
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
    public function setRequestID($RequestID): void
    {
        $this->RequestID = $RequestID;
    }

    /**
     * @return string
     */
    public function getPaymentFlagStatus(): string
    {
        return $this->PaymentFlagStatus;
    }

    /**
     * @param string $PaymentFlagStatus
     */
    public function setPaymentFlagStatus(string $PaymentFlagStatus): void
    {
        $this->PaymentFlagStatus = $PaymentFlagStatus;
    }

    /**
     * @return string[]
     */
    public function getPaymentFlagReason(): array
    {
        return $this->PaymentFlagReason;
    }

    /**
     * @param mixed $PaymentFlagReason
     */
    public function setPaymentFlagReason($PaymentFlagReason): void
    {
        $this->PaymentFlagReason = $PaymentFlagReason;
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
    public function setCustomerName($CustomerName): void
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
    public function setCurrencyCode($CurrencyCode): void
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
    public function setPaidAmount($PaidAmount): void
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
    public function setTotalAmount($TotalAmount): void
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
     * @return string
     */
    public function getTransactionDate(): string
    {
        return $this->TransactionDate;
    }

    /**
     * @param string $TransactionDate
     */
    public function setTransactionDate($TransactionDate): void
    {
        $this->TransactionDate = $TransactionDate;
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
        if (count($DetailBills) > 0) {
            if (!empty($DetailBills[0])){
                $bills = collect();
                collect($DetailBills)->each(function ($detailBill) use (&$bills) {
                    $bill = new PaymentBcaInvocationDetailResponse();
                    $bill->setBillNumber($detailBill['BillNumber']);
                    $bills->push($bill);
                });
                $this->DetailBills = $bills;
            }
        }
    }

    /**
     * @return array
     */
    public function getFreeTexts(): array
    {
        return $this->FreeTexts;
    }

    /**
     * @param array $FreeTexts
     */
    public function setFreeTexts(array $FreeTexts): void
    {
        $this->FreeTexts = $FreeTexts;
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
