<?php
require "insurance_example_functions.php";
require "insurance_example_auth.php";

// GET INPUT PARAMETERS
$input_parameters = [
    "strInsuranceAccNr" => $_POST['UIPATH_INSURANCE_ACC_NR'],
    "strInsuranceType" => $_POST['UIPATH_INSURANCE_TYPE'],
    "strName" => $_POST['FIRST_NAME'],
    "strEmail" => $_POST['EMAIL_ADDRESS'],
    "strAccidentDescr" => $_POST['UIPATH_ACCIDENT_DESCRIPTION'],
    "strAccidentTime" => $_POST['UIPATH_ACCIDENT_TIME'],
    "strCarType" => $_POST['UIPATH_CAR_TYPE'],
    "strCarDamage" => $_POST['UIPATH_CAR_DAMAGE'],
    "strOtherCars" => $_POST['UIPATH_OTHER_CARS'],
    "strInjuries" => $_POST['UIPATH_INJURIES'],
    "strPolice" => $_POST['UIPATH_POLICE'],
    "strDate" => date('Y-m-d'),
    "strTransactionID" => rand(),
    "processStatus" => 'Pending'
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
