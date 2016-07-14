<?php
/**
 * PaymentWall Purchase Request.
 *
 * Class WidgetPurchaseRequest
 *
 * @author Satheesh Narayanan <satheesh@incube8.sg>
 */
namespace Omnipay\PaymentWall\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * PaymentWall Widget Gateway.
 *
 * Paymentwall Widget is the leading digital payments platform for globally monetizing
 * digital goods and services. Paymentwall Widget assists game publishers, dating publics,
 * rewards publics, SaaS companies and many other verticals to monetize their
 * digital content and services.
 *
 * This uses the PaymentWall library at https://github.com/paymentwall/paymentwall-php
 * and the Brick API to communicate to PaymentWall.
 *
 * ### Example
 *
 * <code>
 *   // Create a gateway for the PaymentWall Widget Gateway
 *   // (routes to GatewayFactory::create)
 *   $gateway = Omnipay::create('PaymentWall_Widget');
 *
 *   // Initialise the gateway
 *   $gateway->initialize(array(
 *       'apiType'      => $gateway::API_GOODS,
 *       'publicKey'    => 'YOUR_PUBLIC_KEY',
 *       'privateKey'   => 'YOUR_PRIVATE_KEY',
 *   ));
 *
 *
 *   // Build PaymentWall Widget Payment Url
 *   $transaction = $gateway->purchase(array(
 *       'amount'                    => '10.00',
 *       'accountId'                 => 12341234,
 *       'currency'                  => 'AUD',
 *       'clientIp'                  => '127.0.0.1',
 *       'packageId'                 => 1234,
 *       'description'               => 'Super Deluxe Excellent Discount Package',
 *       'browserDomain'             => 'SiteName.com',
 *   ));
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *       echo "Build paymentWall Widget URL!\n";
 *       $pwUrl = $response->getUrl();
 *       echo "PaymentWall Widget URL = " . $pwUrl . "\n";
 *   }
 * </code>
 *
 *
 * @see \Omnipay\Common\AbstractGateway
 * @see \Omnipay\PaymentWall\Message\AbstractRestRequest
 * @link https://www.paymentwall.com/en/documentation/getting-started
 * @link https://www.paymentwall.com/
 * @link https://github.com/paymentwall/paymentwall-php
 * */
class WidgetPurchaseRequest extends AbstractLibraryRequest
{
    /**
     * Get the request packageId.
     *
     * @return string
     */
    public function getPackageId()
    {
        return $this->getParameter('packageId');
    }

    /**
     * Set the request packageId.
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
     * Get the request accountId.
     *
     * @return string
     */
    public function getAccountId()
    {
        return $this->getParameter('accountId');
    }

    /**
     * Set the request accountId.
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
     * Get the request email.
     *
     * The email can be in the parameter bag or the card data
     *
     * @return string
     */
    public function getEmail()
    {
        $email = $this->getParameter('email');
        $card = $this->getCard();
        if (empty($email) && !empty($card)) {
            $email = $this->getCard()->getEmail();
        }

        return $email;
    }

    /**
     * Set the request email.
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
     * Get browserDomain.
     *
     * @return string Site name or URL
     */
    public function getBrowserDomain()
    {
        return $this->getParameter('browserDomain');
    }

    /**
     * Set the request browserDomain.
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
     * Get Country Code.
     *
     * @return string 2 character country code.
     */
    public function getCountry()
    {
        return $this->getParameter('country');
    }

    /**
     * Set the request country code.
     *
     * @param string $value 2 character country code
     *
     * @return PurchaseRequest provides a fluent interface.
     */
    public function setCountry($value)
    {
        return $this->setParameter('country', $value);
    }

    /**
     * Validate the parameters.
     *
     *  @throws InvalidRequestException directly for missing email, indirectly through validate
     *
     *  @return void
     */
    public function getData()
    {
        // verify that required parameters are provided
        // calls \Omnipay\Common\Message\AbstractRequest::validate()
        $requiredParams = array('email', 'clientIp', 'browserDomain', 'accountId', 'widgetKey', 'packageId', 'amount', 'currency', 'description');

        // pass the param list to the validate function
        call_user_func_array(array($this, 'validate'), $requiredParams);
        //Using the getData method only to validate the data, so return nothing
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
        $this->response = new WidgetPurchaseResponse($this, $data);

        return $this->response;
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
     * @return PurchaseRequest provides a fluent interface.
     */
    public function setWidgetKey($value)
    {
        return $this->setParameter('widgetKey', $value);
    }

    /**
     * Submit a payment through the PaymentWall Library.
     *
     * @param mixed $data
     *
     * @return Response
     */
    public function sendData($data)
    {
        // Initialise the PaymentWall configuration
        $this->setPaymentWallObject();

        $widget = new \Paymentwall_Widget(
            $this->getAccountId(), // token ID of end user who's making payment
            $this->getWidgetKey(), //Widget code p2_1
            array(
                new \Paymentwall_Product(
                    $this->getPackageId(),
                    $this->getAmount(),
                    $this->getCurrency(),
                    $this->getDescription(),
                    \Paymentwall_Product::TYPE_FIXED
                ),
            ),
            array( //Pass the additional/custom parameters
                'email'             => $this->getEmail(),
                'browser_ip'        => $this->getClientIp(),
                'browser_domain'    => $this->getBrowserDomain(),
                'success_url'       => $this->getReturnUrl(),
                'pingback_url'      => $this->getNotifyUrl(),
                'country_code'      => $this->getCountry(),
            )
        );

        try {
            // $widget->getHtmlCode(); //will redirect user to widget page
            return new WidgetPurchaseResponse($this, $widget->getUrl());
        } catch (\Exception $e) {
            return $this->returnError('Cannot process payment', 231, $e->getMessage());
        }
    }
}
