samba:
  pkg.installed:
    - name: samba

  file.managed:
    - name: /etc/samba/smb.conf
    - source: salt://samba/smb.conf
    - template: jinja
    - require:
      - pkg: samba

  service.running:
    - name: smbd
    - require:
      - pkg: samba
    - watch:
      - file: samba


samba-password-vagrant:
  cmd.run:
    - name: '(echo "vagrant"; echo "vagrant") | smbpasswd -s -a vagrant'
    - output_loglevel: quiet


vagrant-user:
  user.present:
    - name: vagrant
    - groups:
      - www-data


var-www-vagrant-permissions:
  cmd.run:
    - name: chown root:www-data -R . && chmod 775 -R .
    - cwd: /var/www
    - order: last

