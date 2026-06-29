import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import 'font-awesome/css/font-awesome.min.css';
import axios from 'axios';

axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
axios.defaults.baseURL = '/';

router.beforeEach((to, from, next) => {
  const publicPages = ['/login', '/register'];
  const authRequired = !publicPages.includes(to.path);
  const loggedIn = localStorage.getItem('user');

  if (authRequired && !loggedIn) {
    return next('/login');
  }

  next();
});

const app = createApp(App);
app.use(router);

app.config.globalProperties.$axios = axios;

app.mount('#app');
