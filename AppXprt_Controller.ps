param (
    [string]$Server = "10.10.10.10",
    [string]$IServer  = "10.10.10.10",
    [string]$MAC = "10.10.10.10",
    [string]$Pass = "",
    [string]$IPass = "",
    [string]$GPass = "",
    [string]$MPass = "",
    [string]$User = "root",
    [string]$MUser = "appxprt",
    [string]$DS = "APP-SSD-01",
    [string]$ISO_DS = "APP-SSD-01",
    #[string]$BLD_OS_ISO = "alpine-appxprte-190629-x86_64.iso",
    #[string]$BLD_OS_ISO = "alpine-appxprte-211015-x86_64.iso",
    #[string]$BLD_OS_ISO = "alpine-appxprte-220214-x86_64.iso",
    [string]$BLD_OS_ISO = "alpine-appxprte-220219-x86_64.iso",
    [Parameter(Mandatory=$false)][string]$WpUserId,
    [Parameter(Mandatory=$false)][string]$SiteURL,
    [Parameter(Mandatory=$false)][string]$Init,
    [Parameter(Mandatory=$false)][string]$Android,
    [Parameter(Mandatory=$false)][string]$IOS,
    [Parameter(Mandatory=$false)][string]$Create,
    [Parameter(Mandatory=$false)][string]$Upload,
    [Parameter(Mandatory=$false)][string]$Boot,
    [Parameter(Mandatory=$false)][string]$Build,
    [Parameter(Mandatory=$false)][string]$Push,
    [Parameter(Mandatory=$false)][string]$Destroy,
    [Parameter(Mandatory=$false)][string]$Status,
    [switch]$Ready,
    [switch]$Queue,
    [switch]$Network,
    [switch]$Storage,
    [switch]$Load
)
Set-Location -Path "/var/www/html/p/app/power/"
Set-StrictMode -Off

$defaultAutoLoad = $PSMmoduleAutoloadingPreference
$PSMmoduleAutoloadingPreference = "none"

if (Get-Module -ListAvailable -Name VMware*) {
    Import-Module VMware.VimAutomation.Core 2>&1 | out-null
    Set-PowerCLIConfiguration -InvalidCertificateAction Ignore -Scope Session -Confirm:$false | out-null
}

