<script setup>
const questions = [
    ['Kā darbojas dzīvā dāvana?', 'Mēs izvietojam QR kodu uz dāvanas. Saņēmējs to noskenē ar telefonu un atver video, mūziku, karikatūru, foto vai personīgu apsveikumu.'],
    ['Kā nosūtīt foto un video?', 'Šajā posmā vietne rāda pasūtījuma izvēles scenāriju. Lai pasūtītu dāvanu, pārejiet uz dāvanas izveidi vai sazinieties ar mums, un materiālus saskaņosim ar menedžeri.'],
    ['Vai var pievienot mūziku?', 'Jā, formātā var iekļaut mūziku vai dziesmu, ja tā atbilst izvēlētajai dāvanai un materiāliem.'],
    ['Vai var izveidot karikatūru?', 'Jā, karikatūru var izmantot kā daļu no personalizētā dizaina, ja tā iederas jūsu idejā.'],
    ['Cik ilgi notiek izgatavošana?', 'Precīzs laiks ir atkarīgs no formāta, maketa un materiāliem. To nosakām pēc konkrētā pasūtījuma saskaņošanas.'],
    ['Kā notiek pasūtījuma saskaņošana?', 'Pirms izgatavošanas pārbaudām materiālus, pārrunājam maketu un apstiprinām, ka dāvana izskatīsies pareizi.'],
];
</script>

<template>
    <section id="faq" class="section faq-section motion-reveal" aria-labelledby="faq-title">
        <div class="shell faq-layout">
            <div class="faq-heading">
                <p class="eyebrow">Jautājumi</p>
                <h2 id="faq-title" class="section-title">Biežāk uzdotie jautājumi</h2>
            </div>
            <v-expansion-panels class="faq-panels" variant="accordion" elevation="0">
                <v-expansion-panel v-for="item in questions" :key="item[0]">
                    <v-expansion-panel-title>{{ item[0] }}</v-expansion-panel-title>
                    <v-expansion-panel-text>{{ item[1] }}</v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>
        </div>
    </section>
</template>
