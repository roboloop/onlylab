# onlyweb

### Dev

Run two programs in separate tabs:

```shell
docker run --rm -it -p 80:80 -v "$(pwd)/dist":/usr/share/nginx/html nginx:alpine

NODE_ENV=development npx vite build -w --minify=false
```

Add plugin to browser `tampermonkey.dev.js`

### Lint

```shell
npm run lint
npm run format
```

### Deploy

```shell
npm run build
node prod.js

# Copy paste the code to tampermonkey
```

### TODO

- issue with displaying Bootstrap styles on the entire page
- image limit
- working deployment
- add a list of image hosters to @connect
- fastpic links without .html (t=5862961)
