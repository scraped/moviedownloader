Install basic packages:
  pkg.installed:
    - pkgs:
      - software-properties-common
      - curl
      - git
      - htop

Ensure that timezone is America/Sao_Paulo:
  timezone.system:
    - name: America/Sao_Paulo

Ensure that pt_BR.UTF-8 locale is present:
  locale.present:
    - name: pt_BR.UTF-8