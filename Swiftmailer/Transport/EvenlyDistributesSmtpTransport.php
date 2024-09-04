<?php

namespace MauticPlugin\MauticEvenlyDistributesSmtpBundle\Swiftmailer\Transport;

use MauticPlugin\MauticEvenlyDistributesSmtpBundle\EvenlyDistributes\EvenlyDistributes;
use Monolog\Logger;

/**
 * Date: 2024-09-04
 * Author: Gordon.Hu
 */
class EvenlyDistributesSmtpTransport extends \Swift_SmtpTransport
{
    /** @var EvenlyDistributes  */
    private $evenlyDistributes;

    /** @var Logger  */
    private $logger;

    /**
     * @param EvenlyDistributes $EvenlyDistributes
     * @param Logger $logger
     */
    public function __construct(EvenlyDistributes $EvenlyDistributes, Logger $logger)
    {
        $this->evenlyDistributes = $EvenlyDistributes;
        $this->logger = $logger;
        $transport = $this->getEvenlyDistributesSmtpServerForConstruct();

        parent::__construct($transport->getHost());
    }

    /**
     * CreateDate: 9/4/2024 9:43 AM
     *
     * @param \Swift_Mime_SimpleMessage $message
     * @param $failedRecipients
     * @return int
     */
    public function send(\Swift_Mime_SimpleMessage $message, &$failedRecipients = null): int
    {
        $transport = new \Swift_SmtpTransport();
        $this->getEvenlyDistributesSmtpServer($transport, $message);
        $mailer = new \Swift_Mailer($transport);
        return $mailer->send($message, $failedRecipients);
    }

    /**
     * CreateDate: 9/4/2024 9:43 AM
     *
     * @param $transport
     * @param \Swift_Mime_SimpleMessage $message
     * @return void
     */
    private function getEvenlyDistributesSmtpServer(&$transport, \Swift_Mime_SimpleMessage &$message): void
    {
        try {
            $this->evenlyDistributes->evenlyDistributesSmtp($transport, $message);
            $this->logger->info(sprintf('Send by evenly distributes SMTP server: %s with username %s and sender email %s to %s',
                $this->getHost(), $this->getUsername(),
                implode(',', $message ? array_keys($message->getFrom()) : []), $message ? implode(', ', array_keys($message->getTo())) :''));
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * CreateDate: 9/4/2024 9:43 AM
     * Just Construct Connect, not represent reality transport
     *
     * @return \Swift_SmtpTransport
     */
    private function getEvenlyDistributesSmtpServerForConstruct(): \Swift_SmtpTransport
    {
        $transport = new \Swift_SmtpTransport();
        $this->evenlyDistributes->evenlyDistributesSmtp($transport);

        return $transport;
    }
}