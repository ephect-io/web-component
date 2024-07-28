# Get the target parameter from the command line
$Target = $args[0]

# Get the current working directory
$CWD = Get-Location

# Check if the target parameter is provided
if ([string]::IsNullOrEmpty($Target)) {
    Write-Host "Target is missing."
    exit 1
}

# Check if the target is "all"
if ($Target -eq "all") {
    Write-Host "Linting web components..."
    npx eslint components --ext .js
    Set-Location $CWD
}

exit 0
