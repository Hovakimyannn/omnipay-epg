<?php

namespace Omnipay\Epg;

/**
 * Arca EPG Gateway
 *
 * Pre-configured EPG gateway for Arca's EPG system (testepg.arca.am).
 */
class ArcaEpgGateway extends Gateway
{
    public function getName(): string
    {
        return 'Arca EPG';
    }

    public function getDefaultParameters(): array
    {
        return array_merge(parent::getDefaultParameters(), [
            'endpoint'     => 'https://epg.arca.am/payment/rest',
            'testEndpoint' => 'https://testepg.arca.am/payment/rest',
        ]);
    }
}
