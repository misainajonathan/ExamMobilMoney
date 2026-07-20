-- cote operateur
CREATE TABLE prefixe(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe VARCHAR(255) NOT NULL
);

CREATE TABLE type_operation(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation VARCHAR(255) NOT NULL
);

CREATE TABLE bareme_frais(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    montant_min DECIMAL(10, 2) NOT NULL,
    montant_max DECIMAL(10, 2) NOT NULL,
    frais DECIMAL(10, 2) NOT NULL,
    id_type_operation INTEGER NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);

-- client
CREATE TABLE client(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    telephone VARCHAR(20) NOT NULL UNIQUE,
    solde DECIMAL(10, 2) NOT NULL DEFAULT 0
);

CREATE TABLE operation(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    montant DECIMAL(10, 2) NOT NULL,
    id_frais INTEGER NOT NULL,
    date_operation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_client_expediteur INTEGER NOT NULL,
    id_client_destinataire INTEGER NOT NULL,
    id_type_operation INTEGER NOT NULL,
    FOREIGN KEY (id_client_expediteur) REFERENCES client(id),
    FOREIGN KEY (id_client_destinataire) REFERENCES client(id),
    FOREIGN KEY (id_frais) REFERENCES bareme_frais(id),
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);