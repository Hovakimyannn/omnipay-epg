<?php

namespace Omnipay\Epg\Message\Request;

/**
 * Register Binding Order Request
 *
 * This request is used to register an order that will be paid using a previously created binding,
 * or to initiate a binding process.
 */
class RegisterBindingOrderRequest extends RegisterRequest
{
    /**
     * @return string
     */
    public function getBindingId(): ?string
    {
        return $this->getParameter('bindingId');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setBindingId(string $value): RegisterBindingOrderRequest
    {
        return $this->setParameter('bindingId', $value);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $data = parent::getData();

        if ($this->getBindingId()) {
            $data['bindingId'] = $this->getBindingId();
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->getUrl() . '/registerBindingOrder.do';
    }
}
