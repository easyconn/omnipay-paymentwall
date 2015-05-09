<?php
/**
 * PaymentWall REST Purchase Request
 */

namespace Omnipay\PaymentWall\Message;

/**
 * PaymentWall REST Purchase Request
 *
 * Route: /payments/submit/
 *
 * Method: POST
 *
 * <h3>Parameters</h3>
 *
 * Information taken from the PaymentWall API documentation:
 *
 * * site_key          [required] - public key for the site
 * * account_id        [required] -
 * * browser_ip        [required] - ip address of the user making a payment
 * * billing_email     [required] - Email where customer wishes to receive receipt. Can use account email
 * * package_id        [required] -
 * * package_name      [required] -
 * * price             [required] -
 * * currency          [required] - ISO 4217 to charge in
 * * token             [optional] - Authorization Token. Used in lieu of credit card information
 *
 * The following properties can all be replaced by an authorization token:
 *
 * * name              [required] - name on the card
 * * card_number       [required] -
 * * expiration_month  [required] -
 * * expiration_year   [required] -
 * * cvv               [required] -
 * * address_street    [optional] -
 * * address_city      [optional] -
 * * address_state     [optional] -
 * * address_country   [required] -
 * * address_zip       [optional] - you should include this to improve the success rate
 * * phone_number      [required] -
 * * remember_my_card  [optional] - default false, use this flag to store the credit card authorization token
 * * return_raw_error  [optional] - Default true - if an error occurs during processing the raw error list
 *   will be returned instead of the /api/forms data with inline error
 *
 * <h4>Test Payments</h4>
 *
 * Test payments can be performed by setting a 'dev-flag' header to any
 * value that PHP evaluates as true and using the following card number
 * / CVV combinations:
 *
 * Card Numbers:
 *
 * * 4242424242424242
 * * 4000000000000002
 *
 * CVV Codes and Expected Response:
 *
 * * 111 Error: Please ensure the CVV/CVC number is correct before retrying the transaction
 * * 222 Error: Please contact your credit card company to check your available balance
 * * 333 Error: Please contact your credit card company to approve your payment
 *
 * Any valid CVV that is not listed above will result in a success when using the test system
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
 *       'siteKey'      => '1234asdf1234asdf',
 *       'siteDomain'   => 'MySiteDomain',
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
            $data['token'] = $this->getCardReference();

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

    /**
     * Get transaction endpoint.
     *
     * Purchases are created using the /payments/submit/ resource.
     *
     * @return string
     */
    protected function getEndpoint()
    {
        return parent::getEndpoint() . 'payments/submit/';
    }

    public function sendData($data)
    {
        // Initialise the PaymentWall configuration, done in the parent function.
        parent::sendData($data);

        // Create a one time token
        $tokenModel = new \Paymentwall_OneTimeToken();
        $token =  $tokenModel->create([
            'public_key'        => $this->getPublicKey(),
            'card[number]'      => $data['card_number'],
            'card[exp_month]'   => $data['expiration_month'],
            'card[exp_year]'    => $data['expiration_year'],
            'card[cvv]'         => $data['cvv']
        ]);

        //  echo "Token data = " . print_r($token, true) . "\n";

        // Create the charge and apply the token
        $charge_data = [
            'email'                 => $data['billing_email'],
            // 'customer[firstname]'   => $purchase->getValue('name'),
            'uid'                   => $data['account_id'],
            'plan'                  => $data['package_id'],
            'amount'                => $data['amount'],
            'currency'              => $data['currency'],
            'token'                 => $token->getToken(),
            'fingerprint'           => $data['description'],
            'description'           => $data['description'],
            'browser_ip'            => $data['browser_ip'],
            // 'browser_domain'        => $purchase->getValue('browser_domain'),
        ];
        $charge = new \Paymentwall_Charge();
        $charge->create($charge_data);

        // Get the response data -- this is returned as a JSON string.
        $charge_response = $charge->getPublicData();
        $this->response = new Response($this, json_decode($charge_response, true));

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
