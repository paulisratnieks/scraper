import type { AxiosInstance, AxiosResponse } from 'axios'
import router from '@/router'
import { HttpStatusCode } from 'axios'

const httpStatusCodeTokenMismatch = 419

let axiosInstance: AxiosInstance | null = null

export function useAxios(): AxiosInstance {
    if (axiosInstance) {
        return axiosInstance
    }

    axiosInstance = window.axios.create({
        baseURL: import.meta.env.VITE_API_URL,
    })

    axiosInstance.interceptors.response.use(
        (response: AxiosResponse<unknown>) => response,
        (error: AxiosResponse<unknown>) => {
            if (error.status === HttpStatusCode.Unauthorized || error.status === httpStatusCodeTokenMismatch) {
                router.push({ name: 'login' })
            }

            return Promise.reject(error)
        },
    )

    return axiosInstance
}
