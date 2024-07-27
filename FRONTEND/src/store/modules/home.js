import HomeService from '@/services/home.service';

const state = {
  users: [],
  menus: [],
  badges: [],
  weekSchedules: [],
};

const getters = {
  users: (state) => state.users,
  menus: state => state.menus,
  badges: (state) => state.badges,
  weekSchedules: (state) => state.weekSchedules,
};

const actions = {
  fetchUsers({ commit }) { 
    return HomeService.getUsers()
      .then((response) => {
        console.log('users: ', response.data); 
        commit('SET_USERS', response.data);
      })
      .catch((error) => {
        console.error('Error fetching users:', error);
        throw error;
      });
  },

  fetchMenus({ commit }) {
    return HomeService.getMenus()
      .then(response => {
        console.log('Menus:', response.data)
        commit('SET_MENUS', response.data)
      })
      .catch(error => {
        console.error('Error fetching menus:', error)
        throw error
      })
  },

  fetchBadges({ commit }) {
    return HomeService.getBadges()
      .then((response) => {
        console.log('badges: ', response.data);
        commit('SET_BADGES', response.data);
      })
      .catch((error) => {
        console.error('Error fetching badges:', error);
        throw error;
      });
  },

  fetchWeekSchedules({ commit }) {
    return HomeService.getWeekSchedules()
      .then((response) => {
        console.log('weekSchedules', response.data)
        commit('SET_WEEK_SCHEDULES', response.data)
      })
      .catch((error) => {
        console.error('Error fetching week schedules:', error)
        throw error
      })
  },
};

const mutations = {
  SET_USERS(state, users) {
    state.users = users;
  },
  SET_MENUS(state, menus) {
    state.menus = menus
  },
  SET_BADGES(state, badges) {
    state.badges = badges;
  },
  SET_WEEK_SCHEDULES(state, weekSchedules) {
    state.weekSchedules = weekSchedules
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
