import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import colors from 'vuetify/util/colors'
import { aliases, mdi } from 'vuetify/iconsets/mdi';
import '@mdi/font/css/materialdesignicons.css'

const vuetify = createVuetify({
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        },
    },
    theme: {
        defaultTheme: 'dark',
        themes: {
            light: {
                colors: {
                    primary: colors.blue.darken4,
                    secondary: colors.blueGrey.darken3,
                    accent: colors.blue.accent4,
                    error: colors.red.base,
                    info: colors.amber.base,
                    success: colors.green.base,
                    warning: colors.orange.base,
                },
            },
            dark: {
                colors: {
                    primary: colors.red.darken4,
                    secondary: colors.blueGrey.lighten5,
                    accent: colors.blue.accent1,
                    error: colors.red.base,
                    info: colors.amber.base,
                    success: colors.green.base,
                    warning: colors.orange.base,
                },
            },
        },
    },
});

export default vuetify;
