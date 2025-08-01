# Evenly Distributes SMTP Server For Mautic
Evenly Distributes SMTP Server For Mautic according to DB

### Features
Supports both third-party SMTP vendors and self-built SMTP servers. When adding records to the table 'smtp_servers', the vendor field defaults to 'self host'.
1. Vendor field is 'self host' value (envelope_address_prefix and envelope_address_domain are the fields of the table 'smtp_servers')
   
   **Monitored Inbox is set**

   **1.1 return-path** will be replaced: envelope_address_prefix+bounce_677b82c416ce2383461223@envelope_address_domain.
   
   **1.2 List-Unsubscribe** will be replaced: <mailto:envelope_address_prefix+unsubscribe_677b82c416ce2383461223@envelope_address_domain>
2. Vendor field is other values
   return-path is set to null

### Support Mautic's Version
Only Support Mautic v6.0.3 and php 8.1.2 other versions have not been tested, please test them yourself

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
