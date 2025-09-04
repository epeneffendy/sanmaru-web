<?php
namespace App\Models;
use Illuminate\Contracts\Support\Arrayable;

class PaymentBcaBillDetailResponse extends \stdClass {
    protected $BillDescription = array(
        "Indonesian" => "Bill Payment",
        "English" => "Bill Payment"
    );
    protected $BillAmount = "";
    protected $BillNumber = "";
    protected $BillSubCompany = "00000";

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
    public function getBillDescription(): array
    {
        return $this->BillDescription;
    }

    /**
     * @param string[] $BillDescription
     */
    public function setBillDescription(array $BillDescription): void
    {
        $this->BillDescription = $BillDescription;
    }

    /**
     * @return string
     */
    public function getBillAmount(): string
    {
        return $this->BillAmount;
    }

    /**
     * @param string $BillAmount
     */
    public function setBillAmount(string $BillAmount): void
    {
        $this->BillAmount = $BillAmount;
    }

    /**
     * @return string
     */
    public function getBillNumber(): string
    {
        return $this->BillNumber;
    }

    /**
     * @param string $BillNumber
     */
    public function setBillNumber(string $BillNumber): void
    {
        $this->BillNumber = $BillNumber;
    }

    /**
     * @return string
     */
    public function getBillSubCompany(): string
    {
        return $this->BillSubCompany;
    }

    /**
     * @param string $BillSubCompany
     */
    public function setBillSubCompany(string $BillSubCompany): void
    {
        $this->BillSubCompany = $BillSubCompany;
    }

}
