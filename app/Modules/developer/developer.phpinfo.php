<?php if(!defined('APP')) die('...');
//kaynak: https://contao.org/en/extension-list/view/phpinfo.html
?>


<style type="text/css">
#phpinfo .center {text-align: unset };
#phpinfo { margin: 10px; }
#phpinfo table { border-collapse: collapse; width: 100%; table-layout: fixed }
#phpinfo th { background-color: #0062A2; color: #fff; text-align: left; padding: 2px; }
#phpinfo td { padding: 2px }
#phpinfo .e { font-weight: bold; vertical-align: top; background-color: #f0f0f0 }
#phpinfo .scroll { overflow: auto }
#phpinfo img { float: right }

#phpinfo h1 { font-size: 150% }
#phpinfo h2 { font-size: 130% }
</style>

<div id="phpinfo">
<?php

ob_start();
phpinfo();
$pinfo = ob_get_contents();
ob_end_clean();

// get content of phpinfo only
$pinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $pinfo);

// adapt table layout
$pinfo = str_replace('<table border="0" cellpadding="3" width="600">', '<table border="1" cellpadding="3">', $pinfo);

// remove blank at end of table data
$pinfo = str_replace(' </td>', '</td>', $pinfo);

// remove a-tags from h2
$pinfo = preg_replace('%<h2><a .*>(.*)</a></h2>%', '<h2>$1</h2>', $pinfo);

// add div container to cell content because of overflow=auto
$pinfo = preg_replace('%<td class="v">(.*?)</td>%', '<td class="v"><div class="scroll">$1</div></td>', $pinfo);
$pinfo = preg_replace('%<td class="e">(.*?)</td>%', '<td class="v"><div class="scroll">$1</div></td>', $pinfo);

// output
echo $pinfo;

?>
