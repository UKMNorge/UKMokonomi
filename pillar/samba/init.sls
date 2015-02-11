samba_sections:
    global:
        security: user

    www:
        path: /var/www/
        valid users: vagrant
        public: no
        writable: yes
        read only: no
