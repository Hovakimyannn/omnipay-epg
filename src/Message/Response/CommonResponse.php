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

    public function getOrderStatus(): ?int
    {
        return isset($this->data['orderStatus']) ? (int) $this->data['orderStatus'] : null;
    }

    public function getActionCode(): ?int
    {
        return isset($this->data['actionCode']) ? (int) $this->data['actionCode'] : null;
    }

    public function getActionCodeDescription(): ?string
    {
        return $this->data['actionCodeDescription'] ?? null;
    }

    public function getBindingId(): ?string
    {
        return $this->data['bindingId'] ?? $this->data['bindingInfo']['bindingId'] ?? null;
    }

    public function getClientId(): ?string
    {
        return $this->data['clientId'] ?? $this->data['bindingInfo']['clientId'] ?? null;
    }

    public function getCardAuthInfo(): array
    {
        return $this->data['cardAuthInfo'] ?? [];
    }

    public function getPan(): ?string
    {
        return $this->data['cardAuthInfo']['pan'] ?? null;
    }

    public function getExpiration(): ?string
    {
        return $this->data['cardAuthInfo']['expiration'] ?? null;
    }

    public function getMerchantOrderParams(): array
    {
        $params = [];
        if (isset($this->data['merchantOrderParams']) && is_array($this->data['merchantOrderParams'])) {
            foreach ($this->data['merchantOrderParams'] as $param) {
                if (isset($param['name'], $param['value'])) {
                    $params[$param['name']] = $param['value'];
                }
            }
        }

        return $params;
    }

    public function getRequestId(): ?string
    {
        return isset($this->headers['Request-Id'])
            ? $this->headers['Request-Id'][0]
            : null;
    }
}
