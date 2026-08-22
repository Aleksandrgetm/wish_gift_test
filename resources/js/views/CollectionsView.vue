<script setup>
import { computed } from 'vue';
import { catalogFilterBySlug, products } from '../data/products';
import { useI18n } from '../composables/useI18n';

const { locale, t, translateOption } = useI18n();

const collectionSlugs = [
    'ziemassvetki',
    'jaunais-gads',
    '14-februaris',
    '8-marts',
    'mates-diena',
    'skolotaju-diena',
    'studentu-diena',
    '1-septembris',
    'teva-diena',
    'dzimsanas-diena',
    'foto-ar-qr-video',
    'digitala-qr-davana',
    'dziesmas-plaksne',
    't-krekls',
    'kruze',
    'sokolade',
    'kartite',
    'saldumu-komplekti',
];

const collectionCopy = {
    lv: {
        title: 'Kolekcijas',
        lead: 'Iedvesmojošas dāvanu kolekcijas svētkiem, cilvēkiem un formātiem, kas palīdz ātri atrast īsto virzienu.',
        meta: 'Kurētas idejas',
        cta: 'Apskatīt kolekciju',
        countLabel: 'preces',
        titles: {
            't-krekls': 'T-krekli',
            kruze: 'Krūzes',
            kartite: 'Kartītes',
        },
        descriptions: {
            ziemassvetki: 'Siltas personalizētas dāvanas svētku sajūtai un ģimenes atmiņām.',
            'jaunais-gads': 'Dzirkstošas idejas jauniem sākumiem, vēlējumiem un pārsteigumiem.',
            '14-februaris': 'Romantiskas dāvanas ar foto, video un personīgu vēstījumu.',
            '8-marts': 'Maigas, skaistas un uzmanīgas dāvanas īpašām sievietēm.',
            'mates-diena': 'Aizkustinošas piemiņas lietas mammai ar ģimenes stāstu.',
            'skolotaju-diena': 'Pateicības dāvanas skolotājiem no klases vai ģimenes.',
            'studentu-diena': 'Vieglas, radošas dāvanas studiju ritmam un kopīgiem jokiem.',
            '1-septembris': 'Sākuma sajūta skolai, klasei un pirmajiem rudens mirkļiem.',
            'teva-diena': 'Personīgas dāvanas tētim ar raksturu, humoru un atmiņām.',
            'dzimsanas-diena': 'Dzimšanas dienas pārsteigumi, kas atveras ar QR stāstu.',
            'foto-ar-qr-video': 'Drukāts foto, kas turpina stāstu video formātā.',
            'digitala-qr-davana': 'Ātra digitāla dāvana ar foto, video vai sveicienu.',
            'dziesmas-plaksne': 'Mūzika, foto un video vienā elegantā piemiņas formātā.',
            't-krekls': 'Ikdienā nēsājami stāsti ar personalizētu QR video.',
            kruze: 'Rīta rituāli ar siltu vēstījumu un īpašu dizainu.',
            sokolade: 'Salds sveiciens ar personalizētu iepakojumu un QR video.',
            kartite: 'Kartītes, kas atver video, mūziku vai personīgu apsveikumu.',
            'saldumu-komplekti': 'Saldumu izlases, ko viegli pielāgot svētkiem un cilvēkam.',
        },
    },
    en: {
        title: 'Collections',
        lead: 'Inspiring gift collections for occasions, people and formats, designed to help you find the right direction quickly.',
        meta: 'Curated ideas',
        cta: 'View collection',
        countLabel: 'items',
        titles: {
            't-krekls': 'T-shirts',
            kruze: 'Mugs',
            kartite: 'Cards',
        },
        descriptions: {
            ziemassvetki: 'Warm personalized gifts for festive moments and family memories.',
            'jaunais-gads': 'Bright ideas for fresh starts, wishes and small surprises.',
            '14-februaris': 'Romantic gifts with photos, video and a personal message.',
            '8-marts': 'Soft, thoughtful gifts for special women.',
            'mates-diena': 'Tender keepsakes for mom with a family story inside.',
            'skolotaju-diena': 'Thank-you gifts for teachers from a class or family.',
            'studentu-diena': 'Creative gifts for study life, shared jokes and everyday stories.',
            '1-septembris': 'Back-to-school pieces for first lessons and autumn beginnings.',
            'teva-diena': 'Personal gifts for dad with character, humor and memories.',
            'dzimsanas-diena': 'Birthday surprises that open into a QR story.',
            'foto-ar-qr-video': 'A printed photo that continues as a video story.',
            'digitala-qr-davana': 'A quick digital gift with photos, video or a greeting.',
            'dziesmas-plaksne': 'Music, photo and video in one elegant keepsake format.',
            't-krekls': 'Wearable everyday stories with a personalized QR video.',
            kruze: 'Morning rituals with a warm message and custom design.',
            sokolade: 'A sweet greeting with personalized packaging and QR video.',
            kartite: 'Cards that open video, music or a personal greeting.',
            'saldumu-komplekti': 'Sweet selections that are easy to tailor to any occasion.',
        },
    },
    ru: {
        title: 'Коллекции',
        lead: 'Вдохновляющие коллекции подарков для праздников, людей и форматов, чтобы быстро найти подходящее направление.',
        meta: 'Подобранные идеи',
        cta: 'Смотреть коллекцию',
        countLabel: 'товаров',
        titles: {
            't-krekls': 'Футболки',
            kruze: 'Кружки',
            kartite: 'Открытки',
        },
        descriptions: {
            ziemassvetki: 'Теплые персональные подарки для праздника и семейных воспоминаний.',
            'jaunais-gads': 'Яркие идеи для новых начинаний, пожеланий и сюрпризов.',
            '14-februaris': 'Романтичные подарки с фото, видео и личным посланием.',
            '8-marts': 'Нежные и внимательные подарки для особенных женщин.',
            'mates-diena': 'Трогательные сувениры для мамы с семейной историей.',
            'skolotaju-diena': 'Подарки благодарности учителям от класса или семьи.',
            'studentu-diena': 'Креативные подарки для учебы, общих шуток и историй.',
            '1-septembris': 'Подарки к началу учебного года и первым осенним моментам.',
            'teva-diena': 'Личные подарки папе с характером, юмором и воспоминаниями.',
            'dzimsanas-diena': 'Сюрпризы на день рождения, которые открывают QR-историю.',
            'foto-ar-qr-video': 'Печатное фото, которое продолжается видеоисторией.',
            'digitala-qr-davana': 'Быстрый цифровой подарок с фото, видео или поздравлением.',
            'dziesmas-plaksne': 'Музыка, фото и видео в одном элегантном формате.',
            't-krekls': 'Истории на каждый день с персональным QR-видео.',
            kruze: 'Утренние ритуалы с теплым посланием и личным дизайном.',
            sokolade: 'Сладкое поздравление с персональной упаковкой и QR-видео.',
            kartite: 'Открытки, которые открывают видео, музыку или личное поздравление.',
            'saldumu-komplekti': 'Сладкие подборки, которые легко адаптировать под праздник.',
        },
    },
};

