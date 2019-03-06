#Windows Power
#$content = [IO.File]::ReadAllText($output)
$content='Compeleted Backup C:/Shared to //nas/Public/Shared '
$postParams = @{user='WindowsTask'; message=$content; category=WINDOWS}
#now call post the information to the PHP script
#Invoke-WebRequest is a powerShell feature introduced in PowerShell 3.0.
Invoke-WebRequest -Uri http://localhost/lab/logevent.php -Method POST -Body $postParams
