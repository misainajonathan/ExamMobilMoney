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

-- Préfixes autorisés par l'opérateur
INSERT INTO prefixe (prefixe) VALUES ('033');
INSERT INTO prefixe (prefixe) VALUES ('037');

-- Types d'opérations
INSERT INTO type_operation (type_operation) VALUES ('depot');
INSERT INTO type_operation (type_operation) VALUES ('retrait');
INSERT INTO type_operation (type_operation) VALUES ('transfert');

-- Barèmes de frais par tranche de montant
-- Dépôt : gratuit (aucun barème nécessaire, frais_appliques = 0 par défaut)

-- Retrait
INSERT INTO bareme_frais (montant_min, montant_max, frais, id_type_operation)
    VALUES (0, 5000, 100, (SELECT id FROM type_operation WHERE type_operation = 'retrait'));
INSERT INTO bareme_frais (montant_min, montant_max, frais, id_type_operation)
    VALUES (5001, 20000, 300, (SELECT id FROM type_operation WHERE type_operation = 'retrait'));
INSERT INTO bareme_frais (montant_min, montant_max, frais, id_type_operation)
    VALUES (20001, 50000, 500, (SELECT id FROM type_operation WHERE type_operation = 'retrait'));
INSERT INTO bareme_frais (montant_min, montant_max, frais, id_type_operation)
    VALUES (50001, 200000, 1000, (SELECT id FROM type_operation WHERE type_operation = 'retrait'));

-- Transfert
INSERT INTO bareme_frais (montant_min, montant_max, frais, id_type_operation)
    VALUES (0, 5000, 50, (SELECT id FROM type_operation WHERE type_operation = 'transfert'));
INSERT INTO bareme_frais (montant_min, montant_max, frais, id_type_operation)
    VALUES (5001, 20000, 200, (SELECT id FROM type_operation WHERE type_operation = 'transfert'));
INSERT INTO bareme_frais (montant_min, montant_max, frais, id_type_operation)
    VALUES (20001, 50000, 400, (SELECT id FROM type_operation WHERE type_operation = 'transfert'));
INSERT INTO bareme_frais (montant_min, montant_max, frais, id_type_operation)
    VALUES (50001, 200000, 800, (SELECT id FROM type_operation WHERE type_operation = 'transfert'));