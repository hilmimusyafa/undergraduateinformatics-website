import './bootstrap';
import React from 'react';
import ReactDOM from 'react-dom/client';
import '../css/app.css';

function App() {
  return (
    <div className="p-8 text-center">
      <h1 className="text-2xl font-bold text-primary">Hello, World!</h1>
    </div>
  );
}

const rootElement = document.getElementById('root');

if (rootElement) {
  ReactDOM.createRoot(rootElement).render(
    <React.StrictMode>
      <App />
    </React.StrictMode>
  );
}
