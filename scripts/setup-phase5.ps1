param(
    [switch]$Rebuild,
    [switch]$ResetDb,
    [switch]$KeepExistingSmtp
)

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

$root = (Resolve-Path (Join-Path $PSScriptRoot "..")).Path
$composeFile = Join-Path $root "docker-compose.phase5.yml"
$localPath = Join-Path $root "local"
$envExamplePath = Join-Path $localPath ".env.example"
$envPath = Join-Path $localPath ".env"
$appPort = if ([string]::IsNullOrWhiteSpace($env:APP_PORT)) { "8081" } else { $env:APP_PORT }
$mailUiPort = if ([string]::IsNullOrWhiteSpace($env:MAIL_UI_PORT)) { "8025" } else { $env:MAIL_UI_PORT }

function Require-Command {
    param([string]$Name)

    if (-not (Get-Command $Name -ErrorAction SilentlyContinue)) {
        throw "Required command '$Name' was not found in PATH."
    }
}

function Invoke-Checked {
    param(
        [ScriptBlock]$Action,
        [string]$ErrorMessage
    )

    & $Action
    if ($LASTEXITCODE -ne 0) {
        throw $ErrorMessage
    }
}

function Set-EnvValue {
    param(
        [string]$Path,
        [string]$Key,
        [string]$Value
    )

    $content = Get-Content -Path $Path -Raw
    $escapedKey = [Regex]::Escape($Key)
    $pattern = "(?m)^$escapedKey=.*$"

    if ($content -match $pattern) {
        $content = [Regex]::Replace($content, $pattern, "$Key=$Value")
    } else {
        if (-not $content.EndsWith("`n")) {
            $content += "`n"
        }

        $content += "$Key=$Value`n"
    }

    Set-Content -Path $Path -Value $content -NoNewline
}

function Wait-ContainerHealthy {
    param(
        [string]$Container,
        [int]$TimeoutSeconds = 120
    )

    $start = Get-Date
    while ($true) {
        $status = docker inspect --format "{{if .State.Health}}{{.State.Health.Status}}{{else}}{{.State.Status}}{{end}}" $Container 2>$null
        if ($status -eq "healthy" -or $status -eq "running") {
            return
        }

        $elapsed = (Get-Date) - $start
        if ($elapsed.TotalSeconds -ge $TimeoutSeconds) {
            throw "Timed out waiting for container '$Container' to become healthy. Last state: '$status'."
        }

        Start-Sleep -Seconds 3
    }
}

function Wait-Http {
    param(
        [string]$Url,
        [int]$ExpectedStatus = 200,
        [int]$TimeoutSeconds = 120
    )

    $start = Get-Date
    while ($true) {
        try {
            $response = Invoke-WebRequest -Uri $Url -Method Get -TimeoutSec 5
            if ([int]$response.StatusCode -eq $ExpectedStatus) {
                return
            }
        } catch {
        }

        $elapsed = (Get-Date) - $start
        if ($elapsed.TotalSeconds -ge $TimeoutSeconds) {
            throw "Timed out waiting for '$Url' to return HTTP $ExpectedStatus."
        }

        Start-Sleep -Seconds 3
    }
}

Write-Host "[phase5] Checking prerequisites..."
Require-Command -Name docker

if (-not (Test-Path $composeFile)) {
    throw "Compose file not found: $composeFile"
}

if (-not (Test-Path $envPath)) {
    if (-not (Test-Path $envExamplePath)) {
        throw "Missing $envPath and template $envExamplePath"
    }

    Copy-Item -Path $envExamplePath -Destination $envPath
    Write-Host "[phase5] Created local/.env from .env.example"
}

Write-Host "[phase5] Applying local development defaults in local/.env"
Set-EnvValue -Path $envPath -Key "DB_HOST" -Value "db"
Set-EnvValue -Path $envPath -Key "DB_DATABASE" -Value "admin_panel"
Set-EnvValue -Path $envPath -Key "DB_USERNAME" -Value "app_adminpanel"
Set-EnvValue -Path $envPath -Key "DB_PASSWORD" -Value "adminpanel"

if ($KeepExistingSmtp) {
    Write-Host "[phase5] Keeping existing SMTP values from local/.env"
} else {
    Write-Host "[phase5] Applying Mailpit SMTP defaults in local/.env"
    Set-EnvValue -Path $envPath -Key "MAIL_DRIVER" -Value "smtp"
    Set-EnvValue -Path $envPath -Key "MAIL_HOST" -Value "maildev"
    Set-EnvValue -Path $envPath -Key "MAIL_PORT" -Value "1025"
    Set-EnvValue -Path $envPath -Key "MAIL_ENCRYPTION" -Value ""
    Set-EnvValue -Path $envPath -Key "AP_SMTP_SECURE" -Value "0"
    Set-EnvValue -Path $envPath -Key "MAIL_FROM_ADDRESS" -Value "no-reply@admin-panel.local"
    Set-EnvValue -Path $envPath -Key "MAIL_FROM_NAME" -Value "Admin Panel"
}

Push-Location $root
try {
    if ($ResetDb) {
        Write-Host "[phase5] Resetting Phase 5 volumes..."
        Invoke-Checked -Action { docker compose -f $composeFile down -v --remove-orphans } -ErrorMessage "Failed to reset Phase 5 containers/volumes."
    }

    Write-Host "[phase5] Starting database and maildev services..."
    Invoke-Checked -Action { docker compose -f $composeFile up -d db maildev } -ErrorMessage "Failed to start db/maildev services."

    Write-Host "[phase5] Installing PHP dependencies with Composer (runtime deps only)..."
    Invoke-Checked -Action { docker compose -f $composeFile run --rm composer install --no-interaction --prefer-dist --no-dev } -ErrorMessage "Composer install failed."

    $appUpArgs = @("compose", "-f", $composeFile, "up", "-d", "app")
    if ($Rebuild) {
        $appUpArgs = @("compose", "-f", $composeFile, "up", "-d", "--build", "app")
    }

    Write-Host "[phase5] Starting app service..."
    Invoke-Checked -Action { docker @appUpArgs } -ErrorMessage "Failed to start app service."

    Write-Host "[phase5] Waiting for DB health..."
    Wait-ContainerHealthy -Container "adminpanel-phase5-db" -TimeoutSeconds 180

    Write-Host "[phase5] Generating APP_KEY if needed and running migrations..."
    Invoke-Checked -Action { docker compose -f $composeFile exec -T app bash -lc "cd /var/www/html/local && php artisan key:generate" } -ErrorMessage "APP_KEY generation failed."
    Invoke-Checked -Action { docker compose -f $composeFile exec -T app bash -lc "cd /var/www/html/local && php artisan migrate --seed --force" } -ErrorMessage "Database migration/seed failed."

    Write-Host "[phase5] Running startup checks..."
    Wait-Http -Url "http://localhost:$appPort/health/live" -ExpectedStatus 200 -TimeoutSeconds 180
    Wait-Http -Url "http://localhost:$appPort/health/ready" -ExpectedStatus 200 -TimeoutSeconds 180

    Write-Host ""
    Write-Host "[phase5] Setup complete."
    Write-Host "App:      http://localhost:$appPort"
    Write-Host "Health:   http://localhost:$appPort/health/live"
    Write-Host "Ready:    http://localhost:$appPort/health/ready"
    Write-Host "Mail UI:  http://localhost:$mailUiPort"
} finally {
    Pop-Location
}
