import { useNavigate } from '@tanstack/react-router';

import { TextButton } from './TextButton';

export function NotFoundPage() {
    const navigate = useNavigate();

    return (
        <div className="flex min-h-[80vh] items-center justify-center px-4">
            <div className="w-full max-w-[37em]">
                <h1 className="text-foreground text-3xl leading-tight font-semibold">
                    Halaman Tidak Ditemukan
                </h1>
                <p className="text-muted-foreground mt-4.5 md:mt-4">
                    Alamat mungkin salah atau sudah tidak tersedia.
                </p>
                <div className="mt-4.5 md:mt-4">
                    <TextButton
                        variant="underline"
                        className="text-blue-600"
                        onClick={() => navigate({ to: '/' })}
                    >
                        Kembali ke Beranda
                    </TextButton>
                </div>
            </div>
        </div>
    );
}
