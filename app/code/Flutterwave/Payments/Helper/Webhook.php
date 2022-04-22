<?php

namespace Flutterwave\Payments\Helper;

use Flutterwave\Payments\Exception\WebhookException;

class Webhook {
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\App\Response\Http $response,
        \Flutterwave\Payments\Logger\WebhooksLogger $webhooksLogger,
        \Flutterwave\Payments\Model\Config $config
    )
    {
        $this->request = $request;
        $this->response = $response;
        $this->webhooksLogger = $webhooksLogger;
        $this->config = $config;
    }

    public function dispatchEvent()
    {
        try
        {
            if ($this->request->getMethod() == 'GET')
                throw new WebhookException("Your webhooks endpoint is accessible!", 200);

            $this->verifyWebhookSignature();

            // Retrieve the request's body and parse it as JSON
            $body = $this->request->getContent();
            $event = json_decode($body, true);
            $stdEvent = json_decode($body);

            if (empty($event['type']))
                throw new WebhookException(__("Unknown event type"));

            if ($event['type'] == "product.created")
            {
                $this->onProductCreated($event, $stdEvent);
                $this->log("200 OK");
                return;
            }

            if ($this->cache->load($event['id']) && empty($this->request->getParam('dev')))
                throw new WebhookException(__("Event with ID %1 has already been processed.", $event['id']), 202);

            $eventType = $this->getEventType($event);
            $this->log("Received $eventType");

            $this->response->setStatusCode(500);
            $this->eventManager->dispatch($eventType, array(
                    'arrEvent' => $event,
                    'stdEvent' => $stdEvent,
                    'object' => $event['data']['object'],
                    'paymentMethod' => $this->getPaymentMethodFrom($event)
                ));
            $this->response->setStatusCode(200);

            $this->cache($event);
            $this->log("200 OK");
        }
        catch (WebhookException $e)
        {
            if (!empty($e->statusCode))
                $this->response->setStatusCode($e->statusCode);
            else
                $this->response->setStatusCode(202);

            $statusCode = $this->response->getStatusCode();

            $this->error($e->getMessage(), $statusCode, true);

            if (!empty($statusCode) && !empty($event) && $statusCode < 400)
                $this->cache($event);
        }
        catch (\Exception $e)
        {
            $statusCode = 500;
            $this->response->setStatusCode($statusCode);

            $this->log($e->getMessage());
            $this->log($e->getTraceAsString());
            $this->error($e->getMessage(), $statusCode);
        }
    }

    protected function getEventType(array $event)
    {
        $eventType = $event['type'];
        return $eventType;
    }

    protected function getPaymentMethodFrom($event)
    {
        if (isset($event['data']['object']['type']))
            $paymentMethod = $event['data']['object']['type'];
        else if (isset($event['data']['object']['payment_method_types']))
            $paymentMethod = implode("_", $event['data']['object']['payment_method_types']);
        else if (isset($event['data']['object']['payment_method_details']))
            $paymentMethod = $event['data']['object']['payment_method_details']['type'];
        else
            $paymentMethod = '';

        return $paymentMethod;
    }

    public function log($msg)
    {
        // Magento 2.0.0 - 2.4.3
        if (method_exists($this->webhooksLogger, 'addInfo'))
            $this->webhooksLogger->addInfo($msg);
        // Magento 2.4.4+
        else
            $this->webhooksLogger->info($msg);
    }

    public function verifyWebhookSignature()
    {
        $signingSecrets = $this->config->getWebhooksSigningSecrets();
        if (empty($signingSecrets))
            return;

        $success = false;
    }
}