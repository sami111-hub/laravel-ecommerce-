#!/usr/bin/env pwsh
# ============================================================
# سكريبت النشر اليدوي المحسّن - Improved Manual Deployment
# يرفع من GitHub ثم يحدث السيرفر تلقائياً عبر Posh-SSH
# لا يحتاج إدخال كلمة المرور يدوياً
# ============================================================

$ErrorActionPreference = "Continue"

# ===== الإعدادات =====
$SERVER_IP   = "13.37.138.216"
$SERVER_USER = "smStore"
$SERVER_PASS = "aDm1n4StoRuSr2"
$REMOTE_PATH = "/home/smStore/laravel_ecommerce_starte"
$SITE_URL    = "https://store.update-aden.com"
$LOCAL_PATH  = $PSScriptRoot
if (-not $LOCAL_PATH) { $LOCAL_PATH = Get-Location }

# ===== الألوان =====
function Write-Step($num, $total, $text) { Write-Host "`n[$num/$total] $text" -ForegroundColor Cyan }
function Write-OK($text)   { Write-Host "  [OK] $text" -ForegroundColor Green }
function Write-Warn($text) { Write-Host "  [!] $text" -ForegroundColor Yellow }
function Write-Err($text)  { Write-Host "  [X] $text" -ForegroundColor Red }
function Write-Info($text) { Write-Host "  >>> $text" -ForegroundColor Gray }

# ===== بداية =====
Clear-Host
Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host "          Smart Store Deployment Script" -ForegroundColor White
Write-Host "           store.update-aden.com" -ForegroundColor Yellow
Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host "  Server:  $SERVER_USER@$SERVER_IP" -ForegroundColor Gray
Write-Host "  Remote:  $REMOTE_PATH" -ForegroundColor Gray
Write-Host "  Time:    $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')" -ForegroundColor Gray
Write-Host "==========================================================" -ForegroundColor Cyan

$stopwatch = [System.Diagnostics.Stopwatch]::StartNew()

# =========================================================
# الخطوة 1: رفع التعديلات إلى GitHub
# =========================================================
Write-Step 1 7 "Checking & pushing to GitHub..."

Push-Location $LOCAL_PATH
$gitStatus = git status --porcelain 2>&1
$hasChanges = ($gitStatus | Where-Object { $_ -notmatch "^\?\?" })
if ($hasChanges) {
    Write-Info "Uncommitted changes found:"
    git status -s
    Write-Host ""
    $defaultMsg = "Deploy update $(Get-Date -Format 'yyyy-MM-dd HH:mm')"
    $commit = Read-Host "  Enter commit message (or Enter for: '$defaultMsg')"
    if (-not $commit) { $commit = $defaultMsg }
    git add -A
    git commit -m $commit
}

Write-Info "Pushing to GitHub..."
$pushResult = git push origin main 2>&1
if ($LASTEXITCODE -eq 0) {
    Write-OK "Pushed to GitHub successfully"
} else {
    Write-Warn "Git push: $pushResult"
}
Pop-Location

# =========================================================
# الخطوة 2: تحقق من Posh-SSH
# =========================================================
Write-Step 2 7 "Checking SSH module..."

$usePoshSSH = $false
$poshSSH = Get-Module -Name Posh-SSH -ListAvailable -ErrorAction SilentlyContinue
if (-not $poshSSH) {
    Write-Info "Installing Posh-SSH module..."
    try {
        Install-Module -Name Posh-SSH -Force -Scope CurrentUser -SkipPublisherCheck -ErrorAction Stop
        $poshSSH = Get-Module -Name Posh-SSH -ListAvailable
    } catch {
        Write-Warn "Could not install Posh-SSH: $($_.Exception.Message)"
    }
}

if ($poshSSH) {
    Import-Module Posh-SSH -Force
    Write-OK "Posh-SSH v$($poshSSH.Version) loaded"
    $usePoshSSH = $true
} else {
    Write-Warn "Posh-SSH not available, will use native SSH/SCP (password will be prompted)"
}

# =========================================================
# الخطوة 3: اتصال بالسيرفر
# =========================================================
Write-Step 3 7 "Connecting to server $SERVER_IP..."

