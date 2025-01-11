<?php

namespace MauticPlugin\MauticEvenlyDistributesSmtpBundle\Helper;

use Doctrine\ORM\EntityManager;
use Mautic\EmailBundle\Entity\StatRepository;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use MauticPlugin\MauticEvenlyDistributesSmtpBundle\Entity\SmtpServersRepository;
use MauticPlugin\MauticEvenlyDistributesSmtpBundle\Entity\SmtpServersLogRepository;
use MauticPlugin\MauticEvenlyDistributesSmtpBundle\Entity\SmtpServersStatsRepository;

/**
 * Date: 2024-12-31
 * Author: Gordon.Hu
 */
class CommonHelper
{
    /** @var EntityManager  */
    private $em;

    /** @var Container  */
    private $container;

    private $statRepository;

    public $smtpServersRepository;

    public $smtpServersLogRepository;

    public $smtpServersStatsRepository;

    public function __construct(
        EntityManager $em,
        Container $container,
        SmtpServersRepository $smtpServersRepository,
        SmtpServersLogRepository $smtpServersLogRepository,
        SmtpServersStatsRepository $smtpServersStatsRepository,
        StatRepository $statRepository
    )
    {
        $this->em = $em;
        $this->container = $container;
        $this->smtpServersRepository = $smtpServersRepository;
        $this->smtpServersLogRepository = $smtpServersLogRepository;
        $this->smtpServersStatsRepository = $smtpServersStatsRepository;
        $this->statRepository = $statRepository;
    }

    /**
     * CreateDate: 12/31/2024 10:08 AM
     *
     * @return array
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function setMultiEmailTransport(): array
    {
        $allActiveServers = $this->smtpServersRepository->getAllActiveSmtpServers();
        $transports = array();
        foreach ($allActiveServers as $value) {
            if (!$this->container->has('mautic.multi.email.' . $value['id'])) {
                $this->container->set('mautic.multi.email.' . $value['id'],
                    (new \Swift_SmtpTransport($value['server'], $value['port'], $value['encryption']))
                        ->setUsername($value['send_email_address'])
                        ->setPassword($value['password'])->setAuthMode($value['auth_mode'])
                );
            }
            $transports[$value['id']] =  $value;
        }
        return $transports;
    }

    /**
     * CreateDate: 1/2/2025 5:10 PM
     *
     * @param $trackingHash
     * @return object|null
     */
    public function getContactId($trackingHash)
    {
        if (isset($trackingHash)) {
            $stat = $this->statRepository->findOneBy(['trackingHash' => $trackingHash]);
            if (!empty($stat->getLead()) && !empty($stat->getLead()->getId())) {
                return $stat;
            } else {
                return null;
            }
        }
        return null;
    }

    /**
     * CreateDate: 1/3/2025 4:51 PM
     *
     * @param $bounceAddress
     * @param $envelopAddressDomain
     * @param $envelopAddressPrefix
     * @return string|null
     */
    public function getCustomBounceAddress($bounceAddress, $envelopAddressDomain, $envelopAddressPrefix)
    {
        if (preg_match('#^(.*?)\+(.*?)@(.*?)$#', $bounceAddress, $parts)) {
            if (strstr($parts[2], '_')) {
                // Has an ID hash so use it to find the lead
                list($ignore, $hashId) = explode('_', $parts[2]);

                return $envelopAddressPrefix . '+bounce_' . $hashId . '@' . $envelopAddressDomain;
            }
        }

        return null;
    }

    /**
     * CreateDate: 1/3/2025 4:54 PM
     *
     * @param $listUnsubscribe
     * @param $envelopeAddressDomain
     * @param $envelopeAddressPrefix
     * @return string
     */
    public function getCustomListUnsubscribe($listUnsubscribe, $envelopeAddressDomain, $envelopeAddressPrefix): string
    {
        list($bounceUnsubscribeLink, $unsubscribeLink) = explode('>, <', $listUnsubscribe);
        list($prefixBounceUnsubscribeLink, $unsubscribeDoMain) = explode('@', $bounceUnsubscribeLink);
        list($prefixBounceUnsubscribe, $hashId) = explode('+unsubscribe_', $prefixBounceUnsubscribeLink);

        return '<mailto:' . $envelopeAddressPrefix . '+unsubscribe_' . $hashId . '@' . $envelopeAddressDomain . '>, <' . $unsubscribeLink;
    }

    /**
     * CreateDate: 1/7/2025 9:45 AM
     *
     * @param $smtp
     * @param $transport
     * @param $message
     * @param $stat
     * @return void
     */
    public function setTransportAndMessageAndStat($smtp, &$transport, &$message, &$stat = null)
    {
        $transport = $this->container->get('mautic.multi.email.' . $smtp['id']);
        $transport->start();
        // get contact id
        $stat = $this->getContactId($message->leadIdHash);
        if ($bounceEmail = $message->getReturnPath()) {
            $message->setReturnPath($this->getCustomBounceAddress($bounceEmail, $smtp['envelope_address_domain'], $smtp['envelope_address_prefix']));
            $listUnsubscribe = $message->getHeaders()->get('List-Unsubscribe');
            $listUnsubscribe->setValue($this->getCustomListUnsubscribe($listUnsubscribe->getValue(), $smtp['envelope_address_domain'], $smtp['envelope_address_prefix']));
        }

        $message->setFrom($smtp['send_email_address'], $smtp['send_email_name']);
        if (empty($stat->getEmail()?->getReplyToAddress())) {
            $message->setReplyTo($smtp['send_email_address']);
        }
    }

    /**
     * CreateDate: 1/7/2025 9:45 AM
     *
     * @param $smtpServerId
     * @param $stat
     * @return void
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function addSentLog($smtpServerId, $stat)
    {
        $this->smtpServersStatsRepository->addSmtpServersStateRecord($smtpServerId);
        $smtpServersLogModel = $this->container->get('mautic.evenly_distributes.model.smtp_servers_log');
        $smtpServersLogModel->addSmtpServersLog($smtpServerId, $stat ? $stat->getLead()->getId() : null,
            $stat->getEmail() ? $stat->getEmail()->getId() : null);
    }
}