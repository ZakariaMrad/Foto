import { createRouter, createWebHashHistory } from 'vue-router'

const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
        path: '/',
        name: 'home',
        component: () => import('../views/pages/HomePage.vue'),
    },
    {
        path: '/upload',
        name: 'upload',
        component: () => import('../views/pages/UploadPage.vue')
    },
    {
        path:'/profil',
        name:'profil',
        component: () => import('../views/pages/ProfilePage.vue')
    }
  ]
})

export default router;
