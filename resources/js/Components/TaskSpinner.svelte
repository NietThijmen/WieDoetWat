<script lang="ts">
    import type { Task } from '../types/task'

    interface SpinnerItem extends Task {
        uniqueKey: string;
        repetition: number;
    }

    interface Props {
        tasks: Task[];
        targetTask: Task;
        title?: string;
        subtitle?: string;
        buttonText?: string;
        audioSrc?: string;
        onSpinComplete?: () => void;
    }

    let {
        tasks,
        targetTask,
        title = 'Wie doet wat?',
        subtitle = 'Draai aan het wiel om jouw taak te onthullen.',
        buttonText = 'Draai het wiel',
        audioSrc = '',
        onSpinComplete,
    }: Props = $props()

    let listElement: HTMLDivElement | null = $state(null)
    let containerElement: HTMLDivElement | null = $state(null)
    let spinnerItems: SpinnerItem[] = $state([])
    let translateX = $state(0)
    let isSpinning = $state(false)
    let hasSpun = $state(false)
    let transitionDuration = $state(0)
    let lastTickIndex = $state(-1)
    let rafId: number | null = null
    let audioElement: HTMLAudioElement | null = null

    const REPETITIONS = 11
    const TARGET_REPETITION = 10
    const DURATION_SECONDS = 10

    function shuffle<T>(array: T[]): T[] {
        const copy = [...array]
        for (let i = copy.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1))
            ;[copy[i], copy[j]] = [copy[j], copy[i]]
        }
        return copy
    }

    function buildItems(): SpinnerItem[] {
        const items: SpinnerItem[] = []
        const targetIndex = Math.max(0, tasks.findIndex(task => task.id === targetTask.id))

        for (let repetition = 0; repetition < REPETITIONS; repetition++) {
            const isTargetRepetition = repetition === TARGET_REPETITION
            const baseTasks = repetition === 0 ? tasks : shuffle(tasks)
            const orderedTasks = isTargetRepetition
                ? [
                    ...tasks.slice(targetIndex),
                    ...tasks.slice(0, targetIndex),
                ]
                : baseTasks

            for (const task of orderedTasks) {
                items.push({
                    ...task,
                    uniqueKey: `${task.id}-${repetition}-${crypto.randomUUID?.() ?? Math.random().toString(36).slice(2)}`,
                    repetition,
                })
            }
        }

        return items
    }

    function getTargetElement(): HTMLElement | null {
        if (!listElement) return null
        const targetIndex = TARGET_REPETITION * tasks.length
        return listElement.querySelector<HTMLElement>(`[data-index="${targetIndex}"]`)
    }

    function getCenterIndex(): number {
        if (!listElement || !containerElement) return 0

        const containerRect = containerElement.getBoundingClientRect()
        const containerCenter = containerRect.left + containerRect.width / 2
        const itemElements = listElement.querySelectorAll<HTMLElement>('.task-item')

        let closestIndex = 0
        let closestDistance = Infinity

        itemElements.forEach((item, index) => {
            const rect = item.getBoundingClientRect()
            const itemCenter = rect.left + rect.width / 2
            const distance = Math.abs(itemCenter - containerCenter)

            if (distance < closestDistance) {
                closestDistance = distance
                closestIndex = index
            }
        })

        return closestIndex
    }

    function playTick(): void {
        if (audioElement) {
            audioElement.currentTime = 0
            audioElement.play().catch(() => {})
            return
        }

        try {
            const AudioContext = window.AudioContext || (window as any).webkitAudioContext
            if (!AudioContext) return

            const ctx = new AudioContext()
            const oscillator = ctx.createOscillator()
            const gainNode = ctx.createGain()

            oscillator.type = 'triangle'
            oscillator.frequency.setValueAtTime(800, ctx.currentTime)
            gainNode.gain.setValueAtTime(0.05, ctx.currentTime)
            gainNode.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.05)

            oscillator.connect(gainNode)
            gainNode.connect(ctx.destination)

            oscillator.start(ctx.currentTime)
            oscillator.stop(ctx.currentTime + 0.05)
        } catch {
            // Ignore audio errors
        }
    }

    function trackTicks(): void {
        if (rafId !== null) {
            cancelAnimationFrame(rafId)
        }

        lastTickIndex = -1

        function loop(): void {
            if (!isSpinning) return

            const index = getCenterIndex()
            if (index !== lastTickIndex) {
                playTick()
                lastTickIndex = index
            }

            rafId = requestAnimationFrame(loop)
        }

        rafId = requestAnimationFrame(loop)
    }

    function handleSpin(): void {
        if (isSpinning || tasks.length === 0) return

        isSpinning = true
        hasSpun = false
        spinnerItems = buildItems()
        translateX = 0
        transitionDuration = 0

        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                const targetElement = getTargetElement()
                if (!targetElement || !containerElement) {
                    isSpinning = false
                    return
                }

                const targetRect = targetElement.getBoundingClientRect()
                const containerRect = containerElement.getBoundingClientRect()
                const targetCenter = targetRect.left + targetRect.width / 2
                const containerCenter = containerRect.left + containerRect.width / 2
                const distanceToCenter = targetCenter - containerCenter

                transitionDuration = DURATION_SECONDS
                translateX = -distanceToCenter
                trackTicks()

                setTimeout(() => {
                    isSpinning = false
                    hasSpun = true
                    if (rafId !== null) {
                        cancelAnimationFrame(rafId)
                        rafId = null
                    }
                    onSpinComplete?.()
                }, DURATION_SECONDS * 1000)
            })
        })
    }

    $effect(() => {
        return () => {
            if (rafId !== null) {
                cancelAnimationFrame(rafId)
            }
        }
    })
