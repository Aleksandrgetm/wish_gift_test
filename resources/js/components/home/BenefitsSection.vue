<script setup>
const benefits = [
    ['mdi-qrcode-scan', 'Фото + видео + музыка', 'QR-код открывает видео, музыку, шарж или персональное поздравление.'],
    ['mdi-account-check-outline', 'Согласование перед изготовлением', 'Перед финальным макетом проверяем материалы и обсуждаем детали.'],
    ['mdi-auto-fix', 'Индивидуальный дизайн', 'Подарок собирается под получателя, праздник и вашу историю.'],
    ['mdi-message-text-outline', 'Помощь с выбором', 'Если идея ещё не оформлена, поможем подобрать формат и наполнение.'],
];
</script>

<template>
    <section class="section benefits-section trust-benefits motion-reveal" aria-labelledby="trust-title">
        <div class="shell trust-layout">
            <div class="trust-copy">
                <p class="eyebrow">Доверие и детали</p>
                <h2 id="trust-title">Подарок выглядит личным, потому что мы не пропускаем согласование</h2>
                <p class="section-lead">Wish Gift — это не готовый шаблон. Мы работаем с вашими материалами, подбираем формат и перед изготовлением сверяем, как будет выглядеть QR-подарок.</p>
                <div class="trust-photo-strip" aria-label="Примеры реальных работ">
                    <img :src="'/images/Products/8 марта 5.png'" alt="Пример персонализированной подарочной работы" loading="lazy">
                    <img :src="'/images/Products/день матери 5.png'" alt="Пример подарка с персональным дизайном" loading="lazy">
                    <img :src="'/images/Products/день студента 2.png'" alt="Пример сувенира Wish Gift" loading="lazy">
                </div>
            </div>
            <div class="benefit-grid">
                <article v-for="item in benefits" :key="item[1]">
                    <v-icon :icon="item[0]" size="30" />
                    <h3>{{ item[1] }}</h3>
                    <p>{{ item[2] }}</p>
                </article>
            </div>
        </div>
    </section>
</template>
