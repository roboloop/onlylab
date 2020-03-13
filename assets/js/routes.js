export default [
    {
        path: '/',
        name: 'main',
        component: { template: '<div>Main page</div>' },
    },
    {
        path: '/genres',
        name: 'genres',
        component: { template: '<div>Genres page</div>' },
    },
    {
        path: '/studios',
        name: 'studios',
        component: () => '<div>Studios page</div>',
    },
    {
        path: '/studios/:id',
        name: 'Suit studio',
        component: () => '',
    },
    {
        path: '*',
        redirect: '/',
    }
];
