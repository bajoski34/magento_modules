<?php

namespace Primfix\Counter\Controller\Index;

use Magento\Framework\App\ActionInterface;

class Json implements ActionInterface
{
    public function __construct(\Magento\Framework\Controller\Result\JsonFactory $jsonFactory)
    {
        $this->jsonFactory = $jsonFactory;
    }

    public function execute()
    {
        return $this->jsonFactory->create()->setHeader('Content-Type', 'application/json')->setData([
            'name' => 'Primfix LTD',
            'job' => 'Software Engineer'
        ]);
    }
}