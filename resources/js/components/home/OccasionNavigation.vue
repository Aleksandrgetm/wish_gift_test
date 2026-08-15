<script setup>
const occasions = [
    { title: 'Dzimšanas diena', text: 'Personīga dāvana ar raksturu', icon: 'mdi-cake-variant-outline', tone: 'rose' },
    { title: 'Kāzas', text: 'Viesiem un jaunlaulātajiem', icon: 'mdi-ring', tone: 'cream' },
    { title: 'Jaunais gads', text: 'Siltas sezonas kolekcijas', icon: 'mdi-snowflake', tone: 'green' },
    { title: 'Korporatīvi', text: 'Iepakojums zīmola stilā', icon: 'mdi-briefcase-outline', tone: 'gold' },
];
</script>

<template>
    <section class="section shell">
        <div class="section-heading"><div><p class="eyebrow">Notikumam ir nozīme</p><h2>Izvēlieties dāvanas noskaņu</h2></div><a href="/catalog">Visi notikumi <span>→</span></a></div>
        <div class="occasion-grid">
            <a v-for="item in occasions" :key="item.title" class="occasion-card" :class="`tone-${item.tone}`" href="/catalog">
                <v-icon :icon="item.icon" size="32" /><div><h3>{{ item.title }}</h3><p>{{ item.text }}</p></div><span class="round-arrow">→</span>
            </a>
        </div>
    </section>
</template>
