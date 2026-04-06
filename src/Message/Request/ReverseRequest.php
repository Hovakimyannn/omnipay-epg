<?php

namespace Omnipay\Epg\Message\Request;

/**
 * Class ReverseRequest
 * @package Omnipay\Epg\Message
 */
class ReverseRequest extends AbstractRequest
{

    /**
     * Prepare data to send
     *
     * @return array|mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData() : array
    {
        $this->validate('transactionId');

        $data = parent::getData();

        $data['orderId'] = $this->getTransactionId();

        return $data;
    }

    /**
     * @return string
     */
    public function getEndpoint() : string
    {
        return $this->getUrl() . '/reverse.do';
    }
}
