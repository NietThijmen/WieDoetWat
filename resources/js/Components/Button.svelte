<script>
    import { Button } from 'bits-ui'

    let {
        variant = 'primary',
        type = 'submit',
        disabled = false,
        class: className = '',
        children,
        ...restProps
    } = $props()

    const baseClasses = 'flex w-fit items-center justify-center px-[20px] py-[10px] rounded-[12px] border-2 border-zz-primary text-[16px] font-body font-semibold cursor-pointer transition-all duration-200 ease disabled:opacity-50 disabled:cursor-not-allowed'

    const variantClasses = {
        primary: 'bg-zz-primary text-zz-white hover:bg-zz-primary-600',
        secondary: 'bg-transparent text-zz-primary hover:bg-zz-primary/10',
    }
</script>

<Button.Root
    {type}
    {disabled}
    class="{baseClasses} {variantClasses[variant]} {className}"
    {...restProps}
>
    {#if children}
        {@render children()}
    {/if}
</Button.Root>
