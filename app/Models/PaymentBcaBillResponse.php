<?php

namespace App\Models;
use Illuminate\Support\Collection;

class PaymentBcaBillResponse extends \stdClass
{
    private $CompanyCode = "";
    private $CustomerNumber = "";
    private $RequestID = "";
    private $InquiryStatus = "00";
    private $InquiryReason = array(
        "Indonesian" => "",
        "English" => "",
    );
    private $CustomerName = "";
    private $CurrencyCode = "IDR";
    private $TotalAmount = "0.00";
    private $SubCompany = "00000";
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

    public function toArray()
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
     * @return mixed
     */
    public function getCompanyCode()
    {
        return $this->CompanyCode;
    }

    /**
     * @param mixed $CompanyCode
     */
    public function setCompanyCode($CompanyCode): void
    {
        $this->CompanyCode = $CompanyCode;
    }

    /**
     * @return mixed
     */
    public function getCustomerNumber()
    {
        return $this->CustomerNumber;
    }

    /**
     * @param mixed $CustomerNumber
     */
    public function setCustomerNumber($CustomerNumber): void
    {
        $this->CustomerNumber = $CustomerNumber;
    }

    /**
     * @return mixed
     */
    public function getRequestID()
    {
        return $this->RequestID;
    }

    /**
     * @param mixed $RequestID
     */
    public function setRequestID($RequestID): void
    {
        $this->RequestID = $RequestID;
    }

    /**
     * @return mixed
     */
    public function getInquiryStatus()
    {
        return $this->InquiryStatus;
    }

    /**
     * @param mixed $InquiryStatus
     */
    public function setInquiryStatus($InquiryStatus): void
    {
        $this->InquiryStatus = $InquiryStatus;
    }

    /**
     * @return mixed
     */
    public function getInquiryReason()
    {
        return $this->InquiryReason;
    }

    /**
     * @param mixed $InquiryReason
     */
    public function setInquiryReason($InquiryReason): void
    {
        $this->InquiryReason = $InquiryReason;
    }

    /**
     * @return mixed
     */
    public function getCustomerName()
    {
        return $this->CustomerName;
    }

    /**
     * @param mixed $CustomerName
     */
    public function setCustomerName($CustomerName): void
    {
        $this->CustomerName = $CustomerName;
    }

    /**
     * @return mixed
     */
    public function getCurrencyCode()
    {
        return $this->CurrencyCode;
    }

    /**
     * @param mixed $CurrencyCode
     */
    public function setCurrencyCode($CurrencyCode): void
    {
        $this->CurrencyCode = $CurrencyCode;
    }

    /**
     * @return mixed
     */
    public function getTotalAmount()
    {
        return $this->TotalAmount;
    }

    /**
     * @param mixed $TotalAmount
     */
    public function setTotalAmount($TotalAmount): void
    {
        $this->TotalAmount = $TotalAmount;
    }

    /**
     * @return mixed
     */
    public function getSubCompany()
    {
        return $this->SubCompany;
    }

    /**
     * @param mixed $SubCompany
     */
    public function setSubCompany($SubCompany): void
    {
        $this->SubCompany = $SubCompany;
    }

    /**
     * @return mixed
     */
    public function getDetailBills()
    {
        return $this->DetailBills;
    }

    /**
     * @param mixed $DetailBills
     */
    public function setDetailBills($DetailBills): void
    {
        if (count($DetailBills) > 0) {
            $bills = collect();
            collect($DetailBills)->each(function ($detailBill) use (&$bills) {
                $bill = new PaymentBcaBillDetailResponse();
                $bill->setBillNumber($detailBill['BillNumber']);
                $bill->setBillAmount($detailBill['BillAmount']);
                $bills->push($bill);
            });
            $this->DetailBills = $bills;
        }
    }

    /**
     * @return mixed
     */
    public function getFreeTexts()
    {
        return $this->FreeTexts;
    }

    /**
     * @param mixed $FreeTexts
     */
    public function setFreeTexts($FreeTexts): void
    {
        $this->FreeTexts = $FreeTexts;
    }

    /**
     * @return mixed
     */
    public function getAdditionalData()
    {
        return $this->AdditionalData;
    }

    /**
     * @param mixed $AdditionalData
     */
    public function setAdditionalData($AdditionalData): void
    {
        $this->AdditionalData = $AdditionalData;
    }


}
