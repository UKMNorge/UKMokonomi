# Remove stuff that exposes ports and services we don't use
purged-pkgs:
    pkg.purged:
        - pkgs:
            - rpcbind
