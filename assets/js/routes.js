export default [
    {
        path: '/',
        name: 'main',
        component: { template: '<div>Main page</div>' },
    },
    {
        path: '/topics/:id',
        name: 'topic',
        component: { template: '<div>Topic page</div>' },
    },
    {
        path: '/topics',
        name: 'topics',
        component: { template: '<div>Topics page</div>' },
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
        path: '/forums',
        name: 'forums',
        component: () => '<div>Forums page</div>',
    },
    {
        path: '/search',
        name: 'search',
        component: () => '<div>Search data page</div>',
    },
    {
        path: '*',
        redirect: '/',
    }
];
