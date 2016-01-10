<?php
/**
 * PaymentWall Widget Purchase Response
 *
 * Class WidgetPurchaseResponse
 *
 * @author Satheesh Narayanan <satheesh@incube8.sg>
 */

namespace Omnipay\PaymentWall\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * PaymentWall Widget Purchase Response
 *
 * This is the response class for all PaymentWall Widget Purchase requests.
 *
 * FIXME: This needs to implement the RedirectResponse interface.
 *
 * FIXME: Docblock return statements are incorrect throughout.
 *
 * @link https://www.paymentwall.com/en/documentation/getting-started
 * @see \Omnipay\PaymentWall\WidgetGateway
 */
class WidgetPurchaseResponse extends AbstractResponse
{
    protected $statusCode;

    /**
     * WidgetPurchaseResponse constructor.
     * @param RequestInterface $request
     * @param mixed $data
     * @param int $statusCode
     */
    public function __construct(RequestInterface $request, $data, $statusCode = 200)
    {
        parent::__construct($request, $data);
        $this->statusCode = $statusCode;
    }

    /**
     * Declare the response is Redirect Method
     *
     * @return bool
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * Fetch the Redirect URL from response data
     *
     * @return mixed
     */
    public function getRedirectUrl()
    {
        return $this->data;
    }


    /**
     * Set captured status
     *
     * @param boolean $captured
     * @return Response provides a fluent interface
     */
    public function setCaptured($captured)
    {
        $this->captured = $captured;
        return $this;
    }

    /**
     * Get captured status
     *
     * FIXME: Accesses undefined class variable.
     *
     * @return boolean
     */
    public function isCaptured()
    {
        return $this->captured;
    }

    /**
     * Set under review status
     *
     * @param boolean $underReview
     * @return Response provides a fluent interface
     */
    public function setUnderReview($underReview)
    {
        $this->underReview = $underReview;
        return $this;
    }

    /**
     * Get under review status
     *
     * FIXME: Accesses undefined class variable.
     *
     * @return boolean
     */
    public function isUnderReview()
    {
        return $this->underReview;
    }

    /**
     * Return the response status
     *
     * @return bool
     */
    public function isSuccessful()
    {
        // The PaymentWall gateway returns errors in several possible different ways.
        if ($this->getCode() >= 400) {
            return false;
        }

        if (! empty($this->data['success'])) {
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
     * Get Transaction Reference
     *
     * @return string
     */
    public function getTransactionReference()
    {
        // This is usually correct for payments, authorizations, etc
        if (isset($this->data['id'])) {
            return $this->data['id'];
        }
        return null;
    }

    /**
     * Get Card Reference
     *
     * @return string
     */
    public function getCardReference()
    {
        if (isset($this->data['card']['token'])) {
            return $this->data['card']['token'];
        }
        return null;
    }

    /**
     * Fetch error Message if any
     *
     * @return null
     */
    public function getMessage()
    {
        if (isset($this->data['error'])) {
            return $this->data['error'];
        }
        return null;
    }

    /**
     * Fetch code from response
     *
     * @return null
     */
    public function getCode()
    {
        if (isset($this->data['error']) && is_array($this->data['error']) && isset($this->data['error']['code'])) {
            return $this->data['error']['code'];
        }
        if (isset($this->data['code'])) {
            return $this->data['code'];
        }
        return null;
    }
}