$session = $null
$maxRetries = 3
$retryDelay = 5

function Connect-ToServer {
    param([int]$Timeout = 20)
    
    $secPw = ConvertTo-SecureString $SERVER_PASS -AsPlainText -Force
    $cred = New-Object System.Management.Automation.PSCredential($SERVER_USER, $secPw)
    
    if ($script:usePoshSSH) {
        try {
            $s = New-SSHSession -ComputerName $SERVER_IP -Credential $cred -Port 22 -AcceptKey -ConnectionTimeout $Timeout -ErrorAction Stop
            return $s
        } catch {
            Write-Warn "SSH: $($_.Exception.Message)"
            return $null
        }
    } else {
        $testResult = ssh -o ConnectTimeout=$Timeout -o BatchMode=yes -o StrictHostKeyChecking=no "$SERVER_USER@$SERVER_IP" "echo SSH_OK" 2>&1
        if ($testResult -match "SSH_OK") { return "native" }
        Write-Warn "SSH: $testResult"
        return $null
    }
}

for ($i = 1; $i -le $maxRetries; $i++) {
    Write-Info "Connection attempt $i/$maxRetries (timeout: 20s)..."
    $session = Connect-ToServer -Timeout 20
    
    if ($session) {
        Write-OK "Connected to server!"
        break
    }
    
    if ($i -lt $maxRetries) {
        Write-Warn "Retry in $retryDelay seconds..."
        Start-Sleep -Seconds $retryDelay
    }
}

if (-not $session) {
    Write-Host ""
    Write-Host "==========================================================" -ForegroundColor Red
    Write-Err "Cannot connect to server $SERVER_IP"
    Write-Host "==========================================================" -ForegroundColor Red
    Write-Host ""
    Write-Host "  Solutions:" -ForegroundColor Yellow
    Write-Host "  1. Check server is running from hosting panel" -ForegroundColor White
    Write-Host "  2. Restart SSH: sudo systemctl restart ssh" -ForegroundColor White
    Write-Host "  3. Check firewall: sudo ufw allow 22/tcp" -ForegroundColor White
    Write-Host ""
    Write-Host "  Run these on server console:" -ForegroundColor Cyan
    Write-Host @"
  cd $REMOTE_PATH
  git fetch origin && git reset --hard origin/main
  composer install --no-dev --optimize-autoloader
  php artisan migrate --force
  php artisan optimize:clear
  php artisan config:cache && php artisan route:cache && php artisan view:cache
  php artisan optimize
  chmod 755 /home/smStore
  chmod -R 755 $REMOTE_PATH
  chmod -R 775 $REMOTE_PATH/storage $REMOTE_PATH/bootstrap/cache
  sudo systemctl restart php8.2-fpm nginx
"@ -ForegroundColor Gray
    Write-Host ""
    Write-Host "==========================================================" -ForegroundColor Red
    $elapsed = $stopwatch.Elapsed
    Write-Host "  Time: $($elapsed.Minutes)m $($elapsed.Seconds)s" -ForegroundColor Gray
    exit 1
}

# =========================================================
# Helper: Run SSH Command
# =========================================================
function Run-SSH($command, $description) {
    if ($description) { Write-Info $description }
    
    if ($script:usePoshSSH -and $script:session.GetType().Name -match "SshSession") {
        $result = Invoke-SSHCommand -SessionId $script:session.SessionId -Command $command -TimeOut 120
        if ($result.ExitStatus -ne 0 -and $result.Error) {
            Write-Warn "  Error: $($result.Error)"
        }
        return ($result.Output -join "`n")
    } else {
        $result = ssh -o ConnectTimeout=10 -o StrictHostKeyChecking=no "$SERVER_USER@$SERVER_IP" $command 2>&1
        return ($result -join "`n")
    }
}

# =========================================================
# الخطوة 4: سحب الكود من GitHub على السيرفر
# =========================================================
Write-Step 4 7 "Pulling latest code from GitHub on server..."

$gitResult = Run-SSH "cd $REMOTE_PATH && git fetch origin && git reset --hard origin/main 2>&1" "Fetching and resetting to origin/main..."
Write-Info "Git: $gitResult"