const productFilterFields = { occasions: 'occasion', categories: 'category', materials: 'material' };
const copy = computed(() => collectionCopy[locale.value] ?? collectionCopy.lv);
const collectionCards = computed(() => collectionSlugs.map((slug) => {
    const entry = catalogFilterBySlug[slug];
    const title = copy.value.titles[slug] ?? translateOption(entry.value);
    const count = products.filter((product) => product[productFilterFields[entry.filterKey]] === entry.value).length;

    return {
        ...entry,
        title,
        count,
        description: copy.value.descriptions[slug],
        to: { name: 'catalog-category', params: { categorySlug: slug } },
    };
}));
</script>

<template>
    <main class="collections-page">
        <section class="collections-hero shell" aria-labelledby="collections-title">
            <div>
                <p class="eyebrow">{{ copy.meta }}</p>
                <h1 id="collections-title">{{ copy.title }}</h1>
                <p>{{ copy.lead }}</p>
            </div>
            <router-link class="collections-hero-link" to="/catalog">
                {{ t('catalog.title') }}
                <v-icon icon="mdi-arrow-right" size="18" aria-hidden="true" />
            </router-link>
        </section>

        <section class="collections-showcase shell" :aria-label="t('nav.collections')">
            <router-link v-for="(collection, index) in collectionCards" :key="collection.slug" class="collection-card" :class="[`collection-card--${collection.tone}`, { 'collection-card--feature': index === 0 || index === 3 }]" :to="collection.to">
                <img :src="collection.image" :alt="collection.title" loading="lazy">
                <span class="collection-card-icon"><v-icon :icon="collection.icon" size="22" aria-hidden="true" /></span>
                <span class="collection-card-body">
                    <small>{{ collection.count }} {{ copy.countLabel }}</small>
                    <strong>{{ collection.title }}</strong>
                    <span>{{ collection.description }}</span>
                </span>
                <span class="collection-card-cta">
                    {{ copy.cta }}
                    <v-icon icon="mdi-arrow-right" size="18" aria-hidden="true" />
                </span>
            </router-link>
        </section>
    </main>
</template>
