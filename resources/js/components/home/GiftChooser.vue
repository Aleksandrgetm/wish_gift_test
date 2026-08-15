<script setup>
import { computed, ref } from 'vue';

const activeTab = ref('recipient');
const groups = {
    recipient: {
        label: 'По получателю',
        title: 'Для кого ищем подарок?',
        description: 'Выберите человека, а дальше каталог можно будет развить под конкретные сценарии.',
        items: [
            ['Мужчине', '/recipient/man', 'Фото, музыка, шутка или тёплый семейный момент.'],
            ['Женщине', '/recipient/woman', 'Эмоциональный сувенир с видео и личным поздравлением.'],
            ['Ребёнку', '/recipient/child', 'Яркая история, которую интересно открыть с телефона.'],
            ['Учителю', '/recipient/teacher', 'Подарок от класса с общим видео или подписью.'],
            ['Врачу', '/recipient/doctor', 'Благодарность в аккуратном персональном формате.'],
            ['Другие подарки', '/recipient/other', 'Если идея нестандартная, поможем собрать формат.'],
        ],
    },
    occasion: {
        label: 'По празднику',
        title: 'К какому событию готовимся?',
        description: 'Повод помогает выбрать тон: нежный, праздничный, семейный или корпоративный.',
        items: [
            ['Рождество', '/occasion/christmas', 'Тёплые семейные видео и праздничные открытки.'],
            ['Новый год', '/occasion/new-year', 'Итоги года, пожелания и музыкальные поздравления.'],
            ['14 февраля', '/occasion/valentine', 'Личное признание, фото и любимая песня.'],
            ['8 марта', '/occasion/womens-day', 'Шоколад, фото или открытка с QR-видео.'],
            ['День матери', '/occasion/mothers-day', 'Самые личные воспоминания в красивом сувенире.'],
            ['День учителя', '/occasion/teachers-day', 'Общее поздравление от класса или группы.'],
            ['День студента', '/occasion/students-day', 'Весёлый подарок с фото, видео и шуткой.'],
            ['1 сентября', '/occasion/september-first', 'Старт учебного года с персональным посланием.'],
            ['День отца', '/occasion/fathers-day', 'Фото, голос, музыка и семейная история.'],
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
                    <p class="eyebrow">Выбор подарка</p>
                    <h2 id="gift-chooser-title">Найдите подарок для своего человека</h2>
                </div>
                <div class="gift-tabs" role="tablist" aria-label="Способ выбора подарка">
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
