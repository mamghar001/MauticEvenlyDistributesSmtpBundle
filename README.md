# Evenly Distributes SMTP Server For Mautic
Evenly Distributes SMTP Server For Mautic according to DB

### Support Mautic's Version
Only Support Mautic 4.4.12, other versions have not been tested, please test them yourself

### Manual installation
1. Download last version https://github.com/HN-PRO/MauticEvenlyDistributesSmtpBundle/releases/
2. Unzip files to plugins/MauticEvenlyDistributesBundle
3. Move Version20240830164529.php file to Migrations directory
4. Go to /s/plugins/reload
5. run doctrine:migrations:migrate command

   1. `php bin/console doctrine:migrations:migrate 20240830164529 --dry-run`
   2. `php bin/console doctrine:migrations:migrate 20240830164529`

6. See Mautic Evenly Distributes

### More...
