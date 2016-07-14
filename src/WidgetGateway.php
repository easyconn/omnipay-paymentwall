<?php
/**
 * PaymentWall widget Gateway.
 *
 * Class WidgetGateway
 *
 * @author Satheesh Narayanan <satheesh@incube8.sg>
 */
namespace Omnipay\PaymentWall;

use Omnipay\Common\AbstractGateway;

/**
 * PaymentWall Widget Gateway.
 *
 * Paymentwall Widget is the leading digital payments platform for globally monetizing
 * digital goods and services. Paymentwall Widget assists game publishers, dating publics,
 * rewards publics, SaaS companies and many other verticals to monetize their
 * digital content and services.
 *
 * This uses the PaymentWall library at https://github.com/paymentwall/paymentwall-php
 *
 * Basically this gateway system will redirect the user to paymentwall widget gateway page
 * where user can see all the payment options and they redirected to the user selected
 * payment method web page. Also PaymentWall widget does not support voids or refunds.
 *
 * ### Example
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
 *
 * // Build PaymentWall Widget Payment Url
 * $transaction = $gateway->purchase(array(
 *     'amount'                    => '10.00',
 *     'accountId'                 => 12341234,
 *     'currency'                  => 'AUD',
 *     'clientIp'                  => '127.0.0.1',
 *     'packageId'                 => 1234,
 *     'description'               => 'Super Deluxe Excellent Discount Package',
 *     'browserDomain'             => 'SiteName.com',
 * ));
 * $response = $transaction->send();
 * if ($response->isSuccessful()) {
 *     echo "Build paymentWall Widget URL!\n";
 *     $pwUrl = $response->getUrl();
 *     echo "PaymentWall Widget URL = " . $pwUrl . "\n";
 * }
 * </code>
 *
 * ### Widget Buttons
 *
 * This uses the Paymentwall library at https://github.com/paymentwall/paymentwall-php to fetch all
 * of the payment options by country code. Normally it will fetch all of the payment button image
 * URLs and their names, into an array.  This is normally just used for display purposes so that
 * the customer can see the available payment types before being redirected to the PaymentWall
 * widget gateway.
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
 *
 * // Fetch all the payment options by country code
 * $transaction = $gateway->pullPaymentListRequest(array(
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
 * ### Quirks
 *
 * Refunds and voids are not supported.
 *
 * @see \Omnipay\Common\AbstractGateway
 * @see \Omnipay\PaymentWall\Message\AbstractRestRequest
 * @link https://www.paymentwall.com/en/documentation/getting-started
 * @link https://www.paymentwall.com/
 * @link https://github.com/paymentwall/paymentwall-php
 */
class WidgetGateway extends AbstractGateway
{
    const API_VC = \Paymentwall_Config::API_VC;
    const API_GOODS = \Paymentwall_Config::API_GOODS;
    const API_CART = \Paymentwall_Config::API_CART;

    /**
     * Get the gateway display name.
     *
     * @return string
     */
    public function getName()
    {
        return 'PaymentWall_Widget';
    }

    /**
     * Get the gateway default parameters.
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'apiType'       => 0,
            'publicKey'     => '',
            'privateKey'    => '',
            'widgetKey'     => '',
            'signVersion'   => 3,
            'country_code'  => 'US',
        );
    }

    /**
     * Get the gateway apiType -- used in every request.
     *
     * @return string
     */
    public function getApiType()
    {
        return $this->getParameter('apiType');
    }

    /**
     * Set the gateway apiType -- used in every request.
     *
     * @param string $value
     *
     * @return Gateway provides a fluent interface.
     */
    public function setApiType($value)
    {
        return $this->setParameter('apiType', $value);
    }

    /**
     * Get the gateway publicKey -- used in every request.
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->getParameter('publicKey');
    }

    /**
     * Set the gateway publicKey -- used in every request.
     *
     * @param string $value
     *
     * @return Gateway provides a fluent interface.
     */
    public function setPublicKey($value)
    {
        return $this->setParameter('publicKey', $value);
    }

    /**
     * Get the gateway privateKey -- used in every request.
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->getParameter('privateKey');
    }

    /**
     * Set the gateway privateKey -- used in every request.
     *
     * @param string $value
     *
     * @return Gateway provides a fluent interface.
     */
    public function setPrivateKey($value)
    {
        return $this->setParameter('privateKey', $value);
    }

    /**
     * Get the gateway widgetKey -- used in every request.
     *
     * @return string
     */
    public function getWidgetKey()
    {
        return $this->getParameter('widgetKey');
    }

    /**
     * Set the gateway widgetKey -- used in every request.
     *
     * @param string $value
     *
     * @return Gateway provides a fluent interface.
     */
    public function setWidgetKey($value)
    {
        return $this->setParameter('widgetKey', $value);
    }

    //
    // Direct API Purchase Calls -- purchase, refund
    //

    /**
     * Create a purchase request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\PaymentWall\Message\WidgetPurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentWall\Message\WidgetPurchaseRequest', $parameters);
    }

    /**
     * Fetch payment system list for PW widget gateway.
     *
     * @param array $parameters
     *
     * @return \Omnipay\PaymentWall\Message\WidgetPaymentListRequest
     */
    public function pullPaymentList(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentWall\Message\WidgetPaymentListRequest', $parameters);
    }
}
