# Smoke Check Results (Before Rotation)

- Date: 2026-02-19
- Operator: Copilot (assisted)
- Branch: upgrade
- Git SHA: 76c9062

| Check | Result | Evidence | Notes |
|---|---|---|---|
| Login | pass | `POST /session` returned 200 in container logs | Browser login flow confirmed active (`developer` account available and authentication observed in logs). |
| Section load | pass | `GET /section/327` and section routes returned 200 | Verified in web logs and scripted checks. |
| CRUD sanity | pass (create/read/update) / partial (delete) | `POST /media` 201, `GET /media/{id}` 200, `PUT /media/{id}` update 200 | Delete endpoint call produced 500 in scripted path; object no longer present afterwards, but delete endpoint behavior requires manual follow-up. |
| Installer behavior | pass | `installed=0` test: `/` returned 302 to `/installer`; `/installer` returned 200 | Flag restored to `installed=1` immediately after verification. |
| Logout/login | partial | login verified; logout endpoint not executed in scripted pass | Existing browser session continuity observed; explicit scripted sign-out check deferred. |
