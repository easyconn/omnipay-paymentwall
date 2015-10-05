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
        $this->gateway->initialize(array(
            'apiType'      => $gateway::API_GOODS,
            'publicKey'    => '',
            'privateKey'   => '',
        ));

        $this->card = new CreditCard($this->getValidCard());
        $this->card->setStartMonth(1);
        $this->card->setStartYear(2000);
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array(
            'amount'                    => '10.00',
            'currency'                  => 'AUD',
            'clientIp'                  => '127.0.0.1',
            'accountId'                 => 12341234,
            'packageId'                 => 1234,
            'packageName'               => 'Super Deluxe Excellent Discount Package',
            'card'                      => $this->card,
        ));

        $this->assertInstanceOf('Omnipay\PaymentWall\Message\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }
}
