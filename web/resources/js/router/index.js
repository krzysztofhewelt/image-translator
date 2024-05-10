import { createRouter, createWebHistory } from 'vue-router';
import LoginForm from '@/views/Authenticate/LoginForm.vue';
import RegisterForm from '@/views/Authenticate/RegisterForm.vue';
import TranslationList from '@/views/Translations/TranslationList.vue';
import PassRoute from '@/components/PassRoute.vue';
import TranslationEdit from '@/views/Translations/TranslationEdit.vue';
import { loadToken, loadUser } from '@/utils/authentication.js';
import ErrorNotFound from '@/views/ErrorNotFound.vue';

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: LoginForm,
  },
  {
    path: '/register',
    name: 'Register',
    component: RegisterForm,
  },
  {
    path: '/',
    name: 'Home',
    component: TranslationList,
  },
  {
    path: '/translations',
    name: 'Translations',
    component: PassRoute,
    children: [
      {
        path: 'edit/:id',
        name: 'TranslationsEdit',
        component: TranslationEdit,
      }
    ],
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'Error404',
    component: ErrorNotFound,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// pages guard
router.beforeEach((to, from, next) => {
  const publicPages = ['/login', '/register'];
  const authRequired = !publicPages.includes(to.path);

  const loggedIn = loadToken();

  if (!loadUser()) return next('/login');
  if (authRequired && !loggedIn) return next('/login');

  next();
});

export default router;
