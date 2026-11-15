# Chapter 3 — How to Read Tailwind

> **What you will learn:** the utility-first idea; how to read a Tailwind class string and predict what it renders; why this way of styling suits AI generation unusually well; and the one build-pipeline fact that will save you a confusing afternoon.

## 3.1 CSS without the stylesheet

Traditional CSS keeps styling in a separate file: HTML says *what* an element is (`class="quiz-card"`), a stylesheet elsewhere says what that *means*. Tailwind collapses the distance. Instead of inventing a name and defining it, you compose the element's appearance from small, single-purpose **utility classes**, right in the markup:

```html
<div class="bg-white rounded-lg shadow p-6">
```

Read it literally, left to right: white background, large rounded corners, a drop shadow, padding level six on all sides. That is a card — the exact card, in fact, that every panel in EduQuiz uses, because `design.md` says so (Chapter 9).

There is no `quiz-card` defined anywhere. The markup *is* the stylesheet.

## 3.2 The reading vocabulary

Tailwind has thousands of classes, but they are generated from a small grammar. Learn the patterns and you can read classes you have never seen:

| Pattern | Meaning | Examples from EduQuiz |
| --- | --- | --- |
| `bg-{colour}-{shade}` | background colour; shades run 50 (palest) to 950 (darkest) | `bg-indigo-600`, `bg-gray-100`, `bg-green-50` |
| `text-{colour}-{shade}` / `text-{size}` | text colour / size | `text-gray-900`, `text-sm`, `text-xl` |
| `p-{n}`, `px-{n}`, `py-{n}`, `m-{n}`, `mt-{n}` | padding and margin; `x` horizontal, `y` vertical, `t` top | `p-6`, `px-4 py-2`, `mt-4` |
| `font-{weight}` | text weight | `font-medium`, `font-semibold` |
| `rounded`, `rounded-md`, `rounded-lg` | corner radius | `rounded-md` on buttons |
| `shadow`, `border`, `divide-y` | depth and lines | `border-gray-300` |
| `flex`, `grid`, `gap-{n}`, `items-center`, `justify-between` | layout | the page-heading row |
| `w-full`, `max-w-4xl`, `mx-auto` | width; `mx-auto` centres | the content column |

Two prefixes complete the grammar. **State prefixes** apply a class only in a state: `hover:bg-indigo-700` means "indigo-700 background, but only under the cursor." **Responsive prefixes** apply a class only from a screen width upward: `md:grid-cols-2` means "two columns, but only on medium screens and wider" — which is how EduQuiz's student quiz cards stack on a phone and sit side by side on a laptop.

Now read a real line — the primary button used throughout EduQuiz:

```html
<button class="px-4 py-2 rounded-md text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white">
```

Comfortable horizontal padding, medium corners, small medium-weight white text on indigo, darkening on hover. You have just read the house style.

## 3.3 Why utility-first suits agentic AI

This book pins Tailwind in the stack for a methodological reason, not a fashionable one: **it keeps everything the agent did in one place.**

With a conventional stylesheet, a styling change spans two files — markup here, CSS there — and reviewing it means holding both in your head while imagining the cascade (which rules win, what inherits, what broke elsewhere). An agent editing shared CSS can change a page it never mentioned. With Tailwind, the entire visual truth of an element sits in its class attribute: the diff you review *is* the appearance; nothing done to one Blade file can restyle another.

It also makes the visual specification enforceable. Chapter 9's `design.md` is largely a table of exact class strings — "primary action: `bg-indigo-600 hover:bg-indigo-700 text-white`" — and `AGENTS.md` orders the agent to follow it, with no custom CSS files and no component libraries. Verifying consistency becomes string comparison, which both you and the agent are good at.

## 3.4 The one pipeline fact: classes must be built

Tailwind does not ship every possible class to the browser — the file would be enormous. Instead a build step (Vite, in Laravel's setup) **scans your templates and generates CSS for exactly the classes it finds**. While `npm run dev` is running — and in our Docker environment of Chapter 5, a container runs it for you — this happens continuously and invisibly.

Why devote a section to plumbing? Because of what happens when the build is *not* running, and this book can show you with a straight face, because it happened during the validation build behind these pages.

> **When the pipeline gets it wrong.** During validation, the student quiz list was built while the CSS bundle was stale — compiled before those templates existed. The page rendered: titles visible, layout roughly intact. But the two-column grid collapsed, and the "Take quiz" button vanished. Not missing from the HTML — *invisible*: `text-white` was already in the old bundle, but `bg-indigo-600` on that element was not, so the button rendered as white text on a white card. One command (`npm run build`) restored both instantly.
>
> The lesson generalises: **if a page looks broken but the HTML looks right, suspect the CSS build before you suspect the agent.** You will meet this box again in Chapter 15, at the exact moment it can bite you, and in Appendix C's troubleshooting table.

## 3.5 Reading a whole template

Here is a condensed version of a student quiz card from the validation build:

```html
<div class="grid gap-4 md:grid-cols-2">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900">{{ $quiz->title }}</h3>
        <p class="mt-1 text-sm text-gray-600">{{ $quiz->questions_count }} questions</p>
        <a href="{{ route('quizzes.take', $quiz) }}"
           class="mt-4 inline-block px-4 py-2 rounded-md text-sm font-medium
                  bg-indigo-600 hover:bg-indigo-700 text-white">Take quiz</a>
    </div>
</div>
```

Read the outer `div`: a grid with gaps, two columns on medium screens and up (so: one column on phones). Each card: the house card style. Title: large-ish, medium weight, near-black. Meta line: small, grey, nudged down. Button: the house primary. The `{{ ... }}` islands are Blade from Chapter 2 — data, not style.

That is the entire review skill for the visual layer: scan the class strings, compare against `design.md`'s table, flag anything foreign. An agent that introduces `bg-blue-500` into this application has broken the specification — and now you can see it at a glance.

## 3.6 What you can now do

You can read any class string EduQuiz uses and predict its rendering; you know why the stack uses utilities (reviewability and enforceability, not fashion); and you know the stale-build failure before it happens to you. Part I is complete: you can read both halves of the application you are about to direct into existence. Next, the workbench.
