# Appendix A — Setting Up GitHub Copilot

> **As of November 2026.** Plans, quotas, and menu names in this appendix change often. The companion repository keeps the current version of this appendix; when they disagree, trust the repository and GitHub's own documentation at `docs.github.com/copilot`.

## A.1 Choose your access route

| Route | Who | Agent mode | Cost |
| --- | --- | --- | --- |
| GitHub Education (Copilot Pro) | Verified teachers and students | Yes, with generous monthly quota | Free |
| Copilot Free | Anyone with a GitHub account | Yes, metered by a small monthly allowance of premium requests | Free |
| Copilot Pro (paid) | Anyone | Yes, with generous monthly quota | Monthly subscription |

At the time of writing, Copilot Free provided a few dozen premium requests per month, and agent-mode interactions drew from that allowance. That is enough to try the workflow but probably not to complete EduQuiz, which needs at least fourteen agent runs (one per task), each usually consuming several requests, plus retries. Educators and students should take the GitHub Education route.

## A.2 Verify through GitHub Education

1. Sign in to GitHub and go to `github.com/education`.
2. Choose the teacher or student benefits application.
3. Add and verify your institutional email address (for UiTM, your university address), or upload proof of affiliation such as a staff or student card.
4. Submit and wait for approval. This is often quick but can take several days; apply well before you need it.
5. Once approved, activate the Copilot benefit from your GitHub account's Copilot settings.

**For instructors:** have the whole class apply in the first week, before any build chapter. A student blocked by pending verification in the middle of Part IV loses momentum the method depends on.

## A.3 Install in VS Code

1. Open VS Code's Extensions view (`Ctrl+Shift+X`, or `Cmd+Shift+X` on macOS).
2. Install **GitHub Copilot**. Recent versions install Copilot Chat with it; if not, install **GitHub Copilot Chat** as well.
3. Sign in with your GitHub account when prompted. VS Code will open a browser window to authorise.
4. Open the Copilot Chat panel from the Copilot icon in the title bar or activity bar.

## A.4 Switch to agent mode

The chat panel has a mode selector near the text box. Choose **Agent**. In this mode Copilot can open, create, and edit files across your workspace and propose terminal commands, asking for your approval before running them.

Check two things before starting Chapter 12:

- **Your project folder is open as the workspace.** Use *File → Open Folder* and choose the `eduquiz` folder, not a parent folder. The agent's view of the project is the workspace.
- **Instructions are being read.** Ask: *"What rules does AGENTS.md give you?"* A correct summary means the file is being picked up. If Copilot does not mention it, begin each session's first prompt with "Read AGENTS.md" — the method works either way.

## A.5 Working habits

- **Approve commands one at a time.** Copilot may offer to run commands without asking each time. Leave that off during this book, so you see every `migrate` and `composer` command before it runs, and can refuse any that break rule 7.
- **One task per chat session.** Start a new chat for each TODO task. Long sessions accumulate context from earlier tasks, which can mislead the agent.
- **Model choice.** Copilot lets you choose among several underlying models. If one struggles repeatedly with a task, switching models and retrying in a fresh session is a legitimate tactic.

## A.6 Troubleshooting

| Symptom | Likely cause | Fix |
| --- | --- | --- |
| No *Agent* option in the mode selector | Outdated extension or VS Code | Update both; restart VS Code |
| "Quota exceeded" or requests refused | Free-tier premium requests used up | Wait for the monthly reset, or verify via GitHub Education |
| Agent edits files outside the project | Wrong folder opened as workspace | Close and reopen VS Code with the `eduquiz` folder |
| Agent runs `php` on your machine, not in Docker | It did not follow `AGENTS.md`'s command section | Remind it: "Run all commands with `docker compose exec app`" |
