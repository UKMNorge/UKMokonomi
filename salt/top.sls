base:
    '*':
        - networking
        - security
        - vim

    'roles:basehost':
        - match: grain
        - basehost

    'vagrant':
        - samba