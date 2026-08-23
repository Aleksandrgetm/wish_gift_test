<script setup>
import { computed, reactive, ref } from 'vue';
import { useI18n } from '../composables/useI18n';

const { t } = useI18n();

const form = reactive({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: '',
});

const errors = reactive({});
const isSubmitting = ref(false);
const submitState = ref('idle');
const serverMessage = ref('');

const fields = computed(() => t('contacts.form.fields'));

const contactLinks = [
    { icon: 'mdi-phone-outline', labelKey: 'contacts.details.phone', value: '+371 28153310', href: 'tel:+37128153310' },
    { icon: 'mdi-email-outline', labelKey: 'contacts.details.email', value: 'ozivajka@inbox.lv', href: 'mailto:ozivajka@inbox.lv' },
];

const setError = (field, message) => {
    errors[field] = message;
};

const clearErrors = () => {
    Object.keys(errors).forEach((key) => delete errors[key]);
};

const validate = () => {
    clearErrors();

    if (!form.name.trim()) setError('name', t('contacts.form.errors.name'));
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email.trim())) setError('email', t('contacts.form.errors.email'));
    if (form.phone.trim() && !/^[0-9+()\s.-]+$/.test(form.phone.trim())) setError('phone', t('contacts.form.errors.phone'));
    if (!form.subject.trim()) setError('subject', t('contacts.form.errors.subject'));
    if (form.message.trim().length < 10) setError('message', t('contacts.form.errors.message'));

    return Object.keys(errors).length === 0;
};

const fieldAttrs = (name) => ({
    id: `contact-${name}`,
    'aria-invalid': Boolean(errors[name]),
    'aria-describedby': errors[name] ? `contact-${name}-error` : undefined,
});

const submit = async () => {
    submitState.value = 'idle';
    serverMessage.value = '';

    if (!validate()) return;

    isSubmitting.value = true;

    try {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
        const response = await fetch('/contact', {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({
                name: form.name.trim(),
                email: form.email.trim(),
                phone: form.phone.trim(),
                subject: form.subject.trim(),
                message: form.message.trim(),
            }),
        });

        const payload = await response.json().catch(() => ({}));

        if (response.status === 422 && payload.errors) {
            Object.entries(payload.errors).forEach(([field, messages]) => setError(field, messages[0]));
            submitState.value = 'error';
            serverMessage.value = t('contacts.form.validationError');
            return;
        }

        if (!response.ok) {
            throw new Error(payload.message || 'Contact form failed.');
        }

        Object.assign(form, { name: '', email: '', phone: '', subject: '', message: '' });
        submitState.value = 'success';
        serverMessage.value = t('contacts.form.success');
    } catch (error) {
        submitState.value = 'error';
        serverMessage.value = t('contacts.form.failure');
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<template>
    <main class="contact-page">
        <section class="contact-main shell" aria-labelledby="contact-details-title">
            <div class="contact-details">
                <p class="eyebrow">{{ t('contacts.details.eyebrow') }}</p>
                <h2 id="contact-details-title">{{ t('contacts.details.title') }}</h2>
                <p>{{ t('contacts.details.text') }}</p>

                <div class="contact-link-list">
                    <a v-for="link in contactLinks" :key="link.href" class="contact-link-card" :href="link.href">
                        <span><v-icon :icon="link.icon" size="22" aria-hidden="true" /></span>
                        <div class="contact-link-card__content">
                            <small>{{ t(link.labelKey) }}</small>
                            <strong>{{ link.value }}</strong>
                        </div>
                    </a>
                </div>
            </div>

            <form class="contact-form" novalidate @submit.prevent="submit">
                <div class="contact-form__header">
                    <p class="eyebrow">{{ t('contacts.form.eyebrow') }}</p>
                    <h2>{{ t('contacts.form.title') }}</h2>
                </div>

                <div class="contact-field">
                    <label for="contact-name">{{ fields.name }}</label>
                    <input v-model="form.name" v-bind="fieldAttrs('name')" type="text" autocomplete="name">
                    <p v-if="errors.name" id="contact-name-error" class="contact-error">{{ errors.name }}</p>
                </div>

                <div class="contact-field contact-field--half">
                    <label for="contact-email">{{ fields.email }}</label>
                    <input v-model="form.email" v-bind="fieldAttrs('email')" type="email" autocomplete="email">
                    <p v-if="errors.email" id="contact-email-error" class="contact-error">{{ errors.email }}</p>
                </div>

                <div class="contact-field contact-field--half">
                    <label for="contact-phone">{{ fields.phone }}</label>
                    <input v-model="form.phone" v-bind="fieldAttrs('phone')" type="tel" autocomplete="tel">
                    <p v-if="errors.phone" id="contact-phone-error" class="contact-error">{{ errors.phone }}</p>
                </div>

                <div class="contact-field">
                    <label for="contact-subject">{{ fields.subject }}</label>
                    <input v-model="form.subject" v-bind="fieldAttrs('subject')" type="text">
                    <p v-if="errors.subject" id="contact-subject-error" class="contact-error">{{ errors.subject }}</p>
                </div>

                <div class="contact-field">
                    <label for="contact-message">{{ fields.message }}</label>
                    <textarea v-model="form.message" v-bind="fieldAttrs('message')" rows="5"></textarea>
                    <p v-if="errors.message" id="contact-message-error" class="contact-error">{{ errors.message }}</p>
                </div>

                <p v-if="serverMessage" class="contact-status" :class="`contact-status--${submitState}`" role="status">
                    {{ serverMessage }}
                </p>

                <v-btn type="submit" color="primary" size="large" append-icon="mdi-send-outline" :loading="isSubmitting">
                    {{ t('contacts.form.submit') }}
                </v-btn>
            </form>
        </section>
    </main>
</template>
