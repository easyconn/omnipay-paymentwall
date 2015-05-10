<?php

namespace Omnipay\PaymentWall;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway();
        $gateway = $this->gateway;
        $this->gateway->initialize([
            'apiType'      => $gateway::API_GOODS,
            'publicKey'    => 't_1e4df15bd0a1348cd2cf13e66feac9',
            'privateKey'   => 't_9add70aa98ca4e244ae43a5760b8aa',
            'testMode'     => true,
        ]);

        $this->card = new CreditCard($this->getValidCard());
        $this->card->setStartMonth(1);
        $this->card->setStartYear(2000);
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase([
            'amount'                    => '10.00',
            'currency'                  => 'AUD',
            'clientIp'                  => '127.0.0.1',
            'accountId'                 => 12341234,
            'packageId'                 => 1234,
            'packageName'               => 'Super Deluxe Excellent Discount Package',
            'card'                      => $this->card,
        ]);

        $this->assertInstanceOf('Omnipay\PaymentWall\Message\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }
}
