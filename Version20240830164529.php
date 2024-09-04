<?php

namespace Mautic\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\Migrations\Exception\SkipMigration;
use Mautic\CoreBundle\Doctrine\AbstractMauticMigration;
final class Version20240830164529 extends AbstractMauticMigration
{
    public function preUp(Schema $schema): void
    {
        if ($schema->hasTable($this->prefix.'smtp_servers')) {
            throw new SkipMigration('Schema includes this migration');
        }
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            "# Dump of table smtp_servers
            # ------------------------------------------------------------

            CREATE TABLE `{$this->prefix}smtp_servers` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `server` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
            `envelope_address_prefix` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
            `envelope_address_domain` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
            `send_email_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
            `send_email_address` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
            `max_daily_volume` int(11) NOT NULL,
            `port` int(11),
            `enabled` tinyint(4) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE `{$this->prefix}smtp_servers`");
    }
}