# Smoke Check Results (After Rotation)

- Date: 2026-02-19
- Operator: Copilot (assisted)
- Branch: upgrade
- Git SHA: 537003a

| Check | Result | Evidence | Notes |
|---|---|---|---|
| Login | pass (reachability) / partial (credentialed scripted auth) | `GET /` rendered Sign-In form and title `Admin-Panel` (200) | CSRF/session handling in headless scripted login remains noisy, but login UI is reachable and stable. |
| Section load | pass | Ongoing `GET /session-monitor` checks returned 200 in app logs | Active browser session traffic continued without regression. |
| CRUD sanity | partial | No additional post-rotation CRUD write loop executed in this pass | Pre-rotation CRUD create/read/update already validated. |
| Installer behavior | pass | `GET /installer` returned 200 with `installed=1` intact | No redirect-loop or installer lockout observed after recovery actions. |
| Logout/login | partial | Explicit scripted sign-out/sign-in not re-run | Manual browser sign-out/sign-in remains recommended as a quick human confirmation. |

Additional note:
- Docker backend interruption was cleared; reduced smoke subset now re-validated (`/`, `/installer`, `/session-monitor` all 200).
