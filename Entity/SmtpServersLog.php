<?php

namespace MauticPlugin\MauticEvenlyDistributesSmtpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;

/**
 * Date: 2024-12-27
 * Author: Gordon.Hu
 */
class SmtpServersLog
{
    private $id;

    private $smtpServerId;

    private $leadId;

    private $emailId;

    private $sent_date;

    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('smtp_servers_log')->addId()
            ->setCustomRepositoryClass('MauticPlugin\MauticEvenlyDistributesSmtpBundle\Entity\SmtpServersLogRepository')
            ->addIndex(['smtp_server_id'], 'smtp_servers_log_smtp_server_id')
            ->addIndex(['lead_id'], 'smtp_servers_log_lead_id')
            ->addIndex(['email_id'], 'smtp_servers_log_email_id')
            ->addIndex(['sent_date'], 'smtp_servers_log_sent_date')
            ->addIndex(['sent_date', 'smtp_server_id', 'email_id'], 'smtp_servers_log_sent_date_smtp_server_id_email_id')
        ;
        $builder->addNamedField('smtpServerId', 'integer', 'smtp_server_id');
        $builder->addNamedField('leadId', 'integer', 'lead_id', true);
        $builder->addNamedField('emailId', 'integer', 'email_id');
        $builder->addNamedField('sent_date', 'datetime', 'sent_date');
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSmtpServerId($smtpServerId)
    {
        $this->smtpServerId = $smtpServerId;
        return $this;
    }

    public function getSmtpServerId()
    {
        return $this->smtpServerId;
    }

    public function setLeadId($leadId)
    {
        $this->leadId = $leadId;
        return $this;
    }

    public function getLeadId()
    {
        return $this->leadId;
    }

    public function setEmailId($emailId)
    {
        $this->emailId = $emailId;
        return $this;
    }

    public function getEmailId()
    {
        return $this->emailId;
    }

    public function setSentDate($sentDate)
    {
        $this->sent_date = $sentDate;
        return $this;
    }

    public function getSentDate()
    {
        return $this->sent_date;
    }
}