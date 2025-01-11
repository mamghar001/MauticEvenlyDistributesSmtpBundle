# Evenly Distributes SMTP Server For Mautic
Evenly Distributes SMTP Server For Mautic according to DB

### Support Mautic's Version
Only Support Mautic 4.4.12/4.4.13, other versions have not been tested, please test them yourself

### Manual installation
1. Download last version https://github.com/HN-PRO/MauticEvenlyDistributesSmtpBundle/releases/
2. Unzip files to plugins/MauticEvenlyDistributesBundle
3. run mautic:plugins:reload command
   1. `php bin/console mautic:plugins:reload`
4. See Mautic Evenly Distributes

### Note
1. Only supports sending emails through file queues
2. When manually removing the plugin directory, please remember to delete the "smtp_Server", "smtp_Server s_log", and "smtp_Server s_stats" tables


### More...
