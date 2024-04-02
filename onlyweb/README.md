# onlyweb

### Build

Run two programs in separate tabs:

```shell
docker run --rm -it -p 80:80 -v "$(pwd)/dist":/usr/share/nginx/html nginx:alpine

NODE_ENV=development npx vite build -w --minify=false
```

### Lint

```shell
npm run lint -- --ignore-pattern 'dummy/*' --ignore-pattern 'dev/*'
npm run format
```

### TODO

- issue with displaying Bootstrap styles on the entire page
- image limit
- deployment
- search page /forum/tracker.php
- fastpic links without .html (t=5862961)