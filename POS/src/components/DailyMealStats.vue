<template>
  <div class="daily-meal-stats min-h-screen p-8">
    <h2 class="text-4xl font-bold mb-8 text-center text-gray-100">Statistiques des Repas Quotidiens</h2>
    
    <div v-if="isLoading" class="flex justify-center items-center h-64">
      <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-indigo-500"></div>
    </div>

    <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
      <strong class="font-bold">Erreur!</strong>
      <span class="block sm:inline">{{ error }}</span>
    </div>

    <div v-else>
      <div v-for="meal in dailyMeals" :key="meal.id" class="meal-card mb-12 bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6 bg-indigo-600 text-white">
          <h3><span class="text-3xl font-semibold mb-2">{{ meal.name }}:</span></h3>
          <p class="text-lg">({{ formatTime(meal.start_time) }} - {{ formatTime(meal.end_time) }}) {{ meal.price }} DH</p>
        </div>
        
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <StatCard title="Collaborateurs" :value="meal.attendee_count" icon="users" />
            <StatCard title="Revenu Total" :value="formatCurrency(meal.total_revenue)" icon="cash" />
            <StatCard title="Total Remisé" :value="formatCurrency(meal.total_discounted)" icon="tag" />
          </div>
          
          <ExpandableSection title="Détails des Collaborateurs">
            <div class="mb-4">
              <input v-model="searchQuery" placeholder="Rechercher par nom ou numéro de matricule" class="w-full p-2 border rounded">
            </div>
            <div class="overflow-x-auto">
              <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                  <tr>
                    <th class="py-2 px-4 text-left">Nom</th>
                    <th class="py-2 px-4 text-left">Numéro de Matricule</th>
                    <th class="py-2 px-4 text-left">Catégorie</th>
                    <th class="py-2 px-4 text-left">Remise</th>
                    <th class="py-2 px-4 text-left">Prix Remisé</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="attendee in paginatedAttendees" :key="attendee.id" class="border-b">
                    <td class="py-2 px-4">{{ attendee.name }}</td>
                    <td class="py-2 px-4">{{ attendee.matriculation_number }}</td>
                    <td class="py-2 px-4">{{ attendee.category_name }}</td>
                    <td class="py-2 px-4">{{ attendee.discount }} DH</td>
                    <td class="py-2 px-4">{{ formatCurrency(attendee.discounted_price) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="mt-4 flex justify-center space-x-2">
              <button 
                v-for="page in totalPages" 
                :key="page" 
                @click="currentPage = page" 
                :class="{ 'bg-indigo-500 text-white': currentPage === page }"
                class="px-3 py-1 rounded border"
              >
                {{ page }}
              </button>
            </div>
          </ExpandableSection>
          
          <ExpandableSection title="Répartition Financière">
            <div class="flex justify-center">
              <PieChart :data="getChartData(meal)" />
            </div>
          </ExpandableSection>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue';
import { useStore } from 'vuex';
import StatCard from './StatCard.vue';
import ExpandableSection from './ExpandableSection.vue';
import PieChart from './PieChart.vue';

export default {
  components: { StatCard, ExpandableSection, PieChart },
  setup() {
    const store = useStore();

    onMounted(() => {
      store.dispatch('mealStats/fetchDailyMealStats');
    });

    const dailyMeals = computed(() => store.getters['mealStats/getDailyMeals']);
    const isLoading = computed(() => store.getters['mealStats/getIsLoading']);
    const error = computed(() => store.getters['mealStats/getError']);

    const searchQuery = ref('');
    const currentPage = ref(1);
    const itemsPerPage = 10;

    const filteredAttendees = computed(() => {
      if (!dailyMeals.value || dailyMeals.value.length === 0) return [];
      return dailyMeals.value[0].attendees.filter(attendee => 
        attendee.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        attendee.matriculation_number.includes(searchQuery.value)
      );
    });

    const paginatedAttendees = computed(() => {
      const start = (currentPage.value - 1) * itemsPerPage;
      const end = start + itemsPerPage;
      return filteredAttendees.value.slice(start, end);
    });
    watch(paginatedAttendees, (newValue) => {
  console.log('Paginated Attendees:', newValue);
}, { immediate: true });

    const totalPages = computed(() => 
      Math.ceil(filteredAttendees.value.length / itemsPerPage)
    );

    const formatCurrency = (value) => {
      return new Intl.NumberFormat('fr-MA', { style: 'currency', currency: 'MAD' }).format(value);
    };

    const formatTime = (time) => {
      return new Date(`2000-01-01T${time}`).toLocaleTimeString('fr-MA', { hour: '2-digit', minute: '2-digit' });
    };

    const getChartData = (meal) => {
      const discountAmount = meal.total_revenue - meal.total_discounted;
      return [
        { name: 'Revenu', value: meal.total_discounted },
        { name: 'Remises', value: discountAmount },
      ];
    };

    return {
      dailyMeals,
      isLoading,
      error,
      formatCurrency,
      formatTime,
      getChartData,
      searchQuery,
      currentPage,
      paginatedAttendees,
      totalPages,
    };
  }
}
</script>
