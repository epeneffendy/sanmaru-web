<?php
namespace App\Models;
class PaymentBcaInvocationDetailResponse extends \stdClass {
    protected $BillNumber = "";
    protected $Status = "00";
    protected $Reason = array(
        "Indonesian" => "Bill Payment",
        "English" => "Bill Payment"
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
    public function getReason(): array
    {
        return $this->Reason;
    }

    /**
     * @param string[] $Reason
     */
    public function setReason(array $Reason): void
    {
        $this->Reason = $Reason;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->Status;
    }

    /**
     * @param string $Status
     */
    public function setStatus(string $Status): void
    {
        $this->Status = $Status;
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


}
