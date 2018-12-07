#!/usr/bin/php
<?php

// Support a PROJECT environment variable.
if (getenv('PROJECT')) {
  $project = getenv('PROJECT');
  echo "Project name $project taken from environment variable.\n";
}
else {
  // We get the project name from the name of the path that Composer created for
  // us.
  $project = basename(realpath("."));
  // Support folder structure like "PROJECT/vcs" as well.
  if ($project == 'vcs') {
    $project = basename(realpath(".."));
  }
  echo "Project name $project taken from directory name.\n";
}

// Specify files for which replacement will be applied.
$file_patterns = [
  'dotenv/.env',
  '*.yml',
  '.*.dist',
];

$replacements = [
  "{{ project }}" => $project,
  // Provide a version with underscore delimiters.
  "{{ project_underscore }}" => str_replace('-', '_', $project),
];

// Process replacements.
foreach ($file_patterns as $pattern) {
  foreach (glob($pattern, GLOB_BRACE) as $file) {
    $content = file_get_contents($file);
    if (($new_content = strtr($content, $replacements)) != $content) {
      echo "Processing replacements in file $file...\n";
      file_put_contents($file, $new_content);
    }
  }
}

exit(0);
