<?php

namespace App\Models\OpenApi\v1;

use Illuminate\Support\Collection;

class PaymentBcaBillResponse extends \stdClass
{
    private $responseCode = "2002400";
    private $responseMessage = "Successful";
    private $virtualAccountData = array(
        "inquiryStatus" => "00",
        "inquiryReason" => array(
            "english" => "Success",
            "indonesia" => "Sukses"
        ),

        "partnerServiceId" =>"",
        "customerNo" =>"",
        "virtualAccountNo" =>"",
        "virtualAccountName" =>"",
        "virtualAccountEmail" =>"",
        "virtualAccountPhone" =>"",
        "inquiryRequestId" =>"",
        "totalAmount" =>array(
            "value"=>"0",
            "currency" => "IDR"
        ),
        "subCompany"=>"00000",
        "billDetails" =>array(),
        "freeTexts" => array(),
        "virtualAccountTrxType" => "1",
        "feeAmount" => null,
        "additionalInfo" => array(),
    );

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
            $response[$name] = $value;
        }
        return $response;
    }

    /**
     * @return mixed
     */
    public function getresponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param mixed $responseCode
     */
    public function setresponseCode($responseCode): void
    {
        $this->responseCode = $responseCode;
    }

    /**
     * @return mixed
     */
    public function getresponseMessage()
    {
        return $this->responseMessage;
    }

    /**
     * @param mixed $responseMessage
     */
    public function setresponseMessage($responseMessage): void
    {
        $this->responseMessage = $responseMessage;
    }

    public function getvirtualAccountData()
    {
        return $this->virtualAccountData;
    }

    /**
     * @param mixed $virtualAccountData
     */
    public function setvirtualAccountData($virtualAccountData): void
    {
        $this->virtualAccountData = $virtualAccountData;
    }


}
