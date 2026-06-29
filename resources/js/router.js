import { createRouter, createWebHistory } from 'vue-router';

// Import your Vue components
import HomePage from './components/HomePage.vue';
import AboutUs from './components/AboutUs.vue';
import Login from './components/Login.vue';
import Register from './components/Register.vue';
import Order from '@/components/Order.vue';
import Checkout from '@/components/Checkout.vue';
import Delivery from '@/components/Delivery.vue'; // Import the Delivery component
import MyProfile from './components/Profile/MyProfile.vue';
import ProfileSettings from './components/Profile/ProfileSettings.vue';
import MyProducts from './components/Profile/MyProducts.vue';

import axios from 'axios';

axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomePage,
  },
  {
    path: '/about-us',
    name: 'AboutUs',
    component: AboutUs,
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
  },
  {
    path: '/order',
    name: 'Order',
    component: Order,
  },
  {
    path: '/checkout',
    name: 'Checkout',
    component: Checkout,
  },
  {
    path: '/delivery', // Add the new route for delivery
    name: 'Delivery',
    component: Delivery, // Associate the Delivery component with the route
  },
  {
    path: '/profile',
    name: 'profile',
    component: MyProfile,
    meta: { requiresAuth: true }
  },
  {
    path: '/profile-settings',
    name: 'profile-settings',
    component: ProfileSettings,
    meta: { requiresAuth: true }
  },
  {
    path: '/my-products',
    name: 'my-products',
    component: MyProducts,
    meta: { requiresAuth: true }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
