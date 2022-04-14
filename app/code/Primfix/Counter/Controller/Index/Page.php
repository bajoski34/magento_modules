<?php

declare(strict_types=1);

namespace Primfix\Counter\Controller\Index;

use Magento\Framework\App\ActionInterface;

class Page  implements ActionInterface
{
    public function __construct(\Magento\Framework\Controller\View\PageFactory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    public function execute()
    {
        $page = $this->viewFactory->create();
        $page->getConfig()->getTitle()->set("Primfix");
        return $page;
    }
}
