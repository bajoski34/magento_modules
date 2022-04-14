<?php

namespace Flutterwave\Payments\Controller\Webhook;

// use Magento\Framework\App\ActionInterface;
use Flutterwave\Payments\Helper\Logger;
use \Magento\Framework\App\Action\Action;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\InvalidRequestException;

class Index extends Action implements CsrfAwareActionInterface
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Flutterwave\Payments\Helper\Webhook $webhooks,
        \Magento\Sales\Model\Service\InvoiceService $invoiceService,
        \Magento\Framework\DB\Transaction $dbTransaction,
        )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->webhooks = $webhooks;
        $this->invoiceService = $invoiceService;
        $this->dbTransaction = $dbTransaction;
    }

    public function execute()
    {
        $this->webhooks->dispatchEvent();
    }

    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}