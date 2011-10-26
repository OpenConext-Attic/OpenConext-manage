SCRIPTFILE=`readlink -f $0`
BASEDIR=`dirname ${SCRIPTFILE}`

#Read environment
if [ -r ${BASEDIR}/_get_environment.sh ] ; then
    . ${BASEDIR}/_get_environment.sh
fi

cd ${SURFCONEXT_BASE}/scripts/ && ${PHPBIN}/php versexport.php > /dev/null
