SET NAMES 'utf8mb4';
CREATE USER IF NOT EXISTS 'user'@'%' IDENTIFIED BY 'user';
GRANT ALL PRIVILEGES ON *.* TO 'user'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;

CREATE DATABASE IF NOT EXISTS InteligentnaSkrytka;

USE InteligentnaSkrytka;

CREATE TABLE IF NOT EXISTS Paczkomat
(
    id_skrytki INT PRIMARY KEY    NOT NULL,
    rozmiar    ENUM ('S','M','L') NOT NULL,
    status     ENUM ('WOLNA','ZAJETA') DEFAULT 'WOLNA',
    CHECK (id_skrytki IN (1, 2, 3))
    )DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS Uzytkownicy
(
    id_uzytkownika INT PRIMARY KEY UNIQUE AUTO_INCREMENT NOT NULL,
    login          VARCHAR(255)                          NOT NULL UNIQUE,
    haslo          VARCHAR(255)                          NOT NULL,
    imie           VARCHAR(255)                          NOT NULL,
    nazwisko       VARCHAR(255)                          NOT NULL,
    rola           ENUM ('ADMIN','KURIER','KLIENT') DEFAULT 'KLIENT'
    )DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS Paczki
(
    id_paczki      INT PRIMARY KEY UNIQUE AUTO_INCREMENT,
    nr_zamowienia  BIGINT                               UNIQUE NOT NULL,
    id_uzytkownika INT                                        NOT NULL,
    kod_odbioru    varchar(6)                               DEFAULT NULL,
    id_skrytki     INT,
    status         ENUM ('NADANA','W_PACZKOMACIE','ODEBRANA') NOT NULL,
    nadawca        VARCHAR(255),
    data_nadania   DATETIME NOT NULL ,
    data_odebrania DATETIME,
    FOREIGN KEY (id_uzytkownika) REFERENCES Uzytkownicy (id_uzytkownika),
    FOREIGN KEY (id_skrytki) REFERENCES Paczkomat (id_skrytki)

    )DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS Archiwum
(
    id_paczki      INT PRIMARY KEY UNIQUE AUTO_INCREMENT,
    id_uzytkownika INT                                        NOT NULL,
    id_skrytki     INT,
    status         ENUM ('NADANA','W_PACZKOMACIE','ODEBRANA') NOT NULL,
    nadawca        VARCHAR(255),
    data_nadania   DATETIME NOT NULL ,
    data_odebrania DATETIME,

    FOREIGN KEY (id_uzytkownika) REFERENCES Uzytkownicy (id_uzytkownika),
    FOREIGN KEY (id_skrytki) REFERENCES Paczkomat (id_skrytki)

    )DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

##              Przykładowi użytkownicy     ##
INSERT INTO Uzytkownicy (login, haslo, imie, nazwisko, rola)
VALUES ('admin', '1234', 'Jan', 'Nowak', 'ADMIN'),
       ('kurier_jan', '1234', 'Jan', 'Kowalski', 'KURIER'),
       ('kurier_anna', '1234', 'Anna', 'Wiśniewska', 'KURIER'),
       ('klient_marek', '1234', 'Marek', 'Zieliński', 'KLIENT'),
       ('klient_ewa', '1234', 'Ewa', 'Kamińska', 'KLIENT'),
       ('klient_piotr', '1234', 'Piotr', 'Lewandowski', 'KLIENT'),
       ('marekz', '1234', 'Marek', 'Zieliński', 'KLIENT'),
       ('ewak', '1234', 'Ewa', 'Kamińska', 'KLIENT'),
       ('piotrlew', '1234', 'Piotr', 'Lewandowski', 'KLIENT'),
       ('olakac', '1234', 'Ola', 'Kaczmarek', 'KLIENT'),
       ('tomaszd92', '1234', 'Tomasz', 'Dąbrowski', 'KLIENT'),
       ('kasiamz', '1234', 'Katarzyna', 'Mazur', 'KLIENT'),
       ('bartekw_', '1234', 'Bartosz', 'Wójcik', 'KLIENT'),
       ('ania.kow', '1234', 'Anna', 'Kowalczyk', 'KLIENT'),
       ('michalw', '1234', 'Michał', 'Woźniak', 'KLIENT'),
       ('magdazaj', '1234', 'Magdalena', 'Zając', 'KLIENT'),
       ('pawelk_', '1234', 'Paweł', 'Krawczyk', 'KLIENT'),
       ('agnieszas', '1234', 'Agnieszka', 'Szymańska', 'KLIENT'),
       ('karolkr', '1234', 'Karol', 'Król', 'KLIENT'),
       ('dominikw', '1234', 'Dominik', 'Witkowski', 'KLIENT'),
       ('martyna.p', '1234', 'Martyna', 'Pawlak', 'KLIENT'),
       ('lukasz83', '1234', 'Łukasz', 'Nowicki', 'KLIENT'),
       ('sylwiaa', '1234', 'Sylwia', 'Lis', 'KLIENT'),
       ('filipm', '1234', 'Filip', 'Michalski', 'KLIENT');

