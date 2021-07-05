<?php
// Incluir ficheiro de configuração, para ligação a base de dados
require_once "config.php";
 
// Inicializa variaveis vazias
$nome = $morada = $email = $tlf = "";
$nome_erro = $morada_erro = $email_erro = $tlf_erro = "";
 
// Processar dados submetidos do formulario
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Obter parametro ID
    $id = $_POST["id"];
    
    // Validar nome
    $input_nome = trim($_POST["nome"]);
    if(empty($input_nome)){
        $nome_erro = "Inserir nome.";
    } elseif(!filter_var($input_nome, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $nome_erro = "Inserir um nome válido.";
    } else{
        $nome = $input_nome;
    }
    
    // Validar morada
    $input_morada = trim($_POST["morada"]);
    if(empty($input_morada)){
        $morada_erro = "Inserir morada";     
    } else{
        $morada = $input_morada;
    }
    
    // Validar email
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_erro = "Inserir email";     
    } else{
        $email = $input_email;
    }

    // Validar telefone
    $input_tlf = trim($_POST["tlf"]);
    if(empty($input_tlf)){
        $tlf_erro = "Inserir número de telefone";     
    } else{
        $tlf = $input_tlf;
    }


    
    // Verifica se os dados inseridos têm erros, antes de inserir na base de dados
    if(empty($nome_erro) && empty($morada_erro) && empty($email_erro) && empty($tlf_erro)){
        // Criar declaração SQL para atualizar registro
        $sql = "UPDATE clientes SET nome=?, morada=?, email=?, tlf=? WHERE id=?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Vincular variaveis aos parametros da declaração SQL
            mysqli_stmt_bind_param($stmt, "ssssi", $param_nome, $param_morada, $param_email, $param_tlf, $param_id);
            
            // Definir parametros
            $param_nome = $nome;
            $param_morada = $morada;
            $param_email = $email;
            $param_tlf = $tlf;
            $param_id = $id;
            
            // Tentar correr declaração de SQL
            if(mysqli_stmt_execute($stmt)){
                // Se o cliente for alterado com sucesso, reedireciona para a pagina inicial
                header("location: index.php");
                exit();
            } else{
                echo "Houve um problema!";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($conn);
} else{
    // Verifica se existe o ID a alterar
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Obter parametro ID
        $id =  trim($_GET["id"]);
        
        // Criar declaração SELECT
        $sql = "SELECT * FROM clientes WHERE id = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
            // Vincular variaveis aos parametros da declaração SQL
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Definir parametros
            $param_id = $id;
            
            // Tentar correr declaração de SQL
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){

                    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    $nome = $record["nome"];
                    $morada = $record["morada"];
                    $email = $record["email"];
                    $tlf = $record["tlf"];
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
    }  else{
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
    <title>Actualizar Cliente</title>
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
                    <h2 class="mt-5">Actualizar cliente ID: <?php  echo $record["id"]; ?></h2>
                    <p>Altere os dados e grave para atualizar o cliente.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" name="nome" class="form-control <?php echo (!empty($nome_erro)) ? 'is-invalid' : ''; ?>" value="<?php echo $nome; ?>">
                            <span class="invalid-feedback"><?php echo $nome_erro;?></span>
                        </div>
                        <div class="form-group">
                            <label>Morada</label>
                            <textarea name="morada" class="form-control <?php echo (!empty($morada_erro)) ? 'is-invalid' : ''; ?>"><?php echo $morada; ?></textarea>
                            <span class="invalid-feedback"><?php echo $morada_erro;?></span>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" class="form-control <?php echo (!empty($email_erro)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                            <span class="invalid-feedback"><?php echo $email_erro;?></span>
                        </div>
                        <div class="form-group">
                            <label>Telefone</label>
                            <input type="text" name="tlf" class="form-control <?php echo (!empty($tlf_erro)) ? 'is-invalid' : ''; ?>" value="<?php echo $tlf; ?>">
                            <span class="invalid-feedback"><?php echo $tlf_erro;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Atualizar">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancelar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>