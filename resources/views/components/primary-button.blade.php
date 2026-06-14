<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150']) }}
    style="background-color: #111827; outline: none;"
    onmouseover="this.style.backgroundColor='#1f2937';"
    onmouseout="this.style.backgroundColor='#111827';"
    onfocus="this.style.outline='none'; this.style.boxShadow='0 0 0 2px #111827';"
    onblur="this.style.boxShadow=''; this.style.backgroundColor='#111827';">
    {{ $slot }}
</button>