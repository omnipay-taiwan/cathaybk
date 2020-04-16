<?php

namespace Omnipay\Cathaybk\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\NotificationInterface;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AcceptNotificationResponse extends AbstractResponse implements NotificationInterface
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->getCode() === '0000';
    }

    /**
     * Response code.
     *
     * @return null|string A response code from the payment gateway
     */
    public function getCode()
    {
        return $this->data['CUBXML']['AUTHINFO']['AUTHSTATUS'];
    }

    /**
     * Gateway Reference
     *
     * @return null|string A reference provided by the gateway to represent this transaction
     */
    public function getTransactionReference()
    {
        return $this->data['CUBXML']['AUTHINFO']['AUTHCODE'];
    }

    /**
     * Get the transaction ID as generated by the merchant website.
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->data['CUBXML']['ORDERINFO']['ORDERNUMBER'];
    }

    /**
     * Response Message.
     *
     * @return string A response message from the payment gateway
     */
    public function getMessage()
    {
        return $this->data['CUBXML']['AUTHINFO']['AUTHMSG'];
    }

    /**
     * Reply Message.
     *
     * @return HttpResponse
     */
    public function getReplyResponse()
    {
        return HttpResponse::create(Helper::array2xml([
            'MERCHANTXML' => [
                'CAVALUE' => $this->data['CAVALUE'],
                'RETURL' => $this->data['RETURL'],
            ],
        ]));
    }

    public function reply()
    {
        $this->getReplyResponse()->send();
    }

    /**
     * @return string
     */
    public function getTransactionStatus()
    {
        return $this->isSuccessful() ? self::STATUS_COMPLETED : self::STATUS_FAILED;
    }
}