INSERT INTO Paczkomat (id_skrytki, rozmiar, status)
VALUES (1, 'L', 'WOLNA'),
       (2, 'M', 'WOLNA'),
       (3, 'M', 'ZAJETA');

INSERT INTO `Paczki` (`id_paczki`, `nr_zamowienia`, `id_uzytkownika`, `kod_odbioru`, `id_skrytki`, `status`, `nadawca`, `data_nadania`, `data_odebrania`) VALUES
(1, 5839201746, 3, '847291', 3, 'W_PACZKOMACIE', 'CCC', '2025-12-10 18:42:28', NULL),
(2, 1049583271, 4, '23156', NULL, 'NADANA', 'MODIVO SP. ZOO', '2025-12-10 18:42:28', NULL),
(3, 7926403851, 5, '519384', NULL, 'NADANA', 'Botland', '2025-12-10 18:42:28', NULL),
(5, 9304175268, 7, '382917', NULL, 'NADANA', 'Media Expert', '2025-10-15 10:30:00', NULL),
(6, 2817394056, 8, '104563', NULL, 'NADANA', 'Zalando', '2025-10-07 13:45:00', NULL),
(7, 8741205963, 9, '695820', NULL, 'W_PACZKOMACIE', 'Allegro Smart!', '2025-10-14 12:10:00', NULL),
(8, 5196830472, 10, '438271', NULL, 'NADANA', 'x-kom', '2025-10-15 08:05:00', NULL),
(9, 3627951840, 11, '956103', NULL, 'W_PACZKOMACIE', 'Reserved', '2025-10-13 19:32:00', NULL),
(10, 7405283196, 12, '271849', NULL, 'NADANA', '4F', '2025-10-01 10:05:00', NULL),
(11, 1584927036, 13, '583026', NULL, 'NADANA', 'Decathlon', '2025-10-15 11:12:00', NULL),
(12, 6973058214, 14, '914637', NULL, 'NADANA', 'H&M', '2025-10-12 15:55:00', NULL),
(13, 2431809675, 15, '362508', NULL, 'NADANA', 'Empik', '2025-09-28 09:10:00', NULL),
(14, 8057169324, 16, '728194', NULL, 'NADANA', 'Morele.net', '2025-10-14 18:42:00', NULL),
(15, 9702148563, 17, '45362', NULL, 'W_PACZKOMACIE', 'Smyk', '2025-10-13 13:05:00', NULL),
(16, 6145792308, 18, '891745', NULL, 'NADANA', 'RTV Euro AGD', '2025-10-15 07:48:00', NULL),
(17, 3280641957, 19, '206483', NULL, 'NADANA', 'Answear.com', '2025-10-14 17:30:00', NULL),
(18, 4869375201, 20, '573910', NULL, 'NADANA', 'Ceneo', '2025-10-15 12:25:00', NULL),
(19, 7591204836, 9, '689247', NULL, 'NADANA', 'Poczta Polska', '2025-10-10 10:00:00', NULL),
(20, 2356489701, 7, '134058', NULL, 'NADANA', 'Komputronik', '2025-10-15 14:45:00', NULL),
(21, 2066353005, 8, '676767', 1, 'W_PACZKOMACIE', 'Nike', '2025-12-14 11:31:36', NULL),
(22, 3026538759, 18, '903414', 2, 'W_PACZKOMACIE', 'botland', '2025-12-14 11:35:29', NULL),
(23, 2647544127, 22, '100164', NULL, 'NADANA', 'Lego', '2025-12-14 11:46:32', NULL),
(24, 5174769846, 14, '278283', NULL, 'NADANA', 'politechnika śląska', '2025-12-14 11:47:12', NULL);

-- ##          ZAPYTANIA DO PANELÓW      ##
-- UPDATE Uzytkownicy
-- SET Uzytkownicy.rola = 'KLIENT'
-- WHERE Uzytkownicy.id_uzytkownika = 2;

-- ## WSTAWIANIE PACZKI (Opcje kuriera)
--
-- SELECT Paczkomat.id_skrytki, Paczkomat.status
-- FROM Paczkomat;
--
-- UPDATE Paczkomat
-- SET Paczkomat.status = 'WOLNA'
-- WHERE Paczkomat.id_skrytki = 1;
--
-- UPDATE Paczki
-- SET Paczki.status = 'NADANA'
-- WHERE id_paczki = 2;
--
-- UPDATE Paczki
-- SET Paczki.status = 'W_PACZKOMACIE'
-- WHERE id_paczki = 2;
--
--
-- SELECT id_paczki, Uzytkownicy.id_uzytkownika AS 'id_uzytkownika',
--     concat(Uzytkownicy.imie,' ', Uzytkownicy.nazwisko) AS 'imie_nazwisko',
--     id_skrytki, status, nadawca, data_nadania, data_odebrania
-- FROM Paczki JOIN Uzytkownicy
--                  ON Paczki.id_uzytkownika = Uzytkownicy.id_uzytkownika
-- order by id_uzytkownika asc;