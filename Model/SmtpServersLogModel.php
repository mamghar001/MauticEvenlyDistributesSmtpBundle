<?php

namespace MauticPlugin\MauticEvenlyDistributesSmtpBundle\Model;

use Mautic\CoreBundle\Model\FormModel;
use MauticPlugin\MauticEvenlyDistributesSmtpBundle\Entity\SmtpServersLog;

class SmtpServersLogModel extends FormModel
{
    /**
     * CreateDate: 12/30/2024 4:31 PM
     *
     * @param $smtpServerId
     * @param $leadId
     * @param $emailId
     * @return void
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addSmtpServersLog($smtpServerId, $leadId, $emailId)
    {
        $smtpServersLog = new SmtpServersLog();
        $smtpServersLog->setSmtpServerId($smtpServerId);
        $smtpServersLog->setLeadId($leadId);
        $smtpServersLog->setEmailId($emailId);
        $smtpServersLog->setSentDate((new \DateTime())->format('Y-m-d H:i:s'));

        $this->em->persist($smtpServersLog);
        $this->em->flush();
    }
}