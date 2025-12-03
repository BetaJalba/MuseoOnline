<!DOCTYPE html>
<?php
    // nuova_esposizione.php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Qui andrebbe la logica per gestire i dati inviati dai form
        // Ad esempio, connettersi al database e inserire i nuovi record
        $username = 'root';
        $password = '';
        $database = 'museoonline';

        $conn = new mysqli('localhost', $username, $password, $database);
        if ($conn -> connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }

        $sql = '';

        $action = $_POST['action'] ?? '';
        switch ($action) {
            case 'create_visita':
                // Logica per creare una nuova visita
                $sql = "INSERT INTO visita (titolo, tipo, tariffa, data_inizio, data_fine) VALUES (?, ?, ?, ?, ?)";
                break;
            case 'create_biglietto':
                // Logica per creare un nuovo biglietto
                $sql = "INSERT INTO biglietto (data_validita, cod_visita, cod_categoria, cod_utente_acq, cod_utente_int) VALUES (?, ?, ?, ?, ?)";
                break;
            case 'create_servizio':
                // Logica per creare un nuovo servizio
                $sql = "INSERT INTO servizio (descrizione, prezzo) VALUES (?, ?)";
                break;
            case 'create_categoria':
                // Logica per creare una nuova categoria
                $sql = "INSERT INTO categoria (descrizione, tipo_documento, percentuale_sconto) VALUES (?, ?, ?)";
                break;
            case 'associate_biglietto_servizio':
                // Logica per associare biglietti e servizi
                $sql = "INSERT INTO associare (id_biglietto, id_servizio) VALUES (?, ?)";
                break;
            case 'create_utente':
                // Logica per creare un nuovo utente
                $sql = "INSERT INTO utente (nome, cognome, tel, email) VALUES (?, ?, ?, ?)";
                break;
            default:
                // Azione non riconosciuta
                // Chiudi la connessione
                $conn -> close();
                $stmt -> close();
                return;
        }

        $stmt = $conn -> prepare($sql);

        // Bind dei parametri in base all'action
        switch ($action) {
            case 'create_visita':
                $stmt -> bind_param("ssdss", $_POST['titolo_visita'], $_POST['tipo_visita'], $_POST['tariffa_visita'], $_POST['data_inizio_visita'], $_POST['data_fine_visita']);
                break;
            case 'create_biglietto':
                $stmt -> bind_param("siiii", $_POST['data_validita_biglietto'], $_POST['cod_visita_biglietto'], $_POST['cod_categoria_biglietto'], $_POST['cod_utente_acquisto_biglietto'], $_POST['cod_utente_intestatario_biglietto']);
                break;
            case 'create_servizio':
                $stmt -> bind_param("sd", $_POST['descrizione_servizio'], $_POST['prezzo_servizio']);
                break;
            case 'create_categoria':
                $stmt -> bind_param("ssd", $_POST['descizione_categoria'], $_POST['tipo_documento_categoria'], $_POST['sconto_categoria']);
                break;
            case 'associate_biglietto_servizio':
                $stmt -> bind_param("ii", $_POST['cod_biglietto_associazione'], $_POST['cod_servizio_associazione']);
                break;
            case 'create_utente':
                $stmt -> bind_param("ssss", $_POST['nome_utente'], $_POST['cognome_utente'], $_POST['telefono_utente'], $_POST['email_utente']);
                break;
            default:
                // Azione non riconosciuta
                // Chiudi la connessione
                $conn -> close();
                $stmt -> close();
                return;
        }

        // Esegui l'inserimento
        if ($stmt -> execute()) {
            echo "Nuovo record creato con successo.";
        } else {
            echo "Errore: " . $stmt -> error;
        }

        // Chiudi la connessione
        $conn -> close();
        $stmt -> close();
    }
