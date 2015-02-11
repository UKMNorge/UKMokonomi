{% if grains['id'] == 'vagrant' %}
basehost:
    host_ip: 10.0.10.50
    host_name: okonomi.dev
    mysql_root_pass: dev
    mysql_db_name: okonomi
    mysql_db_pass: devonly
    mysql_db_user: okonomiuser
    facebook_app_secret: bogus
{% else %}
{% endif %}
