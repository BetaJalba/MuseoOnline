<!DOCTYPE html>
<?php
    // Ricerche negli ultimi 10 minuti
    $searches = [];

    foreach ($_COOKIE as $name => $value) {
        if (strpos($name, 'search_') === 0) {
            $searches[] = $value;
        }
    }
?>

<?php
    function fetchEsposizione($conn, $codice) {
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
        return $esposizione;
    }

    function displayEsposizione($esposizione) {
        return "Titolo: " . htmlspecialchars($esposizione['titolo']) .
               " Tipo: " . htmlspecialchars($esposizione['tipo']) .
               " Tariffa: " . htmlspecialchars($esposizione['tariffa']) .
               " Data Inizio: " . htmlspecialchars($esposizione['data_inizio']) . 
               " Data Fine: " . htmlspecialchars($esposizione['data_fine']);
    }
?>
<html>
    <head>
    </head>
    <body>
        <h1>Pagina Principale Museo Online</h1>
        
        <h2>Ricerca Esposizione e Biglietti</h2>
        <form id="myForm" method="post">
            <label for="codice">Inserisci codice: </label>
            <input type="text" id="codice" name="codice" placeholder="Inserisci codice..." required>
            <input type="submit" value="Cerca Esposizione"
                onclick="document.getElementById('myForm').action='esposizione.php'">
            <input type="submit" value="Ricerca Biglietti"
                onclick="document.getElementById('myForm').action='biglietti_esposizione.php'">
            <input type="submit" value="Cerca Ricavi"
                onclick="document.getElementById('myForm').action='ricavato_data.php'">
        </form>
        
        <h2>Visualizza Storico Utente</h2>
        <form action="storico_utente.php" method="post">
            <label for="cod_utente">Inserisci codice utente: </label>
            <input type="text" id="cod_utente" name="cod_utente" placeholder="Inserisci codice utente..." required>
            <input type="submit">
        </form>
        
        <br>
        <button><a href="nuova_esposizione.php">Crea nuova esposizione</a></button>
        <br><br>
        <label for="searches">Ricerche recenti (ultimi 10 minuti): </label>
        <br>
        <select id="searches" size="<?php echo count($searches);?>">
        <?php
            // Connessione al database
            $username = 'root';
            $password = '';
            $database = 'museoonline';

            $conn = new mysqli('localhost', $username, $password, $database);
            if ($conn -> connect_error) {
                die("Connessione fallita: " . $conn->connect_error);
            }

            foreach ($searches as $search) {
                echo "<option>" . displayEsposizione(fetchEsposizione($conn, $search)) . "</option>";
            }

            $conn -> close();
        ?>
        </select>
    </body>
</html>