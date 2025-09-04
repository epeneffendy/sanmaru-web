<?php
namespace App\Models\OpenApi\v1;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class PaymentVirtualAccountDataFailedResponse extends \stdClass
{
    private $inquiryStatus = "00";
    private $inquiryReason = array(
        "english" => "Success",
        "indonesia" => "Sukses"
    );
    private $partnerServiceId = "";
    private $customerNo = "";
    private $virtualAccountNo = "";
    private $virtualAccountName = "";
    private $virtualAccountEmail = "";
    private $virtualAccountPhone = "";
    private $inquiryRequestId = "";
    private $totalAmount = array(
        "value" => "0",
        "currency" => "IDR"
    );
    private $subCompany = "00000";
    private $billDetails = array(
        "billCode" => "",
        "billNo" => "",
        "billName" => "",
        "billShortName" => "",
        "billDescription" => array(
            "english" => "",
            "indonesia" => ""
        ),
        "billSubCompany" => "",
        "billAmount" => array(
            "value" => "0",
            "currency" => "IDR"
        ),
        "billAmountLabel" => "",
        "billAmountValue" => "",
        "additionalInfo" => []
    );
    private $freeTexts = array();
    private $virtualAccountTrxType = "1";
    private $feeAmount = null;
    private $additionalInfo = array();


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
    public function getinquiryStatus()
    {
        return $this->inquiryStatus;
    }

    /**
     * @param mixed $inquiryStatus
     */
    public function setinquiryStatus($inquiryStatus): void
    {
        $this->inquiryStatus = $inquiryStatus;
    }

    /**
     * @return mixed
     */
    public function getinquiryReason()
    {
        return $this->inquiryReason;
    }

    /**
     * @param mixed $inquiryReason
     */
    public function setinquiryReason($inquiryReason): void
    {
        $this->inquiryReason = $inquiryReason;
    }

    /**
     * @return mixed
     */
    public function getpartnerServiceId()
    {
        return $this->partnerServiceId;
    }

    /**
     * @param mixed $partnerServiceId
     */
    public function setpartnerServiceId($partnerServiceId): void
    {
        $this->partnerServiceId =$partnerServiceId;
    }

    /**
     * @return mixed
     */
    public function getcustomerNo()
    {
        return $this->customerNo;
    }

    /**
     * @param mixed $customerNo
     */
    public function setcustomerNo($customerNo): void
    {
        $this->customerNo = $customerNo;
    }

    /**
     * @return mixed
     */
    public function getvirtualAccountNo()
    {
        return $this->virtualAccountNo;
    }

    /**
     * @param mixed $virtualAccountNo
     */
    public function setvirtualAccountNo($virtualAccountNo): void
    {
        $this->virtualAccountNo = $virtualAccountNo;
    }

    /**
     * @return mixed
     */
    public function getvirtualAccountName()
    {
        return $this->virtualAccountName;
    }

    /**
     * @param mixed $virtualAccountName
     */
    public function setvirtualAccountName($virtualAccountName): void
    {
        $this->virtualAccountName = $virtualAccountName;
    }

    /**
     * @return mixed
     */
    public function getvirtualAccountEmail()
    {
        return $this->virtualAccountEmail;
    }

    /**
     * @param mixed $virtualAccountEmail
     */
    public function setvirtualAccountEmail($virtualAccountEmail): void
    {
        $this->virtualAccountEmail = $virtualAccountEmail;
    }

    /**
     * @return mixed
     */
    public function getvirtualAccountPhone()
    {
        return $this->virtualAccountPhone;
    }

    /**
     * @param mixed $virtualAccountPhone
     */
    public function setvirtualAccountPhone($virtualAccountPhone): void
    {
        $this->virtualAccountPhone = $virtualAccountPhone;
    }

    /**
     * @return mixed
     */
    public function getinquiryRequestId()
    {
        return $this->inquiryRequestId;
    }

    /**
     * @param mixed $inquiryRequestId
     */
    public function setinquiryRequestId($inquiryRequestId): void
    {
        $this->inquiryRequestId = $inquiryRequestId;
    }

    /**
     * @return mixed
     */
    public function gettotalAmount()
    {
        return $this->freeTexts;
    }

    /**
     * @param mixed $totalAmount
     */
    public function settotalAmount($totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    /**
     * @return mixed
     */
    public function getsubCompany()
    {
        return $this->subCompany;
    }

    /**
     * @param mixed $subCompany
     */
    public function setsubCompany($subCompany): void
    {
        $this->subCompany = $subCompany;
    }

    /**
     * @return mixed
     */
    public function getbillDetails()
    {
        return $this->billDetails;
    }

    /**
     * @param mixed $billDetails
     */
    public function setbillDetails($billDetails): void
    {
        if (count($billDetails) > 0) {
            $bills = collect();
            collect($billDetails)->each(function ($detailBill) use (&$bills) {
                $bill = new PaymentBcaBillDetailResponse();
                $bill->setbillCode($detailBill['billCode']);
                $bill->setbillNo($detailBill['billNo']);
                $bill->setbillName($detailBill['billName']);
                $bill->setbillShortName($detailBill['billShortName']);
                $bill->setbillDescription(array(
                    "english" => $detailBill['billDescription']['english'],
                    "indonesia" => $detailBill['billDescription']['indonesia'],
                ));
                $bill->setbillSubCompany("00000");
                $bill->setbillAmount(array(
                    "value" => $detailBill['billAmount']['value'],
                    "currency" => $detailBill['billAmount']['currency']
                ));
                $bill->setbillAmountLabel($detailBill['billAmountLabel']);
                $bill->setbillAmountValue($detailBill['billAmountValue']);
                $bill->setadditionalInfo((object)array());
                $bills->push($bill);
            });

        }else{
            $bills = array();
        }
        $this->billDetails = $bills;
    }

    /**
     * @return mixed
     */
    public function getfreeTexts()
    {
        return $this->freeTexts;
    }

    /**
     * @param mixed $freeTexts
     */
    public function setfreeTexts($freeTexts): void
    {
        $this->freeTexts = $freeTexts;
    }

    /**
     * @return mixed
     */
    public function getvirtualAccountTrxType()
    {
        return $this->virtualAccountTrxType;
    }

    /**
     * @param mixed $virtualAccountTrxType
     */
    public function setvirtualAccountTrxType($virtualAccountTrxType): void
    {
        $this->virtualAccountTrxType = $virtualAccountTrxType;
    }

    /**
     * @return mixed
     */
    public function getfeeAmount()
    {
        return $this->feeAmount;
    }

    /**
     * @param mixed $feeAmount
     */
    public function setfeeAmount($feeAmount): void
    {
        $this->feeAmount = $feeAmount;
    }

    /**
     * @return mixed
     */
    public function getadditionalInfo()
    {
        return $this->additionalInfo;
    }

    /**
     * @param mixed $FadditionalInfo
     */
    public function setadditionalInfo($additionalInfo): void
    {
        $this->additionalInfo = $additionalInfo;
    }
}



