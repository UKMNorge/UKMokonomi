{% set basehost = pillar.get('basehost') %}

include:
    - apache
    - phpunit
    - mysql
    - basehost.database
    - okonomi
    
basehost-deps:
    pkg.installed:
        - pkgs:
            - git
            - php5-curl
            - unzip

composer:
    cmd.run:
        - name: curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
        - unless: which composer
        - require:
            - pkg: mod-php
            
basehost-vhost:
    file.managed:
        - name: /etc/apache2/sites-enabled/{{ basehost.get('host_name') }}.conf
        - source: salt://basehost/vhost.conf
        - template: jinja
        - require:
            - pkg: apache
        - watch_in:
            - service: apache

basehost-apache-mods:
    cmd.run:
        - name: a2enmod rewrite
        - unless: test -f /etc/apache2/mods-enabled/rewrite.load
        - watch_in:
            - service: apache

temp-folders:
    file.directory:
        - name: /tmp/symfony
        - makedirs: True
        - mode: 777
        - user: www-data
        - group: www-data
