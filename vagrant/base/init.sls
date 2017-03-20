Create swap file:
  cmd.run:
    - name: fallocate -l 2048M /swapfile && chmod 600 /swapfile && mkswap /swapfile
    - unless: test -f /swapfile
  mount.swap:
    - name: /swapfile
    - require:
      - cmd: Create swap file

Install base packages:
  pkg.installed:
    - pkgs:
      - software-properties-common
      - curl
      - build-essential
      - git-core
      - vim
      - htop
      - fontconfig
      - openjdk-7-jre-headless
      - libmysql-java
      - graphviz
      - python-mysqldb

Remove useless packages:
  pkg.purged:
    - pkgs:
      - puppet
      - chef

Ensure that timezone is America/Sao_Paulo:
  cmd.run:
    - name: echo "America/Sao_Paulo" > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata
    - unless: cat /etc/timezone | grep "America/Sao_Paulo"

Ensure that some locales are set:
  file.append:
    - name: /etc/environment
    - text:
      - LC_ALL=en_US.UTF-8
      - LANG=en_US.UTF-8
      - LANGUAGE=en_US.UTF-8
