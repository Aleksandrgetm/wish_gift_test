<script setup>
const products = [
    {
        name: 'Foto ar QR video',
        description: 'Drukāts foto ar QR video, mūziku vai personīgu apsveikumu.',
        price: 'no 25 €',
        badge: 'Populāri',
        image: '/images/Products/день матери 2.png',
        to: '/souvenirs/photo',
    },
    {
        name: 'Krūze ar QR video',
        description: 'Rīta dāvana ar personīgu video un siltu vēstījumu.',
        price: 'pēc saskaņošanas',
        badge: 'QR video',
        image: '/images/Products/8 марта 8.jpeg',
        to: '/souvenirs/mug',
    },
    {
        name: 'T-krekls ar QR video',
        description: 'Stāsts, ko var nēsāt līdzi.',
        price: 'pēc saskaņošanas',
        badge: 'Personīgi',
        image: '/images/Products/день студента 1.png',
        to: '/souvenirs/tshirt',
    },
    {
        name: 'Atslēgu piekariņš ar QR',
        description: 'Mazs suvenīrs ar lielu personīgu nozīmi.',
        price: 'pēc saskaņošanas',
        badge: 'Mazs formāts',
        image: '/images/Products/1 сентября.png',
        to: '/souvenirs/keychain',
    },
    {
        name: 'Šokolāde ar QR',
        description: 'Dāvanu iepakojums, foto, apsveikums un QR video.',
        price: 'pēc saskaņošanas',
        badge: 'Salds',
        image: '/images/Products/день учителя.png',
        to: '/souvenirs/chocolate',
    },
    {
        name: 'Dziesmas plāksne',
        description: 'Foto, video un mīļākā mūzika vienā formātā.',
        price: 'no 30 €',
        badge: 'Mūzika',
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
                    <p class="eyebrow">Populāras dāvanas</p>
                    <h2 id="popular-products-title" class="section-title">Suvenīri, ko gribas atvērt uzreiz</h2>
                </div>
                <router-link to="/catalog">Apskatīt katalogu <span>→</span></router-link>
            </div>
            <div class="alive-product-grid home-product-grid">
                <article v-for="product in products" :key="product.name" class="home-product-card tilt-card">
                    <router-link class="home-product-image" :to="product.to" :aria-label="`Atvērt ${product.name}`">
                        <img :src="product.image" :alt="product.name" loading="lazy">
                        <span>{{ product.badge }}</span>
                    </router-link>
                    <div class="home-product-content">
                        <div>
                            <h3>{{ product.name }}</h3>
                            <p>{{ product.description }}</p>
                        </div>
                        <div class="home-product-footer">
                            <strong>{{ product.price }}</strong>
                            <v-btn variant="text" size="small" :to="product.to" append-icon="mdi-arrow-right">Uzzināt vairāk</v-btn>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>
</template>
