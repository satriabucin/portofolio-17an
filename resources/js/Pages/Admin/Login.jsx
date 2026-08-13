import { useForm, usePage } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';

export default function Login() {
    const { flash } = usePage().props;
    const { data, setData, post, processing } = useForm({
        username: '',
        password: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/admin/login');
    };

    return (
        <AdminLayout title="Login Admin">
            <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', minHeight: '80vh' }}>
                <div className="glass-card" style={{ padding: '50px 40px', width: '100%', maxWidth: '420px', textAlign: 'center', background: 'rgba(255, 255, 255, 0.05)', borderRadius: '12px', border: '1px solid rgba(255,255,255,0.1)', backdropFilter: 'blur(20px)' }}>
                    <h2 style={{ marginBottom: '5px', fontWeight: 800, color: 'var(--color-text)' }}>Masuk Panel Admin</h2>
                    <p style={{ color: 'var(--color-text)', opacity: 0.8, fontSize: '0.95rem', marginBottom: '30px' }}>Silakan otentikasi untuk mengelola sistem pendaftaran lomba.</p>

                    <form onSubmit={handleSubmit}>
                        <div className="form-group" style={{ textAlign: 'left', marginBottom: '20px' }}>
                            <label style={{ display: 'block', color: 'var(--color-text)', opacity: 0.8, fontSize: '0.85rem', textTransform: 'uppercase', letterSpacing: '1px', marginBottom: '8px' }}>Username</label>
                            <input 
                                type="text" 
                                className="form-control" 
                                style={{ background: 'rgba(0,0,0,0.2)', border: '1px solid var(--glass-border)', color: 'var(--color-text)', padding: '12px 15px', borderRadius: '8px', fontSize: '1rem', width: '100%', boxSizing: 'border-box' }}
                                required 
                                autoFocus
                                value={data.username}
                                onChange={e => setData('username', e.target.value)}
                            />
                        </div>
                        
                        <div className="form-group" style={{ textAlign: 'left', marginBottom: '30px' }}>
                            <label style={{ display: 'block', color: 'var(--color-text)', opacity: 0.8, fontSize: '0.85rem', textTransform: 'uppercase', letterSpacing: '1px', marginBottom: '8px' }}>Password</label>
                            <input 
                                type="password" 
                                className="form-control" 
                                style={{ background: 'rgba(0,0,0,0.2)', border: '1px solid var(--glass-border)', color: 'var(--color-text)', padding: '12px 15px', borderRadius: '8px', fontSize: '1rem', width: '100%', boxSizing: 'border-box' }}
                                required
                                value={data.password}
                                onChange={e => setData('password', e.target.value)}
                            />
                        </div>

                        <button type="submit" disabled={processing} className="btn" style={{ width: '100%', background: 'var(--color-primary)', color: '#fff', padding: '15px', borderRadius: '8px', fontWeight: 700, fontSize: '1.05rem', letterSpacing: '0.5px', border: 'none', boxShadow: '0 10px 20px rgba(255, 71, 71, 0.3)', transition: 'all 0.3s ease', cursor: 'pointer', opacity: processing ? 0.7 : 1 }}>
                            {processing ? 'MEMPROSES...' : 'LOGIN SEKARANG'}
                        </button>
                    </form>
                </div>
            </div>
        </AdminLayout>
    );
}
