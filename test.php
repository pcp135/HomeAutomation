<?php

echo time().' <BR>';
echo time()+1.' <BR>';
echo date_sunset(time(), SUNFUNCS_RET_TIMESTAMP, 48, 11, 90, 1) . '<BR>';

?>
