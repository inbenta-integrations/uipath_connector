<?php
require "functions.php";
require "auth.php";

// GET INPUT PARAMETERS
// REPLACE PARAMETER1/2/3 WITH YOUR PARAMETER NAMES
// WE RECOMEND USING THE SAME PARAMETER NAMES AS USED IN UIPATH
$input_parameters = [
    "parameter1" => $_POST['parameter1'],
    "parameter2" => $_POST['parameter2'],
    "parameter3" => $_POST['parameter3']
];

// GET ACCESS TOKEN
$access_token = getAccessToken($url_token, $tenant_name, $api_client_id, $api_user_key);

// GET PROCESS RELEASE KEY
$release_key = getReleaseKey($access_token, $org_name, $tenant_name, $process_version, $process_name);

// START JOB AND GET JOB ID
$job_id = startJobGetJobId($org_name, $tenant_name, $release_key, $input_parameters, $access_token, $org_unit_id);

// GET JOB STATUS AND OUTPUT PARAMETERS
$job_status = getJobStatus($org_name, $tenant_name, $job_id, $access_token);

// DEFINE WEBHOOK RESPONSE PARAMETERS
webhookReply($job_status);
?>
