#!/bin/sh
#判断是不是远端仓库
IS_BARE=$(git rev-parse --is-bare-repository)
if [ -z "$IS_BARE" ]; then
echo >&2 "fatal: post-receive: IS_NOT_BARE"
exit 1
fi
 
unset GIT_DIR
DeployPath="/home/git/webrepo/webrepo.git"
WwwPath="/home/wwwroot/web/"
 
echo "==============================================="
cd $WwwPath
echo "deploying the test web"
 
#git pull origin master  
git fetch --all  
git reset --hard origin/master  

 
time=`date`
echo "web server pull at webserver at time: $time."

echo "================================================"
