<script setup>
const benefits = [
    ['mdi-pencil-ruler-outline', 'Собственный дизайн', 'Создаём упаковку от идеи до готового тиража.'],
    ['mdi-package-variant-closed', 'От 1 до 10 000 штук', 'Одинаково внимательно к личным и оптовым заказам.'],
    ['mdi-truck-fast-outline', 'Доставка по всей стране', 'Надёжно упакуем и отправим точно в срок.'],
    ['mdi-auto-fix', 'Персонализация', 'Логотип, имя, лента или особое послание.'],
];
</script>

<template>
    <section class="section benefits-section motion-reveal"><div class="shell"><p class="eyebrow centered">Почему Éclat</p><h2 class="centered">Красиво снаружи. Продумано внутри.</h2><div class="benefit-grid"><article v-for="item in benefits" :key="item[1]"><v-icon :icon="item[0]" size="30" /><h3>{{ item[1] }}</h3><p>{{ item[2] }}</p></article></div></div></section>
</template>
