<script setup>
const benefits = [
    ['mdi-qrcode-scan', 'Foto + video + mūzika', 'QR kods atver video, mūziku, karikatūru vai personīgu apsveikumu.'],
    ['mdi-account-check-outline', 'Saskaņošana pirms izgatavošanas', 'Pirms gala maketa pārbaudām materiālus un pārrunājam detaļas.'],
    ['mdi-auto-fix', 'Individuāls dizains', 'Dāvana tiek veidota atbilstoši saņēmējam, svētkiem un jūsu stāstam.'],
    ['mdi-message-text-outline', 'Palīdzība ar izvēli', 'Ja ideja vēl nav skaidra, palīdzēsim piemeklēt formātu un saturu.'],
];
</script>

<template>
    <section class="section benefits-section trust-benefits motion-reveal" aria-labelledby="trust-title">
        <div class="shell trust-layout">
            <div class="trust-copy">
                <p class="eyebrow">Uzticēšanās un detaļas</p>
                <h2 id="trust-title" class="section-title">Dāvana izskatās personīga, jo maketu saskaņojam pirms izgatavošanas</h2>
                <p class="section-lead">Wish Gift nav gatavs šablons. Mēs strādājam ar jūsu materiāliem, piemeklējam formātu un pirms izgatavošanas pārbaudām, kā izskatīsies QR dāvana.</p>
                <div class="trust-photo-strip" aria-label="Reālu darbu piemēri">
                    <img :src="'/images/Products/8 марта 5.png'" alt="Personalizētas dāvanas piemērs" loading="lazy">
                    <img :src="'/images/Products/день матери 5.png'" alt="Dāvana ar personalizētu dizainu" loading="lazy">
                    <img :src="'/images/Products/день студента 2.png'" alt="Wish Gift suvenīra piemērs" loading="lazy">
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
