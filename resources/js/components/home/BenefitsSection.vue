<script setup>
const benefits = [
    ['mdi-qrcode-scan', 'QR-эффект оживания', 'Фото открывает видео, музыку или личное поздравление.'],
    ['mdi-image-multiple-outline', 'Ваши материалы', 'Работаем с фото, видео, музыкой и персональной надписью.'],
    ['mdi-account-check-outline', 'Согласование заказа', 'Менеджер проверит файлы и подтвердит формат до оформления.'],
    ['mdi-auto-fix', 'Персональный дизайн', 'Соберём подарок под получателя, праздник и вашу историю.'],
];
</script>

<template>
    <section class="section benefits-section motion-reveal"><div class="shell"><p class="eyebrow centered">Почему Éclat</p><h2 class="centered">Лично по смыслу. Точно по технологии.</h2><div class="benefit-grid"><article v-for="item in benefits" :key="item[1]"><v-icon :icon="item[0]" size="30" /><h3>{{ item[1] }}</h3><p>{{ item[2] }}</p></article></div></div></section>
</template>
