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

- notify on a tab if all images are loaded
- issue with displaying Bootstrap styles on the entire page
- image limit