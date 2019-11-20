<?php

$conn = new SQLite3("./database.db");
$result = $conn->query("SELECT * FROM configs");
$row = $result->fetchArray();

?>


<!DOCTYPE html>
<html lang="pt">

<head>
    <?php include_once('./imports/header.php'); ?>

    <title>Configurações - Consultas API do ML</title>

    <?php include_once('./imports/imports.php'); ?>
</head>

<body>
    <?php include_once('./imports/navbar.php'); ?>


    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center">
                            Sandbox
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Key:</label>
                                    <input type="text" name="sandbox_key" id="sandbox_key" value="<?php echo $row['sandbox_key']; ?>" class="form-control input-sm" readonly />
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Token:</label>
                                    <input type="text" name="sandbox_token" id="sandbox_token" value="<?php echo $row['sandbox_token']; ?>" class="form-control input-sm" readonly />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="panel panel-warning">
                        <div class="panel-heading text-center">
                            Produção
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Key:</label>
                                    <input type="text" name="producao_key" id="producao_key" value="<?php echo $row['producao_key']; ?>" class="form-control input-sm" readonly />
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Token:</label>
                                    <input type="text" name="producao_token" id="producao_token" value="<?php echo $row['producao_token']; ?>" class="form-control input-sm" readonly />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center">
                            URLs
                        </div>
                        <div class="panel-body">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php include_once('./imports/scripts.php'); ?>

    <script>
        function salvarSandbox() {
            alert("Clicou em salvar");
        }

        function salvarProducao() {
            alert("Clicou em salvar");
        }
    </script>

</body>

</html>
<?php

$conn->close();

?>