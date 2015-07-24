DROP TABLE IF EXISTS history CASCADE;

CREATE TABLE history(
    id SERIAL,
    chat_id CHARACTER VARYING(60),
    chat_title CHARACTER VARYING(60),
    user_id CHARACTER VARYING(60),
    username CHARACTER VARYING(60),
    avatar_16 CHARACTER VARYING(90),
    avatar_32 CHARACTER VARYING(90),
    timestamp INTEGER NOT NULL,
    message TEXT
);

ALTER TABLE history ADD PRIMARY KEY(id);
