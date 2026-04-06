<?php

namespace Omnipay\Epg\Message\Request;

class RegisterPreAuthRequest extends AbstractRequest
{
    public function getPageView()
    {
        return $this->getParameter('pageView');
    }

    public function setPageView(string $value): static
    {
        return $this->setParameter('pageView', $value);
    }

    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    public function setClientId($value): static
    {
        return $this->setParameter('clientId', $value);
    }

    public function getTimeout()
    {
        return $this->getParameter('sessionTimeoutSecs');
    }

    public function setTimeout($value): static
    {
        return $this->setParameter('sessionTimeoutSecs', $value);
    }

    public function getFailUrl()
    {
        return $this->getParameter('failUrl');
    }

    public function setFailUrl($value): static
    {
        return $this->setParameter('failUrl', $value);
    }

    public function getData(): array
    {
        $this->validate('transactionId', 'amount', 'returnUrl');

        $data = parent::getData();

        $data['orderNumber'] = $this->getTransactionId();
        $data['amount']      = $this->getAmountInteger();
        $data['returnUrl']   = $this->getReturnUrl();

        if ($this->getCurrency()) {
            $data['currency'] = str_pad($this->getCurrencyNumeric(), 3, '0', STR_PAD_LEFT);
        }

        if ($this->getDescription()) {
            $data['description'] = $this->getDescription();
        }

        if ($this->getLanguage()) {
            $data['language'] = $this->getLanguage();
        }

        if ($this->getPageView()) {
            $data['pageView'] = $this->getPageView();
        }

        if ($this->getClientId()) {
            $data['clientId'] = $this->getClientId();
        }

        if ($this->getJsonParams()) {
            $data['jsonParams'] = $this->getJsonParams();
        }

        if ($this->getTimeout()) {
            $data['sessionTimeoutSecs'] = $this->getTimeout();
        }

        if ($this->getFailUrl()) {
            $data['failUrl'] = $this->getFailUrl();
        }

        return $data;
    }

    public function getEndpoint(): string
    {
        return $this->getUrl() . '/registerPreAuth.do';
    }
}
