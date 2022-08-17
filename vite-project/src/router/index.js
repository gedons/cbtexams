import { createWebHistory } from "vue-router";
import { createRouter } from "vue-router";
import Dashboard from "../views/Dashboard.vue";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import ExamPublicView from '../views/ExamPublicView.vue';
import NotFound from "../views/NotFound.vue";
import Exams from "../views/Exams.vue";
import Users from '../views/Users.vue';
import ExamView from "../views/ExamView.vue";
import store from "../store";
import AuthLayout from "../components/AuthLayout.vue";
import DefaultLayout from "../components/DefaultLayout.vue";



const routes = [
    {
        path: "/",
        redirect: "/dashboard",
        component: DefaultLayout,
        meta: { requiresAuth: true },
        children: [
            { path: '/dashboard', name: 'Dashboard', component: Dashboard },
            { path: '/exams', name: 'Exams', component: Exams },
            { path: '/users', name: 'Users', component: Users },
            // create and editupdate has one view ExamView
            {path: '/exams/create', name: 'ExamCreate', component: ExamView},
            {path: '/exams/:id', name: 'ExamView', component: ExamView},
          ]
    },
    {
        path: '/view/exam/:slug',
        name: 'ExamPublicView',
        component: ExamPublicView
    },
            {
                path: '/auth',
                redirect: '/login',
                name: 'Auth',
                component: AuthLayout,
                meta: {isGuest: true},
                children: [
                    {
                        path: "/login",
                        name: "Login",
                        component: Login
                    },
                    {
                        path: "/register",
                        name: "Register",
                        component: Register
                    }
                ],

            },
            {
                path: '/access_denied',
                name: 'NotFound',
                component: NotFound
            }

];

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth && !store.state.user.token) {
        next({ name: 'Login' })
    } else if (store.state.user.token && (to.meta.isGuest)) {
        next({ name: 'Dashboard' });
    } else {
        next();
    }
})

export default router;  