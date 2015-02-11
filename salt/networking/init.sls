{% set basehost = pillar.get('basehost', {}) %}

{% if grains.get('hostname') %}
hostname:
    cmd.run:
        - name: hostname {{ grains.get('hostname') }}
{% endif %}

basehost-host:
    host.present:
        - ip: {{ basehost.get('host_ip') }}
        - name: {{ basehost.get('host_name') }}