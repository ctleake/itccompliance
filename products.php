<!DOCTYPE html>
<html lang="en" ng-app="help">
<head>
    <meta charset="utf-8">
    <title>Available Products</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="generator" content="Sphido CMS"/>
</head>

<body class="<?php echo  $page->class ?>" onload="prettyPrint()">

<div class="container">
<pre class="prettyprint"><code>
<?php echo '
<table style="width:100%; word-break:keep-all;">
    <tr>
         <th>Name</th>
         <th>Description</th>
         <th>Type</th>
         <th>Suppliers</th>
     </tr>
' ?>
<?php foreach($product_details as $detail): ?>
    <tr>
<?php if (!is_array($detail) || !isset($detail['error'])): ?>
    <?php foreach($detail as $sub_detail): ?>
        <td></nobr><?php echo $sub_detail->name; ?></td>
        <td><?php echo $sub_detail->description; ?></td>
        <td><?php echo $sub_detail->type; ?></td>
        <td>
            <table style="white-space:initial;">
                <?php foreach($sub_detail->suppliers as $supplier): ?>
                    <td><?php echo $supplier; ?></td>
                <?php endforeach; ?>
            </table>
        </td>
    <?php endforeach; ?>
<?php else: ?>
        <td colspan="100%"><?php echo $detail['error']; ?></td>
<?php endif; ?>
    </tr>
<?php endforeach; ?>
</table>
</code></pre>
</div>

<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.js"></script>

</body>
</html>