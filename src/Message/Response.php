<?php
/**
 * PaymentWall Library Response.
 */
namespace Omnipay\PaymentWall\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * PaymentWall Library Response.
 *
 * This is the response class for all PaymentWall Library requests.
 *
 * @link https://www.paymentwall.com/en/documentation/getting-started
 * @see \Omnipay\PaymentWall\Gateway
 */
class Response extends AbstractResponse
{
    protected $statusCode;

    /** @var bool */
    protected $captured = false;

    /** @var bool */
    protected $underReview = false;

    public function __construct(RequestInterface $request, $data, $statusCode = 200)
    {
        parent::__construct($request, $data);
        $this->statusCode = $statusCode;
    }

    /**
     * Set captured status.
     *
     * @param bool $captured
     *
     * @return Response provides a fluent interface
     */
    public function setCaptured($captured)
    {
        $this->captured = $captured;

        return $this;
    }

    /**
     * Get captured status.
     *
     * @return bool
     */
    public function isCaptured()
    {
        return $this->captured;
    }

    /**
     * Set under review status.
     *
     * @param bool $underReview
     *
     * @return Response provides a fluent interface
     */
    public function setUnderReview($underReview)
    {
        $this->underReview = $underReview;

        return $this;
    }

    /**
     * Get under review status.
     *
     * @return bool
     */
    public function isUnderReview()
    {
        return $this->underReview;
    }

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
     * @return string
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
     * @return string
     */
    public function getCardReference()
    {
        if (isset($this->data['card']['token'])) {
            return $this->data['card']['token'];
        }
    }

    public function getMessage()
    {
        if (isset($this->data['error'])) {
            return $this->data['error'];
        }
    }

    public function getCode()
    {
        if (isset($this->data['error']) && is_array($this->data['error']) && isset($this->data['error']['code'])) {
            return $this->data['error']['code'];
        }
        if (isset($this->data['code'])) {
            return $this->data['code'];
        }
    }
}
