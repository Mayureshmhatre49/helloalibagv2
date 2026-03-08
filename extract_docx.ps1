Add-Type -AssemblyName System.IO.Compression.FileSystem

$zipPath = "C:\xampp\htdocs\helloalibgv2\HelloAlibaug_Full_Audit_v2.docx"
$extractPath = "C:\xampp\htdocs\helloalibgv2\temp_docx"

If(Test-Path $extractPath) { Remove-Item $extractPath -Recurse -Force }
New-Item -Path $extractPath -ItemType Directory | Out-Null

[System.IO.Compression.ZipFile]::ExtractToDirectory($zipPath, $extractPath)

$xmlPath = "$extractPath\word\document.xml"
[xml]$xml = Get-Content -Path $xmlPath

$ns = @{ w = "http://schemas.openxmlformats.org/wordprocessingml/2006/main" }
$textNodes = Select-Xml -Xml $xml -XPath "//w:p" -Namespace $ns
$extractedText = $textNodes | ForEach-Object { 
    $pText = Select-Xml -Xml $_.Node -XPath ".//w:t" -Namespace $ns | ForEach-Object { $_.Node.InnerText }
    $pText -join ""
}
$extractedText -join "`n"

Remove-Item $extractPath -Recurse -Force
