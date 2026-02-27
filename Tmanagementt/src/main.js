import { createApp } from 'vue'

import { createPinia } from 'pinia'
import { createRouter, createWebHistory } from 'vue-router'

import App from './App.vue'
import HomeView from './views/HomeView.vue'
import './style.css'   // <-- THIS LINE IS CRITICAL


const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: HomeView }
  ]
})

createApp(App)
  .use(createPinia())
  .use(router)
  .mount('#app')
