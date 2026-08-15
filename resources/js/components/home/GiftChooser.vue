<script setup>
import { computed, ref } from 'vue';

const activeTab = ref('recipient');
const groups = {
    recipient: {
        label: 'Pēc saņēmēja',
        title: 'Kam meklējam dāvanu?',
        description: 'Izvēlieties saņēmēju, un vēlāk katalogu varēs pielāgot konkrētām dāvanu situācijām.',
        items: [
            ['Vīrietim', '/recipient/man', 'Foto, mūzika, joks vai silts ģimenes mirklis.'],
            ['Sievietei', '/recipient/woman', 'Emocionāls suvenīrs ar video un personīgu apsveikumu.'],
            ['Bērnam', '/recipient/child', 'Spilgts stāsts, ko ir interesanti atvērt telefonā.'],
            ['Skolotājam', '/recipient/teacher', 'Dāvana no klases ar kopīgu video vai uzrakstu.'],
            ['Ārstam', '/recipient/doctor', 'Pateicība gaumīgā un personīgā formātā.'],
            ['Citas dāvanas', '/recipient/other', 'Ja ideja ir neparasta, palīdzēsim atrast piemērotu formātu.'],
        ],
    },
    occasion: {
        label: 'Pēc svētkiem',
        title: 'Kādiem svētkiem gatavojamies?',
        description: 'Notikums palīdz izvēlēties noskaņu: maigu, svinīgu, ģimenisku vai korporatīvu.',
        items: [
            ['Ziemassvētki', '/occasion/christmas', 'Silti ģimenes video un svētku kartītes.'],
            ['Jaunais gads', '/occasion/new-year', 'Gada atmiņas, novēlējumi un muzikāli apsveikumi.'],
            ['Valentīndiena', '/occasion/valentine', 'Personīgs atzīšanās mirklis, foto un mīļākā dziesma.'],
            ['8. marts', '/occasion/womens-day', 'Šokolāde, foto vai kartīte ar QR video.'],
            ['Mātes diena', '/occasion/mothers-day', 'Vissiltākās atmiņas skaistā suvenīrā.'],
            ['Skolotāju diena', '/occasion/teachers-day', 'Kopīgs apsveikums no klases vai grupas.'],
            ['Studentu diena', '/occasion/students-day', 'Jautra dāvana ar foto, video un joku.'],
            ['1. septembris', '/occasion/september-first', 'Mācību gada sākums ar personīgu vēstījumu.'],
            ['Tēva diena', '/occasion/fathers-day', 'Foto, balss, mūzika un ģimenes stāsts.'],
        ],
    },
};

const current = computed(() => groups[activeTab.value]);
</script>

<template>
    <section id="gift-chooser" class="section gift-chooser-section motion-reveal" aria-labelledby="gift-chooser-title">
        <div class="shell gift-chooser">
            <div class="gift-chooser-head">
                <div>
                    <p class="eyebrow">Dāvanas izvēle</p>
                    <h2 id="gift-chooser-title" class="section-title">Atrodiet dāvanu savam cilvēkam</h2>
                </div>
                <div class="gift-tabs" role="tablist" aria-label="Dāvanas izvēles veids">
                    <button
                        v-for="(group, key) in groups"
                        :id="`gift-tab-${key}`"
                        :key="key"
                        type="button"
                        role="tab"
                        :aria-controls="`gift-panel-${key}`"
                        :aria-selected="activeTab === key"
                        :class="{ active: activeTab === key }"
                        @click="activeTab = key"
                    >
                        {{ group.label }}
                    </button>
                </div>
            </div>
            <Transition name="question" mode="out-in">
                <div
                    :id="`gift-panel-${activeTab}`"
                    :key="activeTab"
                    class="gift-panel"
                    role="tabpanel"
                    :aria-labelledby="`gift-tab-${activeTab}`"
                >
                    <div class="gift-panel-intro">
                        <h3>{{ current.title }}</h3>
                        <p>{{ current.description }}</p>
                    </div>
                    <div class="gift-choice-grid">
                        <router-link v-for="item in current.items" :key="item[0]" :to="item[1]" class="gift-choice-card">
                            <span>{{ item[0] }}</span>
                            <p>{{ item[2] }}</p>
                            <i aria-hidden="true">→</i>
                        </router-link>
                    </div>
                </div>
            </Transition>
        </div>
    </section>
</template>
