<?php

namespace Omnipay\Epg;

use Omnipay\Epg\Message\Request\BindingPaymentRequest;
use Omnipay\Epg\Message\Request\DepositRequest;
use Omnipay\Epg\Message\Request\GetBindingsRequest;
use Omnipay\Epg\Message\Request\GetOrderStatusExtendedRequest;
use Omnipay\Epg\Message\Request\GetOrderStatusRequest;
use Omnipay\Epg\Message\Request\RefundRequest;
use Omnipay\Epg\Message\Request\RegisterBindingOrderRequest;
use Omnipay\Epg\Message\Request\RegisterPreAuthRequest;
use Omnipay\Epg\Message\Request\RegisterRequest;
use Omnipay\Epg\Message\Request\ReverseRequest;
use Omnipay\Epg\Message\Request\VerifyEnrollmentRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * SmartVista EPG Gateway
 *
 * Generic driver for the SmartVista E-Commerce Payment Gateway (EPG).
 * Set `endpoint` and `testEndpoint` to target any EPG-based bank.
 * Use {@see ArcaGateway} for Arca / iPay with pre-configured endpoints.
 *
 * @method RequestInterface authorize(array $options = [])
 * @method RequestInterface completeAuthorize(array $options = [])
 * @method RequestInterface capture(array $options = [])
 * @method RequestInterface void(array $options = [])
 * @method RequestInterface createCard(array $options = [])
 * @method RequestInterface updateCard(array $options = [])
 * @method RequestInterface deleteCard(array $options = [])
 * @method NotificationInterface acceptNotification(array $options = [])
 * @method RequestInterface fetchTransaction(array $options = [])
 */
class Gateway extends AbstractGateway
{
    public function getName(): string
    {
        return 'EPG';
    }

    public function getDefaultParameters(): array
    {
        return [
            'username'     => '',
            'password'     => '',
            'endpoint'     => '',
            'testEndpoint' => '',
        ];
    }

    // -------------------------------------------------------------------------
    // Credentials
    // -------------------------------------------------------------------------

    public function getUsername(): string
    {
        return $this->getParameter('username');
    }

    public function setUsername($value): static
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword(): string
    {
        return $this->getParameter('password');
    }

    public function setPassword($value): static
    {
        return $this->setParameter('password', $value);
    }

    /**
     * Optional second username used specifically for binding-related requests.
     */
    public function getBindingUsername(): ?string
    {
        return $this->getParameter('bindingUsername');
    }

    public function setBindingUsername(?string $value): static
    {
        return $this->setParameter('bindingUsername', $value);
    }

    // -------------------------------------------------------------------------
    // Endpoint configuration
    // -------------------------------------------------------------------------

    public function getEndpoint(): string
    {
        return $this->getParameter('endpoint');
    }

    public function setEndpoint(string $value): static
    {
        return $this->setParameter('endpoint', $value);
    }

    public function getTestEndpoint(): string
    {
        return $this->getParameter('testEndpoint');
    }

    public function setTestEndpoint(string $value): static
    {
        return $this->setParameter('testEndpoint', $value);
    }

    // -------------------------------------------------------------------------
    // Requests
    // -------------------------------------------------------------------------

    public function purchase(array $options = []): AbstractRequest
    {
        return $this->createRequest(RegisterRequest::class, $options);
    }

    public function completePurchase(array $options = []): AbstractRequest
    {
        return $this->getOrderStatusExtended($options);
    }

    public function registerPreAuth(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(RegisterPreAuthRequest::class, $parameters);
    }

    public function getOrderStatus(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(GetOrderStatusRequest::class, $parameters);
    }

    public function getOrderStatusExtended(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(GetOrderStatusExtendedRequest::class, $parameters);
    }

    public function verifyEnrollment(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(VerifyEnrollmentRequest::class, $parameters);
    }

    public function deposit(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(DepositRequest::class, $parameters);
    }

    public function reverse(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(ReverseRequest::class, $parameters);
    }

    public function refund(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    public function bindingPayment(array $parameters = []): BindingPaymentRequest
    {
        return $this->createRequest(BindingPaymentRequest::class, $parameters);
    }

    public function getBindings(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(GetBindingsRequest::class, $parameters);
    }

    public function registerBindingOrder(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(RegisterBindingOrderRequest::class, $parameters);
    }

    public function __call($name, $arguments)
    {
        // TODO: acceptNotification, fetchTransaction
    }
}
