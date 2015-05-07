<?php
/**
 * PaymentWall Library Response
 */

namespace Omnipay\PaymentWall\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * PaymentWall Library Response
 *
 * This is the response class for all PaymentWall Library requests.
 *
 * @link https://www.paymentwall.com/en/documentation/getting-started
 * @see \Omnipay\PaymentWall\Gateway
 */
class LibraryResponse extends AbstractResponse
{
    protected $statusCode;

    public function __construct(RequestInterface $request, $data, $statusCode = 200)
    {
        parent::__construct($request, $data);
        $this->statusCode = $statusCode;
    }

    public function isSuccessful()
    {
        // The PaymentWall gateway returns errors in several possible different ways.
        if ($this->getCode() >= 400) {
            return false;
        }

        if (! empty($this->data['error'])) {
            return false;
        }

        if (! empty($this->data['response']['success'])) {
            return $this->data['response']['success'];
        }

        // PaymentWall returns an empty response for API calls that are not
        // implemented, so in this case assume that the call failed.
        return false;
    }

    public function getTransactionReference()
    {
        // This is usually correct for payments, authorizations, etc
        if (isset($this->data['payment']) &&
            isset($this->data['payment']['charge']) &&
            isset($this->data['payment']['charge']['order_id'])) {
            return $this->data['payment']['charge']['order_id'];
        }
        return null;
    }

    /**
     * Get Card Reference
     *
     * This doesn't work yet.
     *
     * @return string
     */
    public function getCardReference()
    {
        if (isset($this->data['payment']) &&
            isset($this->data['payment']['card']) &&
            isset($this->data['payment']['card']['auth_token'])) {
            return $this->data['payment']['card']['auth_token'];
        }
        return null;
    }

    public function getMessage()
    {
        if (isset($this->data['error']) &&
            isset($this->data['error']['message'])) {
            return $this->data['error']['message'];
        }
        return null;
    }

    public function getCode()
    {
        return $this->statusCode;
    }
}
