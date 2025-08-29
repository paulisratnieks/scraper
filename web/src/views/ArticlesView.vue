<script setup lang="ts">
import { onMounted } from 'vue'
import { useArticlesStore } from '@/stores/articles.ts'
import type { Article } from '@/types/article.ts'
import { useToast } from 'primevue'
import DashboardLayout from '@/layouts/DashboardLayout.vue'

const articlesStore = useArticlesStore()
const toast = useToast()

const rowsPerPage = 10

const onRowClick = (event: { data: Article }) => {
    window.open(event.data.link, '_blank')
}

const onClickDelete = (event: Article) => {
    articlesStore
        .deleteById(event.id)
        .then(() => toast.add({ severity: 'success', summary: 'Successful', detail: 'Article Deleted', life: 3000 }))
        .catch(() =>
            toast.add({ severity: 'error', summary: 'Failure', detail: 'Article Deletion Failed', life: 3000 }),
        )
}

const onPageChange = (event: { page: number }) => {
    articlesStore.fetch(event.page + 1)
}

onMounted(() => {
    articlesStore.fetch()
})
</script>

<template>
    <DashboardLayout>
        <Card>
            <template #title> Hackers News Articles </template>
            <template #content>
                <DataTable
                    :loading="articlesStore.loading"
                    :value="articlesStore.articles"
                    :lazy="true"
                    :paginator="true"
                    :rows="rowsPerPage"
                    :totalRecords="articlesStore.pagination.total"
                    tableStyle="min-width: 50rem"
                    selectionMode="single"
                    @page="onPageChange"
                    @rowClick="onRowClick"
                >
                    <Column field="id" header="Id"></Column>
                    <Column field="title" header="Title"></Column>
                    <Column field="points" header="Points"></Column>
                    <Column field="created_at" header="Created At"></Column>
                    <Column :exportable="false" style="min-width: 12rem">
                        <template #body="slotProps">
                            <Button
                                icon="pi pi-trash"
                                variant="outlined"
                                rounded
                                severity="danger"
                                @click="onClickDelete(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
            </template>
        </Card>
    </DashboardLayout>
</template>