function Connect-ESXi {
    $Session = Connect-VIServer -Server $Server -User:$User -Pass:$Pass 2>&1 | out-null

    if($global:DefaultVIServer.name -like $Server){
    } else {
        Write-Host "Couldn't Connect to $Server"
        exit
    }
    return $Session
}
function Close-ESXi {
    if($global:DefaultVIServer.name -like $Server){
        Remove-PSDrive -Name DS -Confirm:$false 2>&1 | out-null
        Disconnect-VIServer $Server -Confirm:$false 2>&1 | out-null
    }
}
function Connect-IESXi {
    $Session = Connect-VIServer -Server $IServer -User:$User -Pass:$IPass 2>&1 | out-null

    if($global:DefaultVIServer.name -like $IServer){
    } else {
        Write-Host "Couldn't Connect to $IServer"
        exit
    }
    return $Session
}
function Close-IESXi {
    if($global:DefaultVIServer.name -like $IServer){
        #Remove-PSDrive -Name DS -Confirm:$false 2>&1 | out-null
        Disconnect-VIServer $IServer -Confirm:$false 2>&1 | out-null
    }
}
function Do-Create{
    param( $Create )
    $DataStore = Get-Datastore -Name $DS
    $ISO = Get-Datastore -Name $ISO_DS

    New-PSDrive -Location $DataStore -Name DS -PSProvider VimDatastore -Root "\"
    New-PSDrive -Location $ISO -Name ISO -PSProvider VimDatastore -Root "\ISO\"

    New-Item -Path DS:\ -ItemType Directory -Name $GUID
    $Folder = Get-Item -Path DS:\$GUID
    if($Folder){
        $New_VM_Task = New-VM -GuestId "otherLinux64Guest" -Name $GUID -Datastore $DataStore -MemoryMB 8192 -NumCpu 32 -DiskGB 8 -CD -DiskStorageFormat Thin -NetworkName "LAN" 2>&1 | out-null

        while ($New_VM_Task.ExtensionData.Info.State -eq "running") {
            sleep 1
            $New_VM_Task.ExtensionData.UpdateViewData('Info.State')
        }
        sleep 4

        $New_VM = Get-VM -name $GUID -ErrorAction SilentlyContinue
        if($New_VM){
            Copy-DatastoreItem -Item $Create -Destination "ds:\$GUID\"
            $CD = Get-CDDrive -VM $New_VM
            Set-CDDrive -CD $CD -ISOPath "[$ISO_DS]\ISO\$BLD_OS_ISO" -Confirm:$false -StartConnected $true

            $BLD = Split-Path $Create -leaf
            $BLD_ISO = "[$DS]\$GUID\"+$BLD
            $Build_CD = New-CDDrive -IsoPath $BLD_ISO -VM $New_VM -StartConnected:$true -Confirm:$false
        }
    }
}
function Do-Boot{
    param( $Boot )
        $VM = Get-VM $Boot
        $Power_Task = Start-VM -VM $VM -Confirm:$false -ErrorAction SilentlyContinue
        Start-Sleep -Seconds 10
        while ($Power_Task.ExtensionData.Info.State -eq "running") {
                Start-Sleep 1
                $Power_Task.ExtensionData.UpdateViewData('Info.State')
        }
        $VMG = Get-VMGuest -VM $VM
        $Status = $VM.ExtensionData.Guest.ToolsStatus
        #while ($Status -ne "toolsOk") {
        #        $VM.ExtensionData.UpdateViewData('Guest.ToolsStatus')
        #        $Status = $VM.ExtensionData.Guest.ToolsStatus
                Start-Sleep -Seconds 15
        #}
        return 1
}
function Do-Build{
    param( $Build )

$BuildScript = @"
mkdir -p /media/$Build
mkdir -p /home/build/Android
mkdir -p /home/build/Android/Sdk

mount /dev/sr1 /media/$Build

cp -R /media/$Build /home/build/
cp -R /media/cdrom/build/.android/ /home/build/

cd /media/cdrom/build/Android/Sdk
cp -R platforms/ platform-tools/ tools/ build-tools/ /home/build/Android/Sdk

yes|/home/build/Android/Sdk/tools/bin/sdkmanager --licenses | xargs echo
chown -R build:build /home/build/
chmod -R 775 /home/build/
cd /home/build/$Build
echo build | sudo -S sh Build\ Debug\ Android\ AppXprt.sh
echo build | sudo -S sh Build\ Release\ Android\ AppXprt.sh
echo build | sudo -S /bin/echo '10.10.10.10 build.appxprt.com' >> /etc/hosts
"@

            $Session = Connect-ESXi
            $VM = Get-VM -name $Build -ErrorAction SilentlyContinue
            $BUILD = Invoke-VMScript -VM $VM -GuestUser "root" -GuestPass $GPass -ScriptType "Bash" -ScriptText $BuildScript
            Close-ESXi
            return $BUILD
}
function Do-Push{
    param( $Push )

$PushScript = @"
echo build | sudo -S /bin/echo '10.10.10.10 build.appxprt.com' >> /etc/hosts
echo build | sudo -S /usr/bin/curl --user build:build --cookie-jar ~/cookies -F "file_contents=@/home/build/$Push/platforms/android/app/build/outputs/apk/debug/app-debug.apk" -F "wp_user_id=$WpUserId" -F "site=$Push" -F "site_url=$SiteURL" http://build.appxprt.com/upload
echo build | sudo -S /usr/bin/curl --user build:build --cookie-jar ~/cookies -F "file_contents=@/home/build/$Push/platforms/android/app/build/outputs/apk/release/app-release-unsigned.apk" -F "wp_user_id=$WpUserId" -F "site=$Push" -F "site_url=$SiteURL" http://build.appxprt.com/upload
echo build | sudo -S /usr/bin/curl --user build:build --cookie-jar ~/cookies -F "file_contents=@/home/build/$Push/platforms/android/app/build/outputs/bundle/release/app-release.aab" -F "wp_user_id=$WpUserId" -F "site=$Push" -F "site_url=$SiteURL" http://build.appxprt.com/upload
"@

            $Session = Connect-ESXi
            $VM = Get-VM -name $Push -ErrorAction SilentlyContinue
            $PUSH = Invoke-VMScript -VM $VM -GuestUser "root" -GuestPass $GPass -ScriptType "Bash" -ScriptText $PushScript
            Close-ESXi
            return $PUSH
}
function Do-Destroy{
    param( $Destroy )
        $Session = Connect-ESXi
        $DataStore = Get-Datastore -Name $DS
        New-PSDrive -Name DS -PSProvider VimDatastore -Root '\' -Location $DataStore > $null

        $Rm_VM = Get-VM -name $Destroy -ErrorAction SilentlyContinue
        if($Rm_VM){
                $Shutdown = Shutdown-VMGuest -VM $Rm_VM -Confirm:$false -Server $Session -ErrorAction SilentlyContinue
                sleep 5
                $Rm_VM_Task = Remove-VM -VM $Rm_VM -DeletePermanently -Server $Session -Confirm:$false -ErrorAction SilentlyContinue
        }

        #while ($Rm_VM_Task.ExtensionData.Info.State -eq "running") {
        #    sleep 1
        #    $Task.ExtensionData.UpdateViewData('Info.State')
        #}
        Get-ChildItem -Path "DS:/$($Destroy)/*.iso" | Foreach-object {Remove-item -Recurse -Force -path $_.FullName }
        Remove-PSDrive -Name DS -Confirm:$false
        Close-ESXi
        return $Rm_VM_Task
}
function Check-Status{
    param( $Status )
        $Session = Connect-ESXi
        $VM = Get-VM $Status
        $Status = $VM.ExtensionData.Guest.ToolsStatus
        Close-ESXi
        return $Status
}
function Do-Android{
    param( $Android )
        $Session = Connect-ESXi
        $GUID = New-Guid

        $VM=Do-Create -Create $Android
        if($VM){
            $Boot = Do-Boot -Boot $GUID
            if($Boot){

            #Android
                Do-Build -Build $GUID
                Do-Push -Push $GUID
                Do-Destroy -Destroy $GUID
                Add-Content log/Build-$GUID.log $Build

            }
        }
        Close-ESXi
}
function Do-IOS{
    param($IOS)
        [securestring]$SMPass = ConvertTo-SecureString $MPass -AsPlainText -Force
        [pscredential]$Cred = New-Object System.Management.Automation.PSCredential ($MUser, $SMPass)

        $SH_Session = New-SSHSession -ComputerName $MAC -Credential $Cred -AcceptKey -KeyFile ~/.ssh/appxprt.priv.rsa
        $SF_Session = New-SFTPSession -ComputerName $MAC -Credential $Cred -AcceptKey -KeyFile ~/.ssh/appxprt.priv.rsa

        if($SiteURL){
            $Removal = Invoke-SSHCommand -Index 0 -Command "rm -R ~/AppXprt/exports/$WpUserId/upload/$SiteURL/"
            $Creation = Invoke-SSHCommand -Index 0 -Command "mkdir -p ~/AppXprt/exports/$WpUserId/upload/$SiteURL/"
        }
        $Copy = Set-SFTPItem -SessionId $SF_Session.SessionId -Path $IOS -Destination /Users/appxprt/AppXprt/exports/$WpUserId/upload/$SiteURL/
        Invoke-SSHCommand -Index 0 -Command "cd /Users/appxprt/AppXprt/exports/$WpUserId/upload/$SiteURL; unzip AppXprt-$SiteURL.zip"
        Invoke-SSHCommand -Timeout 300 -Index 0 -Command "PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games:/snap/bin:/home/appxprt/.composer/vendor/bin/; cd /Users/appxprt/AppXprt/exports/$WpUserId/upload/$SiteURL && /bin/bash Build\ Debug\ iOS\ AppXprt.sh"

        Remove-SFTPSession -SFTPSession $SF_Session
        Remove-SSHSession -SSHSession $SH_Session
}
if($Create){
    $Session = Connect-ESXi

    $GUID = New-Guid
    $defaultAutoLoad = $PSMmoduleAutoloadingPreference
    $PSMmoduleAutoloadingPreference = "none"

    $DataStore = Get-Datastore -Name $DS
    $ISO = Get-Datastore -Name $ISO_DS

    New-PSDrive -Location $DataStore -Name DS -PSProvider VimDatastore -Root "\"
    New-PSDrive -Location $ISO -Name ISO -PSProvider VimDatastore -Root "\ISO\"

    New-Item -Path DS:\ -ItemType Directory -Name $GUID
    $Folder = Get-Item -Path DS:\$GUID
    if($Folder){
        $Task = New-VM -GuestId "ubuntu64Guest" -Name $GUID -Datastore $DataStore -MemoryMB 8192 -NumCpu 32 -DiskGB 8 -CD -DiskStorageFormat Thin -NetworkName "LAN" 2>&1 | out-null

        while ($Task.ExtensionData.Info.State -eq "running") {
            sleep 1
            $Task.ExtensionData.UpdateViewData('Info.State')
        }
        sleep 4

        $New_VM = Get-VM -name $GUID -ErrorAction SilentlyContinue
        if($New_VM){
	    Copy-DatastoreItem -Item $Create -Destination "ds:\$GUID\"
            $CD = Get-CDDrive -VM $New_VM
            Set-CDDrive -CD $CD -ISOPath "[$ISO_DS]\$BLD_OS_ISO" -Confirm:$false -StartConnected $true

	    $BLD = Split-Path $Create -leaf
	    $BLD_ISO = "[$DS]\$GUID\"+$BLD

            $Build_CD = New-CDDrive -IsoPath $BLD_ISO -VM $New_VM -StartConnected:$true -Confirm:$false
            Close-ESXi
            return '{"GUID":"'+$GUID+'"}'
        }
    }
}
if($Upload){
    $Session = Connect-ESXi

    $GUID = New-Guid
    $defaultAutoLoad = $PSMmoduleAutoloadingPreference
    $PSMmoduleAutoloadingPreference = "none"

    $DataStore = Get-Datastore -Name $DS
    $ISO = Get-Datastore -Name $ISO_DS

    New-PSDrive -Location $DataStore -Name DS -PSProvider VimDatastore -Root "\"
    New-PSDrive -Location $ISO -Name ISO -PSProvider VimDatastore -Root "\ISO\"

    New-Item -Path DS:\ -ItemType Directory -Name $GUID
    $Folder = Get-Item -Path DS:\$GUID
    if($Folder){
	    Copy-DatastoreItem -Item $Upload -Destination "ds:\$GUID\"
        Close-ESXi
        return '{"GUID":"'+$GUID+'"}'
    }
}
if($Boot){
    $Session = Connect-ESXi
    $VM = Get-VM $Boot
    $Power_Task = Start-VM -VM $VM -Confirm:$false -ErrorAction SilentlyContinue
    Start-Sleep -Seconds 10
    while ($Power_Task.ExtensionData.Info.State -eq "running") {
        Start-Sleep 1
        $Power_Task.ExtensionData.UpdateViewData('Info.State')
    }
    $VMG = Get-VMGuest -VM $VM
            $Status = $VM.ExtensionData.Guest.ToolsStatus
            #while ($Status -ne "toolsOk") {
            #        $VM.ExtensionData.UpdateViewData('Guest.ToolsStatus')
            #        $Status = $VM.ExtensionData.Guest.ToolsStatus
            Start-Sleep -Seconds 15
            #}
    Close-ESXi
    return $Status
}
if($Build){

$BuildScript = @"
mkdir -p /media/$Build
mkdir -p /home/build/Android
mkdir -p /home/build/Android/Sdk

mount /dev/sr1 /media/$Build

cp -R /media/$Build /home/build/
cp -R /media/cdrom/build/.android/ /home/build/

cd /media/cdrom/build/Android/Sdk
cp -R platforms/ platform-tools/ tools/ build-tools/ /home/build/Android/Sdk

yes|/home/build/Android/Sdk/tools/bin/sdkmanager --licenses | xargs echo
chown -R build:build /home/build/
chmod -R 775 /home/build/
cd /home/build/$Build
echo build | sudo -S sh Build\ Debug\ Android\ AppXprt.sh
echo build | sudo -S sh Build\ Release\ Android\ AppXprt.sh
echo build | sudo -S /bin/echo '10.10.10.10 build.appxprt.com' >> /etc/hosts
"@


    $Session = Connect-ESXi
    $VM = Get-VM -name $Build -ErrorAction SilentlyContinue
    $BUILD = Invoke-VMScript -VM $VM -GuestUser "root" -GuestPass $GPass -ScriptType "Bash" -ScriptText $BuildScript
    Close-ESXi
    return $BUILD

}
if($Push){

$PushScript = @"
echo build | sudo -S /bin/echo '10.10.10.10 build.appxprt.com' >> /etc/hosts
echo build | sudo -S /usr/bin/curl --user build:build --cookie-jar ~/cookies -F "file_contents=@/home/build/$Push/platforms/android/app/build/outputs/apk/debug/app-debug.apk" -F "wp_user_id=$WpUserId" -F "site=$Push" -F "site_url=$SiteURL" http://build.appxprt.com/upload
echo build | sudo -S /usr/bin/curl --user build:build --cookie-jar ~/cookies -F "file_contents=@/home/build/$Push/platforms/android/app/build/outputs/apk/release/app-release-unsigned.apk" -F "wp_user_id=$WpUserId" -F "site=$Push" -F "site_url=$SiteURL" http://build.appxprt.com/upload
echo build | sudo -S /usr/bin/curl --user build:build --cookie-jar ~/cookies -F "file_contents=@/home/build/$Push/platforms/android/app/build/outputs/bundle/release/app-release.aab" -F "wp_user_id=$WpUserId" -F "site=$Push" -F "site_url=$SiteURL" http://build.appxprt.com/upload
"@

        $Session = Connect-ESXi
        $VM = Get-VM -name $Push -ErrorAction SilentlyContinue
        $PUSH = Invoke-VMScript -VM $VM -GuestUser "root" -GuestPass $GPass -ScriptType "Bash" -ScriptText $PushScript
        Close-ESXi
        return $PUSH
}
if($Destroy){
        $defaultAutoLoad = $PSMmoduleAutoloadingPreference
        $PSMmoduleAutoloadingPreference = "none"

        $Session = Connect-ESXi
        $DataStore = Get-Datastore -Name $DS
        New-PSDrive -Name DS -PSProvider VimDatastore -Root '\' -Location $DataStore > $null
        $Shutdown = Shutdown-VMGuest -VM $Destroy -Confirm:$false -Server $Session
        sleep 3
#           $Rm_VM_Task = Remove-VM -VM $Destroy -DeletePermanently -Server $Session -Confirm:$false
#           while ($Rm_VM_Task.ExtensionData.Info.State -eq "running") {
#              sleep 1
#              $Task.ExtensionData.UpdateViewData('Info.State')
#           }
#            #$Rm_VM = Get-VM -name $Destroy -ErrorAction SilentlyContinue
        Get-ChildItem -Path "DS:/$($Destroy)/*.iso" | Foreach-object {Remove-item -Recurse -Force -path $_.FullName }
        Remove-PSDrive -Name DS -Confirm:$false
        Close-ESXi
        return $Rm_VM_Task

}
if($Status){
        $Session = Connect-ESXi
        $VM = Get-VM $Status
        $Status = $VM.ExtensionData.Guest.ToolsStatus
        Close-ESXi
        return $Status
}
if($Queue){
    Connect-ESXi
    $VMCount = Get-VMHost | Select @{N="Cluster";E={Get-Cluster -VMHost $_}}, Name, @{N="NumVM";E={($_ | Get-VM).Count}}
    Close-ESXi
    return $VMCount.NumVM
}
if($Ready){
    Connect-ESXi

    $SQL = Get-VM -name "APP-SQL-01" -ErrorAction SilentlyContinue
    $API = Get-VM -name "APP-API-01" -ErrorAction SilentlyContinue
    $VMS = @($SQL, $API)

    $Checks = @{}
    foreach($VM in $VMS) {
        $VMG = Get-VMGuest -VM $VM
        $Status = $VM.ExtensionData.Guest.ToolsStatus
        $Checks."$VM" = $Status
    }

    $Checks = $Checks |ConvertTo-Json
    Close-ESXi
    return $Checks
}
if($Load){
    Connect-ESXi
    $API = Get-VM -name "APP-API-01"
    $l = Get-Stat -CPU -Memory -Realtime -Entity $API
    Write-Host $l
    Close-ESXi
    return $l
}
if($Init){
    $Session = Connect-ESXi
    $GUID = New-Guid

    $VM=Do-Create -Create $Init
    if($VM){
        $Boot = Do-Boot -Boot $GUID
        if($Boot){

        #Android
            Do-Build -Build $GUID
            Do-Push -Push $GUID
            Do-Destroy -Destroy $GUID

        #IOS

            Do-IOS -IOS $GUID

        }
    }
    Close-ESXi
}
if($Android){
        $Session = Connect-ESXi
        $GUID = New-Guid

        $VM=Do-Create -Create $Android
        if($VM){
            $Boot = Do-Boot -Boot $GUID
            if($Boot){

            #Android
                Do-Build -Build $GUID
                Do-Push -Push $GUID
                Do-Destroy -Destroy $GUID
                }
        }
}
if($IOS){
    $GUID = New-Guid

    [securestring]$SMPass = ConvertTo-SecureString $MPass -AsPlainText -Force
    [pscredential]$Cred = New-Object System.Management.Automation.PSCredential ($MUser, $SMPass)

    $SH_Session = New-SSHSession -ComputerName $MAC -Credential $Cred -AcceptKey -KeyFile ~/.ssh/appxprt.priv.rsa
    $SF_Session = New-SFTPSession -ComputerName $MAC -Credential $Cred -AcceptKey -KeyFile ~/.ssh/appxprt.priv.rsa

    if($SiteURL){
        $Removal = Invoke-SSHCommand -Index 0 -Command "rm -R ~/AppXprt/exports/$WpUserId/upload/$SiteURL/"
        $Creation = Invoke-SSHCommand -Index 0 -Command "mkdir -p ~/AppXprt/exports/$WpUserId/upload/$SiteURL/"
    }
    $Copy = Set-SFTPItem -SessionId $SF_Session.SessionId -Path $IOS -Destination /Users/appxprt/AppXprt/exports/$WpUserId/upload/$SiteURL/
    Invoke-SSHCommand -Index 0 -Command "cd /Users/appxprt/AppXprt/exports/$WpUserId/upload/$SiteURL; unzip AppXprt-$SiteURL.zip"
    Invoke-SSHCommand -Timeout 300 -Index 0 -Command "PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games:/snap/bin:/home/appxprt/.composer/vendor/bin/; cd /Users/appxprt/AppXprt/exports/$WpUserId/upload/$SiteURL/ && /bin/bash Build\ Debug\ iOS\ AppXprt.sh"

    Remove-SFTPSession -SFTPSession $SF_Session
    Remove-SSHSession -SSHSession $SH_Session
}
