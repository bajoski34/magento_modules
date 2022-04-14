<?php

namespace Primfix\Counter\Controller\Index;

use Magento\Framework\App\ActionInterface;

class Redirect implements ActionInterface
{
    public function __construct(\Magento\Framework\Controller\Result\RedirectFactory $redirectFactory)
    {
        $this->redirectFactory = $redirectFactory;
    }

    public function execute()
    {
        //set the url to whatever comes after the domain EX: https://example.com/primfix/index/page should be :primfix/index/page
        return $this->redirectFactory->create()->setUrl("primfix/index/page");
        //TODO: find if this can be redirected to an external link if possible.
    }
}