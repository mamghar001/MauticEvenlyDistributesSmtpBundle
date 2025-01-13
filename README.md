# Evenly Distributes SMTP Server For Mautic
Evenly Distributes SMTP Server For Mautic according to DB

### Support Mautic's Version
Only Support Mautic 4.4.12/4.4.13 and PHP 8.0 or later, other versions have not been tested, please test them yourself

### Manual installation
1. Download last version https://github.com/HN-PRO/MauticEvenlyDistributesSmtpBundle/releases/
2. Unzip files to plugins/MauticEvenlyDistributesBundle
3. run mautic:plugins:reload command
   1. `php bin/console cache:clear`
   2. `php bin/console mautic:plugins:reload`
4. Please add the smtp information you need to use in the smtp_servers table
5. See Mautic Evenly Distributes Plugin

### Note
1. Only supports sending emails through file queues
2. When you no longer need to use it, please manually delete the plugin directory, as well as delete the "smtp_servers", "smtp_servers_log", and "smtp_servers_stats" tables


### More...
