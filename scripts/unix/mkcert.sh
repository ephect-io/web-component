#!/bin/bash
C=$1;
ST=$2;
L=$3;
O=$4;
OU=$5;
CN=$6;

if [ -z "${C}" ];
then
	echo "The country code is missing";
	exit 1;
fi

if [ -z "${ST}" ];
then
	echo "The state is missing";
	exit 1;
fi

if [ -z "${L}" ];
then
	echo "The location is missing";
	exit 1;
fi

if [ -z "${O}" ];
then
	echo "The organization name is missing";
	exit 1;
fi

if [ -z "${OU}" ];
then
	echo "The organization unit is missing";
	exit 1;
fi

if [ -z "${CN}" ];
then
	echo "The common name is missing";
	exit 1;
fi

if [ ! -d ~/.certs ];
then
	mkdir -p ~/.certs;
fi

cd ~/.certs || exit;

if [ ! -f rootCA.key ];
then
	openssl genrsa -des3 -out rootCA.key 2048

	openssl req -x509 \
		-new -nodes \
 		-key rootCA.key \
		-sha256 \
		-days 825 \
		-out rootCA.crt \
		-subj "/C=FR/ST=NO/L=Ville/O=Contoso/OU=IT/CN=e-commerce/emailAddress=john.doe@example.com"
fi


if [ ! -d ~/.certs/${CN} ];
then
	mkdir -p ~/.certs/${CN};
fi

cd ~/.certs/${CN} || exit;

openssl req \
 	-newkey rsa:2048 \
	-nodes \
	-keyout ${CN}.key \
	-out ${CN}.csr \
	-subj "/C=${C}/ST=${ST}/L=${L}/O=${O}/OU=${OU}/CN=${CN}/emailAddress=jane.doe@example.com"

cat << V3_EXT > v3.ext
authorityKeyIdentifier=keyid,issuer
basicConstraints=CA:FALSE
keyUsage = digitalSignature, nonRepudiation, keyEncipherment, dataEncipherment
extendedKeyUsage = serverAuth
subjectAltName = @alt_names

[alt_names]
DNS.1 = $CN
V3_EXT

cat v3.ext

openssl x509 \
	-req -in ${CN}.csr \
	-CA ~/.certs/rootCA.crt \
	-CAkey ~/.certs/rootCA.key \
	-CAcreateserial \
	-out ${CN}.pem \
	-days 825 -sha256 \
	-extfile v3.ext

openssl x509 \
	-signkey ${CN}.key \
	-in ${CN}.csr \
	-req \
	-days 365 \
	-out ${CN}.crt

openssl pkcs12 -export -clcerts -in ${CN}.crt -inkey ${CN}.key -out ${CN}.p12

echo Use ${CN}.crt as SSL certificate
echo Use ${CN}.key as certificate key
echo Use ${CN}.p12 as client side certificate

