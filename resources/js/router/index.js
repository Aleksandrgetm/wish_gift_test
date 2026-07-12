import { createRouter, createWebHistory } from 'vue-router';
const HomeView = () => import('../views/HomeView.vue');
const CatalogView = () => import('../views/CatalogView.vue');
const PlaceholderView = () => import('../views/PlaceholderView.vue');

export default createRouter({
    history: createWebHistory(),
    scrollBehavior: () => ({ top: 0 }),
    routes: [
        { path: '/', name: 'home', component: HomeView },
        { path: '/catalog', name: 'catalog', component: CatalogView, meta: { title: 'Каталог' } },
        { path: '/corporate', name: 'corporate', component: PlaceholderView, meta: { title: 'Корпоративным клиентам' } },
        { path: '/about', name: 'about', component: PlaceholderView, meta: { title: 'О компании' } },
        { path: '/contacts', name: 'contacts', component: PlaceholderView, meta: { title: 'Контакты' } },
        { path: '/:pathMatch(.*)*', name: 'not-found', component: PlaceholderView, meta: { title: 'Страница не найдена' } },
    ],
});
