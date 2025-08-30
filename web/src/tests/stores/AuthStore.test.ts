import { describe, test, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useAuthStore } from '@/stores/auth.ts'

const mockAxios = {
    get: vi.fn().mockResolvedValue({ data: {} }),
    post: vi.fn().mockResolvedValue({ data: {} }),
}

vi.mock('@/composables/axios', () => ({
    useAxios: () => mockAxios,
}))

describe('Auth Store', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
    })
    test('logs in and and has correct state', async () => {
        const authStore = useAuthStore()
        const credentials = {
            username: 'username',
            password: 'password',
        }

        await authStore.login(credentials)

        expect(mockAxios.get).toHaveBeenCalledWith('sanctum/csrf-cookie')
        expect(mockAxios.post).toHaveBeenCalledWith('login', credentials)
        expect(authStore.isAuthenticated).toBe(true)
        expect(authStore.loading).toBe(false)
    })
    test('logs out and has correct state', async () => {
        const authStore = useAuthStore()

        await authStore.logout()

        expect(mockAxios.post).toHaveBeenCalledWith('logout')
        expect(authStore.isAuthenticated).toBe(false)
        expect(authStore.loading).toBe(false)
    })
})
