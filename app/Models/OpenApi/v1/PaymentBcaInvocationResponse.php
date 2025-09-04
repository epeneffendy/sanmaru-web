<?php

namespace App\Models\OpenApi\v1;

use Illuminate\Support\Collection;

class PaymentBcaInvocationResponse extends \stdClass
{
    private $responseCode = "2002500";
    private $responseMessage = "Successful";
    private $virtualAccountData = array();
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


    public function toArray(): array
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
    public function getresponseCode(): string
    {
        return $this->responseCode;
    }

    /**
     * @param string $responseCode
     */
    public function setresponseCode($responseCode): void
    {
        $this->responseCode = $responseCode;
    }

    /**
     * @return string
     */
    public function getresponseMessage(): string
    {
        return $this->responseMessage;
    }

    /**
     * @param string $responseMessage
     */
    public function setresponseMessage($responseMessage): void
    {
        $this->responseMessage = $responseMessage;
    }

    /**
     * @return string
     */
    public function getvirtualAccountData(): Collection
    {
        return collect($this->virtualAccountData);
    }

    /**
     * @param string $virtualAccountData
     */
    public function setvirtualAccountData($virtualAccountData): void
    {
//        $detail = new PaymentBcaInvocationDetailResponse();
//        $detail = $virtualAccountData;
//        $this->virtualAccountData = $detail;
        $this->virtualAccountData = $virtualAccountData;
    }

    /**
     * @return string
     */
    public function getadditionalInfo()
    {
        return $this->additionalInfo;
    }

    /**
     * @param string $additionalInfo
     */
    public function setadditionalInfo($additionalInfo): void
    {
        $this->additionalInfo = $additionalInfo;
    }

}
