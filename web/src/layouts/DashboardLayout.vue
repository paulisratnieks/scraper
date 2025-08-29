<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth.ts'
import { useToast } from 'primevue'

const router = useRouter()
const toast = useToast()
const authStore = useAuthStore()

const onClickArticles = (): void => {
    router.push({ name: 'articles' })
}

const onClickLogout = (): void => {
    authStore
        .logout()
        .then(() => {
            toast.add({ severity: 'success', summary: 'Successful', detail: 'Logged Out', life: 3000 })
            router.push({ path: '/' })
        })
        .catch(() => {
            toast.add({ severity: 'error', summary: 'Failure', detail: 'Logging Out Failed', life: 3000 })
        })
}
</script>

<template>
    <Toolbar>
        <template #start>
            <Button
                icon="pi pi-th-large"
                label="Articles"
                class="mr-2"
                severity="secondary"
                text
                @click="onClickArticles"
            />
        </template>

        <template #end>
            <Button
                :loading="authStore.loading"
                icon="pi pi-sign-out"
                label="Logout"
                class="mr-2"
                severity="secondary"
                text
                @click="onClickLogout"
            />
        </template>
    </Toolbar>
    <main>
        <slot></slot>
    </main>
</template>

<style lang="scss" scoped>
main {
    max-width: 80rem;
    margin: 2rem auto;
}
</style>
