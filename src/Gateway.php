<?php
/**
 * PaymentWall Gateway
 */

namespace Omnipay\PaymentWall;

use Omnipay\Common\AbstractGateway;

/**
 * PaymentWall Gateway
 *
 * Paymentwall is the leading digital payments platform for globally monetizing
 * digital goods and services. Paymentwall assists game publishers, dating publics,
 * rewards publics, SaaS companies and many other verticals to monetize their
 * digital content and services.
 *
 * This uses the PaymentWall library at https://github.com/paymentwall/paymentwall-php
 * and the Brick API to communicate to PaymentWall.
 *
 * <h4>Example</h4>
 *
 * <code>
 *   // Create a gateway for the PaymentWall REST Gateway
 *   // (routes to GatewayFactory::create)
 *   $gateway = Omnipay::create('PaymentWall');
 *
 *   // Initialise the gateway
 *   $gateway->initialize(array(
 *       'apiType'      => $gateway::API_GOODS,
 *       'publicKey'    => 'YOUR_PUBLIC_KEY',
 *       'privateKey'   => 'YOUR_PRIVATE_KEY',
 *   ));
 *
 *   // Create a credit card object
 *   // This card can be used for testing.
 *   $card = new CreditCard(array(
 *               'firstName'             => 'Example',
 *               'lastName'              => 'Customer',
 *               'number'                => '4242424242424242',
 *               'expiryMonth'           => '01',
 *               'expiryYear'            => '2020',
 *               'cvv'                   => '123',
 *               'email'                 => 'customer@example.com',
 *               'billingPostcode'       => '4999',
 *   ));
 *
 *   // Do a purchase transaction on the gateway
 *   $transaction = $gateway->purchase(array(
 *       'amount'                    => '10.00',
 *       'accountId'                 => 12341234,
 *       'currency'                  => 'AUD',
 *       'clientIp'                  => '127.0.0.1',
 *       'packageId'                 => 1234,
 *       'description'               => 'Super Deluxe Excellent Discount Package',
 *       'fingerprint'               => '*token provided by Brick.js*',
 *       'browserDomain'             => 'SiteName.com',
 *       'card'                      => $card,
 *   ));
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *       echo "Purchase transaction was successful!\n";
 *       $sale_id = $response->getTransactionReference();
 *       echo "Transaction reference = " . $sale_id . "\n";
 *   }
 * </code>
 *
 * <h4>Quirks</h4>
 *
 * * There is no separate createCard message in this gateway.  The
 *   PaymentWall gateway only supports card creation at the time of a
 *   purchase.  Instead, a cardReference is returned when a purchase
 *   message is sent, as a component of the response to the purchase
 *   message.  This card token can then be used to make purchases
 *   in place of card data, just like other gateways.
 * * Refunds are not supported, these must be done manually.  Voids
 *   are supported.
 *
 * @see \Omnipay\Common\AbstractGateway
 * @see \Omnipay\PaymentWall\Message\AbstractRestRequest
 * @link https://www.paymentwall.com/en/documentation/getting-started
 * @link https://www.paymentwall.com/
 * @link https://github.com/paymentwall/paymentwall-php
 */
class Gateway extends AbstractGateway
{

	const API_VC	= \Paymentwall_Config::API_VC;
	const API_GOODS	= \Paymentwall_Config::API_GOODS;
	const API_CART	= \Paymentwall_Config::API_CART;

    /**
     * Get the gateway display name
     *
     * @return string
     */
    public function getName()
    {
        return 'PaymentWall';
    }

    /**
     * Get the gateway default parameters
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'apiType'       => 0,
            'publicKey'     => '',
            'privateKey'    => '',
        );
    }

    /**
     * Get the gateway apiType -- used in every request
     *
     * @return string
     */
    public function getApiType()
    {
        return $this->getParameter('apiType');
    }

    /**
     * Set the gateway apiType -- used in every request
     *
     * @return Gateway provides a fluent interface.
     */
    public function setApiType($value)
    {
        return $this->setParameter('apiType', $value);
    }

    /**
     * Get the gateway publicKey -- used in every request
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->getParameter('publicKey');
    }

    /**
     * Set the gateway publicKey -- used in every request
     *
     * @return Gateway provides a fluent interface.
     */
    public function setPublicKey($value)
    {
        return $this->setParameter('publicKey', $value);
    }

    /**
     * Get the gateway privateKey -- used in every request
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->getParameter('privateKey');
    }

    /**
     * Set the gateway privateKey -- used in every request
     *
     * @return Gateway provides a fluent interface.
     */
    public function setPrivateKey($value)
    {
        return $this->setParameter('privateKey', $value);
    }

    //
    // Direct API Purchase Calls -- purchase, refund
    //

    /**
     * Create a purchase request.
     *
     * @param array $parameters
     * @return \Omnipay\PaymentWall\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentWall\Message\PurchaseRequest', $parameters);
    }

    /**
     * Create a void request.
     *
     * @param array $parameters
     * @return \Omnipay\PaymentWall\Message\VoidRequest
     */
    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentWall\Message\VoidRequest', $parameters);
    }
}
