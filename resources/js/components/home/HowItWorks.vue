<script setup>
const steps = [
    ['mdi-gift-outline', 'Izvēlies dāvanu', 'Foto, krūze, T-krekls, šokolāde, piekariņš, kartīte vai dziesmas plāksne.'],
    ['mdi-image-multiple-outline', 'Nosūti foto un video', 'Atsūtiet materiālus, kas kļūs par daļu no personīga stāsta.'],
    ['mdi-music-note-outline', 'Pievieno mūziku vai novēlējumu', 'Var pievienot dziesmu, uzrakstu, karikatūru vai īsu apsveikumu.'],
    ['mdi-palette-outline', 'Saskaņo maketu', 'Pirms izgatavošanas pārbaudām materiālus un saskaņojam dizainu.'],
    ['mdi-qrcode-scan', 'Saņem gatavo dāvanu', 'Suvenīrs tiek sagatavots ar QR kodu, kas atver jūsu vēstījumu.'],
];
</script>

<template>
    <section id="how-it-works" class="section process-section motion-reveal" aria-labelledby="process-title">
        <div class="shell">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Kā pasūtīt</p>
                    <h2 id="process-title" class="section-title">No idejas līdz gatavai dāvanai bez liekas steigas</h2>
                </div>
                <a href="#finder">Izvēlēties formātu <span>→</span></a>
            </div>
            <div class="process-track process-journey">
                <article v-for="(step, index) in steps" :key="step[0]" class="process-card">
                    <div class="process-card-top">
                        <span>0{{ index + 1 }}</span>
                        <i aria-hidden="true"><v-icon :icon="step[0]" size="22" /></i>
                    </div>
                    <h3>{{ step[1] }}</h3>
                    <p>{{ step[2] }}</p>
                </article>
            </div>
        </div>
    </section>
</template>
