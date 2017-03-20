Install transmission packages:
  pkg.installed:
    - pkgs:
      - transmission-cli
      - transmission-common
      - transmission-daemon

Replace user:
  file.replace:
    - name: /lib/systemd/system/transmission-daemon.service
    - pattern: User=debian-transmission
    - repl: User=vagrant

Reload transmission service (unit) configuration:
  cmd.wait:
    - name: systemctl daemon-reload
    - watch:
      - file: /lib/systemd/system/transmission-daemon.service

Ensure that transmission service is running:
  service.running:
    - name: transmission-daemon
    - enable: True
    - watch:
      - file: /lib/systemd/system/transmission-daemon.service