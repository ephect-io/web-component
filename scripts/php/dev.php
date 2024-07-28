<?php

// Get the target argument from the command line
$target = $argv[1] ?? null;
$cwd = getcwd();
$docroot = trim(file_get_contents($cwd . '/config/document_root'));
$app = trim(file_get_contents($cwd . '/config/app'));

if (empty($target)) {
    echo "Target is missing.";
    exit(1);
}

if ($target === 'all') {
    // Remove the 'dist' directory if it exists
    if (is_dir('dist')) {
        removeDirectory('dist');
    }

    echo "Building web components...\n";
    $output = null;
    $returnValue = null;
    exec('webpack --config webpack.config.js', $output, $returnValue);

    // Copy the built files to the document root
    copy('dist/app.min.js', $docroot);
    copyDirectory($app . '/Assets/', $docroot);

    // Create the 'modules' directory if it doesn't exist
    $modulesDir = $docroot . '/modules';
    if (!is_dir($modulesDir)) {
        mkdir($modulesDir, 0755, true);
    }

    // Copy the 'human-writes' module to the 'modules' directory
    copy('node_modules/human-writes/dist/web/human-writes.min.js', $modulesDir . '/human-writes.min.js');

    // Run the 'egg build' command
    $output = null;
    $returnValue = null;
    exec('php ./egg build', $output, $returnValue);
}

exit(0);

/**
 * Recursively remove a directory and its contents.
 *
 * @param string $dir The directory to remove
 * @return bool
 */
function removeDirectory($dir)
{
    if (!is_dir($dir)) {
        return false;
    }

    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        is_dir($path) ? removeDirectory($path) : unlink($path);
    }

    return rmdir($dir);
}

/**
 * Recursively copy a directory and its contents.
 *
 * @param string $src The source directory
 * @param string $dst The destination directory
 * @return bool
 */
function copyDirectory($src, $dst)
{
    $dir = opendir($src);
    if (!is_dir($dst)) {
        mkdir($dst, 0755, true);
    }

    while (($file = readdir($dir)) !== false) {
        if ($file !== '.' && $file !== '..') {
            if (is_dir($src . '/' . $file)) {
                copyDirectory($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }

    closedir($dir);
    return true;
}
