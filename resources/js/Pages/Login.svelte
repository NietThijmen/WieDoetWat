<script>
    import { useForm } from '@inertiajs/svelte'
    import Button from '@/Components/Button.svelte'
    import Input from '@/Components/Input.svelte'
    import Label from '@/Components/Label.svelte'

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    })

    function submit(e) {
        e.preventDefault()
        form.post('/login')
    }
</script>

<svelte:head>
    <title>Inloggen - Wie Doet Wat</title>
</svelte:head>

<div class="flex min-h-screen w-screen items-center justify-center bg-zz-background">
    <div class="flex w-full max-w-[560px] flex-col gap-8 rounded-md bg-zz-background-300 p-12" style="box-shadow: 0 0 15px 0 rgba(0, 0, 0, 0.15);">
        <div class="flex flex-col gap-2 text-center">
            <h2 class="text-zz-primary">Inloggen</h2>
            <p class="text-zz-text-light">
                Log in op jouw huishouden
            </p>
        </div>

        <form onsubmit={submit} class="flex flex-col gap-6">
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
                <Label for="password">Wachtwoord</Label>
                <Input
                    id="password"
                    type="password"
                    placeholder="Jouw wachtwoord"
                    autocomplete="current-password"
                    required
                    bind:value={form.password}
                    error={form.errors.password}
                />
            </div>

            <div class="flex items-center gap-2">
                <input
                    id="remember"
                    type="checkbox"
                    bind:checked={form.remember}
                    class="h-4 w-4 cursor-pointer accent-zz-primary"
                />
                <Label for="remember" class="cursor-pointer font-normal">Blijf ingelogd</Label>
            </div>

            <div class="flex flex-col gap-3 pt-2">
                <Button
                    type="submit"
                    variant="secondary"
                    class="w-full"
                    disabled={form.processing}
                >
                    {form.processing ? 'Inloggen...' : 'Inloggen'}
                </Button>

                <p class="text-center text-[14px] text-zz-text-light">
                    Nog geen account?
                    <a href="/register" class="ml-1 text-zz-primary underline hover:text-zz-primary-600 transition-colors duration-200">Registreren</a>
                </p>
            </div>
        </form>
    </div>
</div>
