const RESERVATION_ERROR_TRANSLATIONS: Record<string, string> = {
    'The schedule on this date and session is already full. Please select another date or session.':
        'Jadwal pada tanggal dan sesi ini sudah terisi. Silakan pilih tanggal atau sesi lain.',
    'The reservation date is not a valid date.': 'Tanggal reservasi tidak valid.',
    'The reservation date must be a Monday, Tuesday, Thursday, or Friday.':
        'Tanggal reservasi harus Senin, Selasa, Kamis, atau Jumat.',
    'The selected session is not available.': 'Sesi yang dipilih tidak tersedia.',
    'This field is required.': 'Field ini wajib diisi.',
};

export function translateReservationError(message: string): string {
    return RESERVATION_ERROR_TRANSLATIONS[message] ?? message;
}
