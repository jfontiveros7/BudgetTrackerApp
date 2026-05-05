param(
    [string]$OutputPath = "docs/media/demo-voiceover.wav",
    [string]$PreferredVoice = "Zira",
    [int]$Rate = 0,
    [int]$Volume = 100
)

Add-Type -AssemblyName System.Speech

$projectRoot = Split-Path -Parent $PSScriptRoot
$fullOutputPath = Join-Path $projectRoot $OutputPath
$outputDir = Split-Path -Parent $fullOutputPath
if (-not (Test-Path $outputDir)) {
    New-Item -Path $outputDir -ItemType Directory -Force | Out-Null
}

$voiceoverText = @"
This is Budget Tracker App by Konticode Labs, a modern PHP and MySQL finance dashboard with alerts, AI-ready workflows, and clean transaction operations.
Out of the box, buyers can log in with demo data and instantly see real financial activity, not an empty shell.
The dashboard highlights income, expenses, net balance, and actionable alerts so users know where to focus next.
Recent transactions are searchable and filterable, making it easy to inspect spending patterns without digging through raw tables.
Adding transactions is simple, and the app is structured for real product workflows with auth, settings, and category-based budget tracking.
Settings and alert preferences are already included, so this is not just a starter CRUD template. It is a sellable product foundation.
Budget Tracker App includes source code, schema, demo seed, and documentation, so buyers can deploy fast and customize with confidence.
Launch your budgeting product faster with Budget Tracker App.
"@

$synth = New-Object System.Speech.Synthesis.SpeechSynthesizer
$synth.Rate = $Rate
$synth.Volume = $Volume

$voices = $synth.GetInstalledVoices() | ForEach-Object { $_.VoiceInfo.Name }
$selectedVoice = $voices | Where-Object { $_ -like "*$PreferredVoice*" } | Select-Object -First 1
if (-not $selectedVoice) {
    $selectedVoice = $voices | Select-Object -First 1
}

if ($selectedVoice) {
    $synth.SelectVoice($selectedVoice)
}

$synth.SetOutputToWaveFile($fullOutputPath)
$synth.Speak($voiceoverText)
$synth.Dispose()

Write-Output "Voiceover generated: $fullOutputPath"
Write-Output "Voice used: $selectedVoice"
