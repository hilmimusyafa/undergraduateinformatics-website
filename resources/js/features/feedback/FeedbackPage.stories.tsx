import type { Story, StoryDefault } from '@ladle/react';
import { msw } from '@ladle/react';

import { branchingPayload, richPayload } from '../../components/ms-form-fixtures';
import { FeedbackPage } from './FeedbackPage';

const formHandlers = [
    msw.http.get('/api/feedback', () =>
        msw.HttpResponse.json({ status: 'success', data: richPayload })
    ),
];

const branchingHandlers = [
    msw.http.get('/api/feedback', () =>
        msw.HttpResponse.json({ status: 'success', data: branchingPayload })
    ),
];

export default {
    title: 'Feedback',
} satisfies StoryDefault;

export const Form: Story = () => <FeedbackPage />;
Form.msw = formHandlers;

export const Branching: Story = () => <FeedbackPage />;
Branching.msw = branchingHandlers;
