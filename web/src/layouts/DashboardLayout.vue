<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth.ts'
import { NotificationType, useNotifications } from '@/composables/notifications.ts'

const router = useRouter()
const notifications = useNotifications()
const authStore = useAuthStore()

const onClickArticles = (): void => {
    router.push({ name: 'articles' })
}

const onClickLogout = (): void => {
    authStore
        .logout()
        .then(() => {
            notifications.add(NotificationType.Success, 'Logged Out')
            router.push({ path: '/' })
        })
        .catch(() => {
            notifications.add(NotificationType.Error, 'Logging Out Failed')
        })
}

const isRouteActive = (route: string): boolean => {
    return router.currentRoute.value.name === route
}

const severityFromRoute = (route: string): string => {
    return isRouteActive(route) ? 'primary' : 'secondary'
}
</script>

<template>
    <Toolbar>
        <template #start>
            <Button
                icon="pi pi-th-large"
                label="Articles"
                class="mr-2"
                :severity="severityFromRoute('articles')"
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
    <main class="my-8 mx-auto max-w-7xl">
        <slot></slot>
    </main>
</template>
