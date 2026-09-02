interface ErrorPageProps {
    error?: { message?: string };
}

export function ErrorPage({ error }: ErrorPageProps) {
    return (
        <div className="mx-auto w-full max-w-[37em] px-4 py-10 md:py-9">
            <h1 className="text-foreground text-3xl leading-tight font-semibold">
                Terjadi kesalahan
            </h1>
            <p className="text-muted-foreground mt-4.5 md:mt-4">
                Maaf, ada yang tidak beres. Silakan muat ulang halaman ini.
            </p>
            {error?.message && (
                <p className="text-muted-foreground mt-4.5 md:mt-4">{error.message}</p>
            )}
        </div>
    );
}
