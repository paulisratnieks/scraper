import './bootstrap'
import './scss/main.css'
import { createApp } from 'vue'
import PrimeVue from 'primevue/config'
import Aura from '@primeuix/themes/aura'
import App from './App.vue'
import router from './router'
import ToastService from 'primevue/toastservice'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import { Form } from '@primevue/forms'
import Message from 'primevue/message'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Card from 'primevue/card'
import Menu from 'primevue/menu'
import Password from 'primevue/password'
import Toolbar from 'primevue/toolbar'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'

const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)

const app = createApp(App)

app.use(pinia)
app.use(router)
app.use(PrimeVue, {
    theme: {
        preset: Aura,
    },
})
app.use(ToastService)

app.component('InputText', InputText)
app.component('Button', Button)
app.component('Form', Form)
app.component('Message', Message)
app.component('DataTable', DataTable)
app.component('Column', Column)
app.component('Card', Card)
app.component('Menu', Menu)
app.component('Password', Password)
app.component('Toolbar', Toolbar)

app.mount('#app')
