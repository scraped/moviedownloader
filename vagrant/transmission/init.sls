Install transmission packages:
  pkg.installed:
    - pkgs:
      - transmission-cli
      - transmission-common
      - transmission-daemon

Move config file to home folder:
  file.copy:
    - name: /home/vagrant/.config/transmission-daemon/settings.json
    - source: /etc/transmission-daemon/settings.json
    - makedirs: True
    - user: vagrant
    - group: vagrant

Ensure .config directory has right permissions:
  file.directory:
    - name: /home/vagrant/.config
    - user: vagrant
    - group: vagrant
    - dir_mode: 755
    - file_mode: 644
    - recurse:
      - user
      - group
      - mode

Enable access to transmission for LAN:
  file.replace:
    - name: /home/vagrant/.config/transmission-daemon/settings.json
    - pattern: "\"rpc-whitelist\": \"127.0.0.1\""
    - repl: "\"rpc-whitelist\": \"127.0.0.1,192.168.*.*\""

Disable authentication for transmission:
  file.replace:
    - name: /home/vagrant/.config/transmission-daemon/settings.json
    - pattern: "\"rpc-authentication-required\": true"
    - repl: "\"rpc-authentication-required\": false"

Ensure resume directory exists:
  file.directory:
    - name: /home/vagrant/.config/transmission-daemon/resume
    - user: vagrant
    - group: vagrant
    - dir_mode: 755
    - file_mode: 644
    - recurse:
      - user
      - group
      - mode

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