# Style & Design Guide — Wie Doet Wat

This guide defines the visual language, design tokens, and component patterns for the platform. It is derived from the original WordPress project (`_old_project`) and adapted for the new Laravel v13 + Tailwind v4 stack.

---

## 1. Design Philosophy

**Warm, approachable, and slightly playful.** The platform helps households organize chores, so the design should feel inviting — not utilitarian. Think rounded shapes, warm beige backgrounds, soft blur effects, and a touch of whimsy via animated geometric backgrounds.

Key principles:
- **Soft & friendly** — rounded borders, smooth transitions, glass-morphism overlays
- **High contrast where it counts** — dark text on light backgrounds, bright accent colors for CTAs
- **Motion with purpose** — GSAP-powered scroll animations, floating background shapes, smooth state transitions
- **Dutch-language UI** — labels, buttons, and copy are in Dutch

---

## 2. Color Palette

All colors use the `--color-zz-*` namespace in Tailwind v4. Token names match the old project's `zz-` prefix for consistency.

### Primary — Blue

```
--color-zz-primary-50:  #eef0f6
--color-zz-primary-100: #cbd1e3
--color-zz-primary-200: #b2bbd5
--color-zz-primary-300: #8f9cc2
--color-zz-primary-400: #7989b6
--color-zz-primary:      #586ba4    ← Default
--color-zz-primary-600: #506195
--color-zz-primary-700: #3e4c74
--color-zz-primary-800: #303b5a
--color-zz-primary-900: #252d45
```

**Usage:** Page titles (`.title`), header text, footer backgrounds, hero square shapes, border accents on cards.

### Secondary — Orange

```
--color-zz-secondary-50:  #fdf3e9
--color-zz-secondary-100: #f8d9ba
--color-zz-secondary-200: #f5c698
--color-zz-secondary-300: #f0ad69
--color-zz-secondary-400: #ed9d4c
--color-zz-secondary:      #e9841f    ← Default
--color-zz-secondary-600: #d4781c
--color-zz-secondary-700: #a55e16
--color-zz-secondary-800: #804911
--color-zz-secondary-900: #62370d
```

**Usage:** Primary CTA buttons, hero circle shapes, nav button backgrounds, spinner task backgrounds (odd items), submenu left-border accent.

### Background — Warm Beige

```
--color-zz-background-50:  #fdfcfb   ← Card background
--color-zz-background-300: #f4f3ee   ← Form card, hover states
--color-zz-background-400: #f0eee6   ← Submenu background
--color-zz-background:      #edeae1   ← Page background, lines
--color-zz-background-600: #e8e5da   ← Input borders
--color-zz-background-700: #d3d0c6
```

**Usage:** Page body background, card backgrounds, form input borders, submenu backgrounds.

### Text Colors

```
--color-zz-text:       #2d2d2d   ← Default body text
--color-zz-text-light: #7f7f7f   ← Secondary/metadata text
--color-zz-text-white: #ececec   ← Text on dark backgrounds
```

### Neutrals

```
--color-zz-white: #ffffff
--color-zz-black: #000000
```

---

## 3. Typography

### Fonts

| Role       | Family       | Weight Range | Source   |
|------------|--------------|-------------|----------|
| Headings   | Noto Serif   | 100–900     | Google Fonts |
| Body       | Nunito       | 200–1000    | Google Fonts |

**Google Fonts import URL:**
```
https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap
```

### Font Size Scale

Root font size is `10px` (62.5% of default), all rem-based.

| Token | Font Size   | Line Height | Typical Use       |
|-------|-------------|-------------|-------------------|
| `sm`  | 1.4rem (14px) | 2.1rem  | Small text, dates |
| `md`  | 2.0rem (20px) | 3.0rem  | Paragraphs, labels |
| `lg`  | 2.5rem (25px) | 3.8rem  | Card person names |
| `xl`  | 3.1rem (31px) | 4.7rem  | Subheadings |
| `2xl` | 3.9rem (39px) | 5.9rem  | Section titles |
| `3xl` | 4.9rem (49px) | 7.4rem  | H5 default |
| `4xl` | 6rem   (60px) | 6rem    | H4 default |
| `5xl` | 8rem   (80px) | 8rem    | H3 default |
| `6xl` | 10rem  (100px)| 10rem   | H2 default |
| `7xl` | 12.8rem(128px)| 12.8rem | H1 default |

