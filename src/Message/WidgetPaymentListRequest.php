<?php
/**
 * PaymentWall Widget Payment List Request.
 *
 * @author Satheesh Narayanan <satheesh@incube8.sg>
 */
namespace Omnipay\PaymentWall\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * PaymentWall widget fetch payment system Request.
 *
 * Paymentwall is the leading digital payments platform for globally monetizing
 * digital goods and services. Paymentwall assists game publishers, dating publics,
 * rewards publics, SaaS companies and many other verticals to monetize their
 * digital content and services.
 *
 * This uses the PaymentWall library at https://github.com/paymentwall/paymentwall-php
 * and the Brick API to communicate to PaymentWall.
 *
 * ### Examples
 *
 * #### Set Up and Initialise Gateway
 *
 * <code>
 * // Create a gateway for the PaymentWall Widget Gateway
 * // (routes to GatewayFactory::create)
 * $gateway = Omnipay::create('PaymentWall_Widget');
 *
 * // Initialise the gateway
 * $gateway->initialize(array(
 *     'apiType'      => $gateway::API_GOODS,
 *     'publicKey'    => 'YOUR_PUBLIC_KEY',
 *     'privateKey'   => 'YOUR_PRIVATE_KEY',
 * ));
 * </code>
 *
 * #### Fetch all the payment options by country code
 *
 * <code>
 * $transaction = $gateway->pullPaymentList(array(
 *     'country_code'              => 'US',
 *     'browserDomain'             => 'SiteName.com',
 * ));
 *
 * $response = $transaction->send();
 * if ($response->isSuccessful()) {
 *     echo "Payment System API response!\n";
 *     $paymentSystem = $response->getData();
 *     echo "Payment Systems = " . $paymentSystem . "\n";
 * }
 * </code>
 *
 * @link https://www.paymentwall.com/en/documentation/getting-started
 * @link https://www.paymentwall.com/
 * @link https://github.com/paymentwall/paymentwall-php
 * @see Omnipay\PaymentWall\WidgetGateway
 */
class WidgetPaymentListRequest extends AbstractLibraryRequest
{
    /**
     * Get transaction endpoint.
     *
     * Fetching all the payment systems by using this end point.
     *
     * @return string
     */
    protected function getEndpoint()
    {
        return parent::getEndpoint().'/payment-systems';
    }

    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    protected function getHttpMethod()
    {
        return 'GET';
    }

    /**
     * Get the payment currency code.
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->getParameter('country_code');
    }

    /**
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setCountryCode($value)
    {
        return $this->setParameter('country_code', $value);
    }

    /**
     * Build an array from the ParameterBag object that is ready for sendData.
     *
     * @throws InvalidRequestException directly for missing email, indirectly through validate
     *
     * @link https://www.paymentwall.com/en/documentation/Payment-Systems-API/2661
     *
     * @return array
     */
    public function getData()
    {
        $params = array(
            'key'          => $this->getPublicKey(),
            'country_code' => $this->getCountryCode(),
            'sign_version' => 2,
        );

        // generate the Paymentwall widget signature
        \Paymentwall_Config::getInstance()->set(array('private_key' => $this->getPrivateKey()));
        $params['sign'] = (new \Paymentwall_Signature_Widget())->calculate(
            $params,
            $params['sign_version']
        );

        return $params;
    }

    /**
     * Build an error response and return it.
     *
     * @param string $message
     * @param string $code
     * @param mixed  $responseLogInformation
     *
     * @return Response
     */
    public function returnError($message, $code, $responseLogInformation = null)
    {
        $data = array(
            'type'          => 'Error',
            'object'        => 'Error',
            'error'         => $message,
            'code'          => $code,
            'log'           => $responseLogInformation,
        );
        $this->response = new Response($this, $data);

        return $this->response;
    }

    /**
     * Submit the data to the Paymentwall api to fetch all Payment systems.
     *
     * @param mixed $data
     *
     * @return WidgetPaymentListResponse
     */
    public function sendData($data)
    {
        //Create HTTPREQUEST using endpoint and the query parameters
        $httpRequest = $this->httpClient->createRequest(
            $this->getHttpMethod(),
            $this->getEndpoint().'/?'.http_build_query($data),
            array()
        );

        try {
            $httpResponse = $httpRequest->send();

            $this->response = new WidgetPaymentListResponse($this, $httpResponse->getBody(true), $httpResponse->getStatusCode());

            return $this->response;
        } catch (\Exception $e) {
            return $this->returnError('Error in communicating with Paymentwall', 231, $e->getMessage());
        }
    }
}
