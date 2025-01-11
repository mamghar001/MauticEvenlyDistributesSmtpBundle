<?php

namespace MauticPlugin\MauticEvenlyDistributesSmtpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;

class SmtpServersStats
{
    private $id;

    private $smtpServerId;

    private $totalNum;

    private $sentDate;

    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('smtp_servers_stats')->addId()
            ->setCustomRepositoryClass('MauticPlugin\MauticEvenlyDistributesSmtpBundle\Entity\SmtpServersStatsRepository');
        $builder->addNamedField('smtpServerId', 'integer', 'smtp_server_id');
        $builder->addNamedField('totalNum', 'integer', 'total_num');
        $builder->addNamedField('sentDate', 'date', 'sent_date');
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSmtpServerId($smtpServerId): SmtpServersStats
    {
        $this->smtpServerId = $smtpServerId;
        return $this;
    }

    public function getSmtpServerId()
    {
        return $this->smtpServerId;
    }

    public function setTotalNum($totalNum): SmtpServersStats
    {
        $this->totalNum = $totalNum;
        return $this;
    }

    public function getTotalNum()
    {
        return $this->totalNum;
    }

    public function setSentDate($sentDate): SmtpServersStats
    {
        $this->sentDate = $sentDate;
        return $this;
    }

    public function getSentDate()
    {
        return $this->sentDate;
    }
}