param(
    [string] $TARGET
)

$CWD = Get-Location
$DOC_ROOT = Get-Content "$CWD\config\document_root"
$APP_DIR = Get-Content "$CWD\config\app"
$APP_JS = "dist\app.min.js"
$ASSETS_DIR = "$APP_DIR\Assets"
$MODULES = @(
    "node_modules\human-writes\dist\web\human-writes.min.js"
)

if (-not $TARGET) {
    Write-Host "Target is missing."
    exit 1
}

if ($TARGET -eq "all") {
    if ((Test-Path "dist")) {
        Remove-Item -Path "dist" -Recurse -Force
    }

    Write-Host "Running webpack..."
    webpack --config webpack.config.js

    if (-not (Test-Path $APP_JS)) {
        Write-Host "FATAL ERROR!"
        Write-Host "Something went wrong while running webpack: dist/app.min.js not found."
        exit 1
    }

    Copy-Item $APP_JS $DOC_ROOT
    Write-Host ""

    Write-Host "Publishing assets..."
    Copy-Item "$ASSETS_DIR\*" $DOC_ROOT -Recurse -Force
    Write-Host ""

    Write-Host "Sharing modules..."
    if (-not (Test-Path "$DOC_ROOT\modules")) {
        New-Item -Path "$DOC_ROOT\modules" -ItemType Directory | Out-Null
    }

    foreach ($module in $MODULES) {
        if (-not (Test-Path $module)) {
            Write-Host "FATAL ERROR!"
            Write-Host "Module not found."
            exit 1
        }

        Copy-Item $module "$DOC_ROOT\modules" -Recurse -Force
    }
    Write-Host ""

    Write-Host "Building the app..."
    php .\egg build
}

exit 0
