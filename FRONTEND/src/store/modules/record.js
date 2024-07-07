import RecordService from '@/services/record.service';

const state = {
  years: [],
  months: [],
  days: [],
  records: [],
  selectedYear: null,
  selectedMonth: null,
  selectedDay: null,
  monthlyTotals: [],
};

const getters = {
  years: (state) => state.years,
  months: (state) => state.months,
  days: (state) => state.days,
  records: (state) => state.records,
  selectedYear: (state) => state.selectedYear,
  selectedMonth: (state) => state.selectedMonth,
  selectedDay: (state) => state.selectedDay,
  monthlyTotals: (state) => state.monthlyTotals,
};

const actions = {
  async fetchYears({ commit }) {
    try {
      const response = await RecordService.getYears();
      commit('SET_YEARS', response.data);
    } catch (error) {
      console.error('Error fetching years:', error);
      throw error;
    }
  },

  async fetchMonths({ commit, state }) {
    if (!state.selectedYear) return;
    try {
      const response = await RecordService.getMonths(state.selectedYear);
      commit('SET_MONTHS', response.data);
    } catch (error) {
      console.error('Error fetching months:', error);
      throw error;
    }
  },

  async fetchDays({ commit, state }) {
    if (!state.selectedYear || !state.selectedMonth) return;
    try {
      const response = await RecordService.getDays(state.selectedYear, state.selectedMonth);
      commit('SET_DAYS', response.data);
    } catch (error) {
      console.error('Error fetching days:', error);
      throw error;
    }
  },

  async fetchDayRecords({ commit, state }) {
    if (!state.selectedYear || !state.selectedMonth || !state.selectedDay) return;
    try {
      const response = await RecordService.getDayRecords(state.selectedYear, state.selectedMonth, state.selectedDay);
      console.log('response:', response);
      commit('SET_RECORDS_AND_TOTALS', response.data);
    } catch (error) {
      console.error('Error fetching day records:', error);
      throw error;
    }
  },

  setSelectedYear({ commit }, year) {
    commit('SET_SELECTED_YEAR', year);
  },

  setSelectedMonth({ commit }, month) {
    commit('SET_SELECTED_MONTH', month);
  },

  setSelectedDay({ commit }, day) {
    commit('SET_SELECTED_DAY', day);
  },

  async fetchMonthlyTotals({ commit, state }) {
    if (!state.selectedYear || !state.selectedMonth) return;
    try {
      const response = await RecordService.getMonthlyTotals(state.selectedYear, state.selectedMonth);
      commit('SET_MONTHLY_TOTALS', response.data.monthlyTotals);
    } catch (error) {
      console.error('Error fetching monthly totals:', error);
      throw error;
    }
  },
  
};

const mutations = {
  SET_YEARS(state, years) {
    state.years = years;
  },
  SET_MONTHS(state, months) {
    state.months = months;
  },
  SET_DAYS(state, days) {
    state.days = days;
  },
  SET_RECORDS(state, records) {
    state.records = records;
  },
  SET_SELECTED_YEAR(state, year) {
    state.selectedYear = year;
  },
  SET_SELECTED_MONTH(state, month) {
    state.selectedMonth = month;
  },
  SET_SELECTED_DAY(state, day) {
    state.selectedDay = day;
  },
  SET_RECORDS_AND_TOTALS(state, { records, monthlyTotals }) {
    state.records = records;
    state.monthlyTotals = monthlyTotals;
  },
  SET_MONTHLY_TOTALS(state, monthlyTotals) {
    state.monthlyTotals = monthlyTotals;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
}; 
