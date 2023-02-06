<?php

include_once 'connection.php';

/**
 * name => required | only letters | capitalize | no html charachters 
 * age => int  
 * phone => numbers | exactrly 11 | required | unique
 * code => int | required | unique
 * dept_num => int | required
 * email => required | unique | email
 * password => required | more than 8 
 * 
 */
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
$value = $result->fetch(PDO::FETCH_ASSOC);

$result2 = $connection->query("SELECT * FROM `departments`");
$departments=$result2->fetchAll(PDO::FETCH_ASSOC);
$image_name = $value['image'];
$path2 = "uploads/images/$image_name";
$errors = [];

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $code = $_POST['code'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $department = $_POST['department'];
    $phone = $_POST['phone'];
    $image_name = $_FILES['image']['name'];
    $image_name = $code . $image_name;
    $tmp_name = $_FILES['image']['tmp_name'];
    $path = "uploads/images/$image_name";
    $allowed_ext = ['png', 'jpg', 'jpeg'];
    $image_explode = explode('.', $image_name);
    $image_ext = end($image_explode);

    if(!in_array($image_ext,$allowed_ext))
    {
        $errors['image']="not allowed ext...";
    }


    //name => required | only letters | capitalize | no html charachters 
    if (empty($name)) {
        $errors['required_name'] = 'please enter your name';
    }
    validate_string($name, 'name', 'your name must contain only letters and space between words');
    //age => int
    if (!filter_var($age, FILTER_VALIDATE_INT)) {
        $errors['age'] = 'please only enter numbers';
    }
    //phone => numbers | exactrly 11 | required | unique
    check_int($phone, 'phone', 'enter correct phone number');
    if (strlen($phone) != 11)
        $errors['phone_length'] = 'phone number must be 11';
    if (empty($phone)) {
        $errors['required_phone'] = 'please enter your phone number';
    }
    check_unique($phone, 'students', 'phone', 'phone_unique', 'this phone number is already exist');
    //code => int | required | unique
    check_int($code, 'code', 'code must be only numbers');
    if (empty($code)) {
        $errors['required_code'] = 'please enter your phone number';
    }
    check_unique($code, 'students', 'code', 'code_unique', 'this code is already exist');
    // email => required | unique | email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'please enter correct email';
    }
    if (empty($email)) {
        $errors['required_email'] = 'please enter your email';
    }
    check_unique($email, 'students', 'email', 'email_unique', 'this email is already exist');
    // password => required | more than 8 
    if (empty($password)) {
        $errors['required_password'] = 'please enter your password';
    }
    if (strlen($password) <= 8)
        $errors['password_length'] = 'password must be greater than 8';
    // dept_num => int | required
    //check_int($_POST['department'],'department','departmen')
    if (empty($_POST['department']))
        $errors['department'] = 'you should choose department';
        if(empty($errors)){
            move_uploaded_file($tmp_name,$path);
            $result=$connection->query("INSERT INTO `students`(`name`, `age`, `phone`, `code`, `dept_num`,`email`,`password`,`image`) VALUES ('$name',$code,'$phone','$code','$department','$email','$password','$image_name')");
        }
}

function validate_string($name, $key, $error)
{
    $len1 = strlen($name);
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $len2 = strlen($name);
    if ($len1 != $len2) {
        $errors[$key] = $error;
    }
}

function check_int($num, $key, $error)
{
    for($i=0;$i<strlen($num) ; $i++ ){
        if (!(filter_var($num[$i], FILTER_VALIDATE_INT)>=0&&filter_var($num[$i], FILTER_VALIDATE_INT)<=9)) {
            global $errors;
            $errors[$key] = $error;
            return;
        }
    }
    
}

function check_unique($input, $table, $column, $key, $error)
{
    global $errors;
    global $connection;
    $input_result = $connection->query("select $column from $table where $column='$input'");
    $input_count = $input_result->rowCount();
    if ($input_count > 0) {
        $errors[$key] = $error;

    }
}



