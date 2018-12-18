#!/bin/bash
#监控多个网站   可把要监控的网站放在url.conf 中
WORK_PATH=$(dirname $0)
for i in `cat  $WORK_PATH/url.conf`
do
	md5_num=`echo $i |md5sum|cut -d ' ' -f1`;
	#echo ${md5_num}
	first_time=0
	lastname="$WORK_PATH/last_$md5_num.html"
	if [ ! -e "$lastname" ];
    then
        first_time=1
	fi
    curl --silent "$i" -o  "recent_$md5_num.html"
	if [ $first_time -ne 1 ];
	then
		lastfile="$WORK_PATH/last_$md5_num.html"
		recentfile="$WORK_PATH/recent_$md5_num.html"
		changes=`diff -u $lastfile  $recentfile`
		if [ -n "$changes" ];
		then
			echo -e "changes:\n"
			echo "$changes"
			# 监控到更改可以发送邮件通知 等
			echo $i  |mail -s "自动检测到站点更新"
		else
			echo -e "$i site has no update"
		fi
	else
		echo "[First run] Archiving.."
	fi
	cp  "$WORK_PATH/recent_$md5_num.html" "$WORK_PATH/last_$md5_num.html"
done
