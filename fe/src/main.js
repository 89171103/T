import { createApp } from 'vue'
import {BootstrapVue3} from 'bootstrap-vue-3'
import App from './App.vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

const app1 = createApp(App)

app1.use(BootstrapVue3)

app1.mount('#app')