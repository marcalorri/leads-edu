<div class="language-switcher">
    <select 
        onchange="window.location.href = this.value" 
        class="select select-sm bg-white/10 text-white border-white/20 hover:bg-white/20 focus:border-white/30 focus:outline-none cursor-pointer font-medium"
    >
        <option value="{{ route('locale.change', 'en') }}" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>
            EN
        </option>
        <option value="{{ route('locale.change', 'es') }}" {{ app()->getLocale() === 'es' ? 'selected' : '' }}>
            ES
        </option>
    </select>
</div>
