<!DOCTYPE html>
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codice'])) {
        $codice = $_POST['codice'];

        // Connessione al database
        $username = 'root';
        $password = '';
        $database = 'museoonline';

        $conn = new mysqli('localhost', $username, $password, $database);
        if ($conn -> connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }

        // Preparazione ed esecuzione della query
        $stmt = $conn -> prepare("SELECT * FROM biglietto WHERE cod_visita = ?");
        $stmt -> bind_param("s", $codice);
        $stmt -> execute();
        $result = $stmt -> get_result();

        // Loop through all rows
        $biglietti = [];
        while ($biglietto = $result->fetch_assoc()) {
            $biglietti[] = $biglietto;
        }

        $stmt -> close();
        $conn -> close();
    }

    function displayBiglietto($biglietto) {
        return "Data validita: " . htmlspecialchars($biglietto['data_validita']) .
               " Codice visita: " . htmlspecialchars($biglietto['cod_visita']) .
               " Codice categoria: " . htmlspecialchars($biglietto['cod_categoria']) .
               " Codice utente acquisto: " . htmlspecialchars($biglietto['cod_utente_acq']) . 
               " Codice utente intestatario: " . htmlspecialchars($biglietto['cod_utente_int']);
    }
?>
<html>
    <head>
    </head>
    <body>
        <a href="index.php">Torna alla pagina principale</a>
        <h2>Biglietti</h2>
        <select id="searches" size="<?php echo count($biglietti); ?>">
        <?php
            foreach ($biglietti as $biglietto) {
                echo "<option>" . displayBiglietto($biglietto) . "</option>";
            }
        ?>
        </select>
    </body>
</html>