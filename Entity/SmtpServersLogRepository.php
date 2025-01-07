<?php

namespace MauticPlugin\MauticEvenlyDistributesSmtpBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Date: 2024-12-30
 * Author: Gordon.Hu
 */
class SmtpServersLogRepository extends CommonRepository
{

    /**
     * CreateDate: 12/30/2024 3:40 PM
     * Get a list of entities.
     *
     * @param array $args
     * @return Paginator
     */
    public function getEntities(array $args = [])
    {
        $q = $this
            ->createQueryBuilder('ssl')
            ->select('ssl');

        $args['qb'] = $q;

        return parent::getEntities($args);
    }


    public function getTableAlias()
    {
        return 'ssl';
    }
}