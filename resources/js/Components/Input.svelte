<script>
    let {
        id = '',
        name = '',
        type = 'text',
        value = $bindable(''),
        autocomplete = 'off',
        required = false,
        disabled = false,
        placeholder = '',
        class: className = '',
        error = '',
        oninput = undefined,
        ...restProps
    } = $props()

    function handleInput(e) {
        value = e.target.value
        if (oninput) {
            oninput(e)
        }
    }
</script>

<div class="w-full">
    <input
        {id}
        {name}
        {type}
        {required}
        {disabled}
        {placeholder}
        {autocomplete}
        {value}
        {oninput}
        class="flex h-[34px] w-full border-2 border-zz-background-600 border-solid rounded-md bg-zz-background-50 px-3 font-body text-[16px] text-zz-text outline-none transition-colors duration-200 caret-zz-primary placeholder:text-zz-text-light focus:border-zz-primary {error ? 'border-red-500' : ''} {disabled ? 'opacity-50 cursor-not-allowed' : ''} {className}"
        {...restProps}
    />
    {#if error}
        <p class="mt-1 text-red-600 text-[14px] font-body">{error}</p>
    {/if}
</div>
