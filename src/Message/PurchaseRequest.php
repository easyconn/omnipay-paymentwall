<?php
/**
 * PaymentWall Purchase Request
 */

namespace Omnipay\PaymentWall\Message;

use Omnipay\Common\Exception\RuntimeException;
use Omnipay\Common\Exception\InvalidRequestException;

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
 *       'email'                     => 'customer@example.com',
 *   ));
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *       echo "Purchase transaction was successful!\n";
 *       $sale_id = $response->getTransactionReference();
 *       echo "Transaction reference = " . $sale_id . "\n";
 *   }
 * </code>
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
     * The email can be in the parameter bag or the card data
     *
     * @return string
     */
    public function getEmail()
    {
        $email = $this->getParameter('email');
        if (empty($email)) {
            $email = $this->getCard()->getEmail();
        }
        return $email;
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

    /**
     * Get the Brick.js fingerprint
     *
     * @return string
     */
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

    /**
     * Get browserDomain
     *
     * @return string Site name or URL
     */
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

    /**
     * Get the capture flag
     *
     * This will only return false if the parameter is set AND false. The default
     * value is true.
     *
     * @return bool
     */
    public function getCapture()
    {
        if (!$this->parameters->has('capture')) {
            return true;
        }
        return $this->getParameter('capture');
    }

    /**
     * Set the capture flag
     *
     * optional parameter capture. Setting this to false allows for card validation/
     * authorization. The call to charge returns a charge object that
     *
     * Whether or not to immediately capture the charge. Default is true

     * @param $value
     *
     * @return PurchaseRequest
     */
    public function setCapture($value)
    {
        return $this->setParameter('capture', (bool) $value);
    }

    /**
     * Build an array from the ParameterBag object that is ready for sendData
     *
     * @throws InvalidRequestException directly for missing email, indirectly through validate
     * @link https://www.paymentwall.com/en/documentation/Brick/2968#charge_create
     * @return array
     */
    public function getData()
    {
        // verify that required parameters are provided
        // calls \Omnipay\Common\Message\AbstractRequest::validate()
        $requiredParams = ['amount', 'currency', 'accountId', 'description'];
        if ($this->getFingerprint()) {
            $requiredParams[] = 'fingerprint';
        } else {
            $requiredParams = array_merge($requiredParams, ['clientIp', 'browserDomain']);
        }

        // pass the param list to the validate function
        call_user_func_array([$this,'validate'], $requiredParams);

        // require an email address
        $email = $this->getEmail();
        if (empty($email)) {
            throw new InvalidRequestException("The email parameter is required");
        }

        $card = $this->getCard();
        $data = [
            'purchase'  => [
                'token'                 => $this->getToken(),
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
                'pingback_url'          => $this->notifyUrl(),
                'options[capture]'      => $this->getCapture(),
            ]
        ];

        // if there is no authorization token we need to provide sendData with
        // the card data so that it can get a one-time token from PaymentWall
        if (empty($data['purchase']['token'])) {
            $data['card'] = [
                'public_key'        => $this->getPublicKey(),
                'card[number]'      => $card->getNumber(),
                'card[exp_month]'   => $card->getExpiryMonth(),
                'card[exp_year]'    => $card->getExpiryYear(),
                'card[cvv]'         => $card->getCvv(),
            ];
        }

        // Callback URLs if they are set
        if ($this->getReturnUrl()) {
            $data['success_url'] = $this->getReturnUrl();
        }
        if ($this->getNotifyUrl()) {
            $data['pingback_url'] = $this->getNotifyUrl();
        }

        return $data;
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
        // Initialise the PaymentWall configuration
        $this->setPaymentWallObject();

        // if no token exists, create one
        if (empty($data['purchase']['token'])) {
            // Create a one time token
            $tokenModel = new \Paymentwall_OneTimeToken();
            $tokenObject = $tokenModel->create($data['card']);

            $data['purchase']['token'] = $tokenObject->getToken();
        }
        if (empty($data['purchase']['token'])) {
            throw new RuntimeException('Payment Token could not be created');
        }

        // Now we know that we have an actual token (one time or
        // permanent), we can create the charge request.
        $charge = new \Paymentwall_Charge();
        $charge->create($data['purchase']);

        // Force the charge properties to be an array
        $properties = $charge->getProperties();
        $properties = json_decode(json_encode($properties), true);

        // Construct the response object
        $this->response = new Response($this, $properties);

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
