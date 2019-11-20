<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <?php include_once('./imports/header.php'); ?>
    <title>Pagamentos - Consultas API do ML</title>
    <?php include_once('./imports/imports.php'); ?>
</head>

<body>
    <?php include_once('./imports/navbar.php'); ?>

    <input type="hidden" name="token" id="token" value="<?php echo $_SESSION['producao_token']; ?>">

    <div class="container">


    </div>

    <?php include_once('./imports/scripts.php'); ?>

    <script>
        var url = "https://api.mercadopago.com/v1/payments/search";

        $(document).ready(function() {
            pesquisar();
        });

        function pesquisar() {
            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                data: {
                    "access_token": $('#token').val(),
                    "offset": "0",
                    "limit": "20",
                    "criteria" : "desc",
                },
                async: true,
                success: function(response) {
                    console.log(response);
                },
            });
        }
    </script>
</body>

</html>