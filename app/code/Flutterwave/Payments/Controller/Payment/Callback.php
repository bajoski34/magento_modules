<?php

namespace Flutterwave\Payments\Controller\Payment;

class Callback extends AbstractFlutterwaveStandard {

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute() {

        $reference = $this->request->get('reference');
        $message = "";
        
        if (!$reference) {
            return $this->redirectToFinal(false, "No reference supplied");
        }
        
        try {
            $transactionDetails = $this->paystack->transaction->verify([
                'reference' => $reference
            ]);
            
            $reference = explode('_', $transactionDetails->data->reference, 2);
            $reference = ($reference[0])?: 0;
            
            $order = $this->orderInterface->loadByIncrementId($reference);
            
            if ($order && $reference === $order->getIncrementId()) {
                // dispatch the `payment_verify_after` event to update the order status
                
                $this->eventManager->dispatch('paystack_payment_verify_after', [
                    "paystack_order" => $order,
                ]);

                return $this->redirectToFinal(true);
            }

            $message = "Invalid reference or order number";
            
        } catch (\Yabacon\Paystack\Exception\ApiException $e) {
            $message = $e->getMessage();
            
        } catch (Exception $e) {
            $message = $e->getMessage();
            
        }

        return $this->redirectToFinal(false, $message);
    }

}