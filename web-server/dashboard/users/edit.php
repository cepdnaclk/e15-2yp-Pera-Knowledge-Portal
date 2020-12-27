<?php include '../data/session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../data/meta.php'; ?>
    <?php include '../data/scripts.php'; ?>

    <style>
        label {
            margin: 5px !important;
        }
    </style>
</head>
<body>

<a name="top"></a>
<?php include '../data/navibar.php'; ?>

<?php

define("FOLDER_NAME", "users");
include_once "../data/accessControl.php";

include_once "../data/database.php";
$db = new database();

?>

<div class="w3-row">
    <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
    <div class="w3-col s12 m8 l8">
        <br><br><br><br>

        <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
            <li><a href="../home">Home</a></li>
            <li><a href="../users">Users</a></li>
            <li class="active">Edit Users</a></li>
        </ul>

        <br>

        <div>
            <?php

            if (!isset($_GET['id'])) {
                echo "<h4>Invalid Access !!!</h4>";
                exit;

            } else if ($db->existsUserId($_GET['id']) == false) {
                echo "<h4>Invalid user Id !!!</h4>";
                exit;
            }

            $id = $_GET['id'];

            $firstName = $db->getUserData($id, "firstName");
            $lastName = $db->getUserData($id, "lastName");
            $salutation = $db->getUserData($id, "honorific");
            $email = $db->getUserData($id, "email");
            $role = $db->getUserData($id, "role");

            $eNum = "";
            $dept = "";

            ?>
            <form name="newStudent" role="form" class="w3-container w3-card-4 w3-light-grey w3-padding-16 w3-margin-8"
                  method="post" action="actions.php?act=update">

                <h2>Edit User</h2>
                <br>

                <p><input name="userId" type="hidden" value="<?php echo $id; ?>"></p>

                <p>
                    <label>Salutation</label>
                    <select class="w3-select w3-border w3-round" name="salutation" required>
                        <option value="" disabled>Select the Salutation</option>
                        <?php
                        $list = json_decode(file_get_contents("../lists/salutations.json"), true);

                        for ($i = 0; $i < sizeof($list); $i++) {
                            $sel = ($i == $salutation) ? "selected" : "";
                            echo "<option value='$i' $sel >$list[$i]</option>";
                        }
                        ?>
                    </select>

                <p>
                    <label>First Name (with initials)</label>
                    <input class="w3-input w3-border w3-round" name="firstName" type="text" required
                           value="<?php echo $firstName; ?>"/></p>

                <p>
                    <label>Last Name</label>
                    <input class="w3-input w3-border w3-round" name="lastName" type="text" required
                           value="<?php echo $lastName; ?>"/></p>

                <p>
                    <label>Email</label>
                    <input class="w3-input w3-border w3-round" name="email" type="email" required
                           value="<?php echo $email; ?>"/></p>

                <p>
                    <label>Role</label>
                    <select class="w3-select w3-border w3-round" id="role" name="role" required>
                        <option value="" disabled selected>Select the Role</option>
                        <?php
                        $list = json_decode(file_get_contents("../lists/roles.json"), true);

                        for ($i = 0; $i < sizeof($list); $i++) {
                            $sel = ($i == $role) ? "selected" : "";
                            echo "<option value='$i' $sel >$list[$i]</option>";
                        }
                        ?>
                    </select>

                <p>
                    <button type="submit" class="w3-btn w3-theme w3-round">Update User</button>
                </p>

            </form>
        </div>

        <br><br><br><br>

    </div>

</div>


</body>
</html>
