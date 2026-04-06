<?php

namespace Omnipay\Epg;

/**
 * Arca / iPay Gateway
 *
 * Pre-configured EPG gateway for Arca bank (ipay.arca.am).
 * All EPG functionality is inherited from {@see Gateway};
 * this class only sets the correct live and test endpoint URLs.
 *
 * Usage:
 *   $gateway = Omnipay::create('Epg\Arca');
 *   $gateway->setUsername('...');
 *   $gateway->setPassword('...');
 */
class ArcaGateway extends Gateway
{
    public function getName(): string
    {
        return 'Arca';
    }

    public function getDefaultParameters(): array
    {
        return array_merge(parent::getDefaultParameters(), [
            'endpoint'     => 'https://ipay.arca.am/payment/rest',
            'testEndpoint' => 'https://ipaytest.arca.am:8445/payment/rest',
        ]);
    }
}
