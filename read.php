<?php
// Verifica se foi fornecido um id
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Incluir ficheiro de configuração, para ligação a base de dados
    require_once "config.php";
    
    // Declaração SELECT
    $sql = "SELECT * FROM clientes WHERE id = ?";
    
    if($stmt = mysqli_prepare($conn, $sql)){
        // Vincular variaveis aos parametros da declaração SQL
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Definir parametros
        $param_id = trim($_GET["id"]);
        
        // Tentar correr declaração de SQL
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            // verifica se foi encontrado algum cliente com esse id
            if(mysqli_num_rows($result) == 1){
                $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
        
                $nome = $record["nome"];
                $morada = $record["morada"];
                $email = $record["email"];
                $tlf = $record["email"];
            } else{
                // Não foi encontrado nenhum registro
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Houve um problema!";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($conn);
} else{
    // URL não contem nenhum parametro
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ver Cliente</title>
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
                    <h1 class="mt-5 mb-3">Cliente ID: <?php  echo $record["id"]; ?></h1>
                    <div class="form-group">
                        <label>Nome</label>
                        <p><b><?php echo $record["nome"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Morada</label>
                        <p><b><?php echo $record["morada"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <p><b><?php echo $record["email"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Telefone</label>
                        <p><b><?php echo $record["tlf"]; ?></b></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Voltar</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>