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
 * digital goods and services. Paymentwall assists game publishers, dating sites,
 * rewards sites, SaaS companies and many other verticals to monetize their
 * digital content and services.
 *
 * This uses the PaymentWall library at https://github.com/paymentwall/paymentwall-php
 * and the Brick API to communicate to PaymentWall.
 *
 * FIXME: This is not finished yet -- just a stub.  The endpoints are incorrect, they
 * need to be grabbed from the existing code.  Not ready for use yet.
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
 *       'siteKey'      => '1234asdf1234asdf',
 *       'siteDomain'   => 'MySiteDomain',
 *       'testMode'     => true, // Or false when you are ready for live transactions
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
 *               'billingAddress1'       => '1 Scrubby Creek Road',
 *               'billingCountry'        => 'AU',
 *               'billingCity'           => 'Scrubby Creek',
 *               'billingPostcode'       => '4999',
 *               'billingState'          => 'QLD',
 *               'billingPhone'          => '12341234',
 *   ));
 *
 *   // Do a purchase transaction on the gateway
 *   $transaction = $gateway->purchase(array(
 *       'amount'                    => '10.00',
 *       'accountId'                 => 12341234,
 *       'currency'                  => 'AUD',
 *       'clientIp'                  => '127.0.0.1',
 *       'packageId'                 => 1234,
 *       'packageName'               => 'Super Deluxe Excellent Discount Package',
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
 * * Refunds are not supported, these must be done manually.
 *
 * @see \Omnipay\Common\AbstractGateway
 * @see \Omnipay\PaymentWall\Message\AbstractRestRequest
 * @link https://www.paymentwall.com/en/documentation/getting-started
 * @link https://www.paymentwall.com/
 */
class Gateway extends AbstractGateway
{
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
            'siteKey'       => '',
            'siteDomain'    => '',
            'testMode'      => false,
        );
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
     * @return Gateway provides a fluent interface.
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
     * @return Gateway provides a fluent interface.
     */
    public function setSiteDomain($value)
    {
        return $this->setParameter('siteDomain', $value);
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
}
