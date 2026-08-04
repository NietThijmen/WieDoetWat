<script lang="ts">
    import { page } from '@inertiajs/svelte'
    import TaskSpinner from '../../Components/TaskSpinner.svelte'
    import type { Task } from '../../types/task'

    interface Props {
        tasks: Task[];
        targetTask: Task | null;
    }

    let { tasks, targetTask }: Props = $props()

    const authUser = $derived($page.props.auth as { id: number; name: string; email: string; is_admin: boolean } | null)
</script>

<svelte:head>
    <title>Home - Wie Doet Wat</title>
</svelte:head>

<main class="flex min-h-screen flex-col bg-zz-background">
    <header class="flex items-center justify-end gap-4 p-6">
        {#if authUser?.is_admin}
            <a
                href="/admin"
                class="rounded-full border-2 border-zz-primary px-4 py-2 text-zz-primary transition-colors hover:bg-zz-primary/10"
            >
                Beheer
            </a>
        {/if}
    </header>

    <TaskSpinner
        {tasks}
        {targetTask}
        title="Wie doet wat?"
        subtitle="Draai aan het wiel om jouw taak te onthullen."
        buttonText="Draai het wiel"
    />
</main>
