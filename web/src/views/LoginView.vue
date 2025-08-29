<script setup lang="ts">
import { reactive } from 'vue'
import { useToast } from 'primevue'
import { useAuthStore } from '@/stores/auth.ts'
import { useRouter } from 'vue-router'
import type { LoginFormValues } from '@/types/login-form.ts'
import type { AxiosError } from 'axios'
import type { FormResolverOptions, FormSubmitEvent } from '@primevue/forms'

const toast = useToast()
const authStore = useAuthStore()
const router = useRouter()

const initialValues: LoginFormValues = reactive({
    name: '',
    password: '',
})

const resolver = (options: FormResolverOptions) => {
    const values = options.values as LoginFormValues
    const errors: Record<string, { message: string }[]> = {}

    if (!values.name) {
        errors.name = [{ message: 'Username is required.' }]
    }
    if (!values.password) {
        errors.password = [{ message: 'Password is required.' }]
    }

    return {
        values,
        errors,
    }
}

const onFormSubmit = (event: FormSubmitEvent) => {
    const { valid, values } = event
    const formValues = values as LoginFormValues
    if (valid) {
        authStore
            .login(formValues)
            .then(() => {
                toast.add({ severity: 'success', summary: 'Successful', detail: 'Logged In', life: 3000 })
                router.push({ path: '/' })
            })
            .catch((response: AxiosError<{ error?: string }>) => {
                if (response.response?.data.error) {
                    toast.add({
                        severity: 'error',
                        summary: 'Failure',
                        detail: response.response?.data.error,
                        life: 3000,
                    })
                } else {
                    toast.add({ severity: 'error', summary: 'Failure', detail: 'Unknown error occurred', life: 3000 })
                }
            })
    }
}
</script>

<template>
    <div class="flex w-full justify-center p-14">
        <Card class="w-md">
            <template #title> Log In </template>
            <template #content>
                <Form v-slot="$form" :initialValues :resolver @submit="onFormSubmit" class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1">
                        <InputText name="name" type="text" placeholder="Username" fluid />
                        <Message v-if="$form.name?.invalid" severity="error" size="small" variant="simple">
                            {{ $form.name.error?.message }}
                        </Message>
                    </div>
                    <div class="flex flex-col gap-1">
                        <Password
                            name="password"
                            type="text"
                            placeholder="Password"
                            :feedback="false"
                            toggleMask
                            fluid
                        />
                        <Message v-if="$form.password?.invalid" severity="error" size="small" variant="simple">
                            {{ $form.password.error?.message }}
                        </Message>
                    </div>
                    <Button :loading="authStore.loading" type="submit" severity="primary" label="Submit" />
                </Form>
            </template>
        </Card>
    </div>
</template>
