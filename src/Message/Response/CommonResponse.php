<?php

namespace Omnipay\Epg\Message\Response;

/**
 * EPG Common Response.
 *
 * Used for all standard EPG requests.
 *
 * @see \Omnipay\Epg\Gateway
 */
class CommonResponse extends AbstractResponse
{
    public function getRedirectUrl(): string
    {
        return $this->data['formUrl'];
    }

    public function getTransactionReference(): ?string
    {
        return $this->data['orderId'] ?? null;
    }

    public function getOrderNumberReference(): mixed
    {
        return $this->data['OrderNumber'] ?? $this->data['orderNumber'] ?? null;
    }

    public function getOrderStatus()
    {
        return $this->data['orderStatus'] ?? null;
    }

    public function getActionCodeDescription(): ?string
    {
        return $this->data['actionCodeDescription'] ?? null;
    }

    public function getBindingId(): ?string
    {
        return $this->data['bindingInfo']['bindingId'] ?? null;
    }

    public function getCardAuthInfo(): array
    {
        return $this->data['cardAuthInfo'] ?? [];
    }

    public function getRequestId(): ?string
    {
        return isset($this->headers['Request-Id'])
            ? $this->headers['Request-Id'][0]
            : null;
    }
}
