<?php

namespace MauticPlugin\MauticEvenlyDistributesSmtpBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

class SmtpServersRepository extends CommonRepository
{
    /**
     * CreateDate: 9/4/2024 9:43 AM
     *
     * @return mixed|mixed[]
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getAvailableSmtpServers()
    {
        $q = $this->getEntityManager()->getConnection()->createQueryBuilder();
        $q->select('*')
            ->from(MAUTIC_TABLE_PREFIX . 'smtp_servers');

        $result = $q->execute()->fetchAllAssociative();

        return $result[0];
    }
}