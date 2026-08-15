<script setup>
const benefits = [
    ['mdi-qrcode-scan', 'QR atdzīvošanās efekts', 'Foto atver video, mūziku vai personīgu apsveikumu.'],
    ['mdi-image-multiple-outline', 'Jūsu materiāli', 'Strādājam ar foto, video, mūziku un personalizētu uzrakstu.'],
    ['mdi-account-check-outline', 'Pasūtījuma saskaņošana', 'Menedžeris pārbaudīs failus un apstiprinās formātu pirms noformēšanas.'],
    ['mdi-auto-fix', 'Personalizēts dizains', 'Izveidosim dāvanu pēc saņēmēja, svētkiem un jūsu stāsta.'],
];
</script>

<template>
    <section class="section benefits-section motion-reveal"><div class="shell"><p class="eyebrow centered">Kāpēc wish_gift</p><h2 class="centered">Personīgi pēc nozīmes. Precīzi pēc tehnoloģijas.</h2><div class="benefit-grid"><article v-for="item in benefits" :key="item[1]"><v-icon :icon="item[0]" size="30" /><h3>{{ item[1] }}</h3><p>{{ item[2] }}</p></article></div></div></section>
</template>
