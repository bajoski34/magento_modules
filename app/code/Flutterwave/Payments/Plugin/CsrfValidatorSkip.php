<?php

namespace Flutterwave\Payments\Plugin;

use Flutterwave\Payments\Helper\Logger;

class CsrfValidatorSkip
{
    public function aroundValidate(
        $subject,
        \Closure $proceed,
        $request,
        $action
    ) {
        // stripe is the route name
        if ($request->getModuleName() == 'flutterwave') {
            return; // Skip CSRF check
        }
        $proceed($request, $action);
    }
}