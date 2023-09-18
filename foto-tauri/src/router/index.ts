import { createRouter, createWebHistory, createWebHashHistory } from 'vue-router'
import HomePage from '../views/pages/HomePage.vue'

const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomePage
    },
  ]
})

export default router