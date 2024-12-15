<?php
   session_start();
   session_unset();
   if(session_destroy()) {
        header("Location: index1.html");
        echo '<script language="javascript">';
        echo 'alert("Logout successful")';
        echo '</script>';

   }
?>