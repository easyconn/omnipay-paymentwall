<?php
/**
 * PaymentWall Widget Payment list Response.
 *
 * Class WidgetPaymentListResponse
 *
 * @author Satheesh Narayanan <satheesh@incube8.sg>
 */
namespace Omnipay\PaymentWall\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * PaymentWall Widget Payment List Response.
 *
 * This is the response class for all PaymentWall Widget payment list requests.
 *
 * @link https://www.paymentwall.com/en/documentation/getting-started
 * @see \Omnipay\PaymentWall\WidgetGateway
 */
class WidgetPaymentListResponse extends AbstractResponse
{
    protected $statusCode;

    /**
     * WidgetPaymentListResponse constructor.
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

        return true;
    }
}
