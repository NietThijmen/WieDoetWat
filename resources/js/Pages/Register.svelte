<script>
    import { useForm } from '@inertiajs/svelte'
    import Button from '@/Components/Button.svelte'
    import Input from '@/Components/Input.svelte'
    import Label from '@/Components/Label.svelte'

    const form = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        subdomain: '',
    })

    let isCheckingSubdomain = $state(false)
    let subdomainAvailable = $state(null)
    let subdomainTouched = $state(false)
    let checkTimeout = null

    function handleSubdomainInput(e) {
        form.subdomain = e.target.value.toLowerCase().replace(/[^a-z0-9-]/g, '')
        subdomainTouched = true

        if (checkTimeout) {
            clearTimeout(checkTimeout)
        }

        if (form.subdomain.length < 3) {
            subdomainAvailable = null
            return
        }

        subdomainAvailable = null
        isCheckingSubdomain = true

        checkTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`/subdomain/check?subdomain=${encodeURIComponent(form.subdomain)}`, {
                    headers: { 'Accept': 'application/json' },
                })
                subdomainAvailable = response.ok
            } catch {
                subdomainAvailable = false
            }
            isCheckingSubdomain = false
        }, 400)
    }

    function submit(e) {
        e.preventDefault()
        form.post('/register')
    }

    let subdomainStatusClass = $derived(
        subdomainAvailable === true ? 'border-green-500' :
        subdomainAvailable === false ? 'border-red-500' :
        ''
    )

    let subdomainStatusText = $derived(
        isCheckingSubdomain ? 'Controleren...' :
        subdomainAvailable === true ? 'Beschikbaar' :
        subdomainAvailable === false ? 'Niet beschikbaar' :
        ''
    )

    let subdomainStatusColor = $derived(
        isCheckingSubdomain ? 'text-zz-text-light' :
        subdomainAvailable === true ? 'text-green-600' :
        subdomainAvailable === false ? 'text-red-600' :
        'text-transparent'
    )
</script>

<svelte:head>
    <title>Registreren - Wie Doet Wat</title>
</svelte:head>

<div class="flex min-h-screen w-screen items-center justify-center bg-zz-background">
    <div class="flex w-full max-w-[560px] flex-col gap-8 rounded-md bg-zz-background-300 p-12" style="box-shadow: 0 0 15px 0 rgba(0, 0, 0, 0.15);">
        <div class="flex flex-col gap-2 text-center">
            <h2 class="text-zz-primary">Registreren</h2>
            <p class="text-zz-text-light">
                Maak een account aan voor jouw huishouden
            </p>
        </div>

        <form onsubmit={submit} class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <Label for="name">Naam</Label>
                <Input
                    id="name"
                    type="text"
                    placeholder="Jouw naam"
                    autocomplete="name"
                    required
                    bind:value={form.name}
                    error={form.errors.name}
                />
            </div>

            <div class="flex flex-col gap-2">
                <Label for="email">E-mailadres</Label>
                <Input
                    id="email"
                    type="email"
                    placeholder="jouw@email.nl"
                    autocomplete="email"
                    required
                    bind:value={form.email}
                    error={form.errors.email}
                />
            </div>

            <div class="flex flex-col gap-2">
                <Label for="subdomain">Subdomein voor jullie huishouden</Label>
                <div class="relative">
                    <Input
                        id="subdomain"
                        type="text"
                        placeholder="ons-huishouden"
                        autocomplete="off"
                        required
                        value={form.subdomain}
                        oninput={handleSubdomainInput}
                        error={form.errors.subdomain}
                        class={subdomainTouched ? subdomainStatusClass : ''}
                    />
                    {#if subdomainTouched && subdomainStatusText}
                        <p class="absolute right-3 top-1/2 -translate-y-1/2 text-[12px] {subdomainStatusColor}">
                            {#if isCheckingSubdomain}
                                ⏳ {subdomainStatusText}
                            {:else}
                                {subdomainStatusText}
                            {/if}
                        </p>
                    {/if}
                </div>
                {#if form.subdomain.length >= 3}
                    <p class="text-[12px] text-zz-text-light">
                        Jullie adres wordt: <strong>{form.subdomain}.wie-doet-wat.test</strong>
                    </p>
                {/if}
            </div>

            <div class="flex flex-col gap-2">
                <Label for="password">Wachtwoord</Label>
                <Input
                    id="password"
                    type="password"
                    placeholder="Minimaal 8 tekens"
                    autocomplete="new-password"
                    required
                    bind:value={form.password}
                    error={form.errors.password}
                />
            </div>

            <div class="flex flex-col gap-2">
                <Label for="password_confirmation">Wachtwoord bevestigen</Label>
                <Input
                    id="password_confirmation"
                    type="password"
                    placeholder="Herhaal je wachtwoord"
                    autocomplete="new-password"
                    required
                    bind:value={form.password_confirmation}
                    error={form.errors.password_confirmation}
                />
            </div>

            <div class="flex flex-col gap-3 pt-2">
                <Button
                    type="submit"
                    variant="secondary"
                    class="w-full"
                    disabled={form.processing}
                >
                    {form.processing ? 'Account aanmaken...' : 'Account aanmaken'}
                </Button>

                <p class="text-center text-[14px] text-zz-text-light">
                    Heb je al een account?
                    <a href="/login" class="ml-1 text-zz-primary underline hover:text-zz-primary-600 transition-colors duration-200">Inloggen</a>
                </p>
            </div>
        </form>
    </div>
</div>
