import { createRouter, createWebHistory } from 'vue-router';
const HomeView = () => import('../views/HomeView.vue');
const CatalogView = () => import('../views/CatalogView.vue');
const FaqView = () => import('../views/FaqView.vue');
const PlaceholderView = () => import('../views/PlaceholderView.vue');

const placeholder = (path, name, title) => ({ path, name, component: PlaceholderView, meta: { title } });

export default createRouter({
    history: createWebHistory(),
    scrollBehavior: (to) => {
        if (to.hash) {
            return { el: to.hash, behavior: 'smooth' };
        }

        return { top: 0 };
    },
    routes: [
        { path: '/', name: 'home', component: HomeView },
        { path: '/catalog', name: 'catalog', component: CatalogView, meta: { title: 'Katalogs' } },
        { path: '/catalog/:categorySlug', name: 'catalog-category', component: CatalogView, meta: { title: 'Katalogs' } },
        placeholder('/souvenirs', 'souvenirs', 'Suvenīri ar QR video'),
        placeholder('/souvenirs/photo', 'souvenirs-photo', 'Foto ar QR video'),
        placeholder('/souvenirs/tshirt', 'souvenirs-tshirt', 'T-krekls ar QR video'),
        placeholder('/souvenirs/mug', 'souvenirs-mug', 'Krūze ar QR video'),
        placeholder('/souvenirs/keychain', 'souvenirs-keychain', 'Atslēgu piekariņš'),
        placeholder('/souvenirs/card', 'souvenirs-card', 'Kartīte'),
        placeholder('/souvenirs/chocolate', 'souvenirs-chocolate', 'Šokolāde'),
        placeholder('/souvenirs/track-record', 'souvenirs-track-record', 'Dziesmas plāksne ar foto un video'),
        placeholder('/recipient', 'recipient', 'Dāvanas saņēmējs'),
        placeholder('/recipient/man', 'recipient-man', 'Dāvana vīrietim'),
        placeholder('/recipient/woman', 'recipient-woman', 'Dāvana sievietei'),
        placeholder('/recipient/child', 'recipient-child', 'Dāvana bērnam'),
        placeholder('/recipient/teacher', 'recipient-teacher', 'Dāvana skolotājam'),
        placeholder('/recipient/doctor', 'recipient-doctor', 'Dāvana ārstam'),
        placeholder('/recipient/other', 'recipient-other', 'Citas dāvanas'),
        { path: '/occasion/:pathMatch(.*)*', redirect: '/catalog' },
        { path: '/faq', name: 'faq', component: FaqView, meta: { title: 'Biežāk uzdotie jautājumi' } },
        { path: '/corporate', name: 'corporate', component: PlaceholderView, meta: { title: 'Korporatīvajiem klientiem' } },
        { path: '/about', name: 'about', component: PlaceholderView, meta: { title: 'Par uzņēmumu' } },
        { path: '/contacts', name: 'contacts', component: PlaceholderView, meta: { title: 'Kontakti' } },
        { path: '/:pathMatch(.*)*', name: 'not-found', component: PlaceholderView, meta: { title: 'Lapa nav atrasta' } },
    ],
});
