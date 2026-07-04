<!-- Premium Footer -->
<footer class="bg-brand-dark2 border-t border-white/5 py-14 px-6 mt-20 font-body">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-10 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-brand-accent text-white font-display font-black px-3 py-1.5 rounded-lg text-base shadow-neon-blue">E2</div>
                <span class="font-display font-black text-xl text-white tracking-tight">Edukan<span class="text-brand-accent">2</span></span>
            </div>
            <p class="text-brand-text-muted text-sm leading-relaxed max-w-xs">La central de aceleración de habilidades de alto valor para las Mentes Divergentes del ecosistema global.</p>
        </div>
        <div>
            <h4 class="text-white font-display text-xs font-bold uppercase tracking-wider mb-4">🛣️ Rutas de Estudio</h4>
            <ul class="list-none p-0 m-0 flex flex-col gap-2.5 text-sm">
                <li><a href="{{ route('courses.index') }}" wire:navigate class="text-brand-text-muted hover:text-white transition-colors no-underline">Automatización con IA</a></li>
                <li><a href="{{ route('courses.index') }}" wire:navigate class="text-brand-text-muted hover:text-white transition-colors no-underline">Importación Internacional</a></li>
                <li><a href="{{ route('courses.index') }}" wire:navigate class="text-brand-text-muted hover:text-white transition-colors no-underline">Negocios de Alta Rotación</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-white font-display text-xs font-bold uppercase tracking-wider mb-4">🔒 Soporte Especializado</h4>
            <p class="text-brand-text-muted text-sm leading-relaxed mb-4">¿Tienes dudas o inconvenientes con tus accesos? Escríbenos directamente de forma inmediata.</p>
            <a href="https://wa.me/584245318103" target="_blank" class="bg-[#25d366] hover:bg-[#25d366]/90 text-black text-xs font-bold px-4 py-2 rounded-lg no-underline inline-block shadow-md">🟢 Soporte Directo WhatsApp</a>
        </div>
    </div>
    <div class="max-w-6xl mx-auto border-t border-white/5 pt-5 flex flex-col sm:flex-row justify-between items-center gap-4 text-[11px] text-brand-text-muted">
        <span>© 2026 Edukan2 · La Universidad de las Mentes Divergentes. Todos los derechos reservados.</span>
        <div class="flex gap-4">
            <span class="opacity-30">v1.5.0 Staging Live (Tailwind v4)</span>
        </div>
    </div>
</footer>
