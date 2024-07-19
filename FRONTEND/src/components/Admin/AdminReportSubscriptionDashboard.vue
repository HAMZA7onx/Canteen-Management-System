<template>
  <div class="admin-report-subscription-dashboard p-4 md:p-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Admin Report Subscriptions</h1>

    <!-- Add New Subscription Form -->
    <div class="mb-8 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
      <h2 class="text-2xl font-semibold mb-4 text-gray-700 dark:text-gray-200">Add New Subscription</h2>
      <form @submit.prevent="addSubscription" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="admin">Admin</label>
          <select v-model="newSubscription.admin_id" id="admin" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">Select an admin</option>
            <option v-for="admin in availableAdmins" :key="admin.id" :value="admin.id">
              {{ admin.name }}
            </option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="frequency">Frequency</label>
          <select v-model="newSubscription.frequency" id="frequency" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
          </select>
        </div>
        <div>
          <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Add Subscription
          </button>
        </div>
      </form>
    </div>

    <!-- Subscriptions List -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
      <h2 class="text-2xl font-semibold p-6 border-b border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200">Current Subscriptions</h2>
      <div v-if="loading" class="flex justify-center items-center h-32">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
      </div>
      <div v-else-if="subscriptions.length === 0" class="p-6 text-center text-gray-500 dark:text-gray-400">
        No subscriptions found.
      </div>
      <div v-else class="divide-y divide-gray-200 dark:divide-gray-700">
        <div v-for="subscription in subscriptions" :key="subscription.id" class="p-6 flex items-center justify-between flex-wrap sm:flex-nowrap">
          <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ getAdminName(subscription) }}</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ getAdminEmail(subscription) }}</p>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Frequency: {{ subscription.frequency }}</p>
          </div>
          <div class="mt-4 sm:mt-0 sm:ml-4 flex-shrink-0 flex space-x-2">
            <button @click="toggleSubscription(subscription)" :class="subscription.is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100'" class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium">
              {{ subscription.is_active ? 'Active' : 'Inactive' }}
            </button>
            <button @click="deleteSubscription(subscription.id)" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import { useStore } from 'vuex'

export default {
  name: 'AdminReportSubscriptionDashboard',
  setup() {
    const store = useStore()
    const newSubscription = ref({ admin_id: '', frequency: 'daily' })

    const subscriptions = computed(() => store.getters['adminReportSubscription/subscriptions'])
    const admins = computed(() => store.getters['adminReportSubscription/admins'])
    const loading = computed(() => store.getters['adminReportSubscription/loading'])
    const error = computed(() => store.getters['adminReportSubscription/error'])

    onMounted(() => {
      store.dispatch('adminReportSubscription/fetchSubscriptions')
      store.dispatch('adminReportSubscription/fetchAdmins')
    })
    const availableAdmins = computed(() => store.getters['adminReportSubscription/availableAdmins'])

    const addSubscription = async () => {
      const createdSubscription = await store.dispatch('adminReportSubscription/addSubscription', newSubscription.value)
      if (createdSubscription) {
        subscriptions.value.push(createdSubscription)
      }
      newSubscription.value = { admin_id: '', frequency: 'daily' }
    }

    const toggleSubscription = async (subscription) => {
      const updatedSubscription = await store.dispatch('adminReportSubscription/updateSubscription', {
        id: subscription.id,
        subscription: { ...subscription, is_active: !subscription.is_active }
      })
      if (updatedSubscription) {
        const index = subscriptions.value.findIndex(s => s.id === updatedSubscription.id)
        if (index !== -1) {
          subscriptions.value[index] = updatedSubscription
        }
      }
    }

    const deleteSubscription = async (id) => {
      if (confirm('Are you sure you want to delete this subscription?')) {
        const success = await store.dispatch('adminReportSubscription/deleteSubscription', id)
        if (success) {
          subscriptions.value = subscriptions.value.filter(s => s.id !== id)
        }
      }
    }

    const getAdminName = (subscription) => {
      const admin = admins.value.find(a => a.id === subscription.admin_id)
      return admin ? admin.name : 'Unknown Admin'
    }

    const getAdminEmail = (subscription) => {
      const admin = admins.value.find(a => a.id === subscription.admin_id)
      return admin ? admin.email : 'Email not available'
    }

    return {
      newSubscription,
      subscriptions,
      admins,
      loading,
      error,
      addSubscription,
      toggleSubscription,
      deleteSubscription,
      getAdminName,
      getAdminEmail,
      availableAdmins
    }
  }
}
</script>
