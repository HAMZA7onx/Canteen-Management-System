import AuthService from '@/services/auth.service';

const state = {
    user: null,
    isLoggedIn: false,
    token: null,
    userRoles: [],
    userPermissions: [],
};

const getters = {
    isLoggedIn: (state) => state.isLoggedIn,
    user: (state) => state.user,
    token: (state) => state.token,
    userRoles: (state) => state.userRoles,
    userPermissions: (state) => state.userPermissions,
};

const actions = {
    async loginWithBadge({ commit }, { rfid }) {
        try {
            const response = await AuthService.loginWithBadge(rfid);
            commit('setAuthData', response.data.data);
            return Promise.resolve(response.data);
        } catch (error) {
            return Promise.reject(error);
        }
    },

    async logout({ commit }) {
        try {
            await AuthService.logout();
            commit('clearAuthData');
        } catch (error) {
            console.error(error);
        }
    },
};

const mutations = {
    setAuthData(state, data) {
        state.user = data.admin;
        state.token = data.token;
        state.isLoggedIn = true;
        state.userRoles = data.roles;
        state.userPermissions = data.permissions;
    },
    clearAuthData(state) {
        state.user = null;
        state.token = null;
        state.isLoggedIn = false;
        state.userRoles = [];
        state.userPermissions = [];
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};
