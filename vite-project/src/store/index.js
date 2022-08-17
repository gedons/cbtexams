import { createStore } from "vuex";
import axiosClient from "../axios";



const store = createStore({
    state: {
        user: {
            data: {

            },
            token: sessionStorage.getItem("TOKEN"),
        },
        dashboard:{
            loading: false,
            data: []
        },
        users:{
            loading: false,
            data:[]
        },
        currentExam: {
            loading: false,
            data: []
        },
        exams: {
            loading: false,
            links: [],
            data: []
        },
        questionTypes: ["text", "select", "radio", "checkbox", "textarea"],
        notification: {
            show: false,
            links: [],
            type: null,
            message: null
        },
    },
    getters: {},
    actions: {
        deleteUser({}, id){
            return axiosClient.delete(`/user/${id}`);
        },
        getUsers({commit},  {url = null} = {}){
            url = url || '/user'
            commit("setUserLoadings", true);
            return axiosClient
                .get(url)
                .then((res) => {
                    commit("setUsers", res.data);
                    commit("setUserLoadings", false);
                    return res;
                })
                .catch((err) => {
                    commit("setUserLoadings", false);
                    throw err;
                });
        },
        getDashboardData({commit}){
            commit('dashboardLoading', true)
            return axiosClient.get(`/dashboard`)
            .then((res) => {
                commit('dashboardLoading', false)
                commit('setDashboardData', res.data)
                return res;
            })
            .catch(error => {
                commit('dashboardLoading', false)
                return error;
            })
        },
        
        getExam({ commit }, id) {
            commit("setCurrentExamLoading", true);
            return axiosClient
                .get(`/exam/${id}`)
                .then((res) => {
                    commit("setCurrentExam", res.data);
                    commit("setCurrentExamLoading", false);
                    return res;
                })
                .catch((err) => {
                    commit("setCurrentExamLoading", false);
                    throw err;
                });
        },
        saveExam({ commit }, exam) {
            delete exam.image_url;
            let response;
            // update if id is present
            if (exam.id) {
                response = axiosClient
                    .put(`/exam/${exam.id}`, exam)
                    .then((res) => {
                        commit("setCurrentExam", res.data);
                        return res;
                    });
            }
            // create if id is not present
            else {
                response = axiosClient.post("/exam", exam).then((res) => {
                    commit("setCurrentExam", res.data);
                    return res;
                });
            }

            return response;
        },
        deleteExam({}, id){
            return axiosClient.delete(`/exam/${id}`);
        },
        getExams({commit}, {url = null} = {}){
            url = url || '/exam'
            commit("setExamsLoading", true);
            return axiosClient
                .get(url)
                .then((res) => {
                    commit("setExamsLoading", false);
                    commit("setExams", res.data);
                    return res;
                });
              
        },
        getExamBySlug({ commit }, slug) {
            commit("setCurrentExamLoading", true);
            return axiosClient.get(`/exam-by-slug/${slug}`)
                .then((res) => {
                    commit('setCurrentExam', res.data);
                    commit('setCurrentExamLoading', false);
                    return res;
                })
                .catch((err) => {
                    commit("setCurrentExamLoading", false);
                    throw err;
                });
        },
        saveExamAnswer({commit}, {examId, answers}){
            return axiosClient.post(`/exam/${examId}/answer`, {answers});
        },
        register({ commit }, user) {
            return axiosClient.post('/register', user)
                .then(({ data }) => {
                    commit('setUser', data);
                    return data
                })
        },

        login({ commit }, user) {
            return axiosClient.post('/login', user)
                .then(({ data }) => {
                    commit('setUser', data);
                    return data
                })
        },

        logout({ commit }) {
            return axiosClient.post('/logout')
                .then(({ response }) => {
                    commit('logout');
                    return response
                })
        },
    },
    mutations: {
        dashboardLoading: (state, loading) => {
            state.dashboard.loading = loading;
        },
        setDashboardData: (state, data) => {
            state.dashboard.data = data;
        },
        setExamsLoading: (state, loading) => {
            state.exams.loading = loading;
        },
        setExams: (state, exams) => {
          
            state.exams.links = exams.meta.links;
            state.exams.data = exams.data;
        },
        setUserLoadings: (state, loading) => {
            state.users.loading = loading;
        },
        setUsers: (state, users) => {
            state.users.data = users.data;
        },
        setCurrentExamLoading: (state, loading) => {
            state.currentExam.loading = loading;
        },
        setCurrentExam: (state, exam) => {
            state.currentExam.data = exam.data;
        },

        logout: (state) => {
            state.user.data = {};
            state.user.token = null;
        },
        setUser: (state, userData) => {
            state.user.token = userData.token,
                state.user.data = userData.user,
                sessionStorage.setItem('TOKEN', userData.token);
        },
        notify: (state, {message, type}) => {
            state.notification.show = true;
            state.notification.type = type;
            state.notification.message = message;
            setTimeout(() => {
                state.notification.show = false;
            }, 3000)
        }
    },
    modules: {}
})

export default store;