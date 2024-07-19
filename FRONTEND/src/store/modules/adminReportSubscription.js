import adminReportSubscriptionService from '@/services/adminReportSubscription.service'

export default {
  namespaced: true,
  state: {
    subscriptions: [],
    admins: [],
    loading: false,
    error: null
  },
  mutations: {
    SET_SUBSCRIPTIONS(state, subscriptions) {
      state.subscriptions = subscriptions
    },
    SET_ADMINS(state, admins) {
      state.admins = admins
    },
    SET_LOADING(state, loading) {
      state.loading = loading
    },
    SET_ERROR(state, error) {
      state.error = error
    }
  },
  actions: {
    async fetchSubscriptions({ commit }) {
      commit('SET_LOADING', true)
      try {
        const subscriptions = await adminReportSubscriptionService.getSubscriptions()
        console.log('SUBSCRIPTIONS : ', subscriptions)
        commit('SET_SUBSCRIPTIONS', subscriptions.data)
      } catch (error) {
        commit('SET_ERROR', error.message)
      } finally {
        commit('SET_LOADING', false)
      }
    },
    async fetchAdmins({ commit }) {
      commit('SET_LOADING', true)
      try {
        const admins = await adminReportSubscriptionService.getAdmins()
        commit('SET_ADMINS', admins.data)
      } catch (error) {
        commit('SET_ERROR', error.message)
      } finally {
        commit('SET_LOADING', false)
      }
    },
    async addSubscription({ dispatch }, subscription) {
      try {
        await adminReportSubscriptionService.addSubscription(subscription)
        dispatch('fetchSubscriptions')
      } catch (error) {
        commit('SET_ERROR', error.message)
      }
    },
    async updateSubscription({ dispatch }, { id, subscription }) {
      try {
        await adminReportSubscriptionService.updateSubscription(id, subscription)
        dispatch('fetchSubscriptions')
      } catch (error) {
        commit('SET_ERROR', error.message)
      }
    },
    async deleteSubscription({ dispatch }, id) {
      try {
        await adminReportSubscriptionService.deleteSubscription(id)
        dispatch('fetchSubscriptions')
      } catch (error) {
        commit('SET_ERROR', error.message)
      }
    }
  },
  getters: {
    subscriptions: state => state.subscriptions,
    admins: state => state.admins,
    loading: state => state.loading,
    error: state => state.error,
    availableAdmins: (state) => {
      const subscribedAdminIds = state.subscriptions.map(sub => sub.admin_id);
      return state.admins.filter(admin => !subscribedAdminIds.includes(admin.id));
    }
  }
}
