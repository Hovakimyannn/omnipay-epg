<?php

namespace Omnipay\Epg\Message\Request;

use Omnipay\Epg\Message\Response\CommonResponse;
use Omnipay\Common\Message\AbstractRequest as CommonAbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

abstract class AbstractRequest extends CommonAbstractRequest
{
    // -------------------------------------------------------------------------
    // Credentials
    // -------------------------------------------------------------------------

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value): static
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value): static
    {
        return $this->setParameter('password', $value);
    }

    public function getBindingUsername(): ?string
    {
        return $this->getParameter('bindingUsername');
    }

    public function setBindingUsername($value): static
    {
        return $this->setParameter('bindingUsername', $value);
    }

    // -------------------------------------------------------------------------
    // Common request parameters
    // -------------------------------------------------------------------------

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setLanguage($value): static
    {
        return $this->setParameter('language', $value);
    }

    public function getJsonParams()
    {
        return $this->getParameter('jsonParams');
    }

    public function setJsonParams(string $value): static
    {
        return $this->setParameter('jsonParams', $value);
    }

    // -------------------------------------------------------------------------
    // Endpoint resolution — reads from gateway parameters so that
    // any EPG-based bank can be targeted by configuring the Gateway.
    //
    // Omnipay's Helper::initialize() propagates gateway parameters to requests
    // by calling set*() methods, so both setters must exist here.
    // -------------------------------------------------------------------------

    abstract public function getEndpoint(): string;

    public function getEndpointBase(): string
    {
        return $this->getParameter('endpoint') ?? '';
    }

    public function setEndpoint(string $value): static
    {
        return $this->setParameter('endpoint', $value);
    }

    public function getTestEndpointBase(): string
    {
        return $this->getParameter('testEndpoint') ?? '';
    }

    public function setTestEndpoint(string $value): static
    {
        return $this->setParameter('testEndpoint', $value);
    }

    public function getUrl(): string
    {
        $url = $this->getTestMode()
            ? $this->getParameter('testEndpoint')
            : $this->getParameter('endpoint');

        return rtrim((string) $url, '/');
    }

    // -------------------------------------------------------------------------
    // HTTP layer
    // -------------------------------------------------------------------------

    public function getHttpMethod(): string
    {
        return 'POST';
    }

    public function getHeaders(): array
    {
        return [];
    }

    public function sendData($data): ResponseInterface
    {
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $body    = $data ? http_build_query($data, '', '&') : null;

        $httpResponse = $this->httpClient->request(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            $headers,
            $body
        );

        return $this->createResponse(
            $httpResponse->getBody()->getContents(),
            $httpResponse->getHeaders()
        );
    }

    protected function createResponse(string $data, array $headers = []): ResponseInterface
    {
        return $this->response = new CommonResponse($this, $data, $headers);
    }

    // -------------------------------------------------------------------------
    // Base data — credentials added to every request
    // -------------------------------------------------------------------------

    public function getData(): array
    {
        $this->validate('username', 'password');

        return [
            'userName' => $this->getUsername(),
            'password' => $this->getPassword(),
        ];
    }
}
