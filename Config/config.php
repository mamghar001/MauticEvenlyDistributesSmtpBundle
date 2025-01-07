<?php

return [
    'name'        => 'Mautic Evenly Distributes Smtp Bundle',
    'author'      => 'ihongliang.hu@gmail.com',
    'version'     => '1.0.0',
    'services' => [
        'models' => [
            'mautic.evenly_distributes.model.smtp_servers_log' => [
                'class' => \MauticPlugin\MauticEvenlyDistributesSmtpBundle\Model\SmtpServersLogModel::class,
            ],
            'mautic.evenly_distributes.model.smtp_servers_stats' => [
                'class' => \MauticPlugin\MauticEvenlyDistributesSmtpBundle\Model\SmtpServersStatsModel::class,
                'arguments' => [
                    'mautic.evenly_distributes.repository.smtp_servers_stats',
                ],
            ],
        ],
        'commands' => [
            'mautic.evenly_distributes.queue' => [
                'class' => MauticPlugin\MauticEvenlyDistributesSmtpBundle\Command\ProcessEmailQueueCommand::class,
                'arguments' => [
                    'swiftmailer.mailer.default.transport.real',
                    'event_dispatcher',
                    'mautic.helper.core_parameters',
                    'mautic.evenly_distributes.helper.common',
                    'service_container',
                ],
                'tag' => 'console.command',
                'decoratedService' => [
                    'mautic.email.command.queue'
                ]
            ],
        ],
        'repositories' => [
            'mautic.evenly_distributes.repository.smtp_servers' => [
                'class' => Doctrine\ORM\EntityRepository::class,
                'factory' => ['@doctrine.orm.entity_manager', 'getRepository'],
                'arguments' => \MauticPlugin\MauticEvenlyDistributesSmtpBundle\Entity\SmtpServers::class,
            ],
            'mautic.evenly_distributes.repository.smtp_servers_log' => [
                'class' => Doctrine\ORM\EntityRepository::class,
                'factory' => ['@doctrine.orm.entity_manager', 'getRepository'],
                'arguments' => [
                    \MauticPlugin\MauticEvenlyDistributesSmtpBundle\Entity\SmtpServersLog::class,
                ],
            ],
            'mautic.evenly_distributes.repository.smtp_servers_stats' => [
                'class' => Doctrine\ORM\EntityRepository::class,
                'factory' => ['@doctrine.orm.entity_manager', 'getRepository'],
                'arguments' => [
                    \MauticPlugin\MauticEvenlyDistributesSmtpBundle\Entity\SmtpServersStats::class,
                ],
            ],
        ],
        'other'   => [
            'mautic.evenly_distributes' => [
                'class'        => MauticPlugin\MauticEvenlyDistributesSmtpBundle\EvenlyDistributes\EvenlyDistributes::class,
                'arguments' => [
                    'doctrine.orm.default_entity_manager',
                    'service_container',
                ],
            ],
            'mautic.transport.evenly_distributes' => [
                'class'        => \MauticPlugin\MauticEvenlyDistributesSmtpBundle\Swiftmailer\Transport\EvenlyDistributesSmtpTransport::class,
                'arguments' => [
                    'mautic.evenly_distributes',
                    'mautic.evenly_distributes.helper.common',
                    'service_container',
                ],
                'tag'          => 'mautic.email_transport',
                'tagArguments' => [
                    \Mautic\EmailBundle\Model\TransportType::TRANSPORT_ALIAS => 'mautic.email.config.mailer_transport.evenly_distributes_smtp',
                ],
            ],
            'mautic.evenly_distributes.spool' => [
                'class'     => \MauticPlugin\MauticEvenlyDistributesSmtpBundle\Swiftmailer\Spool\EvenlyDistributesSpool::class,
                'arguments' => [
                    'mautic.helper.core_parameters',
                    'swiftmailer.mailer.default.transport.real',
                    'mautic.transport.evenly_distributes',
                    'mautic.evenly_distributes.helper.common',
                    'service_container',
                ],
                'decoratedService' => [
                    'mautic.spool.delegator'
                ]
            ],
            'mautic.evenly_distributes.helper.common' => [
                'class' =>  \MauticPlugin\MauticEvenlyDistributesSmtpBundle\Helper\CommonHelper::class,
                'arguments' => [
                    'doctrine.orm.entity_manager',
                    'service_container',
                    'mautic.evenly_distributes.repository.smtp_servers',
                    'mautic.evenly_distributes.repository.smtp_servers_log',
                    'mautic.evenly_distributes.repository.smtp_servers_stats',
                    'mautic.email.repository.stat',
                ],
            ],
        ],
    ],
];