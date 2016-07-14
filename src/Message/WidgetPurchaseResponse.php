<?php
/**
 * PaymentWall Widget Purchase Response.
 *
 * Class WidgetPurchaseResponse
 *
 * @author Satheesh Narayanan <satheesh@incube8.sg>
 */
namespace Omnipay\PaymentWall\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * PaymentWall Widget Purchase Response.
 *
 * This is the response class for all PaymentWall Widget Purchase requests.
 *
 * @link https://www.paymentwall.com/en/documentation/getting-started
 * @see \Omnipay\PaymentWall\WidgetGateway
 */
class WidgetPurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    protected $statusCode;

    /**
     * WidgetPurchaseResponse constructor.
     *
     * @param RequestInterface $request
     * @param mixed            $data
     * @param int              $statusCode
     */
    public function __construct(RequestInterface $request, $data, $statusCode = 200)
    {
        parent::__construct($request, $data);
        $this->statusCode = $statusCode;
    }

    /**
     * Declare the response is Redirect Method.
     *
     * @return bool
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * Fetch the Redirect URL from response data.
     *
     * @return mixed
     */
    public function getRedirectUrl()
    {
        return $this->data;
    }

    /**
     * Return the response status.
     *
     * @return bool
     */
    public function isSuccessful()
    {
        // The PaymentWall gateway returns errors in several possible different ways.
        if ($this->getCode() >= 400) {
            return false;
        }

        if (!empty($this->data['success'])) {
            return true;
        }

        if (empty($this->data['error'])) {
            return true;
        }

        // PaymentWall returns an empty response for API calls that are not
        // implemented, so in this case assume that the call failed.
        return false;
    }

    /**
     * Get Transaction Reference.
     *
     * @return string|null
     */
    public function getTransactionReference()
    {
        // This is usually correct for payments, authorizations, etc
        if (isset($this->data['id'])) {
            return $this->data['id'];
        }
    }

    /**
     * Get Card Reference.
     *
     * @return string|null
     */
    public function getCardReference()
    {
        if (isset($this->data['card']['token'])) {
            return $this->data['card']['token'];
        }
    }

    /**
     * Fetch error Message if any.
     *
     * @return string|null
     */
    public function getMessage()
    {
        if (isset($this->data['error'])) {
            return $this->data['error'];
        }
    }

    /**
     * Fetch code from response.
     *
     * @return string|null
     */
    public function getCode()
    {
        if (isset($this->data['error']) && is_array($this->data['error']) && isset($this->data['error']['code'])) {
            return $this->data['error']['code'];
        }
        if (isset($this->data['code'])) {
            return $this->data['code'];
        }
    }

    /**
     * Get redirect method.
     *
     * @return null
     */
    public function getRedirectMethod()
    {
    }

    /**
     * Fetch redirect data.
     *
     * @return null
     */
    public function getRedirectData()
    {
    }
}
