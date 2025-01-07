<?php

namespace MauticPlugin\MauticEvenlyDistributesSmtpBundle\Swiftmailer\Transport;

use MauticPlugin\MauticEvenlyDistributesSmtpBundle\EvenlyDistributes\EvenlyDistributes;
use MauticPlugin\MauticEvenlyDistributesSmtpBundle\Helper\CommonHelper;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Date: 2024-09-04
 * Author: Gordon.Hu
 */
class EvenlyDistributesSmtpTransport extends \Swift_SmtpTransport
{
    /** @var EvenlyDistributes  */
    private $evenlyDistributes;

    private $commonHelper;

    private $container;

    /**
     * @param EvenlyDistributes $EvenlyDistributes
     * @param CommonHelper $commonHelper
     * @param Container $container
     */
    public function __construct(
        EvenlyDistributes $EvenlyDistributes,
        CommonHelper $commonHelper,
        Container $container
    )
    {
        $this->evenlyDistributes = $EvenlyDistributes;
        $this->commonHelper = $commonHelper;
        $this->container = $container;
        $transport = $this->getEvenlyDistributesSmtpServerForConstruct();

        parent::__construct($transport->getHost());
    }

    /**
     * CreateDate: 9/4/2024 9:43 AM
     * Just Construct Connect, not represent reality transport
     *
     */
    private function getEvenlyDistributesSmtpServerForConstruct()
    {
        $this->commonHelper->setMultiEmailTransport();
        $smtp = $this->commonHelper->smtpServersRepository->getAllActiveSmtpServers();
        return $this->container->get('mautic.multi.email.' . $smtp[0]['id']);
    }
}