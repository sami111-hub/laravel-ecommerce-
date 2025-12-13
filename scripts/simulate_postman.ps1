# Utility script to mimic manual Postman steps via PowerShell
param()

$projectPath = 'c:\xampp\htdocs\laravel_ecommerce_starter'
$baseUrl = 'http://127.0.0.1:8001'

Set-Location $projectPath

$serveJob = Start-Job -ScriptBlock {
    param($path)
    Set-Location $path
    php artisan serve --host=127.0.0.1 --port=8001 --no-reload
} -ArgumentList $projectPath

Start-Sleep -Seconds 5

try {
    Invoke-WebRequest -Uri "$baseUrl/login" -SessionVariable session | Select-Object -ExpandProperty Content | Out-File -FilePath 'storage\logs\login_page.html' -Encoding UTF8
    $session.Cookies.GetCookies($baseUrl) | ForEach-Object { '{0}={1}' -f $_.Name, $_.Value } | Out-File -FilePath 'storage\logs\login_cookies.txt' -Encoding UTF8

    $loginHtml = Get-Content -Path 'storage\logs\login_page.html' -Raw
    $tokenMatch = [regex]::Match($loginHtml, 'name="_token" value="([^"]+)"')
    if ($tokenMatch.Success) {
        $tokenMatch.Groups[1].Value | Out-File -FilePath 'storage\logs\csrf_token.txt' -Encoding UTF8
    }
}
finally {
    Stop-Job $serveJob | Out-Null
    $serveOutput = Receive-Job $serveJob -ErrorAction SilentlyContinue | Out-String
    $serveOutput | Out-File -FilePath 'storage\logs\serve_output.txt' -Encoding UTF8
    Remove-Job $serveJob | Out-Null
}
