import { describe, test, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useArticlesStore } from '@/stores/articles'
import type { Article } from '@/types/article'
import type { AxiosResponse } from 'axios'

const mockArticles: Article[] = [
    {
        id: 1,
        title: 'Test Article 1',
        link: 'https://example.com/1',
        points: 100,
        created_at: '2024-01-01T00:00:00Z',
    },
]
const mockPagination = {
    total: 25,
    current_page: 1,
}
const mockApiIndexResponse = {
    data: {
        data: mockArticles,
        ...mockPagination,
    },
} as AxiosResponse

const mockAxios = {
    get: vi.fn().mockResolvedValue(mockApiIndexResponse),
    delete: vi.fn().mockResolvedValue({ data: {} }),
}

vi.mock('@/composables/axios', () => ({
    useAxios: () => mockAxios,
}))

describe('Articles Store', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
    })
    test('fetches articles and updates state', async () => {
        const articlesStore = useArticlesStore()

        await articlesStore.fetch(1)

        expect(mockAxios.get).toHaveBeenCalledWith('articles?page=1')
        expect(articlesStore.articles).toEqual(mockArticles)
        expect(articlesStore.pagination).toEqual(mockPagination)
        expect(articlesStore.loading).toBe(false)
    })
    test('deletes article and update state', async () => {
        const articlesStore = useArticlesStore()

        await articlesStore.deleteById(1)

        expect(mockAxios.delete).toHaveBeenCalledWith('articles/1')
        expect(mockAxios.get).toHaveBeenCalledWith('articles?page=1')
        expect(articlesStore.articles).toEqual(mockArticles)
        expect(articlesStore.pagination).toEqual(mockPagination)
        expect(articlesStore.loading).toBe(false)
    })
})
