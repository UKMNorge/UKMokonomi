# Installs apache and php, and disables the apache default vhost

apache:
    pkg.installed:
        - name: apache2

    service.running:
        - name: apache2
        - require:
             - pkg: apache
        - watch:
            - cmd: mod-php
            - pkg: php-mysql

    # Remove default site
    file.absent:
        - name: /etc/apache2/sites-enabled/000-default.conf

php-mysql:
    pkg.installed:
        - name: php5-mysql


mod-php:
    pkg.installed:
        - name: libapache2-mod-php5
        - require:
            - pkg: apache
        - watch_in:
            - service: apache

    cmd.run:
        - name: a2enmod php5
        - unless: test -f /etc/apache2/mods-enabled/php5.conf
        - require:
            - pkg: mod-php

    file.managed:
        - name: /etc/php5/apache2/php.ini
        - source: salt://apache/php.ini
        - template: jinja
        - require:
            - pkg: mod-php
        - watch_in:
            - service: apache

apache-restart:
    cmd.run:
        - name: service apache2 restart
        - order: last
