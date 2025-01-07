<?php

namespace MauticPlugin\MauticEvenlyDistributesSmtpBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

/**
 * Date: 2024-12-31
 * Author: Gordon.Hu
 */
class SmtpServersStatsRepository extends CommonRepository
{
    const TABLE_SMTP_SERVERS_STATS = 'smtp_servers_stats';

    /**
     * CreateDate: 12/31/2024 1:23 PM
     *
     * @param $smtpServerId
     *
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function addSmtpServersStateRecord($smtpServerId)
    {
        $date = (new \DateTime())->format('Y-m-d');
        $connection = $this->getEntityManager()->getConnection();
        $qb = $connection->createQueryBuilder();

        $res = $this->getSmtpServerStatsBySmtpServersId($smtpServerId, $date);
        if ($res) {
            $this->incrementSmtpServerStats($qb, $smtpServerId, $date);
        } else {
            try {
                $this->insertSmtpServerStats($qb, $smtpServerId, $date);
            } catch (\Exception $exception) {
                // retry.fixed different web servers inserting at the same time.
                $res = $this->getSmtpServerStatsBySmtpServersId($smtpServerId, $date);
                if ($res) {
                    $this->incrementSmtpServerStats($qb, $smtpServerId, $date);
                } else {
                    $this->insertSmtpServerStats($qb, $smtpServerId, $date);
                }
            }
        }
    }

    /**
     * CreateDate: 12/31/2024 1:09 PM
     *
     * @param $qb
     * @param $smtpServerId
     * @param $date
     * @return mixed
     */
    private function incrementSmtpServerStats($qb, $smtpServerId, $date)
    {
        $qb->update(self::TABLE_SMTP_SERVERS_STATS)
            ->set('total_num', 'total_num + 1')
            ->where(
                $qb->expr()->eq('smtp_server_id', ':smtpServerId'),
                $qb->expr()->eq('sent_date', ':sentDate'),
            )
            ->setParameter('smtpServerId', $smtpServerId)
            ->setParameter('sentDate', $date);
        return $qb->execute();
    }

    /**
     * CreateDate: 12/31/2024 1:08 PM
     *
     * @param $qb
     * @param $smtpServerId
     * @param $date
     * @return false|int|string
     */
    private function insertSmtpServerStats($qb, $smtpServerId, $date)
    {
        $qb->insert(self::TABLE_SMTP_SERVERS_STATS)
            ->values([
                'smtp_server_id' => $smtpServerId,
                'total_num' => 1,
                'sent_date' => $qb->expr()->literal($date),
            ]);
        $qb->execute();
        return $this->getEntityManager()->getConnection()->lastInsertId();
    }

    /**
     * CreateDate: 12/31/2024 1:18 PM
     *
     * @param $smtpServerId
     * @param $date
     * @return array|mixed|mixed[]
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getSmtpServerStatsBySmtpServersId($smtpServerId, $date)
    {
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();
        $qb->select('*')
            ->from(self::TABLE_SMTP_SERVERS_STATS)
            ->where(
                $qb->expr()->eq('smtp_server_id', ':smtpServerId'),
                $qb->expr()->eq('sent_date', ':sentDate'),
            )
            ->setParameter('smtpServerId', $smtpServerId)
            ->setParameter('sentDate', $date)
        ;
        $res = $qb->execute()->fetchAllAssociative();
        return !empty($res[0]) ? $res[0] : [];
    }
}