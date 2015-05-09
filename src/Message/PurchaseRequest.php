<?php
/**
 * PaymentWall REST Purchase Request
 */

namespace Omnipay\PaymentWall\Message;

/**
 * PaymentWall REST Purchase Request
 *
 * Paymentwall is the leading digital payments platform for globally monetizing
 * digital goods and services. Paymentwall assists game publishers, dating publics,
 * rewards publics, SaaS companies and many other verticals to monetize their
 * digital content and services.
 *
 * This uses the PaymentWall library at https://github.com/paymentwall/paymentwall-php
 * and the Brick API to communicate to PaymentWall.
 *
 * FIXME: There are no  transaction references coming back from the gateway and there
 * are no cards being stored.  Also see Quirks, below.
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
 *       'testMode'     => true, // Or false when you are ready for live transactions
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
 *       'packageName'               => 'Super Deluxe Excellent Discount Package',
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
     * Get the request packageId -- used in every purchase request
     *
     * @return string
     */
    public function getPackageId()
    {
        return $this->getParameter('packageId');
    }

    /**
     * Set the request packageId -- used in every purchase request
     *
     * @return PurchaseRequest provides a fluent interface.
     */
    public function setPackageId($value)
    {
        return $this->setParameter('packageId', $value);
    }

    /**
     * Get the request accountId -- used in every purchase request
     *
     * @return string
     */
    public function getAccountId()
    {
        return $this->getParameter('accountId');
    }

    /**
     * Set the request accountId -- used in every purchase request
     *
     * @return PurchaseRequest provides a fluent interface.
     */
    public function setAccountId($value)
    {
        return $this->setParameter('accountId', $value);
    }

    /**
     * Get the request packageName -- used in every purchase request
     *
     * @return string
     */
    public function getPackageName()
    {
        return $this->getParameter('packageName');
    }

    /**
     * Set the request packageName -- used in every purchase request
     *
     * @return PurchaseRequest provides a fluent interface.
     */
    public function setPackageName($value)
    {
        return $this->setParameter('packageName', $value);
    }

    /**
     * Get the request email -- used in every purchase request
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * Set the request email -- used in every purchase request
     *
     * @return PurchaseRequest provides a fluent interface.
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getData()
    {
        // An amount parameter is required, as is a currency and
        // an account ID.
        $this->validate('amount', 'currency', 'accountId');
        $data                   = parent::getData();
        $data['amount']         = $this->getAmount();
        $data['currency']       = $this->getCurrency();
        $data['account_id']     = $this->getAccountId();
        $data['package_id']     = $this->getPackageId();
        $data['package_name']   = $this->getPackageName();
        $data['description']    = $this->getDescription();
        $data['browser_ip']     = $this->getClientIp();

        // Use account id for package id if package id is not set.
        if (empty($data['package_id'])) {
            $data['package_id']   = $this->getAccountId();
        }

        // Use transaction description for package name if package name is not set.
        if (empty($data['package_name'])) {
            $data['package_name']   = $this->getDescription();
        }

        // A card token can be provided if the card has been stored
        // in the gateway.
        if ($this->getCardReference()) {
            $this->validate('email');
            $data['token'] = $this->getCardReference();
            $data['billing_email'] = $this->getEmail();
        } elseif ($this->getToken()) {
            $this->validate('email');
            $data['token'] = $this->getToken();
            $data['billing_email'] = $this->getEmail();

        // If no card token is provided then there must be a valid
        // card presented.
        } else {
            $this->validate('card');
            $card = $this->getCard();
            $card->validate();
            $data['name']               = $card->getName();
            $data['card_number']        = $card->getNumber();
            $data['expiration_month']   = $card->getExpiryMonth();
            $data['expiration_year']    = $card->getExpiryYear();
            $data['cvv']                = $card->getCvv();
            $data['address_street']     = $card->getBillingAddress1();
            $data['address_city']       = $card->getBillingCity();
            $data['address_state']      = $card->getBillingState();
            $data['address_country']    = $card->getBillingCountry();
            $data['address_zip']        = $card->getBillingPostcode();
            $data['phone_number']       = $card->getBillingPhone();
            $data['billing_email']      = $card->getEmail();
            $data['remember_my_card']   = 1;
        }

        return $data;
    }

    public function sendData($data)
    {
        // Initialise the PaymentWall configuration
        $this->setPaymentWallObject();

        if (empty($data['token'])) {
            // Create a one time token
            $tokenModel = new \Paymentwall_OneTimeToken();
            $tokenObject =  $tokenModel->create([
                'public_key'        => $this->getPublicKey(),
                'card[number]'      => $data['card_number'],
                'card[exp_month]'   => $data['expiration_month'],
                'card[exp_year]'    => $data['expiration_year'],
                'card[cvv]'         => $data['cvv']
            ]);

            //  echo "Token data = " . print_r($tokenObject, true) . "\n";
            $token = $tokenObject->getToken();
        } else {
            // Use the token passed in
            $token = $data['token'];
        }

        // Create the charge and apply the token
        $charge_data = [
            'email'                 => $data['billing_email'],
            // 'customer[firstname]'   => $purchase->getValue('name'),
            'uid'                   => $data['account_id'],
            'plan'                  => $data['package_id'],
            'amount'                => $data['amount'],
            'currency'              => $data['currency'],
            'token'                 => $token,
            'fingerprint'           => $data['description'],
            'description'           => $data['description'],
            'browser_ip'            => $data['browser_ip'],
            // 'browser_domain'        => $purchase->getValue('browser_domain'),
        ];
        $charge = new \Paymentwall_Charge();
        $charge->create($charge_data);

        // Get the response data -- this is returned as a JSON string.
        $charge_response = $charge->getPublicData();
        $charge_data = json_decode($charge_response, true);
        // echo "Charge Data == " . print_r($charge_data, true) . "\n";

        // Get the remaining data from the response
        // echo "Charge == " . print_r($charge, true) . "\n";
        // echo "Charge ID == " . $charge->getId() . "\n";
        // echo "Card == " . print_r($charge->getCard(), true) . "\n";
        // echo "Card Token == " . $charge->getCard()->getToken() . "\n";
        $charge_data['transaction_reference'] = $charge->getId();
        $charge_data['card_reference'] = $charge->getCard()->getToken();

        // Construct the response object
        $this->response = new Response($this, $charge_data);

        if ($charge->isSuccessful()) {
            if ($charge->isCaptured()) {
                $this->response->setCaptured(true);
                return $this->response;
            } elseif ($charge->isUnderReview()) {
                $this->response->setUnderReview(true);
                return $this->response;
            }
        } else {
            return $this->response;
        }
    }
}
