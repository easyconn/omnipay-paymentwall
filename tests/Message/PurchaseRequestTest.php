<?php

namespace Omnipay\PaymentWall\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /** @var PurchaseRequest */
    protected $request;

    /** @var CreditCard */
    protected $card;

    public static function setUpBeforeClass()
    {
        /* can't create mocks because the classes that we are mocking contain
         * public static methods (so can't use normal mocks) and the alias
         * mocks are failing because the classes are being loaded via a require_once
         * statement rather than an autoloader.  FIXME: Get the classes loaded via an
         * autoloader.
            $success = json_encode(['success' => 1]);
            $configMock = \Mockery::mock('alias:\Paymentwall_Config');
            $configMock->shouldReceive('getInstance')->once();
            $chargeMock = \Mockery::mock('alias:\Paymentwall_Charge');
            $chargeMock->shouldReceive('charge')->once()->andReturn($success);
         */
    }

    public function setUp()
    {
        $this->gateway = new \Omnipay\PaymentWall\Gateway();
        $gateway = $this->gateway;

        $this->card = new CreditCard($this->getValidCard());
        $this->card->setStartMonth(1);
        $this->card->setStartYear(2000);

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'apiType'               => $gateway::API_GOODS,
            'publicKey'             => 'asdfasdf',
            'privateKey'            => 'asdfasdf',
            'amount'                => '10.00',
            'currency'              => 'AUD',
            'clientIp'              => '127.0.0.1',
            'browserDomain'         => 'PairMeUp',
            'accountId'             => 12341234,
            'packageId'             => 1234,
            'packageName'           => 'Super Deluxe Excellent Discount Package',
            'description'           => 'Super Deluxe Excellent Discount Package',
            'email'                 => 'customer@example.com',
            'card'                  => $this->card,
        ));
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('10.00', $data['purchase']['amount']);

        $this->assertSame($this->card->getNumber(), $data['card']['card[number]']);
        $this->assertSame($this->card->getExpiryMonth(), $data['card']['card[exp_month]']);
        $this->assertSame($this->card->getExpiryYear(), $data['card']['card[exp_year]']);
        $this->assertSame($this->card->getCvv(), $data['card']['card[cvv]']);
    }

/* Can't do this because can't create mocks.
    public function testSendSuccess()
    {


        $response = $this->request->send();
        print_r($response);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('5616524151429286237_test', $response->getTransactionReference());
        $this->assertSame('token_asdf1234asdf1234', $response->getCardReference());
        $this->assertNull($response->getMessage());
    }
*/
}
