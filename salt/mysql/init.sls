{% set basehost = pillar.get('basehost') %}

mysql-deps:
    pkg.installed:
        - name: python-mysqldb

mysql_debconf:
  debconf.set:
    - name: mysql-server
    - data:
        'mysql-server/root_password': {'type': 'password', 'value': '{{ basehost.get('mysql_root_pass') }}'}
        'mysql-server/root_password_again': {'type': 'password', 'value': '{{ basehost.get('mysql_root_pass') }}'}
        'mysql-server/start_on_boot': {'type': 'boolean', 'value': 'true'}


mysql-client:
    pkg.installed


mysql-server:
    pkg.installed:
        - name: mysql-server
        - require:
            - debconf: mysql_debconf

    service.running:
        - name: mysql
        - watch:
            - pkg: mysql-server
            - pkg: mysql-deps
            - file: mysql-server

    file.managed:
        - name: /etc/mysql/my.cnf
        - source: salt://mysql/my.cnf
        - template: jinja
