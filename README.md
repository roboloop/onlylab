# OnlyTracker

## Deployment

1. Create a folder on the server with a bare repository

    ```shell
    git init --bare /opt/git/onlytracker.git
    ```

2. Create a hook and give it write permissions:

    ```shell
    touch /opt/git/onlytracker.git/hooks/post-receive
    chmod +x /opt/git/onlytracker.git/hooks/post-receive
    ```

3. Put the following content into the hook:

    ```shell
    #!/bin/bash

    TARGET="/var/www/onlytracker"
    GIT_DIR="/opt/git/onlytracker.git"
    BRANCH="master"

    read oldrev newrev ref

    mkdir -p "$TARGET"
    git --work-tree="$TARGET" --git-dir="$GIT_DIR" checkout -f "$BRANCH"
    cd "$TARGET"
    docker compose up -d
    docker compose exec fpm composer install
    docker compose exec fpm yarn install
    docker compose exec fpm yarn build
    docker compose exec fpm php bin/console doctrine:migrations:migrate -n
    ```

4. On the local machine, set the new remote repository (`remote_server` — the name in `.ssh/config`):

    ```shell
    git remote set-url origin root@remote_server:/opt/git/onlytracker.git
    ```

5. Push a test commit

    ```shell
    git commit --allow-empty -m 'New remote server'
    git push
    ```

6. The login and password can be found in the logs `docker logs onlytracker-nginx-1`:

    ```shell
    cd /var/www/onlytracker
    docker compose logs nginx

    # Adding password for user tracker_user
    # User: tracker_user
    # Pass: secret
    ```

7. Upload the dump from the local folder to the server and set correct permissions

    ```shell
    scp var/data.db remote_server:/var/www/onlytracker/var/data.db
    chmod 777 -R /var/www/onlytracker/var/
    ```

8. Access via SSH, open `https://127.0.0.1:9000`

    ```shell
    ssh -L 9000:127.0.0.1:443 remote_server
    ```

