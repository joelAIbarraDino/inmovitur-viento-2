export function formatCurrency(
    value: number | null | undefined,
    locale = 'es-MX',
    currency?: string
): string {
    if (value === null || value === undefined) return '-'

    return currency
        ? value.toLocaleString(locale, {
              style: 'currency',
              currency,
          })
        : value.toLocaleString(locale, {
              minimumFractionDigits: 0,
              maximumFractionDigits: 2,
          })
}
