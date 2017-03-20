Install php dependencies:
  composer.installed:
    - name: /opt/moviedownloader
    - user: vagrant
    - optimize: False
    - no_dev: False

Ensure database file is present:
  file.touch:
    - name: /opt/moviedownloader/database/database.sqlite

Run migrations:
  cmd.run:
    - name: php artisan migrate
    - cwd: /opt/moviedownloader
    - runas: vagrant

Ensure that crontab entry is present:
  cron.present:
    - name: /usr/bin/php /opt/moviedownloader/artisan schedule:run >> /dev/null 2>&1
    - user: vagrant
    - minute: '*'
    - hour: '*'
    - daymonth: '*'
    - month: '*'
    - dayweek: '*'

