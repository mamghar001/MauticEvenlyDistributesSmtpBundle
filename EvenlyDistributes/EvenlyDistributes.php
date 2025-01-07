<?php

namespace MauticPlugin\MauticEvenlyDistributesSmtpBundle\EvenlyDistributes;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Date: 2024-09-04
 * Author: Gordon.Hu
 */
class EvenlyDistributes
{
    /** @var EntityManager  */
    private $entityManager;

    private $container;

    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    /**
     * CreateDate: 1/2/2025 4:21 PM
     *
     * @param \Swift_Mime_SimpleMessage|null $message
     * @return object|null
     */
    public function evenlyDistributesSmtp(\Swift_Mime_SimpleMessage &$message = null)
    {
        $smtpRepository = $this->entityManager->getRepository('MauticEvenlyDistributesSmtpBundle:SmtpServers');
        $smtp = $smtpRepository->getActiveServer();
        $evenlyDistributesSmtpTransport = $this->container->get('mautic.multi.email.' . $smtp['id']);

//        $evenlyDistributesSmtpTransport->setHost($smtp['server']);
//        $evenlyDistributesSmtpTransport->setPort($smtp['port'] ?? 25);
//        $evenlyDistributesSmtpTransport->setEncryption($smtp['encryption']);
//        $evenlyDistributesSmtpTransport->setAuthMode($smtp['auth_mode']);
//        $evenlyDistributesSmtpTransport->setUsername($smtp['send_email_address']);
//        $evenlyDistributesSmtpTransport->setPassword($smtp['password']);
        if ($message !== null) {
            $message->setFrom($smtp['send_email_address'], $smtp['send_email_name']);
        }
        return $evenlyDistributesSmtpTransport;
    }
}