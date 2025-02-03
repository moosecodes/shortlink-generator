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
                    primary: "#fe2c55",
                    primaryAppBar: "rgba(44, 44, 44, .8)",
                    primaryCard: "rgba(44, 44, 44, .8)",
                    primaryDisabledCard: "rgba(44, 44, 44, .5)",
                    secondary: colors.blue.lighten5,
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
