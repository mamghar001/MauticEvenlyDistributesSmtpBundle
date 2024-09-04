# Evenly Distributes SMTP Server For Mautic
Evenly Distributes SMTP Server For Mautic according to DB
### Manual installation
1. Download last version url
2. Unzip files to plugins/MauticEvenlyDistributesBundle
3. Go to /s/plugins/reload
4. Move Version20240830164529.php file to Migrations directory
5. run doctrine:migrations:migrate command

   1. `php bin/console doctrine:migrations:migrate 20240830164529 --dry-run`
   2. `php bin/console doctrine:migrations:migrate 20240830164529`

6. See Mautic Evenly Distributes
