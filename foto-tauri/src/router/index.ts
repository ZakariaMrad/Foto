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
      path: '/profil',
      name: 'profil',
      component: () => import('../views/pages/ProfilePage.vue')
    },
    {
      path: '/otherUserProfile/:idAccount',
      name: 'otherUserProfile',
      component: () => import('../views/pages/OtherUserProfilePage.vue')
    },
    {
      path: '/friendsList',
      name: 'friendsList',
      component: () => import('../views/pages/FriendsListPage.vue')
    },
    {
       path:'/admin',
        name:'admin',
        component: () => import('../views/pages/AdminPage.vue')
    },
    {
      path:'/albums',
      name:'albums',
      component: () => import('../views/pages/AlbumsPage.vue')
    }
  ]

})


export default router;
