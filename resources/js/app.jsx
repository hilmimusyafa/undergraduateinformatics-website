import React from 'react';
import { createRoot } from 'react-dom/client';

import AdminDashboard from './AdminPanel/AdminDashboard';
import './bootstrap';

const el = document.getElementById('admin-react-root');
if (el) {
    const root = createRoot(el);
    root.render(<AdminDashboard />);
}
