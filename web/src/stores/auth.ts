import { type Ref, ref } from 'vue'
import { defineStore } from 'pinia'
import { useAxios } from '@/composables/axios.ts'
import type { AxiosResponse } from 'axios'
import type { LoginFormValues } from '@/types/login-form.ts'

export const useAuthStore = defineStore(
    'auth',
    () => {
        const isAuthenticated: Ref<boolean> = ref(false)
        const loading: Ref<boolean> = ref(false)

        const login = (credentials: LoginFormValues): Promise<void> => {
            loading.value = true

            return useAxios()
                .get('sanctum/csrf-cookie')
                .then((): Promise<AxiosResponse<{ message: string }>> => useAxios().post('login', credentials))
                .then((): void => {
                    isAuthenticated.value = true
                })
                .finally(() => {
                    loading.value = false
                })
        }

        const logout = (): Promise<void> => {
            loading.value = true

            return useAxios()
                .post('logout')
                .then((): void => {
                    isAuthenticated.value = false
                })
                .finally(() => {
                    loading.value = false
                })
        }

        return { isAuthenticated, loading, login, logout }
    },
    {
        persist: {
            pick: ['isAuthenticated'],
        },
    },
)
