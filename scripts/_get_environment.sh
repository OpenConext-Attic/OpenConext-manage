#Get system environment settings.
if [ -r /etc/profile.d/surfconext.sh ] ; then
    . /etc/profile.d/surfconext.sh
fi

HOST=`hostname`
case "${HOST}" in
  't03.dev.coin.surf.net'|'vip2-app1'|'vip2-app2' ) \
     PHPBIN='/usr/bin'
     SURFCONEXT_BASE='/opt/www/manage'
     ;;
  'ebdev.net' ) \
     PHPBIN='/usr/bin'
     SURFCONEXT_BASE='/mnt/hgfs/ebdev/surfconext-admin'
     ;;
  * ) \
     echo "${0}: Unknown host, unable to determine SURFCONEXT_BASE and PHPBIN, please edit scripts"
     exit 1
     ;;
esac

#Override system-wide defaults.
if [ -r ${BASEDIR}/../.surfconext ] ; then
    . ${BASEDIR}/../.surfconext
fi
