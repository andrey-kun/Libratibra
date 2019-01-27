INSERT INTO libratibra.authors (id, name, rating, number_books)
VALUES (1, 'Стивен Кинг', 4.13333, 3);
INSERT INTO libratibra.authors (id, name, rating, number_books)
VALUES (2, 'Бернар Вербер', 3.9, 1);
INSERT INTO libratibra.authors (id, name, rating, number_books)
VALUES (3, 'Ларс Кеплер', 3.6, 2);
INSERT INTO libratibra.authors (id, name, rating, number_books)
VALUES (4, 'Эдуард Катлас', 3.35, 2);
INSERT INTO libratibra.authors (id, name, rating, number_books)
VALUES (5, 'Андрей Круз', 3.7, 2);

INSERT INTO libratibra.books (id, name, rating, author_id, genre_id)
VALUES (1, 'Зелёная Миля', 4.5, 1, 3);
INSERT INTO libratibra.books (id, name, rating, author_id, genre_id)
VALUES (2, 'Танатонавты', 3.9, 2, 3);
INSERT INTO libratibra.books (id, name, rating, author_id, genre_id)
VALUES (3, 'Сияние', 4, 1, 4);
INSERT INTO libratibra.books (id, name, rating, author_id, genre_id)
VALUES (4, 'Песочный человек', 3.7, 3, 4);
INSERT INTO libratibra.books (id, name, rating, author_id, genre_id)
VALUES (5, 'Кладбище домашних животных', 3.9, 1, 5);
INSERT INTO libratibra.books (id, name, rating, author_id, genre_id)
VALUES (6, 'Создатели', 3.8, 4, 5);
INSERT INTO libratibra.books (id, name, rating, author_id, genre_id)
VALUES (7, 'Эпоха мертвых', 4, 5, 6);
INSERT INTO libratibra.books (id, name, rating, author_id, genre_id)
VALUES (8, 'Двери во Тьме', 3.4, 5, 3);
INSERT INTO libratibra.books (id, name, rating, author_id, genre_id)
VALUES (9, 'Прикладное терраформирование', 2.9, 4, 3);
INSERT INTO libratibra.books (id, name, rating, author_id, genre_id)
VALUES (10, 'Соглядатай', 3.5, 3, 4);

INSERT INTO libratibra.genres (id, name)
VALUES (6, 'Боевик');
INSERT INTO libratibra.genres (id, name)
VALUES (5, 'Мистика');
INSERT INTO libratibra.genres (id, name)
VALUES (4, 'Триллер');
INSERT INTO libratibra.genres (id, name)
VALUES (3, 'Фантастика');
