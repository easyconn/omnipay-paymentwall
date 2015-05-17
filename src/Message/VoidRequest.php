<?php
/**
 * PaymentWall Void Request
 */

namespace Omnipay\PaymentWall\Message;

/**
 * PaymentWall Void Request
 *
 * Paymentwall is the leading digital payments platform for globally monetizing
 * digital goods and services. Paymentwall assists game publishers, dating publics,
 * rewards publics, SaaS companies and many other verticals to monetize their
 * digital content and services.
 *
 * This uses the PaymentWall library at https://github.com/paymentwall/paymentwall-php
 * and the Brick API to communicate to PaymentWall.
 *
 * FIXME: There are no  transaction references coming back from the gateway and there
 * are no cards being stored.  Also see Quirks, below.
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
 * <h4>Void an existing Transaction</h4>
 *
 * This assumes that the transaction has been made and that
 * the transaction ID is stored in $sale_id
 *
 * <code>
 *   // Do a purchase transaction on the gateway
 *   $transaction = $gateway->void(array(
 *       'transactionReference'      => $sale_id
 *   ));
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *       echo "Void transaction was successful!\n";
 *       $void_id = $response->getTransactionReference();
 *       echo "Transaction reference = " . $void_id . "\n";
 *   }
 * </code>
 *
 * <h4>Quirks</h4>
 *
 * * Refunds are not supported, these must be done manually.  Voids are
 *   the only form of refund supported.
 * * Void transaction IDs are the same as the purchase transaction IDs.
 *
 * @link https://www.paymentwall.com/en/documentation/getting-started
 * @link https://www.paymentwall.com/
 * @link https://github.com/paymentwall/paymentwall-php
 * @see Omnipay\PaymentWall\Gateway
 */
class VoidRequest extends AbstractLibraryRequest
{

    public function getData()
    {
        // An amount parameter is required, as is a currency and
        // an account ID.
        $this->validate('transactionReference');
        $data                   = parent::getData();
        $data['sale_id']        = $this->getTransactionReference();
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
        $charge_response = $charge->getPublicData();
        $charge_data = json_decode($charge_response, true);
        // echo "Charge Data == " . print_r($charge_data, true) . "\n";

        // Get the remaining data from the response
        // echo "Charge == " . print_r($charge, true) . "\n";
        // echo "Charge ID == " . $charge->getId() . "\n";
        $charge_data['transaction_reference'] = $charge->getId();

        // Construct the response object
        $this->response = new Response($this, $charge_data);
        return $this->response;
    }
}
