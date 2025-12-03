<!DOCTYPE html>
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codice'])) {
        $codice = $_POST['codice'];

        // Imposta cookie per 10 minuti
        $cookie_name = 'search_' . time();
        setcookie($cookie_name, $codice, time() + 600, "/"); // 600 secondi = 10 minuti

        // Connessione al database
        $username = 'root';
        $password = '';
        $database = 'museoonline';

        $conn = new mysqli('localhost', $username, $password, $database);
        if ($conn -> connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }

        // Preparazione ed esecuzione della query
        $stmt = $conn -> prepare("SELECT * FROM visita WHERE id_visita = ?");
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
        <h2>Esposizione Dettagli</h2>
        <p><strong>Titolo:</strong> <?php echo htmlspecialchars($esposizione['titolo']); ?></p>
        <p><strong>Tipo:</strong> <?php echo htmlspecialchars($esposizione['tipo']); ?></p>
        <p><strong>Tariffa:</strong> <?php echo htmlspecialchars($esposizione['tariffa']); ?></p>
        <p><strong>Data Inizio:</strong> <?php echo htmlspecialchars($esposizione['data_inizio']); ?></p>
        <p><strong>Data Fine:</strong> <?php echo htmlspecialchars($esposizione['data_fine']); ?></p>
    </body>
</html>