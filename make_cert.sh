#!/bin/sh
#1、创建CA 私钥
openssl  genrsa -out ca.key 2048  -config openssl.cnf
#2、创建CA 签名请求
openssl req -new -key ca.key  -out  ca.csr  -subj  "/C=CN/ST=HN/L=ZZ/O=XXXX/OU=SERVER/CN=ca" -config openssl.cnf
#3 、自签发CA根证书
openssl x509 -req -days 3650 -sha256 -extensions v3_ca -signkey   ca.key  -in  ca.csr -out   ca.crt  -config openssl.cnf
#4、把根证书 从PEM编码转为 PKCS编码
openssl pkcs12 -export -cacerts -inkey  ca.key -in  ca.crt -out  ca.p12  -config openssl.cnf
#5、签发服务端证书
openssl genrsa -out   server.key  2048   -config openssl.cnf
#6、创建服务签发请求 
openssl req -new -key  server.key -out  server.csr -subj "/C=CN/ST=HN/L=ZZ/O=XXXX/OU=SERVER/CN=server" -config openssl.cnf
#  7、利用CA根证书 签发服务端证书
openssl x509 -req -days 3650 -sha256 -extensions v3_req -CA     ca.crt -CAkey   ca.key -CAserial ca.srl -CAcreateserial -in server.csr -out server.crt  -config openssl.cnf
#8、签发客户端私钥
openssl genrsa -out   client.key 2048 -config openssl.cnf
#9、创建客户端签发请求
openssl req -new -key  client.key -out  client.csr -subj "/C=CN/ST=HN/L=ZZ/O=XXXX/OU=CLIENT/CN=client" -config openssl.cnf
#10、签发客户端证书
openssl x509 -req -days 3650 -sha256 -extensions v3_req -CA    ca.crt -CAkey    ca.key   -CAserial ca.srl -CAcreateserial -in client.csr -out client.crt     -config openssl.cnf
#11、把客户端证书转换成p12格式
openssl pkcs12 -export -clcerts -inkey   client.key -in client.crt -out client.p12 -config openssl.cnf

