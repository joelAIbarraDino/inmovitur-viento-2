export function formatDate(
    value: string | null | undefined,
    locale = 'es-MX'
): string {
    if (!value) return '-'

    // Normaliza timestamps de Laravel
    const date = value.includes('Z')
        ? new Date(value)
        : new Date(value.replace(' ', 'T') + 'Z')

    if (isNaN(date.getTime())) return value

    return date.toLocaleString(locale, {
        year: 'numeric',
        month: 'short',
        day: '2-digit',
    })
}