<template>
    <section class="features-section" :class="{ 'dark': isDarkMode }">
      <h2 class="section-title">Fonctionnalités Clés</h2>
      <div class="features-grid">
        <div class="feature-card" v-for="feature in features" :key="feature.title">
          <div class="icon-wrapper">
            <font-awesome-icon :icon="feature.icon" />
          </div>
          <h3>{{ feature.title }}</h3>
          <p>{{ feature.description }}</p>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  import { computed } from 'vue';
  import { useStore } from 'vuex';
  
  export default {
    name: 'Descriptif',
    setup() {
      const store = useStore();
      const isDarkMode = computed(() => store.state.darkMode);
  
      const features = [
        {
          icon: 'users',
          title: 'Gestion des Utilisateurs',
          description: 'Gérez efficacement le personnel, les étudiants et les autres utilisateurs avec un contrôle d\'accès basé sur les rôles.'
        },
        {
          icon: 'utensils',
          title: 'Planification des Menus',
          description: 'Créez et gérez des menus variés avec des composants alimentaires détaillés et des informations nutritionnelles.'
        },
        {
          icon: 'id-card',
          title: 'Système de Badge RFID',
          description: 'Mettez en place un contrôle d\'accès sécurisé avec des badges RFID assignables pour des opérations de cantine fluides.'
        },
        {
          icon: 'calendar',
          title: 'Planification Hebdomadaire',
          description: 'Planifiez et organisez les repas pour toute la semaine avec des modes de planification flexibles.'
        },
        {
          icon: 'chart-bar',
          title: 'Tableau de Bord Analytique',
          description: 'Obtenez des insights sur les opérations de la cantine avec une visualisation et des rapports de données complets.'
        },
        {
          icon: 'cogs',
          title: 'Configuration du Système',
          description: 'Personnalisez et configurez le système pour répondre à vos besoins spécifiques de gestion de cantine.'
        }
      ];
  
      return {
        isDarkMode,
        features
      };
    }
  };
  </script>
  
  
  <style scoped>
  .features-section {
    @apply py-16 px-4 transition-colors duration-300;
  }
  
  .features-section.dark {
    @apply bg-gray-900 text-white;
  }
  
  .section-title {
    @apply text-3xl font-bold text-center mb-12 text-indigo-600 dark:text-indigo-400;
  }
  
  .features-grid {
    @apply grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto;
  }
  
  .feature-card {
    @apply bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-all duration-300 hover:shadow-lg hover:scale-105;
  }
  
  .icon-wrapper {
    @apply text-4xl mb-4 text-green-500 dark:text-green-400;
  }
  
  .feature-card h3 {
    @apply text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200;
  }
  
  .feature-card p {
    @apply text-gray-600 dark:text-gray-400;
  }
  
  @media (max-width: 640px) {
    .features-grid {
      @apply grid-cols-1;
    }
  }
  </style>
  