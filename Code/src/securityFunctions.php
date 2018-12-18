<?php
function xss_protect($string){
    return htmlspecialchars($string, ENT_NOQUOTES, 'UTF-8');
}
?>