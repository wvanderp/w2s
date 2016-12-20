<?php

    $options = json_decode(file_get_contents("options.json"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
    <select>
        <?php
            foreach ($option as $options){
                echo "<option value=\"".$option."\">".$option."</option>";
            }
        ?>
    </select>
</body>
</html>