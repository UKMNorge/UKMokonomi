vim:
    pkg.installed:
        - name: vim

    file.managed:
        - name: /etc/vim/vimrc.local
        - source: salt://vim/vimrc
        - require:
            - pkg: vim
