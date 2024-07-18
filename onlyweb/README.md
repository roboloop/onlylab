# onlyweb

### Lint

```shell
npm run lint
npm run format
```

### Deploy

It's work based on building a single tampermonkey script that accumulates all the dependencies (js, css, tampermonkey logic). The delivery process is:

1. Bump a new version in `tampermonkey/*.js.template`

    ```shell
    # Bump dev
    ./bump dev

    # Bump prod
    ./bump prod
    ```

2. Run a build process

    ```shell
    # Production env
    npm run build

    # Development env
    NODE_ENV=development npx vite build -w --minify=false
    ```
3. Run nginx server to serve files

    ```shell
    docker run --rm -it -p 80:80 -v "$(pwd)/dist":/usr/share/nginx/html nginx:alpine sh -c "sed -i '4i\add_header Cache-Control \"no-cache\";' /etc/nginx/conf.d/default.conf && nginx -g nginx -g 'daemon off;'"
    ```

4. (optional) If it is the first deploy:

    1. Open Tampermonkey Dashboard
    2. Move to `Utilities` / `Import from file`
    3. Choose the file `dist/tampermonkey.js`
    4. Save

5. Open the settings of tampermonkey's script
6. Click "Check for userscript updates"
7. Apply new updates

### TODO

- issue with displaying Bootstrap styles on the entire page
- add a list of image hosters to @connect
- display the spoiler title if it has a name, duration, size, etc
- fastpic — some images dont
- move storage management to a separate service
- popup with hotkeys
- separate service for registering hotkeys
