<?php

namespace Flutterwave\Payments\Api;

interface PaymentManagementInterface
{

    /**
     * @param string $reference
     * @return bool
     */
    public function verifyPayment($reference);
  
}