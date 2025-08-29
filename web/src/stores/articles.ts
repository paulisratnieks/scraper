import { type Ref, ref } from 'vue'
import { defineStore } from 'pinia'
import type { Article } from '@/types/article.ts'
import { useAxios } from '@/composables/axios.ts'
import type { AxiosResponse } from 'axios'

export const useArticlesStore = defineStore(
    'articles',
    () => {
        const articles: Ref<Article[]> = ref([])
        const pagination: Ref<{
            total: number
            current_page: number
        }> = ref({
            total: 0,
            current_page: 1,
        })
        const loading: Ref<boolean> = ref(false)

        const fetch = (page: number = 1): Promise<void> => {
            loading.value = true

            return useAxios()
                .get('articles?page=' + page)
                .then(
                    (
                        response: AxiosResponse<{
                            data: Article[]
                            total: number
                            current_page: number
                        }>,
                    ) => {
                        articles.value = response.data.data
                        pagination.value = {
                            total: response.data.total,
                            current_page: response.data.current_page,
                        }
                    },
                )
                .finally(() => {
                    loading.value = false
                })
        }

        const deleteById = (id: number): Promise<void> => {
            loading.value = true

            return useAxios()
                .delete('articles/' + id)
                .then(() => fetch(pagination.value.current_page))
                .finally(() => {
                    loading.value = false
                })
        }

        return { articles, pagination, loading, fetch, deleteById }
    },
    {},
)
