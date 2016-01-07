<?php
/**
 * PaymentWall Purchase Request
 *
 * @author Satheesh Narayanan <satheesh@incube8.sg>
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
 * ### Examples
 *
 * FIXME: These examples are not correct for the WidgetGateway.
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
 *
 *   // Do a purchase transaction on the gateway
 *   $transaction = $gateway->purchase(array(
 *       'amount'                    => '10.00',
 *       'accountId'                 => 12341234,
 *       'currency'                  => 'AUD',
 *       'clientIp'                  => '127.0.0.1',
 *       'packageId'                 => 1234,
 *       'description'               => 'Super Deluxe Excellent Discount Package',
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
 * #### Payment with Card Token
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
 * @see Omnipay\PaymentWall\WidgetGateway
 */
// FIXME: Broken class name.
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
        $card = $this->getCard();
        if (empty($email) && ! empty($card)) {
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
     * Build an array from the ParameterBag object that is ready for sendData
     *
     * FIXME: The link below is not correct for this API call.
     *
     * @throws InvalidRequestException directly for missing email, indirectly through validate
     * @link https://www.paymentwall.com/en/documentation/Brick/2968#charge_create
     * @return array
     */
    public function getData()
    {
        // verify that required parameters are provided
        // calls \Omnipay\Common\Message\AbstractRequest::validate()
        // FIXME: It seems that email, clientIp and browserDomain are required parameters.
        $requiredParams = array('accountId','widgetKey','packageId','amount','currency','description');

        // pass the param list to the validate function
        call_user_func_array(array($this, 'validate'), $requiredParams);

        $data = array(
            $this->getAccountId(), // token ID of end user who's making payment
            $this->widgetKey(), //Widget code p2_1
            array(
                new \Paymentwall_Product(
                    $this->getPackageId(),
                    $this->getAmount(),
                    $this->getCurrency(),
                    $this->getDescription(),
                    \Paymentwall_Product::TYPE_FIXED
                )
            ),
            array( //Pass the additional/custom parameters
                'email'             => $this->getEmail(),
                'browser_ip'        => $this->getClientIp(),
                'browser_domain'    => $this->getBrowserDomain()
            )
        );

        return $data;
    }

    /**
     * Build an error response and return it.
     *
     * @param string    $message
     * @param string    $code
     * @return Response
     */
    public function returnError($message, $code, $responseLogInformation = null)
    {
        $data = array(
            'type'          => 'Error',
            'object'        => 'Error',
            'error'         => $message,
            'code'          => $code,
            'log'           => $responseLogInformation
        );
        // FIXME: This is not correct. I think that you need a new response class here.
        $this->response = new Response($this, $data);
        return $this->response;
    }

    /**
     * Submit a payment through the PaymentWall Library
     *
     * @param mixed $data
     * @return Response
     */
    public function sendData($data)
    {
        // Initialise the PaymentWall configuration
        $this->setPaymentWallObject();

        $widget = new \Paymentwall_Widget($data);

        try{
            //$widget->getHtmlCode(); will redirect user to widget page
            // FIXME: No I don't think that getHtmlCode is what you want. I think that
            // you want a new response class here that is a redirect response.  The
            // redirect URL is the $widget->getUrl() result.  See the email from Ivan
            // on 21st Dec.  Check how some of the other gateways create their
            // redirect responses.
            echo $widget->getHtmlCode();
        } catch (\Exception $e) {
            return $this->returnError('Cannot process payment', 231, $e->getMessage());
        }
    }
}
