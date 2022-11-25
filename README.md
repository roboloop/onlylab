````
# OnlyTracker

## Deployment

1. Upload the repository to the folder `/opt/git/onlytracker.git` using scp

2. Create the hook `/opt/git/onlytracker.git/hooks/post-receive` and put the following content into it:

```shell
#!/bin/bash

TARGET="/var/www/onlytracker"
GIT_DIR="/opt/git/onlytracker.git"
BRANCH="master"

mkdir -p "$TARGET"

while read oldrev newrev ref
do
    git --work-tree=$TARGET --git-dir=$GIT_DIR checkout -f $BRANCH
    cd "$TARGET"
    docker compose exec fpm composer install
    docker compose exec fpm yarn install
    docker compose exec fpm yarn build
    docker compose exec fpm php bin/console doctrine:migrations:migrate -n
done
````

3. Go to this folder and run `docker compose up -d`

4. Check the logs of the running nginx container: `docker logs onlytracker-nginx-1`

5. Find the user and password there:

```
Adding password for user tracker_user
User: tracker_user
Pass: secret
```

6. On the local machine, set the new remote repository (`remote_server` — the name in `.ssh/config`):

```shell
git remote set-url origin root@remote_server:/opt/git/onlytracker.git
```

7. Push a test commit:

```shell
git commit --allow-empty -m 'empty commit'
git push
```

8. Don’t forget to move the dump from the folder `/var/www/html/dump/` to `/var/www/onlytracker/var/data.db` and set the permissions:

```shell
chmod 777 /var/www/onlytracker/var/data.db
```
