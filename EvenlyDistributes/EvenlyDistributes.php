<?php

namespace MauticPlugin\MauticEvenlyDistributesSmtpBundle\EvenlyDistributes;

use Doctrine\ORM\EntityManager;

/**
 * Date: 2024-09-04
 * Author: Gordon.Hu
 */
class EvenlyDistributes
{
    /** @var EntityManager  */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * CreateDate: 9/4/2024 9:42 AM
     *
     * @param \Swift_SmtpTransport $evenlyDistributesSmtpTransport
     * @param \Swift_Mime_SimpleMessage|null $message
     * @return void
     */
    public function evenlyDistributesSmtp(\Swift_SmtpTransport &$evenlyDistributesSmtpTransport, \Swift_Mime_SimpleMessage &$message = null): void
    {
        $smtpRepository = $this->entityManager->getRepository('MauticEvenlyDistributesSmtpBundle:SmtpServers');
        $smtp = $smtpRepository->getAvailableSmtpServers();

        $evenlyDistributesSmtpTransport->setHost($smtp['server']);
        $evenlyDistributesSmtpTransport->setPort($smtp['port'] ?? 25);
        $evenlyDistributesSmtpTransport->setEncryption($smtp['encryption']);
        $evenlyDistributesSmtpTransport->setAuthMode($smtp['auth_mode']);
        $evenlyDistributesSmtpTransport->setUsername($smtp['send_email_name']);
        $evenlyDistributesSmtpTransport->setPassword($smtp['password']);
        if ($message !== null) {
            $message->setFrom($smtp['fromEmail'], $smtp['fromName']);
        }
    }
}