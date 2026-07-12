import { createRouter, createWebHistory } from 'vue-router';
const HomeView = () => import('../views/HomeView.vue');
const CatalogView = () => import('../views/CatalogView.vue');
const PlaceholderView = () => import('../views/PlaceholderView.vue');

const placeholder = (path, name, title) => ({ path, name, component: PlaceholderView, meta: { title } });

export default createRouter({
    history: createWebHistory(),
    scrollBehavior: () => ({ top: 0 }),
    routes: [
        { path: '/', name: 'home', component: HomeView },
        { path: '/catalog', name: 'catalog', component: CatalogView, meta: { title: 'Каталог' } },
        placeholder('/souvenirs', 'souvenirs', 'Сувениры-оживайки'),
        placeholder('/souvenirs/photo', 'souvenirs-photo', 'Фото-оживайка'),
        placeholder('/souvenirs/tshirt', 'souvenirs-tshirt', 'Майка-оживайка'),
        placeholder('/souvenirs/mug', 'souvenirs-mug', 'Оживи кружку'),
        placeholder('/souvenirs/keychain', 'souvenirs-keychain', 'Брелок'),
        placeholder('/souvenirs/card', 'souvenirs-card', 'Открытка'),
        placeholder('/souvenirs/chocolate', 'souvenirs-chocolate', 'Шоколад'),
        placeholder('/souvenirs/track-record', 'souvenirs-track-record', 'Трек-пластинка с фото и видео'),
        placeholder('/recipient', 'recipient', 'Получатель подарка'),
        placeholder('/recipient/man', 'recipient-man', 'Подарок мужчине'),
        placeholder('/recipient/woman', 'recipient-woman', 'Подарок женщине'),
        placeholder('/recipient/child', 'recipient-child', 'Подарок ребёнку'),
        placeholder('/recipient/teacher', 'recipient-teacher', 'Подарок учителю'),
        placeholder('/recipient/doctor', 'recipient-doctor', 'Подарок врачу'),
        placeholder('/recipient/other', 'recipient-other', 'Другие подарки'),
        placeholder('/occasion', 'occasion', 'Праздники'),
        placeholder('/occasion/christmas', 'occasion-christmas', 'Рождество'),
        placeholder('/occasion/new-year', 'occasion-new-year', 'Новый год'),
        placeholder('/occasion/valentine', 'occasion-valentine', '14 февраля'),
        placeholder('/occasion/womens-day', 'occasion-womens-day', '8 марта'),
        placeholder('/occasion/mothers-day', 'occasion-mothers-day', 'День матери'),
        placeholder('/occasion/teachers-day', 'occasion-teachers-day', 'День учителя'),
        placeholder('/occasion/students-day', 'occasion-students-day', 'День студента'),
        placeholder('/occasion/september-first', 'occasion-september-first', '1 сентября'),
        placeholder('/occasion/fathers-day', 'occasion-fathers-day', 'День отца'),
        { path: '/corporate', name: 'corporate', component: PlaceholderView, meta: { title: 'Корпоративным клиентам' } },
        { path: '/about', name: 'about', component: PlaceholderView, meta: { title: 'О компании' } },
        { path: '/contacts', name: 'contacts', component: PlaceholderView, meta: { title: 'Контакты' } },
        { path: '/:pathMatch(.*)*', name: 'not-found', component: PlaceholderView, meta: { title: 'Страница не найдена' } },
    ],
});
