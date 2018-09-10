#!/bin/bash
#目的ip和端口
dst_ip="192.168.1.2"
dst_port="2221"
#FTP服务的ip和端口
FTP_IP_PORT="192.168.1.3:2221"
$ftpname=ftp; 
$ftppasswd=ftppasswd; 
method=$1
 
ftp_upload()
{
        file_md5=`md5sum $filenames | awk -F" " '{printf $1}'`
        curl -s -T $1 -u  $ftpname:$ftppasswd ftp://$dst_ip:21/
        sleep 1
        curl -H "FileName=$filenames;Md5Sum=$file_md5;Dst-Ip-Port=$FTP_IP_PORT;protol=ftp;Type=$method" "$dst_ip:$dst_port"
}
 
file_list=`ls . | grep -v $0`
for filenames in $file_list
do
        ftp_upload $filenames &
done
        
create_file()
{
    seq $NUM | xargs -i dd if=/dev/zero of={}.dat bs=1M count=1
}

