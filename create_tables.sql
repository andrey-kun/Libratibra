create table authors
(
  id           int auto_increment
    primary key,
  name         varchar(255) not null,
  rating       float        null,
  number_books int          null,
  constraint author_name_uindex
    unique (name)
);

create table books
(
  id        int auto_increment
    primary key,
  name      varchar(255) null,
  rating    float        null,
  author_id int          null,
  genre_id  int          null
);

create table genres
(
  id   int auto_increment
    primary key,
  name varchar(255) not null,
  constraint genre_name_uindex
    unique (name)
);
