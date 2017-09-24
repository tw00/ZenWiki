#!/bin/bash

if [ $# -ne 1 ]; then
    echo "Usage: $0 <wiki>"
    exit
fi

backupsrc=$1
backupdir=backup
backupdate=`date +"%d"-"%m"-"%Y"_"%H"-"%M"`
backupfile=wiki_backup_$backupdate.tar.gz
currentpwd=`pwd`

mkdir -p $backupdir
rm -rf $backupdir/$backupsrc
cp -R $backupsrc $backupdir/$backupsrc
find $backupdir/$backupsrc|grep ".svn"|xargs rm -rf
cd $backupdir && tar cfz $backupfile $backupsrc
cd $currentpwd
rm -rf $backupdir/$backupsrc

backupsize=`du -hsc $backupdir/$backupfile | head -n 1`

echo -e "\033[1;32mBACKUP DONE\033[0m"
echo -e "filesize / name:\t$backupsize";
