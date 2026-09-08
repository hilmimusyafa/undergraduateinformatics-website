import { render, screen } from '@testing-library/react';
import { describe, expect, it, vi } from 'vitest';

import { LineChart } from './line-chart';

vi.mock('recharts', () => {
    const MockLine = (props: any) => (
        <div data-testid="line" data-datakey={props.dataKey} data-stroke={props.stroke} />
    );

    return {
        ResponsiveContainer: ({ children }: any) => <div data-testid="responsive">{children}</div>,
        LineChart: ({ children }: any) => <div data-testid="line-chart">{children}</div>,
        Line: MockLine,
        CartesianGrid: () => <div data-testid="grid" />,
        XAxis: ({ interval }: any) => (
            <div data-testid="x-axis" data-interval={interval} />
        ),
        YAxis: () => <div data-testid="y-axis" />,
        Tooltip: ({ trigger, active }: any) => (
            <div data-testid="tooltip" data-trigger={trigger} data-active={String(active)} />
        ),
    };
});

vi.mock('@/hooks/useMediaQuery', () => ({
    useMediaQuery: vi.fn(() => false),
}));

describe('LineChart', () => {
    const labels = ['Jan', 'Feb', 'Mar'];
    const values = [490, 495, 500];

    it('renders a single line using the value data key', () => {
        render(<LineChart labels={labels} values={values} />);

        const lines = screen.getAllByTestId('line');
        expect(lines).toHaveLength(1);
        expect(lines[0]).toHaveAttribute('data-datakey', 'value');
    });

    it('wires the shared tooltip', () => {
        render(<LineChart labels={labels} values={values} />);

        expect(screen.getByTestId('tooltip')).toHaveAttribute('data-trigger', 'hover');
    });

    it('forces every x-axis label to render', () => {
        render(<LineChart labels={labels} values={values} />);

        expect(screen.getByTestId('x-axis')).toHaveAttribute('data-interval', '0');
    });
});
