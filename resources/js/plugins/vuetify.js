import 'vuetify/styles';
import { createVuetify } from 'vuetify';

export default createVuetify({
    theme: {
        defaultTheme: 'eclatLight',
        themes: {
            eclatLight: {
                dark: false,
                colors: {
                    primary: '#8B416D',
                    secondary: '#DAB67D',
                    surface: '#FFFFF6',
                    background: '#FFFFF6',
                    'surface-variant': '#D4E6E8',
                    'on-primary': '#FFFFF6',
                    'on-secondary': '#3F2436',
                    'on-surface': '#3F2436',
                    outline: '#D8D9E5',
                    accent: '#A0A2BB',
                    success: '#2F7D5B',
                    warning: '#B96D12',
                    error: '#B04455',
                    info: '#A0A2BB',
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
