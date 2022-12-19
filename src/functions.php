<?php
// GET ACCESS TOKEN
function getAccessToken($url_token, $tenant_name, $api_client_id, $api_user_key)
{
    $data_input_token = [
        "grant_type" => "refresh_token",
        "client_id" => $api_client_id,
        "refresh_token" => $api_user_key
        ];
    $headers = [
        "Content-Type: application/json",
        "X-UIPATH-TenantName: $tenant_name"
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_token);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_input_token));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $output = curl_exec($ch);
    $access_token = (json_decode($output, true))['access_token'];
    curl_close($ch);
    return $access_token;
}

// GET PROCESS RELEASE KEY
function getReleaseKey($access_token, $org_name, $tenant_name, $process_version, $process_name)
{
    $url_release_key = 'https://cloud.uipath.com/' . $org_name . '/' .$tenant_name . '/orchestrator_/odata/Releases?$filter=ProcessKey%20eq%20' . $process_name;

    $headers = [
        "Content-Type: application/json",
        "X-UIPATH-TenantName: $tenant_name",
        "Authorization: Bearer $access_token"
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_release_key);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $output = curl_exec($ch);
    foreach((json_decode($output, true))['value'] as $array_element)
    {
        if($array_element['ProcessVersion'] == $process_version)
        {
           $release_key = $array_element['Key'];
        }
    }
    curl_close($ch);
    return $release_key;
}

// START JOB AND GET JOB ID
function startJobGetJobId($org_name, $tenant_name, $release_key, $input_parameters, $access_token, $org_unit_id)
{
    $url_start_job = "https://cloud.uipath.com/$org_name/$tenant_name/orchestrator_/odata/Jobs/UiPath.Server.Configuration.OData.StartJobs";
    $data_input_start_job = [
        "startInfo" => [
            "ReleaseKey" => $release_key,
            "Strategy" => "ModernJobsCount",
            "JobsCount" => 1,
            "RobotIds" => [],
            "NoOfRobots" => 0,
            "InputArguments" => "$input_parameters" 
        ]
    ];
    $headers = [
        "Content-Type: application/json",
        "X-UIPATH-TenantName: $tenant_name",
        "X-UIPATH-OrganizationUnitId: $org_unit_id",
        "Authorization: Bearer $access_token",
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_start_job);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_input_start_job));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $output = curl_exec($ch);
    $job_id = (json_decode($output, true))['value'][0]['Id'];
    curl_close($ch);
    return $job_id;
}

// GET JOB STATUS AND OUTPUT PARAMETERS
function getJobStatus($org_name, $tenant_name, $job_id, $access_token)
{
    sleep(5);
    $url_job_status = 'https://cloud.uipath.com/' . $org_name . '/' .$tenant_name . '/orchestrator_/odata/Jobs?$filter=ID%20eq%20' . $job_id;
    $headers = [
        "Content-Type: application/json",
        "X-UIPATH-TenantName: $tenant_name",
        "Authorization: Bearer $access_token"
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_job_status);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $output = curl_exec($ch);
    $job_status = json_decode($output, true);
    if ($job_status['value'][0]['State'] == 'Successful')
    {   
        $job_status_output_obj = json_decode($job_status['value'][0]['OutputArguments']);
        $output_parameter = $job_status_output_obj -> strOutputParam;
        $message_reply="Thanks, your Output Parameter is -> $output_parameter ";
    }
    else
    {
        $message_reply="There was some troubles with your request.. Please try again later or contact one of our support agents for more information";
    }   
    curl_close($ch);
    return $message_reply;
}

// DEFINE WEBHOOK RESPONSE PARAMETERS
function webhookReply($job_status)
{
    $data_reply = [
        'status' => 'success',
        'chatbot_response' => $job_status
    ];
    $data_reply_json = json_encode($data_reply, JSON_PRETTY_PRINT);
    echo $data_reply_json;
}
?>
