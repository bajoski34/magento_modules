<?php

namespace Primfix\Counter\Controller\Index;

use Magento\Framework\App\ActionInterface;

class Foward implements ActionInterface
{
    public function __construct(\Magento\Framework\Controller\Result\ForwardFactory $forwardFactory)
    {
        $this->forwardFactory = $forwardFactory;
    }

    public function execute()
    {
        return $this->forwardFactory->create()->forward('page');
    }
}