<?php

namespace MauticPlugin\MauticEvenlyDistributesSmtpBundle\Swiftmailer\Spool;

use Mautic\CoreBundle\Helper\CoreParametersHelper;
use Mautic\EmailBundle\Swiftmailer\Spool\DelegatingSpool;
use MauticPlugin\MauticEvenlyDistributesSmtpBundle\Helper\CommonHelper;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Date: 2024-09-04
 * Author: Gordon.Hu
 */
class EvenlyDistributesSpool extends DelegatingSpool
{
    /** @var CoreParametersHelper  */
    private $coreParametersHelper;

    /** @var CommonHelper  */
    private $commonHelper;

    private $container;

    /**
     * DelegatingSpool constructor.
     *
     * @throws \Swift_IoException
     */
    public function __construct(
        CoreParametersHelper $coreParametersHelper,
        \Swift_Transport $realTransport,
        CommonHelper $commonHelper,
        Container $container
    )
    {
        $this->coreParametersHelper = $coreParametersHelper;
        $this->commonHelper = $commonHelper;
        $this->container = $container;
        parent::__construct($coreParametersHelper, $realTransport);
    }

    /**
     * CreateDate: 9/4/2024 9:45 AM
     *
     * @param \Swift_Transport $transport
     * @param $failedRecipients
     * @return int
     */
    public function flushQueue(\Swift_Transport $transport, &$failedRecipients = null)
    {
        $directoryIterator = new \DirectoryIterator($this->getSpoolDir());
        /* Start the transport only if there are queued files to send */
        if (!$transport->isStarted()) {
            foreach ($directoryIterator as $file) {
                if ('.message' == substr($file->getRealPath(), -8)) {
                    $transport->start();
                    break;
                }
            }
        }

        $failedRecipients = (array) $failedRecipients;
        $count = 0;
        $time = time();
        foreach ($directoryIterator as $file) {
            $file = $file->getRealPath();

            if ('.message' != substr($file, -8)) {
                continue;
            }

            /* We try a rename, it's an atomic operation, and avoid locking the file */
            if (rename($file, $file.'.sending')) {
                $message = unserialize(file_get_contents($file.'.sending'));

                $smtp = $this->commonHelper->smtpServersRepository->getActiveServer();
                if (empty($smtp)) {
                    echo 'All servers reached 100% daily max';
                    return 0;
                }
                // set transport, message, stat object
                $this->commonHelper->setTransportAndMessageAndStat($smtp, $transport, $message, $stat);

                $count += $transport->send($message, $failedRecipients);
                // add successful sent log
                $this->commonHelper->addSentLog($smtp['id'], $stat);

                unlink($file.'.sending');
            } else {
                /* This message has just been catched by another process */
                continue;
            }

            if ($this->getMessageLimit() && $count >= $this->getMessageLimit()) {
                break;
            }

            if ($this->getTimeLimit() && (time() - $time) >= $this->getTimeLimit()) {
                break;
            }
        }

        return $count;
    }

    /**
     * CreateDate: 9/4/2024 9:45 AM
     *
     * @return string
     */
    private function getSpoolDir(): string
    {
        $filePath = $this->coreParametersHelper->get('mailer_spool_path');
        $rootPath = realpath(__DIR__.'/../../../../');

        if (!$filePath) {
            return $rootPath.'/../var/spool';
        }

        return str_replace('%kernel.root_dir%', $rootPath, $filePath);
    }
}