<?php

/*
CLI tool to echo workflow yml with a scheduled cron
cli params:
- worfklow e.g. ci | standards | keepalive
- ghrepo e.g. ${{ github.repository }}
- ${{ github.action_path }} (optional)

Examples:
php create_workflow.php ci silverstripe/silverstripe-admin
php create_workflow.php keepalive silverstripe/recipe-cms /path/to/action

Notes:
- github crons run on UTC
- https://crontab.guru/ is useful to translate a cron to english
*/


$workflow = $argv[1] ?? '';
if (!in_array($workflow, ['ci', 'standards', 'keepalive'])) {
    echo "Undefined workflow $workflow\n";
    exit(1);
}

$ghrepo = $argv[2] ?? '';
if (!preg_match('#[a-zA-Z0-9\-]/[a-zA-Z0-9\-]#', $ghrepo)) {
    echo "Invalid ghrepo $ghrepo\n";
    exit(1);
}

$actionPath = $argv[3] ?? '';
if ($actionPath != '' && !file_exists($actionPath)) {
    echo "Invalid actionPath $actionPath\n";
    exit(1);
}

include 'WorkflowCreator.php';
echo  (new WorkflowCreator())->createWorkflow($workflow, $ghrepo, $actionPath);
