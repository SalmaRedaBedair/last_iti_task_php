<?php
session_start();
if (!isset($_SESSION['id']))
    header("location: login.php");
include_once 'up.php';
include_once 'connection.php';


$result = $connection->query("SELECT `name`, `code` FROM `students`");
//echo "<pre>";
$students = $result->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <strong class="card-title">Students</strong>
        </div>
        <div class="card-body">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">name</th>
                        <th scope="col">code</th>
                        <th scope="col">options</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($students as $value) {

                        ?>
                        <tr>

                            <td>
                                <?= $value['name'] ?>
                            </td>
                            <td>
                                <?= $value['code'] ?>
                            </td>
                            <td>
                                <a href="show.php?code=<?php echo $value['code'] ?>" style="color: blue;">show</a>
                                <a href="delete.php?code=<?php echo $value['code'] ?>" style="color: red;">delete</a>
                                <a href="update.php?code=<?php echo $value['code'] ?>" style="color: green;">update</a>
                            </td>
                            
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div>


<?php
include_once 'dowm.php';
?>