<?php
/**
 * PaymentWall Purchase Request
 */

namespace Omnipay\PaymentWall\Message;

use Omnipay\Common\Exception\RuntimeException;

/**
 * PaymentWall Purchase Request
 *
 * Paymentwall is the leading digital payments platform for globally monetizing
 * digital goods and services. Paymentwall assists game publishers, dating publics,
 * rewards publics, SaaS companies and many other verticals to monetize their
 * digital content and services.
 *
 * This uses the PaymentWall library at https://github.com/paymentwall/paymentwall-php
 * and the Brick API to communicate to PaymentWall.
 *
 * <h3>Examples</h3>
 *
 * <h4>Set Up and Initialise Gateway</h4>
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
 * <h4>Payment with Card Details</h4>
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
 * <h4>Payment with Card Token</h4>
 *
 * <code>
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
 *       'cardReference'             => 'token_asdf1234asdf1234',
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
 * @link https://www.paymentwall.com/en/documentation/getting-started
 * @link https://www.paymentwall.com/
 * @link https://github.com/paymentwall/paymentwall-php
 * @see Omnipay\PaymentWall\Gateway
 */
class PurchaseRequest extends AbstractLibraryRequest
{

    /**
     * Get the request packageId
     *
     * @return string
     */
    public function getPackageId()
    {
        return $this->getParameter('packageId');
    }

    /**
     * Set the request packageId
     *
     * Optional parameter, plan
     *
     * Identifies the product ID, sent back as goodsid parameter in Pingbacks
     *
     * @param mixed $value
     *
     * @return PurchaseRequest provides a fluent interface.
     */
    public function setPackageId($value)
    {
        return $this->setParameter('packageId', $value);
    }

    /**
     * Get the request accountId
     *
     * @return string
     */
    public function getAccountId()
    {
        return $this->getParameter('accountId');
    }

    /**
     * Set the request accountId
     *
     * Optional parameter, uuid
     *
     * Identifies the internal end-user ID within merchant's system. Used for uid
     * parameter in Pingbacks. If omitted, email is used as uid parameter in Pingbacks
     *
     * @param mixed $value
     *
     * @return PurchaseRequest provides a fluent interface.
     */
    public function setAccountId($value)
    {
        return $this->setParameter('accountId', $value);
    }

    /**
     * Get the request email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * Set the request email
     *
     * Required parameter, email
     *
     * End-user's email
     *
     * PaymentWall will use this email to send the transaction receipt
     *
     * @param mixed $value
     *
     * @return PurchaseRequest provides a fluent interface.
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getFingerprint()
    {
        return $this->getParameter('fingerprint');
    }

    /**
     * Set the request FingerPrint
     *
     * Required parameter fingerprint, if browserIp and browserDomain are not supplied
     *
     * This value is produced by the Brick.js (if utilized) and contains both the
     * IP and Domain info
     *
     * @param string $value
     *
     * @return PurchaseRequest provides a fluent interface.
     */
    public function setFingerprint($value)
    {
        return $this->setParameter('fingerprint', $value);
    }

    public function getBrowserDomain()
    {
        return $this->getParameter('browserDomain');
    }

    /**
     * Set the request browserDomain
     *
     * Required parameter browserDomain, if fingerprint is not supplied
     *
     * Domain of the website where the payment is originating from
     *
     * @param string $value Name or URL of the site making the payment
     *
     * @return PurchaseRequest provides a fluent interface.
     */
    public function setBrowserDomain($value)
    {
        return $this->setParameter('browserDomain', $value);
    }

    public function getPingBackURL()
    {
        return $this->getParameter('pingBackURL');
    }

    /**
     * Set the request PingBackURL
     *
     * Optional parameter pingBackURL
     *
     * URL of pingback listener script where pingbacks should be sent. Takes effect
     * only if activated for the merchant account per request. Requires widget call
     * to be signed with signature version 2 or higher.
     *
     * Use this to override the default pingback url. Allows you to share a project
     * (and one set of keys) across multiple testing environments while still
     * receiving the pingbacks
     *
     * @param string $value a valid, absolute URL including http(s)
     *
     * @return PurchaseRequest provides a fluent interface.
     */
    public function setPingBackURL($value)
    {
        return $this->setParameter('pingBackURL', $value);
    }

    /**
     * Build an array from the ParameterBag object that is ready for sendData
     *
     * @see https://www.paymentwall.com/en/documentation/Brick/2968#charge_create
     * @return array
     */
    public function getData()
    {
        // verify that required parameters are provided
        // calls \Omnipay\Common\Message\AbstractRequest::validate()
        $requiredParams = ['amount', 'currency', 'accountId', 'description', 'email'];
        if ($this->getFingerprint()) {
            $requiredParams[] = 'fingerprint';
        } else {
            array_push($requiredParams, ['browserIp', 'browserDomain']);
        }

        $this->validate();
        $card = $this->getCard();
        return [
            'token'     => $this->getToken(),
            'card'      => [
                'public_key'        => $this->getPublicKey(),
                'card[number]'      => $card->getNumber(),
                'card[exp_month]'   => $card->getExpiryMonth(),
                'card[exp_year]'    => $card->getExpiryYear(),
                'card[cvv]'         => $card->getCvv(),
            ],
            'purchase'  => [
                'token'                 => null,
                'email'                 => $card->getEmail(),
                'customer[firstname]'   => $card->getFirstName(),
                'customer[lastname]'    => $card->getLastName(),
                'uid'                   => $this->getAccountId(),
                'plan'                  => $this->getPackageId(),
                'amount'                => $this->getAmount(),
                'currency'              => $this->getCurrency(),
                'fingerprint'           => $this->getFingerprint(),
                'description'           => $this->getDescription(),
                'browser_ip'            => $this->getClientIp(),
                'browser_domain'        => $this->getBrowserDomain(),
                'customer[zip]'         => $card->getBillingPostcode(),
                'pingback_url'          => $this->getPingBackURL(),
            ]
        ];
    }


    /**
     * Submit a payment through the PaymentWall Library
     *
     * @param mixed $data
     *
     * @throws RuntimeException
     * @return Response
     */
    public function sendData($data)
    {
        if (empty($data['card']) or empty($data['purchase'])) {
            $data = $this->getData();
        }

        // Initialise the PaymentWall configuration
        $this->setPaymentWallObject();

        // if no token exist, create one
        $token = $data['token'];
        if (empty($token)) {
            // Create a one time token
            $tokenModel = new \Paymentwall_OneTimeToken();
            $tokenObject = $tokenModel->create($data['card']);

            $token = $tokenObject->getToken();
        }
        if (empty($token)) {
            throw new RuntimeException('Payment Token could not be created');
        }

        $data['purchase']['token'] = $token;
        $charge = new \Paymentwall_Charge();
        $charge->create($data['purchase']);

        // Construct the response object
        $this->response = new Response($this, $charge->getProperties());

        if ($charge->isSuccessful()) {
            if ($charge->isCaptured()) {
                $this->response->setCaptured(true);
            } elseif ($charge->isUnderReview()) {
                $this->response->setUnderReview(true);
            }
        }
        return $this->response;
    }
}
