<?php

namespace Omnipay\PaymentWall\Message;

use Omnipay\Tests\TestCase;

class VoidRequestTest extends TestCase
{
    /** @var VoidRequest */
    protected $request;

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

        $this->request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'transactionReference'  => 'ASDF1234',
            'publicKey'             => 'asdfasdf',
            'privateKey'            => 'asdfasdf',
            'clientIp'              => '127.0.0.1',
            'browserDomain'         => 'PairMeUp',
        ));
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('ASDF1234', $data['sale_id']);
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
