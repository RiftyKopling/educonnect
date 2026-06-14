@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 rounded-md shadow-sm block w-full']) }}
    style="outline: none;"
    onfocus="this.style.borderColor='#111827'; this.style.boxShadow='0 0 0 1px #111827'; this.style.backgroundColor='white';"
    onblur="this.style.borderColor=''; this.style.boxShadow=''; this.style.backgroundColor='';">