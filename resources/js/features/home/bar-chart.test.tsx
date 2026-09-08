import type { ReactNode } from 'react';

import { render, screen } from '@testing-library/react';
import { describe, expect, it, vi } from 'vitest';

import { BarChart } from './bar-chart';
import { ChartTooltip } from './chart-tooltip';

vi.mock('recharts', () => {
    const MockBar = (props: { dataKey?: string; children?: ReactNode }) => (
        <div data-testid="bar" data-datakey={props.dataKey}>
            {props.children}
        </div>
    );
    const MockCell = ({ fill }: any) => <div data-testid="cell" data-fill={fill} />;

    return {
        ResponsiveContainer: ({ children }: any) => <div data-testid="responsive">{children}</div>,
        BarChart: ({ children }: any) => <div data-testid="bar-chart">{children}</div>,
        Bar: MockBar,
        Cell: MockCell,
        CartesianGrid: () => <div data-testid="grid" />,
        XAxis: ({ dataKey, interval }: any) => (
            <div data-testid="x-axis" data-datakey={dataKey} data-interval={interval} />
        ),
        YAxis: () => <div data-testid="y-axis" />,
        Tooltip: ({ content, trigger, active }: any) => (
            <div data-testid="tooltip" data-trigger={trigger} data-active={String(active)}>
                {content}
            </div>
        ),
    };
});

vi.mock('@/hooks/useMediaQuery', () => ({
    useMediaQuery: vi.fn(() => false),
}));

describe('BarChart', () => {
    const labels = ['2022', '2023', '2024'];
    const values = [240, 255, 270];

    it('renders a single bar series for the value data key', () => {
        render(<BarChart labels={labels} values={values} />);

        const bars = screen.getAllByTestId('bar');
        expect(bars).toHaveLength(1);
        expect(bars[0]).toHaveAttribute('data-datakey', 'value');
    });

    it('colors each bar from the chart palette by index', () => {
        render(<BarChart labels={labels} values={values} />);

        const cells = screen.getAllByTestId('cell');
        expect(cells).toHaveLength(3);
        expect(cells[0]).toHaveAttribute('data-fill', 'var(--chart-1)');
        expect(cells[1]).toHaveAttribute('data-fill', 'var(--chart-2)');
        expect(cells[2]).toHaveAttribute('data-fill', 'var(--chart-3)');
    });

    it('wires the shared tooltip with trigger and content', () => {
        render(<BarChart labels={labels} values={values} />);

        const tooltip = screen.getByTestId('tooltip');
        expect(tooltip).toHaveAttribute('data-trigger', 'hover');
        expect(ChartTooltip).toBeTruthy();
    });

    it('forces every x-axis label to render', () => {
        render(<BarChart labels={labels} values={values} />);

        expect(screen.getByTestId('x-axis')).toHaveAttribute('data-interval', '0');
    });
});
