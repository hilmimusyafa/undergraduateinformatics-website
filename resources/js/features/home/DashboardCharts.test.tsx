import { fireEvent, render, screen } from '@testing-library/react';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { useMediaQuery } from '@/hooks/useMediaQuery';

import { DashboardCharts } from './DashboardCharts';
import { type DashboardDataset } from './types';

vi.mock('@/hooks/useMediaQuery', () => ({
    useMediaQuery: vi.fn(),
}));

vi.mock('./ChartCard', () => ({
    ChartCard: () => <div data-testid="chart-card" />,
}));

const mockApi = {
    on: vi.fn(),
    off: vi.fn(),
    selectedScrollSnap: vi.fn(() => 0),
    canScrollPrev: vi.fn(() => true),
    canScrollNext: vi.fn(() => true),
    scrollTo: vi.fn(),
    scrollNext: vi.fn(),
    scrollPrev: vi.fn(),
};

vi.mock('embla-carousel-react', () => {
    return {
        default: () => [vi.fn(), mockApi],
    };
});

const datasets: DashboardDataset[] = [
    {
        id: 1,
        title: 'Mahasiswa per Angkatan',
        chart_type: 'bar',
        x_label: 'Angkatan',
        y_label: 'Jumlah',
        labels: ['2022'],
        values: [240],
    },
    {
        id: 2,
        title: 'Mahasiswa per Provinsi',
        chart_type: 'pie',
        x_label: 'Provinsi',
        y_label: 'Jumlah',
        labels: ['Jawa Barat'],
        values: [350],
    },
];

describe('DashboardCharts', () => {
    beforeEach(() => {
        vi.clearAllMocks();
        mockApi.selectedScrollSnap.mockReturnValue(0);
    });

    it('renders the section heading', () => {
        vi.mocked(useMediaQuery).mockReturnValue(true);

        render(<DashboardCharts datasets={datasets} />);

        expect(screen.getByRole('heading', { name: 'Statistik Mahasiswa' })).toBeInTheDocument();
    });

    it('renders an empty message when there are no datasets', () => {
        vi.mocked(useMediaQuery).mockReturnValue(true);

        render(<DashboardCharts datasets={[]} />);

        expect(screen.getByText('Belum ada data statistik.')).toBeInTheDocument();
        expect(screen.queryByTestId('chart-card')).not.toBeInTheDocument();
    });

    it('renders a chart card per dataset', () => {
        vi.mocked(useMediaQuery).mockReturnValue(true);

        render(<DashboardCharts datasets={datasets} />);

        expect(screen.getAllByTestId('chart-card')).toHaveLength(2);
    });

    it('does not render carousel navigation on the grid layout', () => {
        vi.mocked(useMediaQuery).mockReturnValue(true);

        render(<DashboardCharts datasets={datasets} />);

        expect(screen.queryByLabelText('Previous slide')).not.toBeInTheDocument();
        expect(screen.queryByLabelText('Next slide')).not.toBeInTheDocument();
    });

    it('renders carousel navigation on mobile', () => {
        vi.mocked(useMediaQuery).mockReturnValue(false);

        render(<DashboardCharts datasets={datasets} />);

        expect(screen.getByLabelText('Previous slide')).toBeInTheDocument();
        expect(screen.getByLabelText('Next slide')).toBeInTheDocument();
    });

    it('navigates the carousel from the buttons', () => {
        vi.mocked(useMediaQuery).mockReturnValue(false);

        render(<DashboardCharts datasets={datasets} />);

        fireEvent.click(screen.getByLabelText('Next slide'));
        expect(mockApi.scrollNext).toHaveBeenCalled();

        fireEvent.click(screen.getByLabelText('Previous slide'));
        expect(mockApi.scrollPrev).toHaveBeenCalled();
    });

    it('renders an indicator per slide and marks the current one', () => {
        vi.mocked(useMediaQuery).mockReturnValue(false);

        render(<DashboardCharts datasets={datasets} />);

        const indicators = screen.getAllByLabelText(/Go to slide/);
        expect(indicators).toHaveLength(2);
        expect(indicators[0]).toHaveAttribute('aria-current', 'true');
    });

    it('scrolls to the selected slide when an indicator is clicked', () => {
        vi.mocked(useMediaQuery).mockReturnValue(false);

        render(<DashboardCharts datasets={datasets} />);

        fireEvent.click(screen.getAllByLabelText(/Go to slide/)[1]);
        expect(mockApi.scrollTo).toHaveBeenCalledWith(1);
    });
});
