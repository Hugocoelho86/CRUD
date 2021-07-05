<?php
// Incluir ficheiro de configuração, para ligação a base de dados
require_once "config.php";
 
// Inicializa variaveis vazias
$nome = $morada = $email = $tlf = "";
$nome_erro = $morada_erro = $email_erro = $tlf_erro = "";
 
// Processar dados submetidos do formulario
if($_SERVER["REQUEST_METHOD"] == "POST"){
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
    } elseif (!filter_var($input_email, FILTER_VALIDATE_EMAIL)) {
        $email_erro = "Inserir um email válido";  
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
        // Criar declaração SQL
        $sql = "INSERT INTO clientes (nome, morada, email, tlf) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Vincular variaveis aos parametros da declaração SQL
            mysqli_stmt_bind_param($stmt, "ssss", $param_nome, $param_morada, $param_email, $param_tlf);
            
            // Definir parametros
            $param_nome = $nome;
            $param_morada = $morada;
            $param_email = $email;
            $param_tlf = $tlf;
            
            // Tentar correr declaração de SQL
            if(mysqli_stmt_execute($stmt)){
                // Se o cliente for criado com sucesso, reedireciona para a pagina inicial
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
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Criar cliente</title>
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
                    <h2 class="mt-5">Criar cliente</h2>
                    <p>Preencher dados do cliente para inserir na base de dados</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                        <input type="submit" class="btn btn-primary" value="Criar">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancelar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>