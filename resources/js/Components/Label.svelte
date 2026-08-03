<script>
    import { Label } from 'bits-ui'

    let {
        for: forId = '',
        class: className = '',
        children,
    } = $props()
</script>

<Label.Root for={forId} class="text-[16px] font-body font-semibold text-zz-text {className}">
    {#if children}
        {@render children()}
    {/if}
</Label.Root>
