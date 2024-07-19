<template>
  <div class="home-page" :class="{ 'dark': isDarkMode }">
    <header class="header">
      <h1 class="title">Tableau de bord de gestion de cantine</h1>
      <p class="subtitle">Rationaliser les opérations de votre cantine</p>
    </header>
     
    <div class="dashboard-grid">
      <div class="dashboard-card">
        <h2><font-awesome-icon icon="users" /> Répartition des utilisateurs</h2>
        <canvas ref="userChart"></canvas>
      </div>

      <div class="dashboard-card">
        <h2><font-awesome-icon icon="utensils" /> Aperçu des menus</h2>
        <canvas ref="menuChart"></canvas>
      </div>

      <div class="dashboard-card">
        <h2><font-awesome-icon icon="id-card" /> Statut du badge RFID</h2>
        <canvas ref="badgeChart"></canvas>
      </div>

      <div class="dashboard-card">
        <h2><font-awesome-icon icon="calendar" /> Programme de repas hebdomadaire</h2>
        <canvas ref="scheduleChart"></canvas>
      </div>
    </div>
    <Descriptive />
    <!-- <section class="system-overview">
      <h2>System Overview</h2>
      <div class="stats-grid">
        <div class="stat-card">
          <font-awesome-icon icon="user" />
          <h3>Total Users</h3>
          <p>{{ totalUsers }}</p>
        </div>
        <div class="stat-card">
          <font-awesome-icon icon="utensils" />
          <h3>Active Menus</h3>
          <p>{{ activeMenus }}</p>
        </div>
        <div class="stat-card">
          <font-awesome-icon icon="id-badge" />
          <h3>Assigned Badges</h3>
          <p>{{ assignedBadges }}</p>
        </div>
        <div class="stat-card">
          <font-awesome-icon icon="calendar-check" />
          <h3>Weekly Schedules</h3>
          <p>{{ weeklySchedules }}</p>
        </div>
      </div>
    </section> -->
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import { useStore } from 'vuex';
import Chart from 'chart.js/auto';
import Descriptive from './Descriptive.vue';

export default {
  name: 'HomePage',
  components: {
    Descriptive
  },
  setup() {
    const store = useStore();
    const isDarkMode = computed(() => store.state.darkMode);

    const userChart = ref(null);
    const menuChart = ref(null);
    const badgeChart = ref(null);
    const scheduleChart = ref(null);

    const totalUsers = ref(0);
    const activeMenus = ref(0);
    const assignedBadges = ref(0);
    const weeklySchedules = ref(0);

    onMounted(async () => {
      await Promise.all([
        store.dispatch('user/fetchUsers'),
        store.dispatch('menu/fetchMenus'),
        store.dispatch('badge/fetchBadges'),
        store.dispatch('weekSchedule/fetchWeekSchedules')
      ]);

      const userData = store.state.user.users;
      const menuData = store.state.menu.menus;
      const badgeData = store.state.badge.badges;
      const weekScheduleData = store.state.weekSchedule.weekSchedules;

      totalUsers.value = userData.length;
      activeMenus.value = menuData.length;
      assignedBadges.value = badgeData.filter(b => b.status === 'assigned').length;
      weeklySchedules.value = weekScheduleData.length;

      createCharts(userData, menuData, badgeData, weekScheduleData);
    });

    const createCharts = (userData, menuData, badgeData, weekScheduleData) => {
      new Chart(userChart.value, {
        type: 'doughnut',
        data: {
          labels: ['Staff', 'Students', 'Others'],
          datasets: [{
            data: [
              userData.filter(u => u.category_id === 4).length,
              userData.filter(u => u.category_id === 7).length,
              userData.filter(u => ![4, 7].includes(u.category_id)).length
            ],
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'bottom',
            },
            title: {
              display: true,
              text: 'User Categories'
            }
          }
        }
      });

      new Chart(menuChart.value, {
        type: 'bar',
        data: {
          labels: menuData.map(m => m.name),
          datasets: [{
            label: 'Food Components',
            data: menuData.map(m => m.food_composants.length),
            backgroundColor: '#4BC0C0'
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            title: {
              display: true,
              text: 'Menu Composition'
            }
          }
        }
      });

      new Chart(badgeChart.value, {
        type: 'pie',
        data: {
          labels: ['Assigned', 'Unassigned'],
          datasets: [{
            data: [
              badgeData.filter(b => b.status === 'assigned').length,
              badgeData.filter(b => b.status !== 'assigned').length
            ],
            backgroundColor: ['#FF9F40', '#4BC0C0']
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'bottom',
            },
            title: {
              display: true,
              text: 'RFID Badge Assignment'
            }
          }
        }
      });

      new Chart(scheduleChart.value, {
        type: 'line',
        data: {
          labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
          datasets: weekScheduleData.map((schedule, index) => ({
            label: schedule.mode_name,
            data: [
              schedule.monday_daily_meals.length,
              schedule.tuesday_daily_meals.length,
              schedule.wednesday_daily_meals.length,
              schedule.thursday_daily_meals.length,
              schedule.friday_daily_meals.length,
              schedule.saturday_daily_meals.length,
              schedule.sunday_daily_meals.length
            ],
            borderColor: `hsl(${index * 60}, 70%, 50%)`,
            fill: false
          }))
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            title: {
              display: true,
              text: 'Weekly Meal Distribution'
            }
          }
        }
      });
    };

    return {
      isDarkMode,
      userChart,
      menuChart,
      badgeChart,
      scheduleChart,
      totalUsers,
      activeMenus,
      assignedBadges,
      weeklySchedules,
    };
  }
};
// comment
</script>

<style scoped>
.home-page {
  @apply min-h-screen p-8 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 text-gray-800 dark:text-gray-100 transition-colors duration-300;
}

.header {
  @apply text-center mb-12;
}

.title {
  @apply text-4xl font-bold mb-2 text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600 dark:from-purple-400 dark:to-indigo-400;
}

.subtitle {
  @apply text-xl text-gray-600 dark:text-gray-400;
}

.dashboard-grid {
  @apply grid grid-cols-1 md:grid-cols-2 gap-8 mb-12;
}

.dashboard-card {
  @apply bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 transition-all duration-300 hover:shadow-xl;
}

.dashboard-card h2 {
  @apply text-2xl font-semibold mb-4 flex items-center text-indigo-600 dark:text-indigo-400;
}

.dashboard-card h2 svg {
  @apply mr-2;
}

.system-overview {
  @apply mb-12;
}

.system-overview h2 {
  @apply text-3xl font-bold mb-6 text-center text-indigo-600 dark:text-indigo-400;
}

.stats-grid {
  @apply grid grid-cols-2 md:grid-cols-4 gap-8;
}

.stat-card {
  @apply bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center transition-all duration-300 hover:shadow-lg hover:scale-105;
}

.stat-card svg {
  @apply text-4xl mb-2 text-indigo-500 dark:text-indigo-400;
}

.stat-card h3 {
  @apply text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300;
}

.stat-card p {
  @apply text-3xl font-bold text-indigo-600 dark:text-indigo-400;
}
</style>
