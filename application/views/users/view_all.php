<br><br>
<div class="container">
<h2 style="margin-bottom: 1rem; color:black !important"> <?= $title ?> </h2>
<br>
<table class="table table-hover">
    <thead>
        <tr class="table-primary" >
            <th scope="col">Surname</th>
            <th scope="col">Forename</th>
            <th scope="col">Email</th>
            <th scope="col">Time Created</th>
            <th scope="col">Last Updated</th>
            <th scope="col">Last Login</th>
            <th scope="col">Privilege Level</th>
        </tr>
    </thead>
    <tbody>
        <?php  // Cycle through all rows in user table and echo ids
            $i = 0;
            foreach($users as $user) :
                $i++;
                if($i % 2 === 0){
                    $table_colour = 'table-light';
                } else {
                    $table_colour= 'table-info';
                }
        ?>

        <tr class="<?php echo $table_colour ?>"  id="<?php echo $user['ID']; ?>">

            <td scope="row"><?php echo $user['Surname']; ?></td>

            <td scope="row"><?php echo $user['Forename']; ?></td>

            <td scope="row"><?php echo $user['Email']; ?></td>

            <td scope="row"><?php echo gmdate("d/m/Y H:i", strtotime($user['Account_Created'])); ?></td>

            <td scope="row"><?php echo gmdate("d/m/Y H:i", strtotime($user['Last_Updated'])); ?></td>

            <td scope="row"><?php if (isset($user['Last_Logged_In'])) echo gmdate("d/m/Y H:i", strtotime($user['Last_Logged_In'])); ?></td>

            <td scope="row">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" style="width:100%"> <?php echo $user['User_Type']; ?> </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="users/edit/<?php echo $user['ID']; ?>/Unverified">Unverified</a>
                        <a class="dropdown-item" href="users/edit/<?php echo $user['ID']; ?>/Verified">Verified</a>
                        <a class="dropdown-item" href="users/edit/<?php echo $user['ID']; ?>/Admin">Admin</a>
                    </div>
                </div>
            </td>

        </tr>
        <?php   endforeach; ?>

        <tr class="table-primary" >
            <td><br><br></td> <td><br></td> <td><br></td> <td><br></td> <td><br></td> <td><br></td> <td><br></td>
        </tr>
    </tbody>
</table>
</div>
<br><br>
