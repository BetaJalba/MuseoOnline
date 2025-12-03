<!DOCTYPE html>
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cod_utente'])) {
        $codice = $_POST['cod_utente'];

        // Connessione al database
        $username = 'root';
        $password = '';
        $database = 'museoonline';

        $conn = new mysqli('localhost', $username, $password, $database);
        if ($conn -> connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }

        // Preparazione ed esecuzione della query
        $stmt = $conn -> prepare("SELECT * FROM biglietto WHERE cod_utente_acq = ?");
        $stmt -> bind_param("s", $codice);
        $stmt -> execute();
        $result = $stmt -> get_result();

        // Loop through all rows
        $acquistati = [];
        while ($acquistato = $result->fetch_assoc()) {
            $acquistati[] = $acquistato;
        }

        $stmt -> close();

        // Preparazione ed esecuzione della query
        $stmt = $conn -> prepare("SELECT * FROM biglietto WHERE cod_utente_int = ?");
        $stmt -> bind_param("s", $codice);
        $stmt -> execute();
        $result = $stmt -> get_result();

        // Loop through all rows
        $intestati = [];
        while ($intestato = $result->fetch_assoc()) {
            $intestati[] = $intestato;
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
        <h2>Biglietti acquistati</h2>
        <select id="searches" size="<?php echo count($acquistati); ?>">
        <?php
            foreach ($acquistati as $acquistato) {
                echo "<option>" . displayBiglietto($acquistato) . "</option>";
            }
        ?>
        </select>

        <h2>Biglietti intestati</h2>
        <select id="searches" size="<?php echo count($intestati); ?>">
        <?php
            foreach ($intestati as $intestato) {
                echo "<option>" . displayBiglietto($intestato) . "</option>";
            }
        ?>
        </select>
    </body>
</html>