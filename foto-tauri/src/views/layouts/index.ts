import { createRouter, createWebHashHistory } from 'vue-router'

const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../views/pages/HomePage.vue')
    },
    {
      path:'/login',
      name:'login',
      component: () => import('../views/pages/LoginPage.vue')
    }
  ]
})

export default router