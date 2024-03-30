<!DOCTYPE html>
<html>
<head>
    <title>Upvote Cookie Manager</title>
    <link rel="stylesheet" type="text/css" href="./style.css">
</head>
<body>
    <h1>Upvote Cookie Manager</h1>
    <form action="update_cookies.php" method="post">
        <label for="newCookie">Add a new cookie:</label>
        <input type="text" name="newCookie" id="newCookie" required>
        <label for="arraySelection">Select the array to modify (0-30):</label>
        <select name="arraySelection" id="arraySelection">
            <?php
                for ($i = 0; $i <= 30; $i++) {
                    echo "<option value='$i'>$i</option>";
                }
            ?>
        </select>
        <input type="submit" value="Add Cookie">
    </form>
</body>
</html>