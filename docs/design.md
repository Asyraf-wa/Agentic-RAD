# design.md — UI/UX Specification: EduQuiz

## Principles

Clean, academic, calm. Tailwind utility classes only — no custom CSS files, no component libraries, no icon packages. Every page must be readable on a phone (stack columns below `md:`).

## Palette and typography

| Use | Tailwind classes |
| --- | --- |
| Primary action | `bg-indigo-600 hover:bg-indigo-700 text-white` |
| Secondary action | `bg-white border border-gray-300 text-gray-700 hover:bg-gray-50` |
| Danger action | `bg-red-600 hover:bg-red-700 text-white` |
| Page background | `bg-gray-100` |
| Card | `bg-white rounded-lg shadow p-6` |
| Badge: draft | `bg-gray-100 text-gray-700` |
| Badge: published | `bg-green-100 text-green-700` |
| Headings | `text-xl font-semibold text-gray-900` (page), `text-lg font-medium` (section) |

Buttons: `px-4 py-2 rounded-md text-sm font-medium`. Form inputs: `w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500`. Validation errors: `text-sm text-red-600` under the field. Flash messages: green banner (`bg-green-50 text-green-800 rounded-md p-4`) under the page heading.

## Layout

Top navbar (white, bottom border): app name "EduQuiz" left; nav links and the user's name + logout right. Content inside `max-w-4xl mx-auto px-4 py-8`. Every page: heading row (title left, primary action right where one exists), then content.

## Pages

| Page | Content |
| --- | --- |
| Login / Register | Breeze defaults, restyled only by the palette above; register adds a role select (Lecturer / Student) |
| Lecturer dashboard | Greeting, count of own quizzes, link to quiz list |
| Quiz list (lecturer) | Table: title, status badge, question count, created date, actions (Manage / Edit / Delete); "New quiz" primary button |
| Quiz form | One card: title field, save + cancel |
| Quiz manage page | Quiz title + status badge + publish/unpublish button; questions listed as numbered cards (text, 4 options, correct one marked); "Add question" form at the bottom: question text, 4 option fields, radio to mark the correct one |
| Student dashboard | Greeting, count of published quizzes available, link to quiz list |
| Quiz list (student) | Cards: title, question count, "Take quiz" primary button or score shown if attempted |
| Take quiz | Quiz title, numbered questions each in a card with 4 radio options, one submit button at the bottom |
| After submit | Redirect to student quiz list with flash: "You scored X/Y on ‹title›" |
| Results (lecturer) | Table: student name, score, submitted at; empty state: "No attempts yet" |
