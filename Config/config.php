<?php

return [
    'name'        => 'Mautic Evenly Distributes Smtp Bundle',
    'author'      => 'ihongliang.hu@gmail.com',
    'version'     => '1.0.0',
    'services' => [
        'commands' => [
            'mautic.evenly_distributes.queue' => [
                'class' => MauticPlugin\MauticEvenlyDistributesSmtpBundle\Command\ProcessEmailQueueCommand::class,
                'arguments' => [
                    'event_dispatcher',
                    'mautic.helper.core_parameters',
                    'mautic.transport.evenly_distributes',
                ],
                'tag' => 'console.command',
                'decoratedService' => [
                    'mautic.email.command.queue'
                ]
            ],
        ],
        'other'   => [
            'mautic.evenly_distributes' => [
                'class'        => MauticPlugin\MauticEvenlyDistributesSmtpBundle\EvenlyDistributes\EvenlyDistributes::class,
                'arguments' => [
                    'doctrine.orm.default_entity_manager',
                ],
            ],
            'mautic.transport.evenly_distributes' => [
                'class'        => \MauticPlugin\MauticEvenlyDistributesSmtpBundle\Swiftmailer\Transport\EvenlyDistributesSmtpTransport::class,
                'arguments' => [
                    'mautic.evenly_distributes',
                    'monolog.logger.mautic'
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
                ],
                'decoratedService' => [
                    'mautic.spool.delegator'
                ]
            ],
        ],
    ],
];