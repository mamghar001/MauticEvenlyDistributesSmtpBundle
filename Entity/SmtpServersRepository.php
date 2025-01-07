<?php

namespace MauticPlugin\MauticEvenlyDistributesSmtpBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

/**
 * Date: 2025-01-02
 * Author: Gordon.Hu
 */
class SmtpServersRepository extends CommonRepository
{
    /**
     * CreateDate: 12/31/2024 10:35 AM
     *
     * @return array|\mixed[][]
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getAllActiveSmtpServers(): array
    {
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();
        $qb->select('*')
            ->from(MAUTIC_TABLE_PREFIX . 'smtp_servers')
            ->where(
                $qb->expr()->eq('enabled', 1),
                $qb->expr()->gt('max_daily_volume', 0),
            )
        ;
        return $qb->execute()->fetchAllAssociative();
    }

    /**
     * CreateDate: 9/4/2024 9:43 AM
     *
     * @return mixed|mixed[]
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getActiveServer()
    {
        $date = date('Y-m-d');
        $query = $this->getEntityManager()->getConnection()->createQueryBuilder();
        $query->select('ss.*,ROUND(sss.total_num/ss.max_daily_volume, 6) as percent')
            ->from(MAUTIC_TABLE_PREFIX . 'smtp_servers', 'ss')
            ->leftJoin('ss', MAUTIC_TABLE_PREFIX . 'smtp_servers_stats', 'sss', "ss.id =
            sss.smtp_server_id and sss.sent_date = '{$date}'")
            ->where(
                $query->expr()->lt('IFNULL(sss.total_num,0)', 'ss.max_daily_volume'),
                $query->expr()->eq('ss.enabled', 1),
                $query->expr()->gt('ss.max_daily_volume', 0),
            )
            ->orderBy('percent ', 'asc')
            ->addOrderBy('RAND()')
            ;

        $result = $query->execute()->fetchAllAssociative();

        return $result[0];
    }
}