<?php

/*
CLI tool to echo workflow yml with a scheduled cron
params:
- worfklow e.g. ci | standards | keepalive
- ghrepo e.g. ${{ github.repository }}

Examples:
php create_workflow.php ci silverstripe/silverstripe-admin
php create_workflow.php keepalive silverstripe/recipe-cms

Notes:
- github crons run on UTC
- https://crontab.guru/ is useful to translate a cron to english
*/


$workflow = $argv[1] ?? '';
if (!in_array($workflow, ['ci', 'standards', 'keepalive'])) {
    echo "Undefined worflow $workflow\n";
    exit(1);
}

$ghrepo = $argv[2] ?? '';
if (!preg_match('#[a-zA-Z0-9\-]/[a-zA-Z0-9\-]#', $ghrepo)) {
    echo "Invalid ghrepo $ghrepo";
    exit(1);
}

include 'WorkflowCreator.php';
echo  (new WorkflowCreator())->createWorkflow($workflow, $ghrepo);
