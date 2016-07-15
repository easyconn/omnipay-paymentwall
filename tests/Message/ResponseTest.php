<?php

namespace Omnipay\PaymentWall\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{
    /** @var Response */
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

        $this->request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'transactionReference'  => 'ASDF1234',
            'publicKey'             => 'asdfasdf',
            'privateKey'            => 'asdfasdf',
            'clientIp'              => '127.0.0.1',
            'browserDomain'         => 'PairMeUp',
        ));

        // Sample response data for testing
        $data = array(
            'success'       => true,
            'id'            => 1234,
            'card'          => array(
                'token'     => 'qwerty12341234',
            ),
            'error'         => 'The quick brown fox',
            'code'          => 200,
        );
        $this->response = new Response($this->request, $data, 200);
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    public function testGetData()
    {
        $data = $this->response->getData();

        $this->response->setCaptured(true);
        $this->response->setUnderReview(false);

        $this->assertSame('qwerty12341234', $data['card']['token']);

        $this->assertTrue($this->response->isCaptured());
        $this->assertFalse($this->response->isUnderReview());
        $this->assertTrue($this->response->isSuccessful());
        $this->assertSame(1234, $this->response->getTransactionReference());
        $this->assertSame(200, $this->response->getCode());
        $this->assertSame('qwerty12341234', $this->response->getCardReference());
        $this->assertSame('The quick brown fox', $this->response->getMessage());
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
