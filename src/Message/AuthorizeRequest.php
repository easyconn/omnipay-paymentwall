<?php
/**
 * PaymentWall Authorize Request.
 */
namespace Omnipay\PaymentWall\Message;

/**
 * PaymentWall Authorize Request.
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
 * </code>
 *
 * #### Payment with Card Details
 *
 * <code>
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
 *   // Do an authorize transaction on the gateway
 *   $transaction = $gateway->authorize(array(
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
 *       echo "Authorize transaction was successful!\n";
 *       $sale_id = $response->getTransactionReference();
 *       echo "Transaction reference = " . $sale_id . "\n";
 *   }
 * </code>
 *
 * #### Payment with Card Token
 *
 * <code>
 *   // Do an authorize transaction on the gateway
 *   $transaction = $gateway->authorize(array(
 *       'amount'                    => '10.00',
 *       'accountId'                 => 12341234,
 *       'currency'                  => 'AUD',
 *       'clientIp'                  => '127.0.0.1',
 *       'packageId'                 => 1234,
 *       'description'               => 'Super Deluxe Excellent Discount Package',
 *       'fingerprint'               => '*token provided by Brick.js*',
 *       'browserDomain'             => 'SiteName.com',
 *       'cardReference'             => 'token_asdf1234asdf1234',
 *       'email'                     => 'customer@example.com',
 *   ));
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *       echo "Authorize transaction was successful!\n";
 *       $sale_id = $response->getTransactionReference();
 *       echo "Transaction reference = " . $sale_id . "\n";
 *   }
 * </code>
 *
 * ### Test Payments
 *
 * Test payments can be performed by setting a testMode parameter to any
 * value that PHP evaluates as true and using the following card number
 * / CVV combinations:
 *
 * #### Card Numbers
 *
 * * 4242424242424242
 * * 4000000000000002
 *
 * #### CVV Codes | Expected Response
 *
 * * 111         Error: Please ensure the CVV/CVC number is correct before retrying the transaction
 * * 222         Error: Please contact your credit card company to check your available balance
 * * 333         Error: Please contact your credit card company to approve your payment
 *
 * Any valid CVV that is not listed above will result in a success when using the test system
 *
 * ### Full parameter Set
 *
 * This includes all optional parameters including those that are used
 * for fraud detection/prevention.
 *
 * <code>
 *   purchase => [
 *       uid
 *       plan
 *       amount
 *       currency
 *       fingerprint
 *       description
 *       browser_ip
 *       browser_domain
 *       customer => [
 *           sex
 *           firstname
 *           lastname
 *           username
 *           zip
 *           birthday
 *       ]
 *       history = > [
 *           membership
 *           membership_date
 *           registration_date
 *           registration_country
 *           registration_ip
 *           registration_email
 *           registration_email_verified
 *           registration_name
 *           registration_lastname
 *           registration_source
 *           logins_number
 *           payments_number
 *           payments_amount
 *           followers
 *           messages_sent
 *           messages_sent_last_24hours
 *           messages_received
 *           interactions
 *           interactions_last_24hours
 *           risk_score
 *           was_banned
 *           delivered_products
 *           cancelled_payments
 *           registration_age
 *       ]
 *       3dsecure
 *       options => []
 *       custom => []
 *   ]
 * </code>
 *
 * @link https://www.paymentwall.com/en/documentation/getting-started
 * @link https://www.paymentwall.com/
 * @link https://github.com/paymentwall/paymentwall-php
 * @see Omnipay\PaymentWall\Gateway
 */
class AuthorizeRequest extends PurchaseRequest
{
    /**
     * Get the capture flag.
     *
     * The default value is false.
     *
     * @return bool
     */
    public function getCapture()
    {
        if (!$this->parameters->has('capture')) {
            return false;
        }

        return $this->getParameter('capture');
    }
}
