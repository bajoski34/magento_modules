<?php

declare(strict_types=1);

namespace Primfix\Counter\Controller\Index;

use Magento\Framework\App\ActionInterface;

class Index implements ActionInterface 
{
    public function __construct(\Magento\Framework\Controller\Result\RawFactory $resultFactory)
    {
        $this->resultFactory = $resultFactory;
    }
    public function execute() 
    {
       return $this->resultFactory->create()->setContents("PrimFix---");
    //    return $this->resultFactory->create()->setHeader('Content-Type', 'text/xml')->setContents("<root><name>Abraham Olaobaju</name><jobs>Software Engineer</jobs></root>");
    }
}