import '../css/app.css';

import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { Toaster } from 'react-hot-toast';

const appName = import.meta.env.VITE_APP_NAME || 'Peringatan 17 Agustus';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(
            <>
                <Toaster 
                    position="top-center" 
                    toastOptions={{
                        duration: 4000,
                        style: {
                            background: 'rgba(30, 30, 40, 0.9)',
                            color: '#fff',
                            backdropFilter: 'blur(10px)',
                            border: '1px solid rgba(255, 255, 255, 0.1)',
                            boxShadow: '0 10px 25px rgba(0,0,0,0.5)',
                            padding: '16px',
                        },
                        success: {
                            iconTheme: { primary: '#4ade80', secondary: '#1e1e28' },
                        },
                        error: {
                            iconTheme: { primary: '#f87171', secondary: '#1e1e28' },
                        }
                    }} 
                />
                <App {...props} />
            </>
        );
    },
    progress: {
        color: '#ff4747',
        showSpinner: true,
    },
});
