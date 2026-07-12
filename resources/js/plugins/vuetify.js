import 'vuetify/styles';
import { createVuetify } from 'vuetify';

export default createVuetify({
    theme: {
        defaultTheme: 'eclatLight',
        themes: {
            eclatLight: {
                dark: false,
                colors: {
                    primary: '#67349B',
                    secondary: '#1EA1B5',
                    surface: '#FFFFFF',
                    background: '#FBF9FC',
                    'surface-variant': '#F3F3F7',
                    'on-primary': '#FFFFFF',
                    'on-secondary': '#102A33',
                    'on-surface': '#263442',
                    outline: '#D6D0DC',
                    accent: '#B89146',
                    success: '#2F7D5B',
                    warning: '#B96D12',
                    error: '#B04455',
                    info: '#177F93',
                },
            },
        },
    },
    defaults: {
        VBtn: { rounded: 'pill', elevation: 0 },
        VCard: { rounded: 'xl', elevation: 0 },
        VChip: { rounded: 'pill' },
    },
});
