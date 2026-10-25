Start-Transcript "C:\Users\d.audra\d.audra.log"
$getRestricted = Get-ChildItem -Path "C:\Tickets\Restricted" -Directory | Sort-Object -Property Name -Descending | Select-Object -First 1 | Select-Object -Property Name
$getScreenshot = Get-ChildItem -Path "C:\Tickets\Restricted\$($getRestricted.Name)" -Filter *.url | Select-Object -Property Name
$getUrl = Select-String -Path "C:\Tickets\Restricted\$($getRestricted.Name)\$($getScreenshot.Name)" -Pattern "^URL="
$getUrlClean = $getUrl.Line -replace '^URL=' -replace '^file://', '\\' -replace '/', '\'
Test-Path -LiteralPath "$getUrlClean"