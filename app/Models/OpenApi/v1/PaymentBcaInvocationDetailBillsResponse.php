<?php
namespace App\Models\OpenApi\v1;

use FontLib\TrueType\Collection;

class PaymentBcaInvocationDetailBillsResponse extends \stdClass
{
    public $billerReferenceId = "";
    public $billCode = "";
    public $billNo = "";
    public $billName = "";
    public $billShortName = "";
    public $billDescription = array(
        "english" => "Success",
        "indonesia" => "Sukses"
    );
    public $billSubCompany = "";
    public $billAmount = array(
        "value" => "0.00",
        "currency" => "IDR"
    );
    public $additionalInfo = array();
    public $status = "00";
    public $reason = array(
        "english" => "Success",
        "indonesia" => "Sukses"
    );

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
     * @return string[]
     */
    public function getbillerReferenceId()
    {
        return $this->billerReferenceId;
    }

    /**
     * @param string[] $billerReferenceId
     */
    public function setbillerReferenceId($billerReferenceId): void
    {
        $this->billerReferenceId = $billerReferenceId;
    }

    /**
     * @return string[]
     */
    public function getbillCode()
    {
        return $this->billCode;
    }

    /**
     * @param string[] $billCode
     */
    public function setbillCode($billCode): void
    {
        $this->billCode = $billCode;
    }

    /**
     * @return string[]
     */
    public function getbillNo()
    {
        return $this->billNo;
    }

    /**
     * @param string[] $billNo
     */
    public function setbillNo($billNo): void
    {
        $this->billNo = $billNo;
    }

    /**
     * @return string[]
     */
    public function getbillName()
    {
        return $this->billName;
    }

    /**
     * @param string[] $billName
     */
    public function setbillName($billName): void
    {
        $this->billName = $billName;
    }

    /**
     * @return string[]
     */
    public function getbillShortName()
    {
        return $this->billShortName;
    }

    /**
     * @param string[] $billShortName
     */
    public function setbillShortName($billShortName): void
    {
        $this->billShortName = $billShortName;
    }

    /**
     * @return string[]
     */
    public function getbillDescription()
    {
        return $this->billDescription;
    }

    /**
     * @param string[] $billDescription
     */
    public function setbillDescription($billDescription): void
    {
        $this->billDescription = $billDescription;
    }

    /**
     * @return string[]
     */
    public function getbillSubCompany()
    {
        return $this->billSubCompany;
    }

    /**
     * @param string[] $billSubCompany
     */
    public function setbillSubCompany($billSubCompany): void
    {
        $this->billSubCompany = $billSubCompany;
    }

    /**
     * @return string[]
     */
    public function getbillAmount()
    {
        return $this->billAmount;
    }

    /**
     * @param string[] $billAmount
     */
    public function setbillAmount($billAmount): void
    {
        $this->billAmount = $billAmount;
    }

    /**
     * @return string[]
     */
    public function getadditionalInfo()
    {
        return $this->additionalInfo;
    }

    /**
     * @param string[] $additionalInfo
     */
    public function setadditionalInfo($additionalInfo): void
    {
        $this->additionalInfo = $additionalInfo;
    }

    /**
     * @return string[]
     */
    public function getstatus()
    {
        return $this->status;
    }

    /**
     * @param string[] $status
     */
    public function setstatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return string[]
     */
    public function getreason()
    {
        return $this->reason;
    }

    /**
     * @param string[] $reason
     */
    public function setreason($reason): void
    {
        $this->reason = $reason;
    }

}