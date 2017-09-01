<?php
    session_start();

    require '../configuration.php';

    if (  isset($_SESSION['valid']) && $_SESSION['valid'] === true ){
        header('location:index.php');
    }
    else if ( isset($_POST['ur']) && $_POST['ur'] === MANAGE_PASSWORD )
    {
        $_SESSION['valid'] = true;
        header('location:index.php');
    }
    else{
?>
    <form role = "form" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
        <input ty ="text" name="ur" required autofocus />
        <input type="submit" />
    </form>
<?php
    }
?>