$composerResult = Run-SSH "cd $REMOTE_PATH && composer install --no-dev --optimize-autoloader --no-interaction 2>&1 | tail -5" "Installing composer dependencies..."
Write-Info "Composer: $composerResult"
Write-OK "Code updated from GitHub"

# =========================================================
# الخطوة 5: تنفيذ الماجريشن + مسح الكاش
# =========================================================
Write-Step 5 7 "Running migrations & clearing caches..."

$migrateResult = Run-SSH "cd $REMOTE_PATH && php artisan migrate --force 2>&1" "Running migrations..."
Write-Info "Migrate: $migrateResult"

$clearResult = Run-SSH "cd $REMOTE_PATH && php artisan optimize:clear 2>&1" "Clearing all caches..."
Write-Info "Clear: $clearResult"

$cacheResult = Run-SSH "cd $REMOTE_PATH && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan optimize 2>&1" "Rebuilding caches..."
Write-Info "Cache: $cacheResult"
Write-OK "Migrations & caches done"

# =========================================================
# الخطوة 6: إصلاح الصلاحيات
# =========================================================
Write-Step 6 7 "Fixing permissions..."

$permCommands = "chmod 755 /home/smStore && chmod -R 755 $REMOTE_PATH && chmod -R 775 $REMOTE_PATH/storage $REMOTE_PATH/bootstrap/cache && cd $REMOTE_PATH && php artisan storage:link 2>/dev/null; echo PERMISSIONS_DONE"

$permResult = Run-SSH $permCommands "Setting file permissions..."
Write-Info "Permissions: $permResult"
Write-OK "Permissions fixed"

# =========================================================
# الخطوة 7: اختبار الموقع
# =========================================================
Write-Step 7 7 "Testing website..."

Start-Sleep -Seconds 3

$siteUp = $false

# Test main page
Write-Info "Testing $SITE_URL ..."
try {
    $response = Invoke-WebRequest -Uri $SITE_URL -UseBasicParsing -TimeoutSec 20
    Write-OK "Website is UP! (HTTP $($response.StatusCode))"
    $siteUp = $true
} catch {
    if ($_.Exception.Response) {
        $code = [int]$_.Exception.Response.StatusCode
        Write-Warn "Website responded with HTTP $code"
        $siteUp = ($code -lt 500)
    } else {
        Write-Err "Website unreachable: $($_.Exception.Message)"
    }
}

# Test API
Write-Info "Testing $SITE_URL/api/v1/cart ..."
try {
    $apiResponse = Invoke-WebRequest -Uri "$SITE_URL/api/v1/cart" -UseBasicParsing -TimeoutSec 15 -Headers @{"Accept"="application/json"}
    Write-Warn "API cart returned $($apiResponse.StatusCode) (expected 401)"
} catch {
    if ($_.Exception.Response -and [int]$_.Exception.Response.StatusCode -eq 401) {
        Write-OK "API is protected (401 Unauthorized)"
    } else {
        Write-Warn "API: $($_.Exception.Message)"
    }
}

# Check Laravel version on server
$artisanResult = Run-SSH "cd $REMOTE_PATH && php artisan --version 2>&1" "Checking Laravel version..."
Write-Info "Server Laravel: $artisanResult"

# =========================================================
# النتيجة النهائية
# =========================================================
$elapsed = $stopwatch.Elapsed

# Cleanup SSH session
if ($usePoshSSH -and $session -and $session.GetType().Name -match "SshSession") {
    Remove-SSHSession -SessionId $session.SessionId -ErrorAction SilentlyContinue | Out-Null
}

Write-Host ""
Write-Host "==========================================================" -ForegroundColor Cyan
if ($siteUp) {
    Write-Host "  [OK] Deployment Successful!" -ForegroundColor Green
} else {
    Write-Host "  [!] Deployed but website may need time. Check manually." -ForegroundColor Yellow
}
Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host "  Website:  $SITE_URL" -ForegroundColor Yellow
Write-Host "  API:      $SITE_URL/api/v1/" -ForegroundColor Yellow
Write-Host "  Time:     $($elapsed.Minutes)m $($elapsed.Seconds)s" -ForegroundColor Gray
Write-Host "==========================================================" -ForegroundColor Cyan