</script>

<div class="flex flex-col shadow-[inset_0px_-32px_10px_-30px_rgba(0,0,0,0.23),inset_0px_32px_10px_-30px_rgba(0,0,0,0.23)]">
    <div class="grid w-[100vw] items-center justify-center" style="grid-template-rows: auto 1fr auto;">
        <div class="dummy" aria-hidden="true"></div>

        <div class="flex w-[100vw] flex-col items-center justify-center">
            <div class="flex flex-col items-center">
                <h1 class="title text-zz-primary">{title}</h1>
                {#if subtitle}
                    <div class="grid items-center" style="grid-template-columns: 1fr auto 1fr;">
                        <svg
                            class="hidden h-auto w-64 stroke-zz-text-light md:block lg:w-64"
                            style="transform: rotate(335deg) scaleX(-1) translate(10px, 10px); stroke-width: 8px; fill: none;"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 190.285"
                            shape-rendering="geometricPrecision"
                            text-rendering="geometricPrecision"
                            image-rendering="optimizeQuality"
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            aria-hidden="true"
                        >
                            <path
                                d="M10,120 C150,30 300,100 260,140 C220,180 140,150 180,100 C250,20 400,60 495,70 L440,35 L495,70 L440,95"
                            />
                        </svg>
                        <p class="text-zz-text-light">{subtitle}</p>
                        <span aria-hidden="true"></span>
                    </div>
                {/if}
            </div>

            <div class="flex w-full flex-col gap-8">
                <div class="flex w-full flex-col items-center justify-center rounded-lg">
                    <div class="flex h-80 w-1 bg-zz-background-50 absolute z-[9]" aria-hidden="true"></div>
                    <div class="flex h-80 w-1 bg-zz-background-50 absolute z-[9]" aria-hidden="true"></div>

                    <div
                        bind:this={containerElement}
                        class="w-full overflow-hidden rounded-b-lg"
                        style="background: linear-gradient(90deg, rgba(253, 252, 251, 0.50) 0%, rgba(253, 252, 251, 0.25) 5%, rgba(0, 0, 0, 0) 15%, rgba(0, 0, 0, 0) 85%, rgba(253, 252, 251, 0.25) 95%, rgba(253, 252, 251, 0.50) 100%);"
                    >
                        <div
                            bind:this={listElement}
                            class="flex h-[148px] w-full flex-row items-center"
                            style="transform: translateX({translateX}px); transition: transform {transitionDuration}s cubic-bezier(0.33, 1, 0.68, 1);"
                            aria-label="Slider met alle taken"
                            role="list"
                        >
                            {#each spinnerItems as item, index (item.uniqueKey)}
                                <div
                                    data-index={index}
                                    data-repetition={item.repetition}
                                    class="task-item flex h-full items-center justify-center text-zz-text-white"
                                    class:bg-zz-primary-400={index % 2 === 0}
                                    class:bg-zz-secondary-400={index % 2 !== 0}
                                    class:scale-125={hasSpun && item.id === targetTask.id && item.repetition === TARGET_REPETITION}
                                    class:z-[1]={hasSpun && item.id === targetTask.id && item.repetition === TARGET_REPETITION}
                                    style="min-width: 33.3334%; width: 33.3334%;"
                                    role="listitem"
                                >
                                    {item.title}
                                </div>
                            {:else}
                                <div class="flex h-full w-full items-center justify-center bg-zz-background-300 text-zz-text">
                                    Geen taken beschikbaar
                                </div>
                            {/each}
                        </div>
                    </div>
                </div>

                <div class="flex flex-col items-center justify-center gap-4">
                    <button
                        type="button"
                        onclick={handleSpin}
                        disabled={isSpinning || tasks.length === 0}
                        class="flex w-fit items-center justify-center overflow-hidden rounded-full bg-zz-secondary px-4 py-2 text-zz-text-white opacity-100 transition-opacity duration-200 hover:bg-zz-secondary-600 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span>{buttonText}</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="dummy" aria-hidden="true"></div>
    </div>
</div>

{#if audioSrc}
    <audio bind:this={audioElement} src={audioSrc} preload="auto" aria-hidden="true"></audio>
{/if}

<style>
    @media (max-width: 768px) {
        .task-item {
            min-width: 100% !important;
            width: 100% !important;
        }
    }
</style>
