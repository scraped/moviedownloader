Install supervisor:
  pkg.installed:
    - name: supervisor

Ensure that configuration file is present:
  file.managed:
    - name: /etc/supervisor/conf.d/moviedownloader-worker.conf
    - source: salt://supervisor/moviedownloader-worker.conf

Comand 1:
  cmd.run:
    - name: supervisorctl reread

Comand 2:
  cmd.run:
    - name: supervisorctl update

Comand 3:
  cmd.run:
    - name: supervisorctl start moviedownloader-worker:*