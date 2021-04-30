#!/bin/bash

######################################
# 这是 一键打包vue 项目到服务器的脚本#
######################################

# 服务器密码
passwd=""
# 服务器ip
serverIp=""
# 服务器目标目录
dstDir=""

npm run prod

if [ $? != 0 ];then
    echo "打包失败"
    exit 1
fi
zip -r dist.zip dist

/usr/bin/expect <<-EOF
set timeout 30
spawn  scp dist.zip  root@${serverIp}:${dstDir}
expect  "*password:"
    send "${passwd}\n"
expect eof

set timeout 300
spawn  ssh  root@${serverIp}  "cd ${dstDir}; unzip -o dist.zip"
expect  "*password:"
   send "${passwd}\n"
expect eof

EOF
