<?php
namespace App\Models\OpenApi\v1;

use FontLib\TrueType\Collection;

class PaymentBcaInvocationDetailResponse extends \stdClass
{
    public $paymentFlagReason = array(
        "english" => "Success",
        "indonesia" => "Sukses"
    );
    public $partnerServiceId = "";
    public $customerNo = "";
    public $virtualAccountNo = "";
    public $virtualAccountName = "";
    public $virtualAccountEmail = "";
    public $virtualAccountPhone = "";
    public $trxId = "";
    public $paymentRequestId = "";
    public $paidAmount = array(
        "value" => "0.00",
        "currency" => "IDR"
    );
    public $paidBills = "";
    public $totalAmount = array(
        "value" => "0.00",
        "currency" => "IDR"
    );
    public $trxDateTime = "";
    public $referenceNo = "";
    public $journalNum = "";
    public $paymentType = "";
    public $flagAdvise = "";
    public $paymentFlagStatus = "";
    public $billDetails = array();
    public $freeTexts = array();

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
    public function getpaymentFlagReason(): string
    {
        return $this->paymentFlagReason;
    }

    /**
     * @param string[] $paymentFlagReason
     */
    public function setpaymentFlagReason($paymentFlagReason): void
    {
        $this->paymentFlagReason = $paymentFlagReason;
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
        $lengthString = 19;
        $this->virtualAccountNo = str_pad($virtualAccountNo, $lengthString, " ", STR_PAD_LEFT);
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
     * @param string $customerNo
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
     * @param string $trxId
     */
    public function settrxId($trxId): void
    {
        $this->trxId = $trxId;
    }

    /**
     * @return string
     */
    public function getpaymentRequestId(): string
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
    public function getpaidAmount(): string
    {
        return $this->paidAmount;
    }

    /**
     * @param string $paidAmount
     */
    public function setpaidAmount($paidAmount): void
    {
        $this->paidAmount = $paidAmount;
    }

    /**
     * @return string
     */
    public function getpaidBills(): string
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
    public function gettotalAmount(): string
    {
        return $this->totalAmount;
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
    public function gettrxDateTime(): string
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
    public function getreferenceNo(): string
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
    public function getjournalNum(): string
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
    public function getpaymentType(): string
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
    public function getflagAdvise(): string
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
    public function getpaymentFlagStatus(): string
    {
        return $this->paymentFlagStatus;
    }

    /**
     * @param string $paymentFlagStatus
     */
    public function setpaymentFlagStatus($paymentFlagStatus): void
    {
        $this->paymentFlagStatus = $paymentFlagStatus;
    }

    /**
     * @return string
     */
    public function getbillDetails()
    {
        return $this->billDetails;
    }

    /**
     * @param string $billDetails
     */
    public function setbillDetails($billDetails): void
    {
        if (count($billDetails) > 0) {
            $bills = collect();
            collect($billDetails)->each(function ($detailBill) use (&$bills) {
                $bill = new PaymentBcaInvocationDetailBillsResponse();
                $bill->setbillerReferenceId($detailBill['billReferenceNo']);
                $bill->setbillCode($detailBill['billCode']);
                $bill->setbillNo($detailBill['billNo']);
                $bill->setbillName($detailBill['billName']);
                $bill->setbillShortName($detailBill['billShortName']);
                $bill->setbillDescription($detailBill['billDescription']);
                $bill->setbillSubCompany($detailBill['billSubCompany']);
                $bill->setbillAmount($detailBill['billAmount']);
                $bill->setadditionalInfo((object)array());
                $bill->getstatus();
                $bill->getreason();
                $bills->push($bill);
            });
            $this->billDetails = $bills;
        }
    }

    /**
     * @return string
     */
    public function getfreeTexts()
    {
        return $this->freeTexts;
    }

    /**
     * @param string $freeText
     */
    public function setfreeTexts($freeTexts): void
    {
        $this->freeTexts = $freeTexts;
    }


}
