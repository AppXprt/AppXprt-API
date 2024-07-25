<?php
//Set-PowerCLIConfiguration -Scope User -ParticipateInCEIP $true or $false.

$worker= new \GearmanWorker();
$worker->addServer();
//$worker->addFunction("init", "init");
//$worker->addFunction("pwa", "pwa");
$worker->addFunction("android", "android");
$worker->addFunction("ios", "ios");
$worker->addFunction("create", "create");
$worker->addFunction("build", "build");
$worker->addFunction("boot", "boot");
$worker->addFunction("delete", "delete");
$worker->addFunction("status", "status");
$worker->addFunction("ready", "ready");
$worker->addFunction("queue", "queue");

while ($worker->work());

function init(GearmanJob $job)
{
 $serialized = $job->workload();
 if (is_string($serialized)){
    $workload = @unserialize($serialized);
    if($workload !== false){
        $ISO = $workload[0];
        $wp_user_id = $workload[1];
    }
 }
 $STR = strpos($ISO, "-");
 $URL = substr($ISO, $STR+1, -4);
 $CMD = "pwsh -Executionpolicy bypass -NoProfile -InputFormat none -File '" . __DIR__ . "/../power/AppXprt_Controller.ps' --WpUserId " . $wp_user_id . " --SiteURL " . $URL  . " --Init " . $ISO;
 //$EXE = shell_exec("pwsh -Executionpolicy bypass -NoProfile -InputFormat none -File '" . __DIR__ . "/../power/AppXprt_Controller.ps' --wp_user_id " . $wp_user_id . " --SiteURL " . $URL  . " --Init " . $ISO);
 $EXE = shell_exec($CMD);
}
function android(GearmanJob $job)
{
    $serialized = $job->workload();
    if (is_string($serialized)){
        $workload = @unserialize($serialized);
        if($workload !== false){
            $ISO = $workload[0];
            $wp_user_id = $workload[1];
        }
    }
    $STR = strpos($ISO, "-");
    $URL = substr($ISO, $STR+1, -4);
    $CMD = "pwsh -Executionpolicy bypass -NoProfile -InputFormat none -File '" . __DIR__ . "/../power/AppXprt_Controller.ps' --WpUserId " . $wp_user_id . " --SiteURL " . $URL  . " --Android " . $ISO;
    //$EXE = shell_exec("pwsh -Executionpolicy bypass -NoProfile -InputFormat none -File '" . __DIR__ . "/../power/AppXprt_Controller.ps' --wp_user_id " . $wp_user_id . " --SiteURL " . $URL  . " --Android " . $ISO);
    $EXE = shell_exec($CMD);
    echo $EXE;
}
function ios(GearmanJob $job)
{
    $serialized = $job->workload();
    if (is_string($serialized)){
        $workload = @unserialize($serialized);
        if($workload !== false){
            $URL = $workload[0];
            $wp_user_id = $workload[1];
            $ZIP = $workload[2];
        }
    }
    $STR = strpos($ZIP, "-");
    $URL = substr($ZIP, $STR+1, -4);
    $CMD = shell_exec("pwsh -Executionpolicy bypass -NoProfile -InputFormat none -File '" . __DIR__ . "/../power/AppXprt_Controller.ps' --WpUserId " . $wp_user_id . " --SiteURL " . $URL  . " --IOS " . $ZIP);
    $EXE = shell_exec($CMD);
    return $EXE;
}
function create(GearmanJob $job)
{
 $ISO = $job->workload();
 $STR = strpos($ISO, "-");
 $URL = substr($ISO, $STR+1, -4);
 echo "\nCreating New Build VM for [".$ISO."]...\n";

 $EXE = shell_exec("pwsh -Executionpolicy bypass -NoProfile -InputFormat none -File '" . __DIR__ . "/../power/AppXprt_Controller.ps' --SiteURL " . $URL  . " --Create " . $ISO);
 return $EXE;
}
function boot(GearmanJob $job)
{
 $GUID = $job->workload();
 echo "\nBooting [".$GUID."]";
 $EXE = shell_exec("pwsh -Executionpolicy bypass -NoProfile -InputFormat none -File '" . __DIR__ . "/../power/AppXprt_Controller.ps' --Boot " . $GUID);
 echo "Boot Returned: ".$EXE;
 return $EXE;
}
function build(GearmanJob $job)
{
 $GUID = $job->workload();
 echo "\nBuilding ".$GUID;
 $EXE = shell_exec("pwsh -Executionpolicy bypass -NoProfile -InputFormat none -File '" . __DIR__ . "/../power/AppXprt_Controller.ps' --Build " . $GUID);
}
function delete(GearmanJob $job)
{
 $GUID = $job->workload();
 echo "\nDestroying ".$GUID;
 $EXE = shell_exec("pwsh -Executionpolicy bypass -NoProfile -InputFormat none -File '" . __DIR__ . "/../power/AppXprt_Controller.ps' --Destroy " . $GUID);
}
function status(GearmanJob $job)
{
    $GUID = $job->workload();
    //echo "\nChecking on Status of ".$GUID;
    $EXE = shell_exec("pwsh -Executionpolicy bypass -NoProfile -InputFormat none -File '" . __DIR__ . "/../power/AppXprt_Controller.ps' --Status " . $GUID);
}
function ready(GearmanJob $job)
{
    $Required_Unused = $job->workload();
    //echo "\nChecking on Infrastructure Status";
    $EXE = shell_exec("pwsh -Executionpolicy bypass -NoProfile -InputFormat none -File '" . __DIR__ . "/../power/AppXprt_Controller.ps' --Ready");
    return $EXE;
}
function queue(GearmanJob $job)
{
    $Required_Unused = $job->workload();
    //echo "\nChecking Build Queue ";
    $EXE = shell_exec("pwsh -Executionpolicy bypass -NoProfile -InputFormat none -File '" . __DIR__ . "/../power/AppXprt_Controller.ps' --Queue");
    return trim($EXE, "\n");
}
function load(GearmanJob $job)
{
    $Required_Unused = $job->workload();
    //echo "\nChecking API Load ";
    $EXE = shell_exec("pwsh -Executionpolicy bypass -NoProfile -InputFormat none -File '" . __DIR__ . "/../power/AppXprt_Controller.ps' --Load");
    return trim($EXE, "\n");
}
?>
