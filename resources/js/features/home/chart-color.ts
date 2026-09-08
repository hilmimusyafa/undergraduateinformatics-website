export const CHART_PALETTE = [
    'var(--chart-1)',
    'var(--chart-2)',
    'var(--chart-3)',
    'var(--chart-4)',
    'var(--chart-5)',
] as const;

export function chartColor(index: number): string {
    return CHART_PALETTE[index % CHART_PALETTE.length];
}
