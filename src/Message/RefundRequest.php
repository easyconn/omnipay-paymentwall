<?php
/**
 * PaymentWall Refund Request.
 */
namespace Omnipay\PaymentWall\Message;

/**
 * PaymentWall Refund Request.
 *
 * Paymentwall is the leading digital payments platform for globally monetizing
 * digital goods and services. Paymentwall assists game publishers, dating publics,
 * rewards publics, SaaS companies and many other verticals to monetize their
 * digital content and services.
 *
 * This uses the PaymentWall library at https://github.com/paymentwall/paymentwall-php
 * and the Brick API to communicate to PaymentWall.
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
 *       'apiType'      => $gateway::API_GOODS,
 *       'publicKey'    => 'YOUR_PUBLIC_KEY',
 *       'privateKey'   => 'YOUR_PRIVATE_KEY',
 *   ));
 * </code>
 *
 * <h4>Purchase Transaction</h4>
 *
 * For examples of that see the PurchaseRequest message class.
 *
 * <h4>Refund an existing Transaction</h4>
 *
 * This assumes that the transaction has been made and that
 * the transaction ID is stored in $sale_id
 *
 * <code>
 *   // Do a purchase transaction on the gateway
 *   $transaction = $gateway->refund(array(
 *       'transactionReference'      => $sale_id
 *   ));
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *       echo "Refund transaction was successful!\n";
 *       $refund_id = $response->getTransactionReference();
 *       echo "Transaction reference = " . $refund_id . "\n";
 *   }
 * </code>
 *
 * @link https://www.paymentwall.com/en/documentation/getting-started
 * @link https://www.paymentwall.com/
 * @link https://github.com/paymentwall/paymentwall-php
 * @see Omnipay\PaymentWall\Gateway
 */
class RefundRequest extends AbstractLibraryRequest
{
    public function getData()
    {
        $this->validate('transactionReference');
        $data = parent::getData();
        $data['sale_id'] = $this->getTransactionReference();

        return $data;
    }

    public function sendData($data)
    {
        // Initialise the PaymentWall configuration
        $this->setPaymentWallObject();

        // Create the charge object
        $charge = new \Paymentwall_Charge($data['sale_id']);
        $charge->refund();

        // Get the response data -- this is returned as a JSON string.
        $charge_data = json_decode($charge->getRawResponseData(), true);

        // Construct the response object
        $this->response = new Response($this, $charge_data);

        return $this->response;
    }
}