include_once 'up.php';
?>
<div class="col-lg-6">
    <div class="card">
        <div class="card-header">
            <strong>Basic Form</strong> Elements
        </div>
        <div class="card-body card-block">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data"
                class="form-horizontal">
                <div class="row form-group">
                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">name</label></div>
                    <div class="col-12 col-md-9"><input type="text" id="text-input" name="name" placeholder="Text"
                            class="form-control" value="<?= $value['name'] ?>">
                        <?php
                        if (!empty($errors['required_name'])) {
                            ?>
                            <small class="form-text text-muted" style="color:red !important">
                                <?= $errors['required_name'] ?>
                            </small>
                            <?php
                        } else if (!empty($errors['name'])) {
                            ?>
                                <small class="form-text text-muted" style="color:red !important">
                                <?= $errors['name'] ?>
                                </small>
                        <?php } ?>
                    </div>
                </div>

        </div>
        <div class="row form-group">
            <div class="col col-md-3"><label for="text-input" class=" form-control-label">code</label></div>
            <div class="col-12 col-md-9"><input type="number" id="text-input" name="code" placeholder="Text"
                    class="form-control"value="<?= $value['code'] ?>">
                <?php
                if (!empty($errors['required_code'])) {
                    ?>
                    <small class="form-text text-muted" style="color:red !important">
                        <?= $errors['required_code'] ?>
                    </small>
                    <?php
                } elseif (!empty($errors['code_unique'])) {
                    ?>
                    <small class="form-text text-muted" style="color:red !important">
                        <?= $errors['code_unique'] ?>
                    </small>
                    <?php
                } elseif (!empty($errors['code'])) {
                    ?>
                    <small class="form-text text-muted" style="color:red !important">
                        <?= $errors['code'] ?>
                    </small>
                    <?php
                } ?>
            </div>

        </div>
        <div class="row form-group">
            <div class="col col-md-3"><label for="text-input" class=" form-control-label">password</label></div>
            <div class="col-12 col-md-9"><input type="password" id="text-input" name="password" placeholder="Text"
                    class="form-control">
                <?php

                if (!empty($errors['required_password'])) {
                    ?>
                    <small class="form-text text-muted" style="color:red !important">
                        <?= $errors['required_password'] ?>
                    </small>
                    <?php
                } elseif (!empty($errors['password_length'])) {
                    ?>
                    <small class="form-text text-muted" style="color:red !important">
                        <?= $errors['password_length'] ?>
                    </small>

                <?php } ?>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3"><label for="text-input" class=" form-control-label">email</label></div>
            <div class="col-12 col-md-9"><input type="text" id="text-input" name="email" placeholder="Text"
                    class="form-control" value="<?= $value['email'] ?>">
                <?php if (!empty($errors['required_email'])) {
                    ?>
                    <small class="form-text text-muted" style="color:red !important">
                        <?= $errors['required_email'] ?>
                    </small>
                    <?php
                } elseif (!empty($errors['email'])) {
                    ?>
                    <small class="form-text text-muted" style="color:red !important">
                        <?= $errors['email'] ?>
                    </small>
                    <?php
                } elseif (!empty($errors['email_unique'])) {
                    ?>
                    <small class="form-text text-muted" style="color:red !important">
                        <?= $errors['email_unique'] ?>
                    </small>
                    <?php
                } ?>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3"><label for="text-input" class=" form-control-label">phone</label></div>
            <div class="col-12 col-md-9"><input type="text" id="text-input" name="phone" placeholder="Text"
                    class="form-control" value="<?= $value['phone'] ?>">
                <?php
                if (!empty($errors['required_phone'])) {
                    ?>
                    <small class="form-text text-muted" style="color:red !important">
                        <?= $errors['required_phone'] ?>
                    </small>
                    <?php
                } elseif (!empty($errors['phone_unique'])) {
                    ?>
                    <small class="form-text text-muted" style="color:red !important">
                        <?= $errors['phone_unique'] ?>
                    </small>
                    <?php
                } elseif (!empty($errors['phone'])) {
                    ?>
                    <small class="form-text text-muted" style="color:red !important">
                        <?= $errors['phone'] ?>
                    </small>
                    <?php
                } ?>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3"><label for="text-input" class=" form-control-label">age</label></div>
            <div class="col-12 col-md-9"><input type="number" id="text-input" name="age" placeholder="Text"
                    class="form-control" value="<?= $value['age'] ?>">
                <?php
                if (!empty($errors['age'])) {
                    ?>
                    <small class="form-text text-muted" style="color:red !important">
                        <?= $errors['age'] ?>
                    </small>
                    <?php
                } ?>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3"><label for="select" class=" form-control-label">department</label></div>
            <div class="col-12 col-md-9">
                <select name="department" id="select" class="form-control">
                    <?php
                    foreach ($departments as $dept) {
                        ?>
                    <option value=<?=$dept['number']?>
                    <?php
                    if ($dept['number'] == $value['dept_num'])
                        echo 'selected';
                    ?>
                    ><?= $dept['name']?></option>

                    <?php } ?>
                </select>
                <?php
                
                if (!empty($errors['department'])) {
                    ?>
                    <small class="form-text text-muted" style="color:red !important">
                        <?= $errors['department'] ?>
                    </small>
                    <?php
                } ?>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3"><label for="file-input" class=" form-control-label">image</label></div>
            <div class="col-12 col-md-9"><input type="file" id="file-input" name="image" class="form-control-file" value="<?=$image_name?>">

            </div>
            <?php
            if (!empty($errors['image'])) {
                    ?>
                    <small class="form-text text-muted" style="color:red !important">
                        <?= $errors['image'] ?>
                    </small>
                    <?php
                } ?>
        </div>
        <div class="card-footer">
            <button name="submit" type="submit" class="btn btn-primary btn-sm">
                <i class="fa fa-dot-circle-o"></i> Submit
            </button>
            <button type="reset" class="btn btn-danger btn-sm">
                <i class="fa fa-ban"></i> Reset
            </button>
        </div>
        </form>
    </div>

</div>
</div>
<?php
include_once 'dowm.php';
?>