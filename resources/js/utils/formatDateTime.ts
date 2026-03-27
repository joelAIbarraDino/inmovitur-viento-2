export function formatDateTime(
    value: string | null | undefined,
    locale = 'es-MX'
): string {
    if (!value) return '-'

    // 1. Limpiamos la cadena de posibles "T" o "Z" para quedarnos solo con los números
    // "2024-08-03 12:00:00" -> ["2024", "08", "03", "12", "00", "00"]
    const parts = value.match(/\d+/g);
    
    if (!parts || parts.length < 5) return value;

    // 2. Creamos la fecha usando el constructor de componentes locales:
    // new Date(año, mes (0-11), día, hora, minuto, segundo)
    const date = new Date(
        parseInt(parts[0]),
        parseInt(parts[1]) - 1, // Los meses en JS van de 0 a 11
        parseInt(parts[2]),
        parseInt(parts[3]),
        parseInt(parts[4]),
        parts[5] ? parseInt(parts[5]) : 0
    );

    if (isNaN(date.getTime())) return value;

    return date.toLocaleString(locale, {
        year: 'numeric',
        month: 'long',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    });
}