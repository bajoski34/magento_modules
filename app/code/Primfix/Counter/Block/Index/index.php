<?php

declare(strict_type=1);

namespace Primfix\Counter\Block\Index;

use Magento\Framework\View\Element\Template;

class Index extends Template
{
    public function getModuleRealName(): string
    {
        return 'Primfix Counter Module';
    }
}