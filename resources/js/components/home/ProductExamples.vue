<script setup>
const products = [
    {
        name: 'Фото-оживайка',
        description: 'Печатное фото с QR-видео, музыкой или поздравлением.',
        price: 'от 25 €',
        badge: 'Популярно',
        image: '/images/Products/день матери 2.png',
        to: '/souvenirs/photo',
    },
    {
        name: 'Кружка-оживайка',
        description: 'Утренний подарок с личным видео и тёплым посланием.',
        price: 'по согласованию',
        badge: 'QR video',
        image: '/images/Products/8 марта 8.jpeg',
        to: '/souvenirs/mug',
    },
    {
        name: 'Майка-оживайка',
        description: 'История, которую можно носить с собой.',
        price: 'по согласованию',
        badge: 'Personal',
        image: '/images/Products/день студента 1.png',
        to: '/souvenirs/tshirt',
    },
    {
        name: 'Брелок с QR',
        description: 'Небольшой сувенир с большим личным смыслом.',
        price: 'по согласованию',
        badge: 'Mini gift',
        image: '/images/Products/1 сентября.png',
        to: '/souvenirs/keychain',
    },
    {
        name: 'Шоколад с QR',
        description: 'Подарочная упаковка, фото, поздравление и QR-видео.',
        price: 'по согласованию',
        badge: 'Sweet',
        image: '/images/Products/день учителя.png',
        to: '/souvenirs/chocolate',
    },
    {
        name: 'Трек-пластинка',
        description: 'Фото, видео и любимая музыка в одном формате.',
        price: 'от 30 €',
        badge: 'Music',
        image: '/images/Products/день рождения.png',
        to: '/souvenirs/track-record',
    },
];
</script>

<template>
    <section id="popular-products" class="section alive-products-section motion-reveal" aria-labelledby="popular-products-title">
        <div class="shell">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Популярные подарки</p>
                    <h2 id="popular-products-title">Сувениры, которые хочется открыть сразу</h2>
                </div>
                <router-link to="/catalog">Смотреть весь каталог <span>→</span></router-link>
            </div>
            <div class="alive-product-grid home-product-grid">
                <article v-for="product in products" :key="product.name" class="home-product-card tilt-card">
                    <router-link class="home-product-image" :to="product.to" :aria-label="`Открыть ${product.name}`">
                        <img :src="product.image" :alt="product.name" loading="lazy">
                        <span>{{ product.badge }}</span>
                        <i aria-hidden="true"><v-icon icon="mdi-qrcode-scan" size="20" /></i>
                    </router-link>
                    <div class="home-product-content">
                        <div>
                            <h3>{{ product.name }}</h3>
                            <p>{{ product.description }}</p>
                        </div>
                        <div class="home-product-footer">
                            <strong>{{ product.price }}</strong>
                            <v-btn variant="text" size="small" :to="product.to" append-icon="mdi-arrow-right">Подробнее</v-btn>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>
</template>