### Heading Scale (CLAMP-based)

Headings use **fluid `clamp()` sizing** rather than fixed breakpoints:

```css
h1 { font-size: clamp(20px, 13vw, 12.8rem); }
h2 { font-size: clamp(18px, 10vw, 10rem); }
h3 { font-size: clamp(16px, 8vw, 8rem); }
h4 { font-size: clamp(14px, 6vw, 6rem); }
h5 { font-size: clamp(12px, 4vw, 4.9rem); }
```

All headings use `font-heading` (Noto Serif). Body text uses `font-body` (Nunito) at `text-md` with `line-height: 1.5`.

---

## 4. Spacing & Layout

### Breakpoints

| Breakpoint | Width    |
|------------|----------|
| `sm`       | 576px    |
| `md`       | 768px    |
| `lg`       | 992px    |
| `xl`       | 1200px   |
| `2xl`      | 1400px   |

### Container

- Centered, with `1rem` horizontal padding
- Use `class="container"` on wrapping elements

### Section Spacing

| Class              | Desktop | Mobile |
|--------------------|---------|--------|
| `.spacing`         | 100px   | 50px   |
| `.spacing-small`   | 75px    | 40px   |
| `.spacing-smaller` | 50px    | 30px   |

Applied as vertical padding (top + bottom).

### Card Grid

4-column responsive grid with 32px gutter on both axes:

- **≥1201px:** 4 columns
- **992px–1200px:** 3 columns
- **768px–991px:** 2 columns
- **≤767px:** 1 column

### Section Height

Full-viewport height sections use `.height` → `min-h-screen`.

---

## 5. Component Patterns

### Buttons

Two variants, both with `.btn` base styles:

**Base (.btn):**
```
flex w-fit items-center justify-center px-[20px] py-[10px] rounded-[12px] border-2 border-zz-primary
```

| Variant       | Background     | Text Color       | Usage              |
|---------------|----------------|------------------|---------------------|
| `.btn-primary`| `zz-primary`   | `zz-white`       | Gravity Forms submit, logged-in nav |
| `.btn-secondary`| `zz-white`   | `zz-primary`     | Outline/secondary action |

**Nav Buttons (Hero & Section CTAs):**
- Container: `.navButtons` — flex row, `w-fit`
- `.buttonText`: rounded-full, `bg-zz-secondary`, `px-4 py-2`, overflow hidden
- `.buttonIcon`: rounded-full, `bg-zz-secondary`, `p-2`, overflow hidden (contains material icon `<span>`)

**Submit Button (inline forms):**
```
text-zz-text-white bg-zz-secondary mt-4 p-3 rounded-full w-fit self-center px-[1rem] py-[0.5rem]
```

---

### Forms

**Input fields:**
```
flex h-[34px] w-full border-solid border-zz-background-600 border-2 rounded-md
```
- Focus: `outline-none caret-zz-primary`
- Label container: flex row, `text-md`
- Error text: `text-red-600`, `text-[16px]`

**Custom checkbox:**
- Hidden native `<input type="checkbox">` (zero width/height)
- `label::before` renders a styled box: `rounded-md`, `border-2 border-zz-background-600`, `bg-zz-background-50`, `w-8 h-8`
- Checked state: uses Material Icons `"check"` character, colored `text-zz-primary`

**Gravity Forms override:**
- Inputs/textareas: `w-full px-4 py-2 bg-zz-primary/20 rounded-md text-zz-black`
- Placeholder: `text-zz-black/50`
- Submit button: uses `.btn-primary` mixin

---

### Cards

**OverviewPersonCard:**
```
relative flex flex-col items-center justify-center bg-zz-background-50 h-[250px]
text-zz-text gap-2 overflow-hidden rounded-[50px]
outline-3 outline outline-transparent
transition: 0.2s all
```

