Ensure that php7 repository is present:
  pkgrepo.managed:
    - humanname: PHP7
    - name: deb http://packages.dotdeb.org jessie all
    - file: /etc/apt/sources.list.d/php.list
    - key_url: https://www.dotdeb.org/dotdeb.gpg

Install php packages:
  pkg.installed:
    - pkgs:
      - php7.0-cli
      - php7.0-curl
      - php7.0-json
      - php7.0-redis
      - php7.0-sqlite
      - php7.0-xmlrpc
      - php7.0-xml
      - php7.0-mbstring
