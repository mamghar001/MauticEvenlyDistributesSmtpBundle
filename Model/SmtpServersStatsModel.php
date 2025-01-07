<?php

namespace MauticPlugin\MauticEvenlyDistributesSmtpBundle\Model;

use Mautic\CoreBundle\Model\FormModel;
use MauticPlugin\MauticEvenlyDistributesSmtpBundle\Entity\SmtpServersStatsRepository;

/**
 * Date: 2024-12-31
 * Author: Gordon.Hu
 */
class SmtpServersStatsModel extends FormModel
{
    private $smtpServersStatsRepository;

    public function __construct(SmtpServersStatsRepository $smtpServersStatsRepository)
    {
        $this->smtpServersStatsRepository = $smtpServersStatsRepository;
    }

    /**
     * CreateDate: 12/31/2024 3:45 PM
     *
     * @param $smtpServerId
     * @return void
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function addSmtpServersStateRecord($smtpServerId)
    {
        $this->smtpServersStatsRepository->addSmtpServersStateRecord($smtpServerId);
    }
}