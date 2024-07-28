<#
.SYNOPSIS
    This script automates the build and deployment process for an application.

.DESCRIPTION
    The script performs the following tasks:
    1. Checks if a target is specified.
    2. If the target is "all":
        - Cleans the "dist" directory.
        - Runs Gulp to build the application.
        - Copies the minified JavaScript file to the document root.
        - Copies assets to the document root.
        - Creates a "modules" directory in the document root if it doesn't exist.
        - Copies the "human-writes" module to the "modules" directory.
        - Builds the application using the "egg" command.

.PARAMETER Target
    The target to build. If not specified, the script will exit with an error.

.EXAMPLE
    .\build.ps1 all
#>

param(
    [string] $TARGET
)
# Define variables based on the original script
#$TARGET = $args[0]
$CWD = (Get-Location).Path
$DOC_ROOT = (Get-Content "$CWD/config/document_root" | Select-Object -First 1).Trim()
$APP_DIR = (Get-Content "$CWD/config/app" | Select-Object -First 1).Trim()
$APP_JS = "dist/app.min.js"
$ASSETS_DIR = "$APP_DIR/Assets"
$MODULES = @(
    "node_modules/human-writes/dist/web/human-writes.min.js"
)

# Check if target is provided
if ([string]::IsNullOrEmpty($TARGET)) {
    Write-Host "Target is missing."
    exit 1
}

# Process "all" target
if ($TARGET -eq "all") {
    # Remove dist directory if exists
    if ((Test-Path "dist")) {
        Remove-Item -Path "dist" -Recurse -Force
    }

    Write-Host "Running gulp..."
    # Invoke gulp (assuming it's available as a command)
    gulp

    # Check if app.min.js was generated
    if (!(Test-Path $APP_JS)) {
        Write-Host "FATAL ERROR!"
        Write-Host "Something went wrong while running gulp: $APP_JS not found."
        exit 1
    }

    # Copy app.min.js to document root
    Copy-Item $APP_JS $DOC_ROOT
    Write-Host ""

    Write-Host "Publishing assets..."
    # Copy assets to document root
    Copy-Item -Recurse -Verbose $ASSETS_DIR $DOC_ROOT
    Write-Host ""

    Write-Host "Sharing modules..."
    # Create modules directory if it doesn't exist
    if (!(Test-Path "$DOC_ROOT/modules")) {
        New-Item -ItemType Directory "$DOC_ROOT/modules"
    }

    # Copy modules to document root
    foreach ($module in $MODULES) {
        if (!(Test-Path $module)) {
            Write-Host "FATAL ERROR!"
            Write-Host "Module not found: $module"
            exit 1
        }
        Copy-Item -Recurse -Verbose $module "$DOC_ROOT/modules"
    }
    Write-Host ""

    Write-Host "Building the app..."
    # Execute php egg build (assuming php and egg are available)
    php ./egg build
}

exit 0
