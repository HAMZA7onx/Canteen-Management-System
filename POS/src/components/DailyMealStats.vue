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

    <div v-else-if="hasMealToday" class="bg-blue-100 border border-blue-400 text-blue-700 px-6 py-4 rounded-lg shadow-md mb-8 flex items-center" role="alert">
      <svg class="w-6 h-6 mr-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v4a1 1 0 102 0V7zm-1 8a1 1 0 100-2 1 1 0 000 2z"/>
      </svg>
      <div>
        <p class="font-bold text-lg mb-1">Information</p>
        <p class="text-sm">Il n'y a pas de repas assigné pour aujourd'hui.</p>
      </div>
    </div>

    <div v-else>
      <button @click="printTicket" class="mb-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
        Imprimer statistiques
      </button>
      <div v-for="meal in dailyMeals" :key="meal.id" class="meal-card mb-12 bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6 bg-indigo-600 text-white">
          <h3><span class="text-3xl font-semibold mb-2">{{ meal.name }}:</span></h3>
          <p class="text-lg">({{ formatTime(meal.start_time) }} - {{ formatTime(meal.end_time) }}) {{ meal.price }} DH</p>
        </div>
       
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <StatCard title="Collaborateurs" :value="meal.attendee_count" icon="users" />
            <StatCard title="Revenu Total" :value="formatCurrency(calculateTotalRevenue(meal))" icon="cash" />
            <StatCard title="Total Remisé" :value="formatCurrency(calculateTotalDiscounted(meal))" icon="tag" />
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
                  <tr v-for="attendee in paginatedAttendees(meal)" :key="attendee.id" class="border-b">
                    <td class="py-2 px-4">{{ attendee.name }}</td>
                    <td class="py-2 px-4">{{ attendee.matriculation_number }}</td>
                    <td class="py-2 px-4">{{ attendee.category_name }}</td>
                    <td class="py-2 px-4">{{ formatCurrency(attendee.discount) }}</td>
                    <td class="py-2 px-4">{{ formatCurrency(calculateDiscountedPrice(meal, attendee)) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="mt-4 flex justify-center space-x-2">
              <button
                v-for="page in totalPages(meal)"
                :key="page"
                @click="currentPage[meal.id] = page"
                :class="{ 'bg-indigo-500 text-white': currentPage[meal.id] === page }"
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
            <div class="mt-4 grid grid-cols-2 gap-4">
              <div class="text-center">
                <p class="font-bold">Total Revenu</p>
                <p>{{ formatCurrency(calculateTotalRevenue(meal)) }}</p>
              </div>
              <div class="text-center">
                <p class="font-bold">Total Remises</p>
                <p>{{ formatCurrency(calculateTotalDiscounted(meal)) }}</p>
              </div>
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
    const currentPage = ref({});
    const itemsPerPage = 10;

    const filteredAttendees = (meal) => {
      return meal.attendees.filter(attendee =>
        attendee.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        attendee.matriculation_number.includes(searchQuery.value)
      );
    };

    const paginatedAttendees = (meal) => {
      const filtered = filteredAttendees(meal);
      const start = ((currentPage.value[meal.id] || 1) - 1) * itemsPerPage;
      const end = start + itemsPerPage;
      return filtered.slice(start, end);
    };

    const totalPages = (meal) => {
      const filtered = filteredAttendees(meal);
      return Math.ceil(filtered.length / itemsPerPage);
    };

    const formatCurrency = (value) => {
      return new Intl.NumberFormat('fr-MA', { style: 'currency', currency: 'MAD' }).format(value);
    };

    const formatTime = (time) => {
      return new Date(`2000-01-01T${time}`).toLocaleTimeString('fr-MA', { hour: '2-digit', minute: '2-digit' });
    };

    const calculateDiscountedPrice = (meal, attendee) => {
      const originalPrice = parseFloat(meal.price);
      const discount = parseFloat(attendee.discount);
      return originalPrice - discount;
    };

    const calculateTotalDiscounted = (meal) => {
      return meal.attendees.reduce((total, attendee) => {
        return total + calculateDiscountedPrice(meal, attendee);
      }, 0);
    };

    const calculateTotalRevenue = (meal) => {
      return meal.attendees.length * parseFloat(meal.price);
    };

    const calculateTotalDiscounts = (meal) => {
      return meal.attendees.reduce((total, attendee) => {
        return total + parseFloat(attendee.discount);
      }, 0);
    };

    const getChartData = (meal) => {
      const totalRevenue = calculateTotalRevenue(meal);
      const totalDiscounted = calculateTotalDiscounted(meal);
      console.log('totalRevenue:', totalRevenue);
      console.log('totalDiscounted:', totalDiscounted);
      return [
        { name: 'Revenu Total', value: totalRevenue },
        { name: 'Total Remisé', value: totalDiscounted },
      ];
    };

    const printTicket = async () => {
      if (process.env.NODE_ENV === 'development') {
        console.log('Printing ticket in development mode:');
        dailyMeals.value.forEach(meal => {
          console.log(`Meal: ${meal.name}`);
          console.log(`Time: ${formatTime(meal.start_time)} - ${formatTime(meal.end_time)}`);
          console.log(`Attendees: ${meal.attendee_count}`);
          console.log(`Total Revenue: ${formatCurrency(calculateTotalRevenue(meal))}`);
          console.log(`Total Discounted: ${formatCurrency(calculateTotalDiscounted(meal))}`);
          console.log('-'.repeat(32));
        });
        console.log('Merci!');
        console.log(new Date().toLocaleString());
      } else {
        try {
          const devices = await navigator.usb.getDevices();
          const printerDevices = devices.filter(device => 
            device.configurations[0].interfaces.some(intf => 
              intf.alternates[0].interfaceClass === 0x07
            )
          );

          let printer;
          if (printerDevices.length === 0) {
            printer = await navigator.usb.requestDevice({ filters: [] });
          } else if (printerDevices.length === 1) {
            printer = printerDevices[0];
          } else {
            printer = printerDevices[0];
          }

          await printer.open();
          let ticketData = "\x1B\x40";
          ticketData += "\x1B\x61\x01";
          ticketData += "\x1B\x21\x30";
          ticketData += "Daily Meal Statistics\n\n";
          ticketData += "\x1B\x21\x00";

          dailyMeals.value.forEach(meal => {
            ticketData += "\x1B\x45\x01";
            ticketData += `${meal.name}\n`;
            ticketData += "\x1B\x45\x00";
            ticketData += `Time: ${formatTime(meal.start_time)} - ${formatTime(meal.end_time)}\n`;
            ticketData += `Attendees: ${meal.attendee_count}\n`;
            ticketData += `Total Revenue: ${formatCurrency(calculateTotalRevenue(meal))}\n`;
            ticketData += `Total Discounted: ${formatCurrency(calculateTotalDiscounted(meal))}\n`;
            ticketData += "\x1B\x61\x00";
            ticketData += "-".repeat(32) + "\n";
            ticketData += "\x1B\x61\x01";
          });

          ticketData += "\n\x1B\x61\x00";
          ticketData += "Thank you for your business!\n";
          ticketData += "\x1B\x61\x01";
          ticketData += new Date().toLocaleString() + "\n";
          ticketData += "\x1B\x64\x02";
          ticketData += "\x1D\x56\x00";

          await printer.transferOut(1, new TextEncoder().encode(ticketData));
          await printer.close();
        } catch (error) {
          console.error('Printing failed:', error);
        }
      }
    };

    watch(dailyMeals, (newValue) => {
      console.log('dailyMeals:', newValue);
    }, { immediate: true });

    const hasMealToday = computed(() => dailyMeals.value.length === 0);

    return {
      dailyMeals,
      isLoading,
      error,
      hasMealToday,
      formatCurrency,
      formatTime,
      getChartData,
      searchQuery,
      currentPage,
      paginatedAttendees,
      totalPages,
      calculateDiscountedPrice,
      calculateTotalDiscounted,
      calculateTotalRevenue,
      calculateTotalDiscounts,
      printTicket,
    };
  }
}
</script>
