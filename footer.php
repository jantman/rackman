<?php
// footer.php
// $Id$
// $Source$

echo '<!-- start footer - footer.php -->'."\n";
echo '<hr />'."\n";
if(strpos($_SERVER['REQUEST_URI'], "rack-mgmt/admin/"))
{
    echo '<a href="../index.php">Main Page</a>'."\n";
}
else
{
    echo '<a href="index.php">Main Page</a>'."\n";
}


?>