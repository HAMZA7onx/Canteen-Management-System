import { createI18n } from 'vue-i18n'

const messages = {
  en: {
    dashboard: 'DASHBOARD',
    profile: 'PROFILE',
    logout: 'Logout'
  },
  fr: {
    dashboard: 'TABLEAU DE BORD',
    profile: 'PROFIL',
    logout: 'Déconnexion'
  }
}

const i18n = createI18n({
  locale: 'en', // set locale
  fallbackLocale: 'en', // set fallback locale
  messages, // set locale messages
})

export default i18n
