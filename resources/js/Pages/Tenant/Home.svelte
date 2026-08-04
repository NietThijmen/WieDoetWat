<script lang="ts">
    import TaskSpinner from '../../Components/TaskSpinner.svelte'
    import type { Task } from '../../types/task'

    interface Props {
        tasks: Task[];
        targetTask: Task | null;
    }

    let { tasks, targetTask }: Props = $props()
</script>

<svelte:head>
    <title>Home - Wie Doet Wat</title>
</svelte:head>

<main class="flex min-h-screen flex-col bg-zz-background">
    <TaskSpinner
        {tasks}
        {targetTask}
        title="Wie doet wat?"
        subtitle="Draai aan het wiel om jouw taak te onthullen."
        buttonText="Draai het wiel"
    />
</main>
