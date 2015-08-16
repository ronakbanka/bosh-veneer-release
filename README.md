The state of BOSH for the stateless. There might be something here.


# directorweb-release


## Goals

 * support automation needs with scriptable data
 * frontend to browse BOSH instead of command line
 * integrate multiple data sources into a unified view of resources
 * improve deployment-management workflows - deploying and monitoring


## Components

 * Core - provides the web frontend to browse the state of your BOSH director.
 * Logsearch - adds references for viewing the usage and performance metrics reported by your resources.
 * AWS CPI - adds links to the AWS Console for your resources.
 * Cloque - adds GitHub links to your configuration files and integrated changelog viewing.


## dev

You might need to rsync...

    $ rsync --exclude .git --progress -auze 'ssh -i bosh.pem' src/webapp/. vcap@192.0.2.1:/var/vcap/packages/directorweb-webapp/.


## /cloque/repository.git

You might need to set things up...

    $ mkdir -p /var/vcap/store/directorweb/repository
    $ /var/vcap/packages/directorweb-git/bin/git --git-dir /var/vcap/store/directorweb/repository/git-root init --bare
    $ cat << EOF > /var/vcap/store/directorweb/repository/git-root/hooks/pre-receive
    #!/bin/bash

    set -e
    set -u

    while read "OLD" "NEW" "REV" ; do
        /var/vcap/packages/directorweb-webapp/app/console bosh:cloque:git-hook:pre-receive "$OLD" "$NEW" "$REV"
    done
    EOF
    $ chmod +x /var/vcap/store/directorweb/repository/git-root/hooks/pre-receive


## export

    export PATH="/var/vcap/packages/directorweb-php/bin:$PATH"
    export SYMFONY_ENV=dev
    export SYMFONY_DEBUG=true
    export SYMFONY_PARAMS=/var/vcap/jobs/directorweb/etc/webapp.yml
    export LOGDIR=/var/vcap/sys/log/directorweb
    export CACHEDIR=/var/vcap/jobs/directorweb/cache


## License

[Copyright 2015](./LICENSE)
