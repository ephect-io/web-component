# Define variables
$VENDOR = "vendor/ephect-io"
$FRAMEWORK = "framework"
$APP_DIR = (Get-Location).Path
$SOURCE = (Get-Item -Path "$APP_DIR/../framework" -Force).FullName

# Check if the framework directory exists
if (!(Test-Path -Path "$VENDOR/$FRAMEWORK")) {
    Write-Host "Are you sure you're in the right place?"
    exit 1
}

# Remove the existing framework directory and create a symbolic link
if (Test-Path -Path "$VENDOR/$FRAMEWORK") {
    Write-Host "Destroying $VENDOR/$FRAMEWORK dir ..."
    Remove-Item -Path "$VENDOR/$FRAMEWORK" -Recurse -Force

    Set-Location -Path $VENDOR
    Write-Host "Linking dev repo to $VENDOR/$FRAMEWORK dir ..."
    New-Item -ItemType SymbolicLink -Path "$FRAMEWORK" -Target $SOURCE
}

exit 0
