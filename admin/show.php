<?php
include_once 'up.php';
include_once 'connection.php';

session_start();
if (!isset($_SESSION['id']))
    header("location: login.php");
if(isset($_GET['code'])){
    $code=$_GET['code'];
}else{
    echo "<h1 align='center'>wrong page !!!!</h1>";
    exit();
}

$result = $connection->query("SELECT students.name as name, `code`, `dept_num`, `email`, `age`, `password`, `phone`, `image`, `is_admin`, departments.name as department FROM `students` INNER JOIN `departments`ON dept_num=number where code = $code");
// echo "<pre>";
$value = $result->fetch(PDO::FETCH_ASSOC);
// var_dump($value);
// die();
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
                        <th scope="col">email</th>
                        <th scope="col">department</th>
                        <th scope="col">age</th>
                        <th scope="col">phone</th>
                    </tr>
                </thead>
                <tbody>
                    
                        <tr>

                            <td>
                                <?= $value['name'] ?>
                            </td>
                            <td>
                                <?= $value['code'] ?>
                            </td>
                            <td>
                                <?= $value['email'] ?>
                            </td>
                            <td>
                                <?= $value['department'] ?>
                            </td>
                            <td>
                                <?= $value['age'] ?>
                            </td>
                            <td>
                                <?= $value['phone'] ?>
                            </td>
                        </tr>
                   
                </tbody>
            </table>

        </div>
    </div>
</div>
<?php
$image_name = $value['image'];
$path = "uploads/images/$image_name";

?>

<img src=<?=$path?> width="500" height="600">


<?php
include_once 'dowm.php';
?>