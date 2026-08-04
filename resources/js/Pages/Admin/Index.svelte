<script lang="ts">
    import { router, useForm } from '@inertiajs/svelte'
    import Button from '../../Components/Button.svelte'
    import Input from '../../Components/Input.svelte'
    import Label from '../../Components/Label.svelte'
    import type { AdminTask, AdminUser } from '../../types/admin'

    interface Props {
        users: AdminUser[];
        tasks: AdminTask[];
        flash?: {
            error?: string;
        };
    }

    let { users, tasks, flash }: Props = $props()

    const userForm = useForm<{
        name: string;
        email: string;
        password: string;
        password_confirmation: string;
        is_admin: boolean;
    }>({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        is_admin: false,
    })

    const taskForm = useForm<{
        title: string;
        description: string;
        weight: number;
    }>({
        title: '',
        description: '',
        weight: 1,
    })

    function submitUser(e: SubmitEvent): void {
        e.preventDefault()
        $userForm.post('/admin/users', {
            preserveScroll: true,
            onSuccess: () => $userForm.reset(),
        })
    }

    function submitTask(e: SubmitEvent): void {
        e.preventDefault()
        $taskForm.post('/admin/tasks', {
            preserveScroll: true,
            onSuccess: () => $taskForm.reset(),
        })
    }

    function deleteUser(user: AdminUser): void {
        if (!confirm(`Weet je zeker dat je ${user.name} wilt verwijderen?`)) {
            return
        }

        router.delete(`/admin/users/${user.id}`, { preserveScroll: true })
    }

    function deleteTask(task: AdminTask): void {
        if (!confirm(`Weet je zeker dat je "${task.title}" wilt verwijderen?`)) {
            return
        }

        router.delete(`/admin/tasks/${task.id}`, { preserveScroll: true })
    }
</script>

<svelte:head>
    <title>Beheer - Wie Doet Wat</title>
</svelte:head>

<main class="min-h-screen bg-zz-background p-8">
    <div class="mx-auto max-w-6xl space-y-12">
        <div class="flex items-center justify-between">
            <h1 class="text-5xl font-heading text-zz-primary">Beheer</h1>
            <a
                href="/home"
                class="rounded-full border-2 border-zz-primary px-4 py-2 text-zz-primary transition-colors hover:bg-zz-primary/10"
            >
                Terug naar home
            </a>
        </div>

        {#if flash?.error}
            <div class="rounded-lg border-2 border-red-500 bg-red-50 p-4 text-red-700" role="alert">
                {flash.error}
            </div>
        {/if}

        <section class="rounded-2xl bg-zz-white p-6 shadow-sm">
            <h2 class="mb-6 text-3xl font-heading text-zz-primary">Gebruikers</h2>

            <form onsubmit={submitUser} class="mb-8 grid gap-4 rounded-xl bg-zz-background-50 p-4 sm:grid-cols-2 lg:grid-cols-5">
                <div>
                    <Label for="name">Naam</Label>
                    <Input id="name" name="name" type="text" bind:value={$userForm.name} error={$userForm.errors.name} required />
                </div>
                <div>
                    <Label for="email">E-mail</Label>
                    <Input id="email" name="email" type="email" bind:value={$userForm.email} error={$userForm.errors.email} required />
                </div>
                <div>
                    <Label for="password">Wachtwoord</Label>
                    <Input id="password" name="password" type="password" bind:value={$userForm.password} error={$userForm.errors.password} required />
                </div>
                <div>
                    <Label for="password_confirmation">Bevestig wachtwoord</Label>
                    <Input id="password_confirmation" name="password_confirmation" type="password" bind:value={$userForm.password_confirmation} error={$userForm.errors.password_confirmation} required />
                </div>
                <div class="flex items-end gap-4">
                    <label class="flex items-center gap-2 text-zz-text">
                        <input
                            type="checkbox"
                            name="is_admin"
                            bind:checked={$userForm.is_admin}
                            class="h-5 w-5 accent-zz-primary"
                        />
                        Admin
                    </label>
                    <Button type="submit" disabled={$userForm.processing} class="w-full sm:w-auto">Toevoegen</Button>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b-2 border-zz-background-600 text-zz-text-light">
                            <th class="pb-2 font-body font-semibold">Naam</th>
                            <th class="pb-2 font-body font-semibold">E-mail</th>
                            <th class="pb-2 font-body font-semibold">Rol</th>
                            <th class="pb-2 font-body font-semibold">Toegevoegd</th>
                            <th class="pb-2 font-body font-semibold"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zz-background-600">
                        {#each users as user (user.id)}
                            <tr class="text-zz-text">
                                <td class="py-3">{user.name}</td>
                                <td class="py-3">{user.email}</td>
                                <td class="py-3">{user.is_admin ? 'Admin' : 'Gebruiker'}</td>
                                <td class="py-3">{new Date(user.created_at).toLocaleDateString('nl-NL')}</td>
                                <td class="py-3 text-right">
                                    <button
                                        type="button"
                                        onclick={() => deleteUser(user)}
                                        class="text-red-600 underline-offset-2 hover:underline"
                                    >
                                        Verwijderen
                                    </button>
                                </td>
                            </tr>
                        {:else}
                            <tr>
                                <td colspan="5" class="py-6 text-center text-zz-text-light">Geen gebruikers gevonden.</td>
                            </tr>
                        {/each}
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-2xl bg-zz-white p-6 shadow-sm">
            <h2 class="mb-6 text-3xl font-heading text-zz-primary">Taken</h2>

            <form onsubmit={submitTask} class="mb-8 grid gap-4 rounded-xl bg-zz-background-50 p-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <Label for="title">Titel</Label>
                    <Input id="title" name="title" type="text" bind:value={$taskForm.title} error={$taskForm.errors.title} required />
                </div>
                <div>
                    <Label for="description">Omschrijving</Label>
                    <Input id="description" name="description" type="text" bind:value={$taskForm.description} error={$taskForm.errors.description} />
                </div>
                <div>
                    <Label for="weight">Gewicht</Label>
                    <Input id="weight" name="weight" type="number" min="1" max="100" bind:value={$taskForm.weight} error={$taskForm.errors.weight} required />
                </div>
                <div class="flex items-end">
                    <Button type="submit" disabled={$taskForm.processing} class="w-full sm:w-auto">Toevoegen</Button>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b-2 border-zz-background-600 text-zz-text-light">
                            <th class="pb-2 font-body font-semibold">Titel</th>
                            <th class="pb-2 font-body font-semibold">Omschrijving</th>
                            <th class="pb-2 font-body font-semibold">Gewicht</th>
                            <th class="pb-2 font-body font-semibold">Toewijzingen</th>
                            <th class="pb-2 font-body font-semibold"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zz-background-600">
                        {#each tasks as task (task.id)}
                            <tr class="text-zz-text">
                                <td class="py-3">{task.title}</td>
                                <td class="py-3">{task.description ?? '-'}</td>
                                <td class="py-3">{task.weight}</td>
                                <td class="py-3">{task.users_count}</td>
                                <td class="py-3 text-right">
                                    <button
                                        type="button"
                                        onclick={() => deleteTask(task)}
                                        class="text-red-600 underline-offset-2 hover:underline"
                                    >
                                        Verwijderen
                                    </button>
                                </td>
                            </tr>
                        {:else}
                            <tr>
                                <td colspan="5" class="py-6 text-center text-zz-text-light">Geen taken gevonden.</td>
                            </tr>
                        {/each}
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>