?>
<html>
    <head>
    </head>
    <body>
        <a href="index.php">Torna alla pagina principale</a>
        <h2>Crea una nuova visita</h2>
        <form action="" method="post">
            <input type="hidden" name="action" value="create_visita">
            <label for="titolo_visita">Titolo visita: </label>
            <input type="text" id="titolo_visita" name="titolo_visita" placeholder="Titolo visita..." required>
            <br>
            <label for="tipo_visita">Tipo visita: </label>
            <input type="text" id="tipo_visita" name="tipo_visita" placeholder="Tipo visita..." required>
            <br>
            <label for="tariffa_visita">Tariffa visita: </label>
            <input type="text" id="tariffa_visita" name="tariffa_visita" placeholder="Tariffa visita..." required>
            <br>
            <label for="data_inizio_visita">Data inizio: </label>
            <input type="date" id="data_inizio_visita" name="data_inizio_visita" required>
            <br>
            <label for="data_fine_visita">Data fine: </label>
            <input type="date" id="data_fine_visita" name="data_fine_visita" required>
            <br>
            <input type="submit" value="Crea visita">
        </form>
        <h2>Crea un nuovo biglietto</h2>
        <form action="" method="post">
            <input type="hidden" name="action" value="create_biglietto">
            <label for="data_validita_biglietto">Data validità: </label>
            <input type="date" id="data_validita_biglietto" name="data_validita_biglietto" required>
            <br>
            <label for="cod_visita_biglietto">Codice visita: </label>
            <input type="text" id="cod_visita_biglietto" name="cod_visita_biglietto" placeholder="Codice visita..." required>
            <br>
            <label for="cod_categoria_biglietto">Codice categoria: </label>
            <input type="text" id="cod_categoria_biglietto" name="cod_categoria_biglietto" placeholder="Codice categoria..." required>
            <br>
            <label for="cod_utente_acquisto_biglietto">Codice utente acquisto: </label>
            <input type="text" id="cod_utente_acquisto_biglietto" name="cod_utente_acquisto_biglietto" placeholder="Codice utente..." required>
            <br>
            <label for="cod_utente_intestatario_biglietto">Codice utente intestatario: </label>
            <input type="text" id="cod_utente_intestatario_biglietto" name="cod_utente_intestatario_biglietto" placeholder="Codice intestatario..." required>
            <br>
            <input type="submit" value="Crea biglietto">
        </form>
        <h2>Crea un nuovo servizio</h2>
        <form action="" method="post">
            <input type="hidden" name="action" value="create_servizio">
            <label for="descrizione_servizio">Descrizione servizio: </label>
            <input type="text" id="descrizione_servizio" name="descrizione_servizio" placeholder="Descrizione servizio..." required>
            <br>
            <label for="prezzo_servizio">Prezzo servizio: </label>
            <input type="text" id="prezzo_servizio" name="prezzo_servizio" placeholder="Prezzo servizio..." required>
            <br>
            <input type="submit" value="Crea servizio">
        </form>
        <h2>Crea una nuova categoria</h2>
        <form action="" method="post">
            <input type="hidden" name="action" value="create_categoria">
            <label for="descizione_categoria">Descrizione categoria: </label>
            <input type="text" id="descizione_categoria" name="descizione_categoria" placeholder="Nome categoria..." required>
            <br>
            <label for="tipo_documento_categoria">Tipo documento categoria: </label>
            <input type="text" id="tipo_documento_categoria" name="tipo_documento_categoria" placeholder="Tipo documento...">
            <br>
            <label for="sconto_categoria">Sconto categoria (%): </label>
            <input type="text" id="sconto_categoria" name="sconto_categoria" placeholder="Sconto categoria...">
            <br>
            <input type="submit" value="Crea categoria">
        </form>
        <h2>Associa biglietti e servizi</h2>
        <form action="" method="post">
            <input type="hidden" name="action" value="associate_biglietto_servizio">
            <label for="cod_biglietto_associazione">Codice biglietto: </label>
            <input type="text" id="cod_biglietto_associazione" name="cod_biglietto_associazione" placeholder="Codice biglietto..." required>
            <br>
            <label for="cod_servizio_associazione">Codice servizio: </label>
            <input type="text" id="cod_servizio_associazione" name="cod_servizio_associazione" placeholder="Codice servizio..." required>
            <br>
            <input type="submit" value="Associa biglietto e servizio">
        </form>
        <h2>Crea un nuovo utente</h2>
        <form action="" method="post">
            <input type="hidden" name="action" value="create_utente">
            <label for="nome_utente">Nome utente: </label>
            <input type="text" id="nome_utente" name="nome_utente" placeholder="Nome utente..." required>
            <br>
            <label for="cognome_utente">Cognome utente: </label>
            <input type="text" id="cognome_utente" name="cognome_utente" placeholder="Cognome utente..." required>
            <br>
            <label for="telefono_utente">Telefono utente: </label>
            <input type="text" id="telefono_utente" name="telefono_utente" placeholder="Telefono utente..." required>
            <br>
            <label for="email_utente">Email utente: </label>
            <input type="email" id="email_utente" name="email_utente" placeholder="Email utente..." required>
            <br>
            <input type="submit" value="Crea utente">
        </form>
    </body>
</html>