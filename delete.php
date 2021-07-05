<?php
// Process delete operation after confirmation
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Incluir ficheiro de configuração, para ligação a base de dados
    require_once "config.php";
    
    // Declaração para apagar cliente pelo ID
    $sql = "DELETE FROM clientes WHERE id = ?";
    
    if($stmt = mysqli_prepare($conn, $sql)){
        // Vincular variaveis aos parametros da declaração SQL
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Definir parametros
        $param_id = trim($_POST["id"]);
        
        // Tentar correr declaração de SQL
        if(mysqli_stmt_execute($stmt)){
            // Se o cliente for apagado com sucesso, reedireciona para a pagina inicial
            header("location: index.php");
            exit();
        } else{
            echo "Houve um problema!";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($conn);
} else{
    //Verifica a existencia do parametro ID
    if(empty(trim($_GET["id"]))){
        // URL não contem nenhum parametro
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apagar cliente</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Apagar cliente</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>Tem a certeza que dejesa apagar o cliente com o ID: <?php echo trim($_GET["id"]); ?>?</p>
                            <p>
                                <input type="submit" value="Sim" class="btn btn-danger">
                                <a href="index.php" class="btn btn-secondary">Não</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>