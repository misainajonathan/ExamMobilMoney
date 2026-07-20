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


INSERT INTO prefixe (prefixe) VALUES ('033');
INSERT INTO prefixe (prefixe) VALUES ('037');

INSERT INTO type_operation (type_operation) VALUES ('depot');
INSERT INTO type_operation (type_operation) VALUES ('retrait');
INSERT INTO type_operation (type_operation) VALUES ('transfert');

INSERT INTO bareme_frais (montant_min, montant_max, frais, id_type_operation) VALUES (0.00, 5000.00, 100.00, 2);
INSERT INTO bareme_frais (montant_min, montant_max, frais, id_type_operation) VALUES (5000.01, 20000.00, 300.00, 2);
INSERT INTO bareme_frais (montant_min, montant_max, frais, id_type_operation) VALUES (20000.01, 50000.00, 700.00, 2);
INSERT INTO bareme_frais (montant_min, montant_max, frais, id_type_operation) VALUES (50000.01, 100000.00, 1200.00, 2);
INSERT INTO bareme_frais (montant_min, montant_max, frais, id_type_operation) VALUES (100000.01, 500000.00, 3000.00, 2);

INSERT INTO bareme_frais (montant_min, montant_max, frais, id_type_operation) VALUES (0.00, 10000.00, 150.00, 3);
INSERT INTO bareme_frais (montant_min, montant_max, frais, id_type_operation) VALUES (10000.01, 50000.00, 400.00, 3);
INSERT INTO bareme_frais (montant_min, montant_max, frais, id_type_operation) VALUES (50000.01, 200000.00, 1000.00, 3);
INSERT INTO bareme_frais (montant_min, montant_max, frais, id_type_operation) VALUES (200000.01, 500000.00, 2500.00, 3);

INSERT INTO client (telephone) VALUES ('0331234567');
INSERT INTO client (telephone) VALUES ('0379876543');
INSERT INTO client (telephone) VALUES ('0345551234');

INSERT INTO operation (montant, frais_appliques, id_client_expediteur, id_client_destinataire, id_type_operation) 
VALUES (150000.00, 0.00, 1, NULL, 1);

INSERT INTO operation (montant, frais_appliques, id_client_expediteur, id_client_destinataire, id_type_operation) 
VALUES (50000.00, 0.00, 2, NULL, 1);

INSERT INTO operation (montant, frais_appliques, id_client_expediteur, id_client_destinataire, id_type_operation) 
VALUES (30000.00, 400.00, 1, 2, 3);

INSERT INTO operation (montant, frais_appliques, id_client_expediteur, id_client_destinataire, id_type_operation) 
VALUES (150000.00, 300.00, 2, NULL, 2);