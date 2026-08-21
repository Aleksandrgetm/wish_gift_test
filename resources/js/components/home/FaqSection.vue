<script setup>
import { computed } from 'vue';
import { useI18n } from '../../composables/useI18n';

const { locale } = useI18n();

const faqContent = {
    lv: {
        accent: 'Ir jautājumi?',
        title: 'Mēs visu izskaidrosim.',
        questions: [
            ['Kas ir atdzīvojoša dāvana?', 'Atdzīvojoša dāvana ir personalizēta dāvana ar QR kodu. Saņēmējs noskenē kodu ar telefonu un var apskatīt viņam sagatavotu video, fotoattēlu vai citu personīgu vēstījumu.'],
            ['Kā darbojas QR kods uz dāvanas?', 'Vienkārši pavērsiet viedtālruņa kameru uz QR kodu un atveriet parādīto saiti. Nekas papildus nav jāinstalē.'],
            ['Vai var pievienot savu foto vai video?', 'Jā. Jūs varat izmantot savus fotoattēlus un video, lai dāvana kļūtu patiesi personīga un unikāla.'],
            ['Uz kādām dāvanām var pievienot QR kodu?', 'QR kodu var pievienot pieejamām personalizētajām precēm no mūsu kataloga: kartītēm, šokolādes dāvanām un citām dāvanām ar individuālu dizainu.'],
            ['Kādiem svētkiem ir piemērotas jūsu dāvanas?', 'Dzimšanas dienai, kāzām, Mātes dienai, Tēva dienai, 8. martam, Skolotāju dienai, Ziemassvētkiem, Jaunajam gadam, gadadienai un citiem svarīgiem notikumiem. Dāvanu var sagatavot arī vienkārši bez īpaša iemesla.'],
            ['Vai es varēšu redzēt dizainu pirms izgatavošanas?', 'Jā. Pirms izgatavošanas mēs saskaņojam detaļas un, ja nepieciešams, veicam labojumus, lai rezultāts jums patiktu.'],
            ['Cik ilgi aizņem izgatavošana?', 'Termiņš ir atkarīgs no izvēlētās dāvanas un personalizācijas. Pirms pasūtījuma noformēšanas mēs paziņosim aktuālo izgatavošanas laiku un saskaņosim to ar jums.'],
            ['Vai veicat piegādi Latvijā?', 'Jā, pasūtījumus var saņemt ar piegādi Latvijā. Pieejamie piegādes veidi un izmaksas tiek parādītas pasūtījuma noformēšanas laikā.'],
        ],
    },
    en: {
        accent: 'Have questions?',
        title: 'We will explain everything.',
        questions: [
            ['What is a living gift?', 'A living gift is a personalized present with a QR code. The recipient scans the code with a phone and can view a prepared video, photo or another personal message.'],
            ['How does the QR code on the gift work?', 'Just point your smartphone camera at the QR code and open the link that appears. No additional app is required.'],
            ['Can I add my own photo or video?', 'Yes. You can use your own photos and videos to make the gift truly personal and unique.'],
            ['Which gifts can include a QR code?', 'A QR code can be added to available personalized products from our catalog: cards, chocolate gifts and other gifts with individual design.'],
            ['Which occasions are your gifts suitable for?', 'Birthdays, weddings, Mother’s Day, Father’s Day, March 8, Teacher’s Day, Christmas, New Year, anniversaries and other important events. A gift can also be prepared just because.'],
            ['Will I see the design before production?', 'Yes. Before production, we approve the details and make edits if needed so you are happy with the result.'],
            ['How long does production take?', 'Timing depends on the selected gift and personalization. Before placing the order, we will share the current production time and agree it with you.'],
            ['Do you deliver across Latvia?', 'Yes, orders can be delivered across Latvia. Available delivery methods and costs are shown during checkout.'],
        ],
    },
    ru: {
        accent: 'Есть вопросы?',
        title: 'Мы всё объясним.',
        questions: [
            ['Что такое подарок-оживайка?', 'Подарок-оживайка — это персональный подарок с QR-кодом. Получатель сканирует код телефоном и может увидеть подготовленное для него видео, фотографию или другое персональное послание.'],
            ['Как работает QR-код на подарке?', 'Просто наведите камеру смартфона на QR-код и перейдите по появившейся ссылке. Ничего дополнительно устанавливать не нужно.'],
            ['Можно ли добавить своё фото или видео?', 'Да. Вы можете использовать собственные фотографии и видео, чтобы сделать подарок действительно личным и уникальным.'],
            ['На какие подарки можно добавить QR-код?', 'QR-код можно добавить на доступные персонализированные товары из нашего каталога: открытки, шоколадные подарки и другие подарки с индивидуальным дизайном.'],
            ['Для каких праздников подходят ваши подарки?', 'Для дня рождения, свадьбы, Дня матери, Дня отца, 8 Марта, Дня учителя, Рождества, Нового года, годовщины и других важных событий. Подарок можно подготовить и просто без повода.'],
            ['Смогу ли я увидеть дизайн до изготовления?', 'Да. Перед изготовлением мы согласуем детали и при необходимости внесём правки, чтобы результат вам понравился.'],
            ['Сколько времени занимает изготовление?', 'Срок зависит от выбранного подарка и персонализации. Перед оформлением мы сообщим актуальное время изготовления и согласуем его с вами.'],
            ['Осуществляете ли вы доставку по Латвии?', 'Да, заказы можно получить с доставкой по Латвии. Доступные способы и стоимость доставки отображаются при оформлении заказа.'],
        ],
    },
};

const content = computed(() => faqContent[locale.value] ?? faqContent.lv);
</script>

<template>
    <section id="faq" class="section faq-section motion-reveal" aria-labelledby="faq-title">
        <div class="shell faq-layout">
            <div class="faq-heading">
                <div class="faq-heading-decor faq-heading-decor--left" aria-hidden="true">
                    <v-icon class="faq-heading-decor__star faq-heading-decor__star--one" icon="mdi-star-four-points-outline" size="28" />
                    <v-icon class="faq-heading-decor__star faq-heading-decor__star--two" icon="mdi-gift-outline" size="22" />
                    <v-icon class="faq-heading-decor__star faq-heading-decor__star--three" icon="mdi-star-outline" size="22" />
                </div>
                <p class="faq-heading-accent">{{ content.accent }}</p>
                <h2 id="faq-title" class="section-title">{{ content.title }}</h2>
                <div class="faq-heading-decor faq-heading-decor--right" aria-hidden="true">
                    <v-icon class="faq-heading-decor__star faq-heading-decor__star--one" icon="mdi-party-popper" size="28" />
                    <v-icon class="faq-heading-decor__star faq-heading-decor__star--two" icon="mdi-star-four-points" size="20" />
                    <v-icon class="faq-heading-decor__star faq-heading-decor__star--three" icon="mdi-star-outline" size="22" />
                </div>
            </div>
            <v-expansion-panels class="faq-panels" variant="accordion" elevation="0">
                <v-expansion-panel v-for="item in content.questions" :key="item[0]">
                    <v-expansion-panel-title>{{ item[0] }}</v-expansion-panel-title>
                    <v-expansion-panel-text>{{ item[1] }}</v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>
        </div>
    </section>
</template>
