<style>
    :root {
        --bg-body: #050505; --bg-card: rgba(255, 255, 255, 0.03); --bg-card-hover: rgba(255, 255, 255, 0.12);
        --border-subtle: rgba(255, 255, 255, 0.1); --border-hover: rgba(255, 255, 255, 0.6);
        --text-main: #ffffff; --text-muted: #a0a0a0; --card-shadow: none;
        --card-shadow-hover: 0 0 40px rgba(255, 255, 255, 0.15); --blur-color: rgba(255,255,255,0.5);
        --spotlight-opacity: 1; --font-heading: 'Cardo', serif; --font-body: 'Inter', sans-serif;
    }
    body.light-mode {
        --bg-body: #f4f5f7; --bg-card: #ffffff; --bg-card-hover: #ffffff;
        --border-subtle: #dbe0e6; --border-hover: #a0a0a0; --text-main: #1a1a1a; --text-muted: #666666;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        --card-shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --blur-color: rgba(0,0,0,0.4); --spotlight-opacity: 0.05;
    }
    body { background-color: var(--bg-body); color: var(--text-main); font-family: var(--font-body); margin: 0; padding: 0; overflow-x: hidden; transition: background-color 0.5s ease, color 0.5s ease; }
    .mono-noise { position: fixed; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.06; pointer-events: none; z-index: -1; }
    .spotlight { position: fixed; top: -50%; left: 50%; transform: translateX(-50%); width: 100vw; height: 100vw; background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%); filter: blur(80px); pointer-events: none; z-index: -1; opacity: var(--spotlight-opacity); transition: opacity 0.5s ease; }
    .toolbar { position: absolute; top: 2rem; right: 2rem; display: flex; gap: 10px; z-index: 100; }
    .tool-btn { background: var(--bg-card); border: 1px solid var(--border-subtle); color: var(--text-muted); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.3s; text-decoration: none; font-size: 1.1rem; }
    .tool-btn:hover { border-color: var(--text-main); color: var(--text-main); transform: scale(1.05); background: var(--bg-card-hover); box-shadow: 0 0 15px rgba(0,0,0,0.1); }
    .container { max-width: 1800px; margin: 0 auto; padding: 3rem; }
    .header-split { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 4rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 2rem; position: relative; }
    .subtitle { color: var(--text-muted); text-transform: uppercase; letter-spacing: 3px; font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.5rem; }
    h1 { font-family: var(--font-heading); font-size: clamp(1.8rem, 6vw, 4rem); margin: 0; line-height: 1.1; color: var(--text-main); transition: color 0.5s ease; word-wrap: break-word; overflow-wrap: break-word; hyphens: auto; max-width: 100%; }
    .mobile-join-btn { display: none; background-color: var(--bg-card); border: 1px solid var(--border-subtle); color: var(--text-main); font-family: var(--font-body); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none; padding: 12px 24px; border-radius: 4px; margin-top: 1rem; transition: all 0.3s ease; align-items: center; gap: 10px; }
    .mobile-join-btn:hover { background-color: var(--bg-card-hover); border-color: var(--text-main); transform: translateY(-2px); }
    .qr-section { display: flex; align-items: center; gap: 1.5rem; cursor: pointer; transition: transform 0.2s; }
    .qr-section:hover { transform: scale(1.02); }
    .qr-text { text-align: right; color: var(--text-muted); font-size: 0.75rem; letter-spacing: 1px; line-height: 1.4; }
    .qr-wrapper { background: white; padding: 8px; border-radius: 4px; display: inline-block; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
    .overlay { position: fixed; inset: 0; background: rgba(5,5,5,0.92); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); z-index: 9999; display: flex; align-items: center; justify-content: center; flex-direction: column; opacity: 0; pointer-events: none; transition: opacity 0.4s ease; cursor: pointer; }
    .overlay.active { opacity: 1; pointer-events: all; }
    .qr-overlay-content { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 0 100px rgba(0,0,0,0.5); transform: scale(0.9); transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); text-align: center; }
    .overlay.active .qr-overlay-content { transform: scale(1); }
    .overlay-instruction { margin-top: 30px; color: var(--text-muted); font-family: var(--font-body); letter-spacing: 2px; text-transform: uppercase; font-size: 0.8rem; }
    .focus-text { font-family: var(--font-heading); color: #ffffff; font-size: clamp(1.5rem, 4vw, 3.5rem); line-height: 1.3; max-width: 80%; text-align: center; transform: translateY(20px); transition: transform 0.4s ease; }
    .overlay.active .focus-text { transform: translateY(0); }
    .dashboard-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 2rem; width: 100%; }
    .column { min-width: 0; }
    .column h2 { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); border-bottom: 1px solid var(--border-subtle); padding-bottom: 1rem; margin: 0 0 1.5rem 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; transition: border-color 0.5s ease; }
    .idea-card-wrapper { margin-bottom: 1rem; perspective: 1000px; }
    .idea-card { background: var(--bg-card); border: 1px solid var(--border-subtle); padding: 1.25rem; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden; font-size: 0.95rem; line-height: 1.5; color: var(--text-main); box-shadow: var(--card-shadow); }
    .idea-card.animate-in { animation: slideIn 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
    @keyframes slideIn { from { opacity: 0; transform: translateY(-20px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .idea-card:hover { border-color: var(--border-hover); background: var(--bg-card-hover); box-shadow: var(--card-shadow-hover); transform: translateY(-2px); }
    .idea-card.blurred { color: var(--blur-color); cursor: default; filter: blur(5px); }
    .idea-card.blurred:hover { border-color: var(--border-subtle); background: var(--bg-card); box-shadow: var(--card-shadow); transform: none; }
    @media (max-width: 1400px) { .dashboard-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 900px) {
        .dashboard-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .container { padding: 2rem 1rem; }
        .header-split { flex-direction: column; align-items: flex-start; gap: 2rem; }
        .qr-section { display: none !important; }
        .mobile-join-btn { display: inline-flex; }
    }
    @media (max-width: 600px) {
        .dashboard-grid { grid-template-columns: 1fr; }
        .toolbar { top: 1rem; right: 1rem; }
    }
</style>