Internal structure:
- `.cardInner` — `p-6 z-[99] flex text-center w-full top-0 justify-start h-full flex-col`
  - `.person` — `text-lg` (person's name)
  - `.line` — horizontal divider: `h-[2px] w-full bg-zz-background rounded-full`
  - `.task` — flex row, `.label` uses `text-zz-text-light`
- `.checkboxContainer` — bottom checkbox area
  - `.checkbox` — hidden by default, shown when `.checked`
  - `.currentUser-checkbox` — always visible for current user
  - Checkbox styling: `flex-row gap-3 bg-zz-background-50 h-fit py-3 px-8 rounded-t-full`
- `.imgContainer` — `absolute w-full h-[150px] bottom-0` with mask gradient fade

**Checked state:**
```
bg-[#e1f0e3] outline-[#99C79F]
```

**Blurred state (pre-spin):**
When `[data-spinned="false"]`, `.taskName` gets `blur-sm` filter. Transition: `all 0.2s ease`.

---

### Header

**Layout:** Fixed, top of viewport, `z-[9999]`, `pointer-events-none` (inner container restores pointer-events).

**Inner bar:**
```
container flex justify-between flex-row items-center rounded-full px-8 py-8 mt-[32px] max-h-[96px] pointer-events-auto
```

**Glass-morphism effect:**
```css
background: rgba(255, 255, 255, 0.1);
box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1), inset 0 0 24px 12px rgba(255, 255, 255, 0.2);
backdrop-filter: blur(3px);
border: 1px solid rgba(255, 255, 255, 0.31);
```

**Title text:** `text-zz-primary-100`, hover → `text-zz-primary`. Transition: `0.2s ease`.

**User area:**
- Profile picture: `w-[64px] h-[64px] rounded-full bg-zz-background-400`
- Name: `text-zz-text`, hover → `text-zz-text-light`

**Submenu dropdown:**
```
absolute p-8 bg-zz-background-400 border-solid border-zz-primary rounded-md
opacity-0 flex gap-3 min-w-fit whitespace-nowrap text-zz-text flex-col
box-shadow: 0 0 15px 0 rgba(0, 0, 0, 0.15)
```
- Links have left border accent: `border-l-2 border-solid border-zz-secondary p-[6px]`
- Hover: `text-zz-text-light border-l-[5px]`

---

### Hero Section

**Container:** Full viewport, relative positioned, black background.

**Animated background:**
- Blur container: `filter: blur(75px)`, covers full viewport
- Blue diamond (square rotated 45°): `bg-zz-primary`, `clip-path: polygon(50% 0, 100% 50%, 50% 100%, 0 50%)`
  - Inner diamond: `bg-zz-primary-300`, 80% size
- Two orange circles: `bg-zz-secondary`, `w-[50vw] h-[50vw]`
  - Inner circle: `bg-zz-secondary-300`, 80% size
  - `#circleLeft`: positioned bottom-left, translated off-screen
  - `#circleRight`: positioned top-right, translated off-screen
- Shapes slowly rotate with GSAP + randomly float within a 200px range, subtle scale oscillation

**Content layer:**
```
flex flex-row items-end justify-between
```
- Title: `h1`, white text, `text-[128px]`, `w-min break-words`
- Description: standard `.description` block
- Nav button: standard `.navButtons` pattern

**Mobile (≤768px):** Stacks vertically, centers text.

---

### Spinner / Wheel of Tasks

**Shadow container:** Inset top and bottom shadows for depth illusion:
```css
box-shadow: inset 0px -32px 10px -30px rgba(0, 0, 0, 0.23),
            inset 0px 32px 10px -30px rgba(0, 0, 0, 0.23);
```

**Spinner layout:**
- Name/select at top: `appearance-none w-full bg-zz-background-50 text-zz-text px-2 rounded-t-md`
- Center indicator lines: `h-80 w-1 bg-zz-background-50 absolute z-[9]`
- Task strip: flex row, `h-[148px]`, alternating task backgrounds:
  - Even: `bg-zz-primary-400`
  - Odd: `bg-zz-secondary-400`
- Gradient fade at edges:
```css
background: linear-gradient(90deg,
  rgba(253,252,251,0.50) 0%,
  rgba(253,252,251,0.25) 5%,
  transparent 15%,
  transparent 85%,
  rgba(253,252,251,0.25) 95%,
  rgba(253,252,251,0.50) 100%);
```

**Spin button:**
```
flex bg-zz-secondary text-zz-text-white rounded-full px-4 py-2 w-fit overflow-hidden
```
- Not logged in: `opacity-50`
- Logged in: `opacity-100`

**Timer text:** `text-zz-text-light` with `.numbDays` in `text-zz-primary-300`.

---

### Login / Auth Forms

**Outer container:** Full viewport centered (`w-screen h-screen flex items-center justify-center`).

**Inner card:**
```
flex flex-col gap-10 items-center justify-center p-12
bg-zz-background-300 rounded-md w-full max-w-[1000px]
box-shadow: 0 0 15px 0 rgba(0, 0, 0, 0.15)
```

**Links container:** 2-column grid, links with Material Icons `arrow_outward`. Hover: `text-zz-primary`.

**Logged-in state:** 2-column button grid (`grid-template-columns: 1fr 1fr`).

---

### Footer

```
text-zz-white bg-zz-primary
```
Simple, solid-color footer. Extend with navigation links and legal text.

---

## 6. Interactive & Motion Patterns

### Transitions

- **Hover/focus transitions:** `0.2s ease` — used consistently across links, buttons, header elements
- **Card state changes:** `0.2s all` — for outline/background transitions

### GSAP Animations

The original project relies on GSAP for complex animations:

1. **Hero background shapes** — continuous slow rotation + random float movement (scale, position, opacity)
2. **Header show/hide** — ScrollTrigger-based hide on scroll down, show on scroll up
3. **Header text color** — IntersectionObserver switches text from `zz-primary-100` to `zz-text` when hero scrolls out
4. **Scroll title reveal** — SplitText + ScrollTrigger for character-by-character title animation
5. **Task spinner** — GSAP-powered task strip animation, stops based on cookie state
6. **Nav button scroll** — GSAP ScrollToPlugin for smooth section scrolling
7. **Arrow SVG** — GSAP DrawSVG animation for decorative arrow
8. **Custom cursor** — Follows mouse position (custom image)

**When rebuilding** these should be replaced with equivalent GSAP imports or replaced with CSS-based alternatives where possible.

### AOS (Animate on Scroll)

Initialized after page load with 200ms delay. Used for entrance animations on content sections.

---

## 7. Animation & Utility Dependencies

| Package          | Version | Purpose                         |
|------------------|---------|---------------------------------|
| GSAP             | ≥3.14   | Core animation engine           |
| GSAP ScrollTrigger| plugin | Scroll-linked animations        |
| GSAP ScrollTo    | plugin  | Smooth section navigation       |
| GSAP SplitText   | plugin  | Character-level text animation  |
| GSAP DrawSVG     | plugin  | SVG path drawing animation      |
| AOS              | 2.3     | Scroll-triggered entrance fades |
| Splide.js        | 4.1     | Carousel/slider                 |
| Material Icons   | —       | Google icon font (arrow_downward, arrow_outward, etc.) |

---

## 8. Custom Icons & Assets

- **Material Icons** Google Font — included in `<head>`: `https://fonts.googleapis.com/icon?family=Material+Icons`
- **Custom cursor** — ACF field `cursor`, rendered as `<img>` with `cursor: none` on body
- **Tick sound** — `assets/tik.mp3` for spinner audio feedback
- **Default profile picture** — `assets/images/defaultProfilePicture.jpg`

---

## 9. Tailwind v4 Configuration Template

Based on `_old_project`'s Tailwind v3 config, adapted for the new project's v4 CSS-based config (`resources/css/app.css`):

```css
@import 'tailwindcss';
@import url('https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap');

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';

@theme {
    --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
    --font-heading: 'Noto Serif', serif;
    --font-body: 'Nunito', sans-serif;

    /* Primary — Blue */
    --color-zz-primary-50: #eef0f6;
    --color-zz-primary-100: #cbd1e3;
    --color-zz-primary-200: #b2bbd5;
    --color-zz-primary-300: #8f9cc2;
    --color-zz-primary-400: #7989b6;
    --color-zz-primary: #586ba4;
    --color-zz-primary-600: #506195;
    --color-zz-primary-700: #3e4c74;
    --color-zz-primary-800: #303b5a;
    --color-zz-primary-900: #252d45;

    /* Secondary — Orange */
    --color-zz-secondary-50: #fdf3e9;
    --color-zz-secondary-100: #f8d9ba;
    --color-zz-secondary-200: #f5c698;
    --color-zz-secondary-300: #f0ad69;
    --color-zz-secondary-400: #ed9d4c;
    --color-zz-secondary: #e9841f;
    --color-zz-secondary-600: #d4781c;
    --color-zz-secondary-700: #a55e16;
    --color-zz-secondary-800: #804911;
    --color-zz-secondary-900: #62370d;

    /* Background — Warm Beige */
    --color-zz-background-50: #fdfcfb;
    --color-zz-background-300: #f4f3ee;
    --color-zz-background-400: #f0eee6;
    --color-zz-background: #edeae1;
    --color-zz-background-600: #e8e5da;
    --color-zz-background-700: #d3d0c6;

    /* Text */
    --color-zz-text: #2d2d2d;
    --color-zz-text-light: #7f7f7f;
    --color-zz-text-white: #ececec;

    /* Neutrals */
    --color-zz-white: #ffffff;
    --color-zz-black: #000000;
}

/* Base styles */
html {
    font-size: 10px; /* 1rem = 10px */
}

body {
    @apply flex flex-col font-body bg-zz-background text-base;
    margin: 0;
    padding: 0;
    width: 100%;
    max-width: 100vw;
    overflow-x: hidden;
    min-height: 100vh;
    font-size: 16px;
    line-height: 1.5;
    scroll-behavior: smooth;
    scroll-padding-top: 30vh;
}

body > main {
    flex: 1;
}

/* Headings use Noto Serif */
h1, h2, h3, h4, h5, h6 {
    @apply font-heading;
}

h1 { font-size: clamp(20px, 13vw, 12.8rem); }
h2 { font-size: clamp(18px, 10vw, 10rem); }
h3 { font-size: clamp(16px, 8vw, 8rem); }
h4 { font-size: clamp(14px, 6vw, 6rem); }
h5 { font-size: clamp(12px, 4vw, 4.9rem); }
p  { font-size: 2.0rem; line-height: 3.0rem; @apply font-body; }

/* Utility classes */
.title { @apply text-zz-primary; }
```

---

## 10. Page Layout Architecture

The page structure follows a single-page, vertically-scrolling layout with distinct full-viewport-height sections:

```
┌──────────────────────────────┐
│  Header (fixed, glass)       │  ← .HeaderComponent
├──────────────────────────────┤
│  Hero Section                │  ← .HeroComponent (100vh)
│  - Animated bg shapes        │
│  - Page title                │
│  - Nav button (scroll down)  │
├──────────────────────────────┤
│  Wheel of Tasks              │  ← .WheelOfTasksComponent (min 100vh)
│  - Name selector / display   │
│  - Animated task slider      │
│  - Spin button               │
│  - Timer                     │
│  - Nav button (scroll down)  │
├──────────────────────────────┤
│  Overview                    │  ← .OverviewComponent (min 100vh)
│  - Section title             │
│  - Date range                │
│  - Person cards (4-col grid) │
│  - Task completion checkbox  │
├──────────────────────────────┤
│  Footer                      │  ← .FooterComponent
│  - Primary blue background   │
│  - Navigation & info         │
└──────────────────────────────┘
```

**Auth pages** (login, forgot password, reset password) use centered card layouts on full-viewport backgrounds.

---

## 11. Naming Conventions

### CSS Classes
- **BEM-ish:** Component-scoped with descriptive names: `.HeroComponent`, `.heroBackground`, `.squareBlue`
- **State modifiers:** `.checked`, `.loggedIn`, `[data-spinned="false"]`
- **Utility classes:** Follow Tailwind conventions, with custom `.spacing`, `.title`, `.description`

### Components (Blade)
- Laravel components should be PascalCase directories with lowercase view files: `components/hero/hero.blade.php`
- Keep the same semantic structure from the original: hero, header, footer, overview, wheel-of-tasks, auth forms

---

## 12. Browser Support

- Modern browsers (latest 2 versions of Chrome, Firefox, Safari, Edge)
- Uses `backdrop-filter` (glass-morphism header) — gracefully degrades on older browsers
- Uses `clip-path` (hero diamond shape) — modern browsers only
- Uses native `<select>` `::picker()` styling — cutting-edge, limited browser support
- Scroll-driven animations degrade to static on unsupported browsers
