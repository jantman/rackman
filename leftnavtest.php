<?php
// index.php
//
// main index page
// Time-stamp: "2009-02-18 19:28:34 jantman"
//
// Rack Management Project
// by Jason Antman <jason@jasonantman.com>
// $Id$
// $Source$
require_once('config/config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Left Navigation</title>
<link rel="stylesheet" type="text/css" href="main.css" />
<script language="javascript" src="TreeMenu.js"></script>
<style type="text/css">
<!--
.treeMenuBold {
    font-weight: bold;
}
// -->
</style>
</head>

<body>

<?php
require_once('HTML/TreeMenu.php');

$icon = 'folder.gif';

$menu  = new HTML_TreeMenu();

$node1 = new HTML_TreeNode(array('text' => "First level",
                                 'link' => "test.php",
                                 'icon' => $icon));

$foo   = &$node1->addItem(new HTML_TreeNode(array('text' => "Second level",
                                                  'link' => "test.php",
                                                  'icon' => $icon)));

$bar   = &$foo->addItem(new HTML_TreeNode(array('text' => "Third level",
                                                'link' => "test.php",
                                                'icon' => $icon)));

$blaat = &$bar->addItem(new HTML_TreeNode(array('text' => "Fourth level",
                                                'link' => "test.php",
                                                'icon' => $icon)));

$blaat->addItem(new HTML_TreeNode(array('text' => "Fifth level",
                                        'link' => "test.php",
                                        'icon' => $icon,
                                        'cssClass' => 'treeMenuBold')));

$node1->addItem(new HTML_TreeNode(array('text' => "Second level, item 2",
                                        'link' => "test.php",
                                        'icon' => $icon)));

$node1->addItem(new HTML_TreeNode(array('text' => "Second level, item 3",
                                        'link' => "test.php",
                                        'icon' => $icon)));

$menu->addItem($node1);
$menu->addItem($node1);

// Create the presentation class
$treeMenu = &new HTML_TreeMenu_DHTML($menu, array('images' => 'images/treemenu/',
                                                  'defaultClass' => 'treeMenuDefault'));
$listBox  = &new HTML_TreeMenu_Listbox($menu);
?>

<table border="0" width="100%">
    <tr>
        <td width="50%" valign="top">
<?$treeMenu->printMenu()?>
<?$listBox->printMenu()?>
        </td>

        <td width="50%" valign="top">
            <?php
$treeMenu->images = 'images/treemenuAlt';
$treeMenu->printMenu()
            ?>
        </td>
    </tr>
</table>

<hr />

<?php

require_once 'HTML/TreeMenu.php';

$menu_styles      = new HTML_TreeNode(array('text'=>'Styles'));
$menu_pays        = new HTML_TreeNode(array('text'=>'Countries'));
$menu_restaurants = new HTML_TreeNode(array('text'=>'Restaurants'));
$menu_plats       = new HTML_TreeNode(array('text'=>'Menus'));

for ($i = 1; $i < 10; $i++)
{
    $menu_styles->addItem(new HTML_TreeNode(array('icon'=>'Image '.($i+0))));
    $menu_pays->addItem(new HTML_TreeNode(array('icon'=>'Image '.($i+10))));
    $menu_restaurants->addItem(new HTML_TreeNode(array('icon'=>'Image '.($i+20))));
    $menu_plats->addItem(new HTML_TreeNode(array('icon'=>'Image '.($i+30))));
}

$menu  = new HTML_TreeMenu();
$menu->addItem($menu_styles);
$menu->addItem($menu_pays);
$menu->addItem($menu_restaurants);
$menu->addItem($menu_plats);

// Chose a generator. You can generate DHTML or a Listbox
$tree = new HTML_TreeMenu_DHTML($menu);

echo $tree->toHTML();

?> 

</body>

</html>
