<?php

namespace MauticPlugin\MauticEvenlyDistributesSmtpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;

/**
 * Date: 2024-09-04
 * Author: Gordon.Hu
 */
class SmtpServers
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $server;

    /**
     * @var string
     */
    private $envelopeAddressPrefix;

    /**
     * @var string
     */
    private $envelopeAddressDomain;

    /**
     * @var string
     */
    private $sendEmailName;

    /**
     * @var string
     */
    private $sendEmailAddress;


    private $encryption;

    private $authMode;

    private $password;

    /**
     * @var int
     */
    private $maxDailyVolume;

    /**
     * @var int
     */
    private $port;

    /**
     * @var int
     */
    private $enabled;

    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('smtp_servers')
            ->setCustomRepositoryClass('MauticPlugin\MauticEvenlyDistributesSmtpBundle\Entity\SmtpServersRepository');
        $builder->addNamedField('server', 'string', 'server');
        $builder->addNamedField('envelopeAddressPrefix', 'string', 'envelope_address_prefix');
        $builder->addNamedField('envelopeAddressDomain', 'string', 'envelope_address_domain');
        $builder->addNamedField('sendEmailName', 'string', 'send_email_name');
        $builder->addNamedField('sendEmailAddress', 'string', 'send_email_address');
        $builder->addNamedField('encryption', 'string', 'encryption');
        $builder->addNamedField('authMode', 'string', 'auth_mode');
        $builder->addNamedField('password', 'string', 'password');
        $builder->addNamedField('maxDailyVolume', 'int', 'max_daily_volume');
        $builder->addNamedField('port', 'int', 'port');
        $builder->addNamedField('enabled', 'int', 'enabled')->addId();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getServer(): string
    {
        return $this->server;
    }
    public function setServer(string $server): SmtpServers
    {
        $this->server = $server;
        return $this;
    }

    public function getEnvelopeAddressPrefix(): string
    {
        return $this->envelopeAddressPrefix;
    }

    public function setEnvelopeAddressPrefix(string $envelopeAddressPrefix): SmtpServers
    {
        $this->envelopeAddressPrefix = $envelopeAddressPrefix;
        return $this;
    }

    public function getEnvelopeAddressDomain(): string
    {
        return $this->envelopeAddressDomain;
    }

    public function setEnvelopeAddressDomain(string $envelopeAddressDomain): SmtpServers
    {
        $this->envelopeAddressDomain = $envelopeAddressDomain;
        return $this;
    }

    public function getSendEmailName(): string
    {
        return $this->sendEmailName;
    }

    public function setSendEmailName(string $sendEmailName): SmtpServers
    {
        $this->sendEmailName = $sendEmailName;
        return $this;
    }

    public function getSendEmailAddress(): string
    {
        return $this->sendEmailAddress;
    }

    public function setSendEmailAddress(string $sendEmailAddress): SmtpServers
    {
        $this->sendEmailAddress = $sendEmailAddress;
        return $this;
    }

    public function getEncryption(): string
    {
        return $this->encryption;
    }

    public function setEncryption(string $encryption): SmtpServers
    {
        $this->encryption = $encryption;
        return $this;
    }

    public function getAuthMode(): string
    {
        return $this->authMode;
    }

    public function setAuthMode(string $authMode): SmtpServers
    {
        $this->authMode = $authMode;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): SmtpServers
    {
        $this->password = $password;
        return $this;
    }

    public function getMaxDailyVolume(): int
    {
        return $this->maxDailyVolume;
    }

    public function setMaxDailyVolume(int $maxDailyVolume): SmtpServers
    {
        $this->maxDailyVolume = $maxDailyVolume;
        return $this;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setPort(int $port): SmtpServers
    {
        $this->port = $port;
        return $this;
    }

    public function getEnabled(): int
    {
        return $this->enabled;
    }

    public function setEnabled(int $enabled): SmtpServers
    {
        $this->enabled = $enabled;
        return $this;
    }
}