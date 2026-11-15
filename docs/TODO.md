# TODO.md — Implementation Roadmap: EduQuiz

One task = one agent run = one commit. Give the agent exactly one task, verify with the "Verify" line, commit, and move on. Tag at each chapter boundary.

> **Note:** `design.md` describes the *finished* app. Each task implements only its own slice; a page may stay incomplete until a later task fills it in.

## Chapter 12 — authentication and roles

- [ ] **T1** Install Laravel Breeze (Blade stack); run its install and migrations. *Verify: register, log in and log out at /login.*
- [ ] **T2** Add `role` enum (`lecturer`, `student`) to users via migration; add a role select to the registration form. *Verify: register one lecturer and one student; roles stored correctly, and `php artisan test` still passes (Breeze's registration test needs the new role field).*
- [ ] **T3** Role-aware dashboard: different welcome text and guidance per role (quiz counts arrive with later tasks). *Verify: both accounts see different dashboards.*

## Chapter 13 — quiz CRUD

- [ ] **T4** `Quiz` model, migration (per ERD), `QuizPolicy`, and quiz list page showing the lecturer's own quizzes. *Verify: lecturer sees an empty list; student is blocked from /quizzes/create.*
- [ ] **T5** Create, edit and delete quizzes (title only; status defaults to draft), plus a minimal manage page (title + status badge; questions arrive next chapter). *Verify: lecturer creates, renames, deletes a quiz; Manage opens without error; another lecturer's quiz returns 403.*

## Chapter 14 — questions and options

- [ ] **T6** `Question` and `Option` models + migrations (per ERD); add-question form on the quiz manage page (text, 4 options, radio marks the correct one) with validation. *Verify: add a question; exactly 4 options saved, one correct.*
- [ ] **T7** List questions on the manage page as numbered cards with the correct option marked; allow deleting a question. *Verify: two questions display in order; deleting removes its options too.*

## Chapter 15 — publishing

- [ ] **T8** Publish/unpublish toggle with status badge; block publishing a quiz with no questions. *Verify: empty quiz won't publish; badge flips between draft and published.*
- [ ] **T9** Student quiz list: published quizzes as cards with title and question count (the Take quiz button arrives with its page in T10). *Verify: student sees only published quizzes; draft quizzes invisible.*

## Chapter 16 — taking a quiz

- [ ] **T10** Take page: all questions with radio options, one submit; blocked for drafts, lecturers, and students who attempted; add the Take quiz button to the student quiz cards. *Verify: student clicks Take quiz and the page opens; lecturer gets 403.*
- [ ] **T11** Submit attempt: validate every question answered; compute and store the score (correct/total); enforce one attempt (unique index per ERD). *Verify: missing answer shows an error; known answers produce the expected stored score; second visit to the take page is blocked.*

## Chapter 17 — scoring and results

- [ ] **T12** Show the result to the student: flash "You scored X/Y" after submission; the student quiz list shows the score instead of the Take quiz button. *Verify: after submitting, the flash appears and the list shows the score.*
- [ ] **T13** Lecturer results page per quiz: student name, score, submitted time; empty state. *Verify: table lists the attempt from T12.*
- [ ] **T14** `DatabaseSeeder` per Architecture.md (1 lecturer, 5 students, 2 quizzes × 5 questions). *Verify: fresh `migrate --seed` yields a working demo; lecturer login shows 2 quizzes.*
