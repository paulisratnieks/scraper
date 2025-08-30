import { useToast } from 'primevue'

export enum NotificationType {
    Success = 'success',
    Error = 'error',
}

const notificationSummary: Record<NotificationType, string> = {
    [NotificationType.Success]: 'Successful',
    [NotificationType.Error]: 'Failure',
}

export function useNotifications() {
    const toast = useToast()

    const add = (type: NotificationType, detail: string) => {
        toast.add({
            severity: type,
            summary: notificationSummary[type],
            detail: detail,
            group: 'br',
            life: 3000,
        })
    }

    return { add }
}
