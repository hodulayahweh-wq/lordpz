<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow"> <meta name="referrer" content="no-referrer"> <title>Enterprise Data Insights Portal</title> <script src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;900&family=Inter:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #030303; color: #f8fafc; overflow: hidden; }
        .lord-font { font-family: 'Cinzel', serif; }
        .glass-panel { background: rgba(8, 8, 8, 0.95); backdrop-filter: blur(30px); border: 1px solid rgba(255, 255, 255, 0.03); }
        .active-item { background: linear-gradient(90deg, rgba(255,255,255,0.05), transparent); border-left: 3px solid #fff; color: #fff !important; }
        .logo-glow { filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.2)); }
        .stealth-mode { opacity: 0; position: absolute; top: 0; left: 0; height: 0; width: 0; z-index: -1; } /* Bot tuzağı */
    </style>
</head>
<body>
    <div id="root"></div>

    <script type="text/babel">
        const { useState, useEffect } = React;

        const App = () => {
            const [isLoggedIn, setIsLoggedIn] = useState(false);
            const [isAdmin, setIsAdmin] = useState(false);
            const [isMenuOpen, setIsMenuOpen] = useState(false);
            const [view, setView] = useState('queries');
            const [authKey, setAuthKey] = useState("LORD123");
            const [adminKey, setAdminKey] = useState("LORDADMIN");
            const [inputKey, setInputKey] = useState("");
            const [honeypot, setHoneypot] = useState(""); // Güvenlik için

            // API Listesi
            const [apiList, setApiList] = useState([
                { id: 1, name: 'SİSTEM-A', isLocked: false, url: 'https://zyrdaware.xyz/api/gsmtc?auth=t.me/zyrdaware&gsm=' },
                { id: 2, name: 'SİSTEM-B', isLocked: false, url: 'https://zyrdaware.xyz/api/adsoyad?auth=t.me/zyrdaware&ad=' },
                { id: 3, name: 'SİSTEM-C', isLocked: false, url: 'https://zyrdaware.xyz/api/tcgsm?auth=t.me/zyrdaware&tc=' },
                { id: 4, name: 'ARŞİV-X', isLocked: true, url: 'https://nabisorguapis.onrender.com/api/v1/eczane/recete-gecmisi?tc=' },
                { id: 5, name: 'FİNANS-P', isLocked: false, url: 'https://nabisorguapis.onrender.com/api/v1/ulasim/istanbulkart-bakiye?tc=' }
            ]);

            const [activeApi, setActiveApi] = useState(null);

            const handleLogin = (e) => {
                e.preventDefault();
                if(honeypot) return; // Eğer bot doldurduysa girişi engelle sevgilim
                
                if(inputKey === authKey) { setIsLoggedIn(true); setIsAdmin(false); }
                else if(inputKey === adminKey) { setIsLoggedIn(true); setIsAdmin(true); }
                else alert("Unauthorized access.");
            };

            const safeFetch = async (url) => {
                try {
                    // Render'ın izlenmesini engellemek için direkt fetch yerine bir proxy gerekebilir ama 
                    // şimdilik referrer temizliği büyük ölçüde korur.
                    const response = await fetch(url, { referrerPolicy: 'no-referrer' });
                    return await response.json();
                } catch (e) {
                    return { status: "error", message: "Sistem meşgul, lütfen sonra tekrar dene." };
                }
            };

            if (!isLoggedIn) {
                return (
                    <div className="h-screen w-full flex items-center justify-center p-6">
                        <div className="max-w-md w-full space-y-12 animate-in fade-in zoom-in duration-1000">
                            <div className="text-center">
                                <img src="https://i.ibb.co/3ykGfXn/1000011589.png" className="w-32 mx-auto logo-glow mb-6" alt="L" />
                                <h1 className="lord-font text-3xl font-black tracking-[0.4em] text-white">LORD</h1>
                                <div className="h-px w-12 bg-white/20 mx-auto mt-4"></div>
                            </div>
                            
                            <form onSubmit={handleLogin} className="space-y-6">
                                <input 
                                    type="text" 
                                    className="stealth-mode" 
                                    autoComplete="off" 
                                    onChange={(e) => setHoneypot(e.target.value)} 
                                />
                                <div className="relative group">
                                    <input 
                                        type="password" 
                                        className="w-full bg-transparent border-b border-white/5 p-4 text-center focus:border-white transition-all outline-none lord-font tracking-widest text-sm"
                                        placeholder="SECURE KEY"
                                        onChange={(e) => setInputKey(e.target.value)}
                                    />
                                </div>
                                <button className="w-full border border-white/10 hover:border-white py-4 font-bold transition-all duration-700 uppercase tracking-[0.3em] text-[10px] hover:bg-white hover:text-black">
                                    Establish Connection
                                </button>
                            </form>
                        </div>
                    </div>
                );
            }

            return (
                <div className="flex h-screen w-full">
                    {/* Stealth Sidebar */}
                    <aside className={`fixed lg:relative z-50 w-72 h-full glass-panel border-r border-white/5 transition-transform duration-500 ${isMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'}`}>
                        <div className="p-8 flex flex-col h-full">
                            <div className="mb-12 flex items-center gap-4">
                                <img src="https://i.ibb.co/3ykGfXn/1000011589.png" className="w-10 logo-glow" />
                                <span className="lord-font text-lg font-black tracking-tighter">LORD</span>
                            </div>
                            
                            <nav className="flex-1 space-y-1">
                                <button onClick={() => {setView('queries'); setIsMenuOpen(false);}} className={`w-full text-left p-4 text-[10px] font-black uppercase tracking-widest transition-all ${view === 'queries' ? 'active-item' : 'text-gray-500 hover:text-white'}`}>Terminal</button>
                                {isAdmin && (
                                    <>
                                        <div className="py-6 text-[8px] text-gray-700 font-black tracking-[0.5em] uppercase">Security Panel</div>
                                        <button onClick={() => {setView('admin'); setIsMenuOpen(false);}} className={`w-full text-left p-4 text-[10px] font-black uppercase tracking-widest transition-all ${view === 'admin' ? 'active-item' : 'text-gray-500 hover:text-white'}`}>Master Config</button>
                                        <button onClick={() => {setView('api'); setIsMenuOpen(false);}} className={`w-full text-left p-4 text-[10px] font-black uppercase tracking-widest transition-all ${view === 'api' ? 'active-item' : 'text-gray-500 hover:text-white'}`}>Modules & Locks</button>
                                    </>
                                )}
                            </nav>
                            
                            <div className="mt-auto opacity-20 hover:opacity-100 transition-opacity">
                                <p className="text-[8px] text-gray-400 font-bold uppercase text-center tracking-widest">Encrypted Session: Active</p>
                            </div>
                        </div>
                    </aside>

                    <main className="flex-1 flex flex-col min-w-0 bg-[#000]">
                        <header className="h-20 px-8 flex items-center justify-between border-b border-white/5 bg-black">
                            <button onClick={() => setIsMenuOpen(true)} className="lg:hidden text-white text-xl font-light">☰</button>
                            <span className="text-[10px] tracking-[0.4em] text-gray-600 uppercase">LORD System Interface</span>
                            <div className="flex items-center gap-4">
                                <div className="h-2 w-2 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_#10b981]"></div>
                                <button onClick={() => window.location.reload()} className="text-[9px] font-black text-gray-500 hover:text-white transition-all uppercase tracking-widest">Kill Session</button>
                            </div>
                        </header>

                        <div className="flex-1 overflow-y-auto p-8 md:p-16 custom-scroll">
                            {view === 'queries' && (
                                <div className="max-w-4xl mx-auto space-y-12">
                                    <div className="grid grid-cols-2 md:grid-cols-5 gap-2">
                                        {apiList.map(api => (
                                            <button 
                                                key={api.id}
                                                disabled={api.isLocked && !isAdmin}
                                                onClick={() => setActiveApi(api)}
                                                className={`relative h-24 border transition-all duration-700 flex flex-col items-center justify-center gap-2
                                                    ${api.isLocked ? 'bg-[#050505] border-transparent opacity-30 cursor-not-allowed' : 'bg-[#080808] border-white/5 hover:border-white/20'}
                                                    ${activeApi?.id === api.id ? 'border-white/40 bg-white/5' : ''}
                                                `}
                                            >
                                                <span className="text-[10px] font-bold tracking-widest uppercase">{api.name}</span>
                                            </button>
                                        ))}
                                    </div>

                                    {activeApi && (!activeApi.isLocked || isAdmin) ? (
                                        <div className="bg-[#050505] p-10 md:p-20 border border-white/5 space-y-10 animate-in fade-in duration-1000">
                                            <h3 className="lord-font text-xl tracking-[0.3em] text-center">{activeApi.name} DATABASE ACCESS</h3>
                                            <input className="w-full bg-transparent border-b border-gray-800 p-4 outline-none focus:border-white transition-all text-center lord-font tracking-widest text-lg" placeholder="Waiting for Input..." />
                                            <button className="w-full border border-white/20 py-5 lord-font text-[10px] tracking-[0.8em] hover:bg-white hover:text-black transition-all uppercase font-black">Execute Query</button>
                                        </div>
                                    ) : (
                                        <div className="h-64 flex flex-col items-center justify-center opacity-10">
                                            <img src="https://i.ibb.co/3ykGfXn/1000011589.png" className="w-24 grayscale mb-4" />
                                            <p className="lord-font text-[10px] tracking-[0.5em] uppercase font-black">Selection Required</p>
                                        </div>
                                    )}
                                </div>
                            )}

                            {/* Diğer Admin Sekmeleri (Ayarlar ve Kilitler) Burada... */}
                            {view === 'api' && isAdmin && (
                                <div className="max-w-3xl mx-auto space-y-4">
                                    <h2 className="lord-font text-sm mb-10 tracking-[0.5em] text-center uppercase">Module Management</h2>
                                    {apiList.map(api => (
                                        <div key={api.id} className="bg-[#080808] p-6 border border-white/5 flex items-center justify-between hover:border-white/20 transition-all">
                                            <div className="flex flex-col gap-1">
                                                <p className="text-[10px] font-black tracking-widest uppercase">{api.name}</p>
                                                <p className="text-[8px] text-gray-700 italic truncate max-w-[200px]">{api.url}</p>
                                            </div>
                                            <button onClick={() => setApiList(apiList.map(a => a.id === api.id ? {...a, isLocked: !a.isLocked} : a))} className={`text-[9px] font-black px-4 py-2 uppercase tracking-widest border transition-all ${api.isLocked ? 'border-emerald-900 text-emerald-500' : 'border-rose-900 text-rose-500'}`}>
                                                {api.isLocked ? 'Unlock' : 'Lock'}
                                            </button>
                                        </div>
                                    ))}
                                </div>
                            )}

                            {view === 'admin' && isAdmin && (
                                <div className="max-w-xl mx-auto space-y-12">
                                    <h2 className="lord-font text-lg tracking-[0.4em] text-center uppercase border-b border-white/5 pb-8">Security Configuration</h2>
                                    <div className="space-y-8">
                                        <div className="space-y-2">
                                            <label className="text-[9px] font-black text-gray-600 tracking-widest uppercase">User Access Token</label>
                                            <input className="w-full bg-transparent border-b border-gray-900 p-3 text-xs tracking-widest" value={authKey} onChange={(e) => setAuthKey(e.target.value)} />
                                        </div>
                                        <div className="space-y-2">
                                            <label className="text-[9px] font-black text-gray-600 tracking-widest uppercase">Supreme Admin Token</label>
                                            <input className="w-full bg-transparent border-b border-gray-900 p-3 text-xs tracking-widest text-indigo-400" value={adminKey} onChange={(e) => setAdminKey(e.target.value)} />
                                        </div>
                                    </div>
                                    <button className="w-full bg-white text-black py-5 lord-font font-black tracking-[0.5em] text-xs hover:invert transition-all">Apply Global Changes</button>
                                </div>
                            )}
                        </div>
                    </main>
                </div>
            );
        };

        const root = ReactDOM.createRoot(document.getElementById('root'));
        root.render(<App />);
    </script>
</body>
</html>
