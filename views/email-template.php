<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php echo $args['subject']; ?></title>
</head>
<body>

	<h1 style="font-family: Arial, sans-serif; font-size:20px;color:#000000;">New form submission details:</h1>

	<?php foreach( $args['fields'] as $field ): ?>
		<p style="font-family: Arial, sans-serif; font-size:14px;color:#000000;"><?php echo $field['label']; ?>: <?php echo $field['value']; ?></p>
	<?php endforeach; ?>

</body>
</html>
