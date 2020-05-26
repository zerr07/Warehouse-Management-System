<?php
function prefixQuery($query){
    return str_replace('*}','',str_replace('{*',_DB['dbprefix'],$query));
}