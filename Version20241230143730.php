<?php

namespace Mautic\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\Migrations\Exception\SkipMigration;
use Mautic\CoreBundle\Doctrine\AbstractMauticMigration;

final class Version20241230143730 extends AbstractMauticMigration
{
    public function preUp(Schema $schema): void
    {
        if ($schema->hasTable($this->prefix.'smtp_servers_log')) {
            throw new SkipMigration('Schema includes this migration');
        }
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            "# Dump of table smtp_servers_log
            # ------------------------------------------------------------
    
            CREATE TABLE `{$this->prefix}smtp_servers_log` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `smtp_server_id` int(10) unsigned NOT NULL,
            `lead_id` int(10) unsigned default NULL,
            `email_id` int(10) unsigned default NULL,
            `sent_date` datetime NOT NULL,
            PRIMARY KEY (`id`),
            INDEX `smtp_servers_log_smtp_server_id` (`smtp_server_id`),
            INDEX `smtp_servers_log_lead_id` (`lead_id`),
            INDEX `smtp_servers_log_email_id` (`email_id`),
            INDEX `smtp_servers_log_sent_date` (`sent_date`),
            INDEX `smtp_servers_log_sent_date_smtp_server_id_email_id` (`sent_date`, `smtp_server_id`, `email_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
        );

        $this->addSql(
            "# Dump of table server_email_stats
            # ------------------------------------------------------------
        
            CREATE TABLE `{$this->prefix}smtp_servers_stats` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `smtp_server_id` int(11) unsigned NOT NULL,
                `total_num` bigint(20) unsigned NOT NULL,
                `sent_date` date NOT NULL,
                PRIMARY KEY (`id`) USING BTREE,
                UNIQUE KEY `smtp_server_id_date` (`smtp_server_id`, `sent_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;"
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE `{$this->prefix}smtp_servers_log`");
        $this->addSql("DROP TABLE `{$this->prefix}smtp_servers_stats`");
    }
}