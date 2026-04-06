<?php

namespace Omnipay\Epg\Message\Request;

class DepositRequest extends AbstractRequest
{
    public function getData(): array
    {
        $this->validate('transactionId');

        $data = parent::getData();

        $data['orderId'] = $this->getTransactionId();

        if ($this->getAmountInteger()) {
            $data['amount'] = $this->getAmountInteger();
        }

        return $data;
    }

    public function getEndpoint(): string
    {
        return $this->getUrl() . '/deposit.do';
    }
}
