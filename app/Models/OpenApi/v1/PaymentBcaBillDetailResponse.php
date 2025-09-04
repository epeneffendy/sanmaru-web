<?php
namespace App\Models\OpenApi\v1;

use Illuminate\Contracts\Support\Arrayable;

class PaymentBcaBillDetailResponse extends \stdClass
{
    protected $billCode = "";
    protected $billNo = "";
    protected $billName = "";
    protected $billShortName = "";

    protected $billDescription = array(
        "english" => "Bill Payment",
        "indonesia" => "Bill Payment"
    );
    protected $billSubCompany = "";
    protected $billAmount = array(
        "value" => "",
        "currency" => ""
    );
    protected $billAmountLabel = "";
    protected $billAmountValue = "";
    protected $additionalInfo = array();

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
    public function getbillCode(): array
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
    public function getbillNo(): array
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
    public function getbillName(): array
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
    public function getbillShortName(): array
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
    public function getbillDescription(): array
    {
        return $this->billDescription;
    }

    /**
     * @param string[] $billDescription
     */
    public function setbillDescription(array $billDescription): void
    {
        $this->billDescription = $billDescription;
    }

    /**
     * @return string
     */
    public function getbillSubCompany(): string
    {
        return $this->billSubCompany;
    }

    /**
     * @param string $BillSubCompany
     */
    public function setbillSubCompany(string $billSubCompany): void
    {
        $this->billSubCompany = $billSubCompany;
    }

    /**
     * @return string
     */
    public function getbillAmount(): string
    {
        return $this->billAmount;
    }

    /**
     * @param string $BillAmount
     */
    public function setbillAmount($billAmount): void
    {
        $this->billAmount = $billAmount;
    }

    /**
     * @return string
     */
    public function getbillAmountLabel(): string
    {
        return $this->billAmountLabel;
    }

    /**
     * @param string $BillNumber
     */
    public function setbillAmountLabel($billAmountLabel): void
    {
        $this->billAmountLabel = $billAmountLabel;
    }

    /**
     * @return string
     */
    public function getbillAmountValue(): string
    {
        return $this->billAmountValue;
    }

    /**
     * @param string $BillNumber
     */
    public function setbillAmountValue($billAmountValue): void
    {
        $this->billAmountValue = $billAmountValue;
    }

    /**
     * @return string
     */
    public function getadditionalInfo(): string
    {
        return $this->additionalInfo;
    }

    /**
     * @param string $BillNumber
     */
    public function setadditionalInfo($additionalInfo): void
    {
        $this->additionalInfo = $additionalInfo;
    }


}
