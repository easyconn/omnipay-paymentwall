<?php
/**
 * PaymentWall Abstract Library Request.
 */
namespace Omnipay\PaymentWall\Message;

// use Guzzle\Http\EntityBody;

/**
 * PaymentWall Abstract Library Request.
 *
 * This is the parent class for all PaymentWall Library requests.
 *
 * @link https://www.paymentwall.com/en/documentation/getting-started
 * @link https://www.paymentwall.com/
 * @link https://github.com/paymentwall/paymentwall-php
 * @see \Omnipay\PaymentWall\Gateway
 */
abstract class AbstractLibraryRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const API_VERSION = '1';

    /**
     * Get the Paymentwall API end point.
     *
     * @var string
     */
    protected $endPoint = 'https://api.paymentwall.com/api';

    /**
     * Fetch EndPoint for Paymentwall Api.
     *
     * @return string
     */
    protected function getEndpoint()
    {
        return $this->endPoint;
    }

    /**
     * Get the gateway apiType -- used in every request.
     *
     * @return string
     */
    public function getApiType()
    {
        return $this->getParameter('apiType');
    }

    /**
     * Set the gateway apiType -- used in every request.
     *
     * @return Gateway provides a fluent interface.
     */
    public function setApiType($value)
    {
        return $this->setParameter('apiType', $value);
    }

    /**
     * Get the gateway publicKey -- used in every request.
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->getParameter('publicKey');
    }

    /**
     * Set the gateway publicKey -- used in every request.
     *
     * @return Gateway provides a fluent interface.
     */
    public function setPublicKey($value)
    {
        return $this->setParameter('publicKey', $value);
    }

    /**
     * Get the gateway privateKey -- used in every request.
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->getParameter('privateKey');
    }

    /**
     * Set the gateway privateKey -- used in every request.
     *
     * @return Gateway provides a fluent interface.
     */
    public function setPrivateKey($value)
    {
        return $this->setParameter('privateKey', $value);
    }

    /**
     * Set the data used in every request.
     *
     * In this gateway a certain amount of data needs to be sent
     * in every request.  This function sets those data into the
     * array and can be extended by child classes.
     *
     * @return array
     */
    public function getData()
    {
        $this->validate('publicKey');
        $data = array(
            'public_key'        => $this->getPublicKey(),
        );

        return $data;
    }

    /**
     * Initialise the PaymentWall Config Instance.
     *
     * @return void
     */
    public function setPaymentWallObject()
    {
        // Initialise the PaymentWall configuration
        \Paymentwall_Config::getInstance()->set(array(
            'api_type'    => $this->getApiType(),
            'public_key'  => $this->getPublicKey(),
            'private_key' => $this->getPrivateKey(),
        ));
    }

    /**
     * Get the PaymentWall Config Instance.
     *
     * @return \Paymentwall_Config
     */
    public function getPaymentWallObject()
    {
        if (\Paymentwall_Config::getInstance()->getPublicKey() == false) {
            $this->setPaymentWallObject();
        }

        return \Paymentwall_Config::getInstance();
    }
}
