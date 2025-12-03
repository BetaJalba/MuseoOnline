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
        $stmt = $conn -> prepare("SELECT SUM((prezzo * (1 - percentuale_sconto / 100))) AS totale FROM servizio S INNER JOIN associare A ON S.id_servizio = A.id_servizio INNER JOIN biglietto B ON A.id_biglietto = B.id_biglietto INNER JOIN categoria C ON B.cod_categoria = C.id_categoria WHERE B.cod_visita = ?");
        $stmt -> bind_param("s", $codice);
        $stmt -> execute();
        $result = $stmt -> get_result();

        $esposizione = null;

        // Verifica se l'esposizione esiste
        if ($result -> num_rows > 0) {
            $esposizione = $result -> fetch_assoc();
        }

        $stmt -> close();
        $conn -> close();
    }
?>
<html>
    <head>
    </head>
    <body>
        <a href="index.php">Torna alla pagina principale</a>
        <h2>Totale ricavi</h2>
        <p><strong>Totale:</strong> <?php echo htmlspecialchars($esposizione['totale']); ?></p>
    </body>
</html>