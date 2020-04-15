<?php

namespace Omnipay\Cathaybk\Message;

use Omnipay\Cathaybk\Traits\AssertSignature;
use Omnipay\Cathaybk\Traits\HasLangParams;
use Omnipay\Cathaybk\Traits\HasStoreParams;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

class CompletePurchaseRequest extends AbstractRequest
{
    use HasStoreParams;
    use HasLangParams;
    use AssertSignature;

    /**
     * @param $strOrderInfo
     * @return CompletePurchaseRequest
     */
    public function setStrOrderInfo($strOrderInfo)
    {
        return $this->setParameter('strOrderInfo', $strOrderInfo);
    }

    /**
     * @return string
     */
    public function getStrOrderInfo()
    {
        return $this->getParameter('strOrderInfo');
    }

    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('strOrderInfo');
        $orderInfo = Helper::xml2array($this->getStrOrderInfo());
        $this->assertSignature($orderInfo, ['STOREID', 'ORDERNUMBER', 'CUBKEY']);

        return $orderInfo;
    }

    /**
     * @param mixed $data
     * @return CompletePurchaseResponse|ResponseInterface
     */
    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
