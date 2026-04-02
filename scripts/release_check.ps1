param(
    [string]$VersionFile = "VERSION",
    [string]$ChangelogFile = "CHANGELOG.md"
)

$ErrorActionPreference = "Stop"

if (!(Test-Path $VersionFile)) { throw "Missing $VersionFile" }
if (!(Test-Path $ChangelogFile)) { throw "Missing $ChangelogFile" }

$version = (Get-Content $VersionFile -Raw).Trim()
$changelog = Get-Content $ChangelogFile -Raw

if ($version -notmatch '^\d+\.\d+\.\d+$') {
    throw "Invalid VERSION '$version'. Expected MAJOR.MINOR.PATCH"
}

if ($changelog -notmatch "\[$([regex]::Escape($version))\]") {
    throw "CHANGELOG.md does not contain release section [$version]"
}

Write-Host "Release check passed."
Write-Host "VERSION=$version"
