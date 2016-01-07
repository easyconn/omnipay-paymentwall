<?php
/**
 * PaymentWall widget Gateway
 *
 * Class WidgetGateway
 *
 * @author Satheesh Narayanan <satheesh@incube8.sg>
 *
 */
namespace Omnipay\PaymentWall;

use Omnipay\Common\AbstractGateway;

/**
 * PaymentWall widget Gateway
 *
 * Class WidgetGateway
 *
 * FIXME: Description and examples required here.
 */
class WidgetGateway extends AbstractGateway
{

    const API_VC = \Paymentwall_Config::API_VC;
    const API_GOODS = \Paymentwall_Config::API_GOODS;
    const API_CART = \Paymentwall_Config::API_CART;

    /**
     * Get the gateway display name
     *
     * @return string
     */
    public function getName()
    {
        return 'PaymentWall Widget';
    }

    /**
     * Get the gateway default parameters
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'apiType'       => 0,
            'publicKey'     => '',
            'privateKey'    => '',
            'widgetKey'     => '',
            'signVersion'  => 3,
            'country_code' => 'US'
        );
    }

    /**
     * Get the gateway apiType -- used in every request
     *
     * @return string
     */
    public function getApiType()
    {
        return $this->getParameter('apiType');
    }

    /**
     * Set the gateway apiType -- used in every request
     *
     * @return Gateway provides a fluent interface.
     */
    public function setApiType($value)
    {
        return $this->setParameter('apiType', $value);
    }

    /**
     * Get the gateway apiType -- used in every request
     *
     * @return string
     */
    public function getWidgetKey()
    {
        return $this->getParameter('widgetKey');
    }

    /**
     * Set the gateway apiType -- used in every request
     *
     * @return Gateway provides a fluent interface.
     */
    public function setWidgetKey($value)
    {
        return $this->setParameter('widgetKey', $value);
    }

    /**
     * Get the gateway apiType -- used in every request
     *
     * @return string
     */
    public function getSignVersion()
    {
        return $this->getParameter('signVersion');
    }

    /**
     * Set the gateway apiType -- used in every request
     *
     * @return Gateway provides a fluent interface.
     */
    public function setSignVersion($value)
    {
        return $this->setParameter('signVersion', $value);
    }

    /**
     * Get the gateway publicKey -- used in every request
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->getParameter('publicKey');
    }

    /**
     * Set the gateway publicKey -- used in every request
     *
     * @return Gateway provides a fluent interface.
     */
    public function setPublicKey($value)
    {
        return $this->setParameter('publicKey', $value);
    }

    /**
     * Get the gateway privateKey -- used in every request
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->getParameter('privateKey');
    }

    /**
     * Set the gateway privateKey -- used in every request
     * @param $value
     * @return Gateway provides a fluent interface.
     */
    public function setPrivateKey($value)
    {
        return $this->setParameter('privateKey', $value);
    }

    //
    // Direct API Purchase Calls -- purchase, refund
    //

    /**
     * Create a purchase request.
     *
     * @param array $parameters
     * @return \Omnipay\PaymentWall\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentWall\Message\WidgetPurchaseRequest', $parameters);
    }

    /**
     * Fetch payment system list for PW widget gateway
     *
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function pullPaymentList(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentWall\Message\WidgetPaymentList', $parameters);
    }

}
