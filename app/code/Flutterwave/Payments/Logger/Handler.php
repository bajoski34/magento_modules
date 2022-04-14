<?php

namespace Flutterwave\Payments\Logger;

use Monolog\Logger;
use \Magento\Framework\Logger\Handler\Base;

class Handler extends Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::INFO;
    public $filePath;

    public function __construct(
        \Magento\Framework\Filesystem\DriverInterface $filesystem,
        \Magento\Framework\App\Filesystem\DirectoryList $dir
    ) {
        $ds = DIRECTORY_SEPARATOR;
        $this->filePath = $dir->getPath('log') . $ds . 'flutterwave_webhooks.log';

        parent::__construct($filesystem, $this->filePath);
    }

    public function exists()
    {
        return file_exists($this->filePath);
    }
}