# PRD.md — Product Requirements: EduQuiz

## Overview

EduQuiz is a web application where lecturers create multiple-choice quizzes and students take them online. A lecturer builds a quiz, adds questions, and publishes it; each student takes a published quiz once and receives an automatic score. The application is in English, responsive, and intended for classroom use.

## Users and roles

| Role | Can |
| --- | --- |
| Lecturer | Create, edit, delete and publish their own quizzes; add questions; view results per quiz |
| Student | See published quizzes; take each quiz once; see their own score |

A user picks their role at registration. A user has exactly one role.

## Functional requirements

- **FR-1 Authentication**: register (name, email, password, role), log in, log out
- **FR-2 Dashboards**: after login, lecturer and student land on role-appropriate dashboards
- **FR-3 Quiz management**: a lecturer creates, edits and deletes their own quizzes; a quiz has a title and a status (`draft` or `published`); new quizzes start as `draft`
- **FR-4 Questions**: a lecturer adds questions to their own quiz; each question has question text and exactly 4 options, exactly one marked correct; questions can be deleted
- **FR-5 Publishing**: a lecturer toggles a quiz between `draft` and `published`; only quizzes with at least 1 question can be published
- **FR-6 Quiz list (student)**: a student sees all published quizzes, each showing title, question count, and either a Take quiz button or their score
- **FR-7 Taking a quiz**: all questions on one page, one radio group of 4 options per question; every question must be answered before submission
- **FR-8 Scoring**: on submission the score (correct answers out of total) is computed, stored, and shown immediately
- **FR-9 One attempt**: a student who has attempted a quiz cannot take it again; the take page is blocked
- **FR-10 Results (lecturer)**: a lecturer sees, per quiz, a table of student name, score, and submission time
- **FR-11 Authorization**: lecturers cannot take quizzes; students cannot reach any quiz-management page; a lecturer can only manage their own quizzes

## Out of scope (v1)

Per-question answer review, timers, true/false and short-answer questions, points per question, question reordering, multiple attempts, categories or tags, email verification, password reset, file uploads, UI languages other than English.

## Non-functional

Responsive layout (usable on a phone), seeded demo data (1 lecturer, 5 students, 2 quizzes), all pages render without JavaScript except where Laravel's defaults require it.
