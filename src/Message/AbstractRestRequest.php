<?php
/**
 * PaymentWall Abstract REST Request
 */

namespace Omnipay\PaymentWall\Message;

use Guzzle\Http\EntityBody;

/**
 * PaymentWall Abstract REST Request
 *
 * FIXME: This is not finished yet -- just a stub.  The endpoints are incorrect, they
 * need to be grabbed from the existing code.  Not ready for use yet.
 *
 * This is the parent class for all PaymentWall REST requests.
 *
 * Test payments can be performed by setting a 'dev-flag' header to any
 * value that PHP evaluates as true and using the following card number
 * / CVV combinations:
 *
 * Card Numbers:
 * 4242424242424242
 * 4000000000000002
 *
 * CVV Codes | Expected Response
 * 111         Error: Please ensure the CVV/CVC number is correct before retrying the transaction
 * 222         Error: Please contact your credit card company to check your available balance
 * 333         Error: Please contact your credit card company to approve your payment
 *
 * Any valid CVV that is not listed above will result in a success when using the test system
 *
 * @link https://www.paymentwall.com/en/documentation/getting-started
 * @see \Omnipay\PaymentWall\Gateway
 */
abstract class AbstractRestRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const API_VERSION = '1';

    /**
     * Sandbox Endpoint URL
     *
     * @var string URL
     */
    // PaymentWall staging endpoint
    # protected $testEndpoint = 'https://staging.paymentwall.com/api/';
    // PaymentWall dev endpoint
    protected $testEndpoint = 'http://dev.paymentwall.com/api/';
    // Look here for POST test results if you're using this endpoint:
    // http://www.posttestserver.com/
    # protected $testEndpoint = 'http://posttestserver.com/post.php';

    /**
     * Live Endpoint URL
     *
     * @var string URL
     */
    protected $liveEndpoint = 'https://www.paymentwall.com/api/';

    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    protected function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * Get API endpoint URL
     *
     * @return string
     */
    protected function getEndpoint()
    {
        $base = $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
        // return $base . '/' . self::API_VERSION;
        return $base;
    }

    /**
     * Get the gateway siteKey -- used in every request
     *
     * @return string
     */
    public function getSiteKey()
    {
        return $this->getParameter('siteKey');
    }

    /**
     * Set the gateway siteKey -- used in every request
     *
     * @return AbstractRestRequest provides a fluent interface.
     */
    public function setSiteKey($value)
    {
        return $this->setParameter('siteKey', $value);
    }

    /**
     * Get the gateway siteDomain -- used in every request
     *
     * @return string
     */
    public function getSiteDomain()
    {
        return $this->getParameter('siteDomain');
    }

    /**
     * Set the gateway siteDomain -- used in every request
     *
     * @return AbstractRestRequest provides a fluent interface.
     */
    public function setSiteDomain($value)
    {
        return $this->setParameter('siteDomain', $value);
    }

    /**
     * Get the request accountId -- used in every request
     *
     * @return string
     */
    public function getAccountId()
    {
        return $this->getParameter('accountId');
    }

    /**
     * Set the request accountId -- used in every request
     *
     * @return AbstractRestRequest provides a fluent interface.
     */
    public function setAccountId($value)
    {
        return $this->setParameter('accountId', $value);
    }

    /**
     * Set the data used in every request.
     *
     * In this gateway a certain amount of data needs to be sent
     * in every request.  This function sets those data into the
     * array and can be extended by child classes.
     *
     * @return array
     */
    public function getData()
    {
        $this->validate('siteKey');
        $data = array(
            'site_key'          => $this->getSiteKey(),
            'account_id'        => $this->getAccountId(),
            'browser_ip'        => $this->getClientIp(),
            'browser_domain'    => $this->getSiteDomain(),
        );
        return $data;
    }

    public function sendData($data)
    {
        // don't throw exceptions for 4xx errors
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function ($event) {
                if ($event['response']->isClientError()) {
                    $event->stopPropagation();
                }
            }
        );

        // Headers
        $headers = ['Accept' => 'application/json'];
        if ($this->getTestMode()) {
            $headers['dev-flag'] = '1';
        }

        // Guzzle HTTP Client createRequest does funny things when a GET request
        // has attached data, so don't send the data if the method is GET.
        if ($this->getHttpMethod() == 'GET') {
            $httpRequest = $this->httpClient->createRequest(
                $this->getHttpMethod(),
                $this->getEndpoint(),
                $headers
            );
        } else {
            $httpRequest = $this->httpClient->createRequest(
                $this->getHttpMethod(),
                $this->getEndpoint(),
                $headers,
                $data
            );
        }

        // Might be useful to have some debug code here.  Perhaps hook to whatever
        // logging engine is being used.
        # $handle = fopen('debug.txt', 'a');
        # fwrite($handle, "Data == " . print_r($data, true) . "\n");

        $httpResponse = $httpRequest->send();
        # fwrite($handle, "Response == " . print_r($httpResponse, true) . "\n");
        # fclose($handle);

        return $this->response = new RestResponse($this, $httpResponse->json(), $httpResponse->getStatusCode());
    }
}
