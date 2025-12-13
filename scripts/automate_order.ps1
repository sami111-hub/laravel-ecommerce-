# Automate login -> add to cart -> create order
$projectPath = 'c:\xampp\htdocs\laravel_ecommerce_starter'
Set-Location $projectPath
$base = 'http://127.0.0.1:8001'

$logs = Join-Path $projectPath 'storage\logs'
if (-not (Test-Path $logs)) { New-Item -ItemType Directory -Path $logs | Out-Null }

$session = New-Object Microsoft.PowerShell.Commands.WebRequestSession

# Load cookies
$cookiesFile = Join-Path $logs 'login_cookies.txt'
if (Test-Path $cookiesFile) {
    foreach ($line in Get-Content $cookiesFile) {
        if ($line -match '^(.*?)=(.*)$') {
            $name = $matches[1]
            $value = $matches[2]
            $cookie = New-Object System.Net.Cookie($name,$value,'/','127.0.0.1')
            $session.Cookies.Add($cookie)
        }
    }
}

# Load CSRF token
$tokenFile = Join-Path $logs 'csrf_token.txt'
$token = ''
if (Test-Path $tokenFile) { $token = (Get-Content $tokenFile -Raw).Trim() }

# Login
$loginBody = @{ email = 'tester@example.com'; password = 'Test@1234'; _token = $token }
try {
    $resp = Invoke-WebRequest -Uri ($base + '/login') -Method POST -Body $loginBody -WebSession $session -Headers @{ 'Referer' = $base + '/login' }
    "$($resp.StatusCode)" | Out-File 'storage\logs\login_post_status.txt' -Encoding UTF8
} catch {
    $_ | Out-File 'storage\logs\login_post_error.txt' -Encoding UTF8
}

# Get a product id
try {
    $prodPage = Invoke-WebRequest -Uri ($base + '/products') -WebSession $session
    $content = $prodPage.Content
    if ($content -match '/products/([0-9]+)') { $pid = $matches[1] } else { $pid = '1' }
    $pid | Out-File 'storage\logs\selected_product_id.txt'
} catch {
    "prod-get-error: $_" | Out-File 'storage\logs\product_error.txt'
    exit 1
}

# Add to cart
$addBody = @{ quantity = '1'; _token = $token }
try {
    $add = Invoke-WebRequest -Uri ($base + '/cart/add/' + $pid) -Method POST -Body $addBody -WebSession $session -Headers @{ 'Referer' = $base + '/products' }
    "$($add.StatusCode)" | Out-File 'storage\logs\add_cart_status.txt' -Encoding UTF8
} catch {
    $_ | Out-File 'storage\logs\add_cart_error.txt' -Encoding UTF8
}

# Open create order page
try {
    $createPage = Invoke-WebRequest -Uri ($base + '/orders/create') -WebSession $session
    if ($createPage.StatusCode) { $createPage.StatusCode | Out-File 'storage\logs\order_create_status.txt' -Encoding UTF8 }
} catch {
    $_ | Out-File 'storage\logs\order_create_error.txt' -Encoding UTF8
}

# Submit order
$orderBody = @{ shipping_address = 'Test Address'; phone = '123456789'; notes = 'Automated order'; _token = $token }
try {
    $order = Invoke-WebRequest -Uri ($base + '/orders') -Method POST -Body $orderBody -WebSession $session -Headers @{ 'Referer' = $base + '/orders/create' }
    if ($order) { $order.StatusCode | Out-File 'storage\logs\order_post_status.txt' -Encoding UTF8 }
} catch {
    $_ | Out-File 'storage\logs\order_post_error.txt' -Encoding UTF8
}

"done" | Out-File (Join-Path $logs 'automate_done.txt') -Encoding UTF8
