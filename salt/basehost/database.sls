{% set basehost = pillar.get('basehost') %}

mysql-database:
    mysql_database.present:
        - name: {{ basehost.get('mysql_db_name') }}
        - host: localhost
        - connection_pass: {{ basehost.get('mysql_root_pass') }}
        - require:
            - service: mysql-server

mysql-user:
    mysql_user.present:
        - name: {{ basehost.get('mysql_db_user') }}
        - host: localhost
        - password: {{ basehost.get('mysql_db_pass') }}
        - connection_pass: {{ basehost.get('mysql_root_pass') }}

mysql-grant-write:
    mysql_grants.present:
        - user: {{ basehost.get('mysql_db_user') }}
#		- grant: select,insert,update,create
        - grant: all privileges
        - database: {{ basehost.get('mysql_db_name') }}.*
        - host: localhost
        - connection_pass: {{ basehost.get('mysql_root_pass') }}
        - require:
            - mysql_user: mysql-user