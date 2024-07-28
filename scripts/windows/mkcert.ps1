param (
    [Parameter(Mandatory=$true)]
    [string]$C,
    [Parameter(Mandatory=$true)]
    [string]$ST,
    [Parameter(Mandatory=$true)]
    [string]$L,
    [Parameter(Mandatory=$true)]
    [string]$O,
    [Parameter(Mandatory=$true)]
    [string]$OU,
    [Parameter(Mandatory=$true)]
    [string]$CN
)

# Check if required parameters are provided
if ([string]::IsNullOrEmpty($C)) {
    Write-Host "The country code is missing"
    exit 1
}

if ([string]::IsNullOrEmpty($ST)) {
    Write-Host "The state is missing"
    exit 1
}

if ([string]::IsNullOrEmpty($L)) {
    Write-Host "The location is missing"
    exit 1
}

if ([string]::IsNullOrEmpty($O)) {
    Write-Host "The organization name is missing"
    exit 1
}

if ([string]::IsNullOrEmpty($OU)) {
    Write-Host "The organization unit is missing"
    exit 1
}

if ([string]::IsNullOrEmpty($CN)) {
    Write-Host "The common name is missing"
    exit 1
}

# Create the ~/.certs directory if it doesn't exist
$certsDir = Join-Path -Path $env:USERPROFILE -ChildPath ".certs"
if (!(Test-Path -Path $certsDir)) {
    New-Item -Path $certsDir -ItemType Directory | Out-Null
}

Set-Location -Path $certsDir

# Generate the root CA key and certificate if they don't exist
if (!(Test-Path -Path "rootCA.key")) {
    openssl.exe genrsa -des3 -out rootCA.key 2048
    openssl.exe req -x509 -new -nodes -key rootCA.key -sha256 -days 825 -out rootCA.crt -subj "/C=FR/ST=NO/L=Ville/O=Contoso/OU=IT/CN=e-commerce/emailAddress=john.doe@example.com"
}

# Create the certificate directory for the specified common name if it doesn't exist
$certDir = Join-Path -Path $certsDir -ChildPath $CN
if (!(Test-Path -Path $certDir)) {
    New-Item -Path $certDir -ItemType Directory | Out-Null
}

Set-Location -Path $certDir

# Generate the certificate signing request (CSR) and the certificate
openssl.exe req -newkey rsa:2048 -nodes -keyout "${CN}.key" -out "${CN}.csr" -subj "/C=$C/ST=$ST/L=$L/O=$O/OU=$OU/CN=$CN/emailAddress=jane.doe@example.com"

$v3ExtContent = @"
authorityKeyIdentifier=keyid,issuer
basicConstraints=CA:FALSE
keyUsage = digitalSignature, nonRepudiation, keyEncipherment, dataEncipherment
extendedKeyUsage = serverAuth
subjectAltName = @alt_names

[alt_names]
DNS.1 = $CN
"@

Set-Content -Path "v3.ext" -Value $v3ExtContent

openssl.exe x509 -req -in "${CN}.csr" -CA "$certsDir\rootCA.crt" -CAkey "$certsDir\rootCA.key" -CAcreateserial -out "${CN}.pem" -days 825 -sha256 -extfile v3.ext
openssl.exe x509 -signkey "${CN}.key" -in "${CN}.csr" -req -days 365 -out "${CN}.crt"
openssl.exe pkcs12 -export -clcerts -in "${CN}.crt" -inkey "${CN}.key" -out "${CN}.p12"

Write-Host "Use ${CN}.crt as SSL certificate"
Write-Host "Use ${CN}.key as certificate key"
Write-Host "Use ${CN}.p12 as client side certificate"
