import { render, screen } from '@testing-library/react';
import { describe, expect, it, vi } from 'vitest';

import { PieChart } from './pie-chart';

vi.mock('recharts', () => {
    const MockCell = ({ fill }: any) => <div data-testid="cell" data-fill={fill} />;

    return {
        ResponsiveContainer: ({ children }: any) => <div data-testid="responsive">{children}</div>,
        PieChart: ({ children }: any) => <div data-testid="pie-chart">{children}</div>,
        Pie: ({ children }: any) => <div data-testid="pie">{children}</div>,
        Cell: MockCell,
        Tooltip: ({ trigger, active }: any) => (
            <div data-testid="tooltip" data-trigger={trigger} data-active={String(active)} />
        ),
        Legend: ({ content }: any) => (
            <div data-testid="legend">
                {typeof content === 'function'
                    ? content({
                          payload: [
                              { value: 'Jawa Barat', color: 'var(--chart-1)' },
                              { value: 'Jawa Tengah', color: 'var(--chart-2)' },
                          ],
                      })
                    : content}
            </div>
        ),
    };
});

vi.mock('@/hooks/useMediaQuery', () => ({
    useMediaQuery: vi.fn(() => false),
}));

describe('PieChart', () => {
    const labels = ['Jawa Barat', 'Jawa Tengah'];
    const values = [350, 180];

    it('renders a cell per value', () => {
        render(<PieChart labels={labels} values={values} />);

        expect(screen.getAllByTestId('cell')).toHaveLength(2);
    });

    it('colors cells from the chart palette', () => {
        render(<PieChart labels={labels} values={values} />);

        const cells = screen.getAllByTestId('cell');
        expect(cells[0]).toHaveAttribute('data-fill', 'var(--chart-1)');
        expect(cells[1]).toHaveAttribute('data-fill', 'var(--chart-2)');
    });

    it('renders a legend with each label', () => {
        render(<PieChart labels={labels} values={values} />);

        expect(screen.getByText('Jawa Barat')).toBeInTheDocument();
        expect(screen.getByText('Jawa Tengah')).toBeInTheDocument();
    });
});
