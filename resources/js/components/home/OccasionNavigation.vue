<script setup>
const occasions = [
    { title: 'День рождения', text: 'Личный подарок с характером', icon: 'mdi-cake-variant-outline', tone: 'rose' },
    { title: 'Свадьба', text: 'Для гостей и молодожёнов', icon: 'mdi-ring', tone: 'cream' },
    { title: 'Новый год', text: 'Тёплые сезонные коллекции', icon: 'mdi-snowflake', tone: 'green' },
    { title: 'Корпоративные', text: 'Упаковка в стиле бренда', icon: 'mdi-briefcase-outline', tone: 'gold' },
];
</script>

<template>
    <section class="section shell">
        <div class="section-heading"><div><p class="eyebrow">Повод имеет значение</p><h2>Выберите настроение подарка</h2></div><a href="/catalog">Все поводы <span>→</span></a></div>
        <div class="occasion-grid">
            <a v-for="item in occasions" :key="item.title" class="occasion-card" :class="`tone-${item.tone}`" href="/catalog">
                <v-icon :icon="item.icon" size="32" /><div><h3>{{ item.title }}</h3><p>{{ item.text }}</p></div><span class="round-arrow">→</span>
            </a>
        </div>
    </section>
</template>
