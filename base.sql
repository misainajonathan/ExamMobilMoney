-- cote operateur
DROP TABLE IF EXISTS prefixe;
CREATE TABLE prefixe(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe TEXT NOT NULL UNIQUE
);

DROP TABLE IF EXISTS type_operation;
CREATE TABLE type_operation(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation TEXT NOT NULL UNIQUE
);

DROP TABLE IF EXISTS bareme_frais;
CREATE TABLE bareme_frais(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    montant_min REAL NOT NULL,
    montant_max REAL NOT NULL,
    frais REAL NOT NULL,
    id_type_operation INTEGER NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);

DROP TABLE IF EXISTS client;
CREATE TABLE client(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    telephone TEXT NOT NULL UNIQUE,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS operation;
CREATE TABLE operation(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    montant REAL NOT NULL,
    frais_appliques REAL DEFAULT 0.0, -- Stocke la valeur fixe au moment T de l'achat
    date_operation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_client_expediteur INTEGER NOT NULL,
    id_client_destinataire INTEGER, -- NULL si Dépôt ou Retrait
    id_type_operation INTEGER NOT NULL,
    FOREIGN KEY (id_client_expediteur) REFERENCES client(id),
    FOREIGN KEY (id_client_destinataire) REFERENCES client(id),
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);