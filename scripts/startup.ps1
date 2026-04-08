param(
    [switch]$Rebuild,
    [switch]$ResetDb,
    [switch]$KeepExistingSmtp
)

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

$internalScript = Join-Path $PSScriptRoot "..\modernization\scripts\setup-phase5.ps1"

if (-not (Test-Path $internalScript)) {
    throw "Startup backend script not found: $internalScript"
}

$internalArgs = @()

if ($Rebuild) {
    $internalArgs += "-Rebuild"
}

if ($ResetDb) {
    $internalArgs += "-ResetDb"
}

if ($KeepExistingSmtp) {
    $internalArgs += "-KeepExistingSmtp"
}

& $internalScript @internalArgs

if ($LASTEXITCODE -ne 0) {
    exit $LASTEXITCODE
}