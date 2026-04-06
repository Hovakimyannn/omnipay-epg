<?php

namespace Omnipay\Epg\Message\Request;

use Omnipay\Epg\Message\Response\BindingPaymentResponse;

/**
 * @method BindingPaymentResponse send()
 */
class BindingPaymentRequest extends AbstractBindingAwareRequest
{
    public function getData(): array
    {
        $data = parent::getData();

        $data['mdOrder']    = $this->getTransactionReference();
        $data['bindingId']  = $this->getBindingId();
        $data['language']   = $this->getLanguage();

        return $data;
    }

    public function getBindingId(): string
    {
        return $this->getParameter('bindingId');
    }

    public function setBindingId(string $value): static
    {
        return $this->setParameter('bindingId', $value);
    }

    public function getEndpoint(): string
    {
        return $this->getUrl() . '/paymentOrderBinding.do';
    }

    protected function createResponse(string $data, array $headers = []): BindingPaymentResponse
    {
        return $this->response = new BindingPaymentResponse($this, $data, $headers);
    }
}
