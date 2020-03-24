-- Types
DROP TYPE IF EXISTS vote_types CASCADE;
DROP TYPE IF EXISTS genders CASCADE;
DROP TYPE IF EXISTS status_types CASCADE;

CREATE TYPE vote_types AS ENUM ('up', 'down');
CREATE TYPE genders AS ENUM ('male', 'female', 'other');
CREATE TYPE status_types AS ENUM ('accepted', 'denied', 'pending');

-- Tables
DROP TABLE IF EXISTS "user" CASCADE;
DROP TABLE IF EXISTS "admin" CASCADE;
DROP TABLE IF EXISTS community CASCADE;
DROP TABLE IF EXISTS post CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS post_vote CASCADE;
DROP TABLE IF EXISTS report CASCADE;
DROP TABLE IF EXISTS comment_report CASCADE;
DROP TABLE IF EXISTS post_report CASCADE;
DROP TABLE IF EXISTS community_report CASCADE;
DROP TABLE IF EXISTS reply CASCADE;
DROP TABLE IF EXISTS comment_vote CASCADE;
DROP TABLE IF EXISTS request CASCADE;
DROP TABLE IF EXISTS follow_request CASCADE;
DROP TABLE IF EXISTS join_community_request CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS block_user CASCADE;
DROP TABLE IF EXISTS follow_user CASCADE;
DROP TABLE IF EXISTS community_member CASCADE;

CREATE TABLE "user" (
    id SERIAL PRIMARY KEY,
    username text UNIQUE NOT NULL,
    first_name text NOT NULL,
    last_name text NOT NULL,
    email text NOT NULL CONSTRAINT user_email_uk UNIQUE,
    password text NOT NULL,
    birthday DATE NOT NULL CONSTRAINT user_birthday_min check (birthday <= (CURRENT_DATE - interval '12' year )),
    gender genders NOT NULL,
    photo text,
    private boolean NOT NULL,
    credibility int DEFAULT 0
);

CREATE TABLE "admin" (
    id SERIAL PRIMARY KEY,
    username text UNIQUE NOT NULL,
    first_name text NOT NULL,
    last_name text NOT NULL,
    email text NOT NULL CONSTRAINT admin_email_uk UNIQUE,
    password text NOT NULL
);

CREATE TABLE community (
    id SERIAL PRIMARY KEY,
    name text UNIQUE NOT NULL,
    image text,
    private boolean NOT NULL,
    id_owner int REFERENCES "user"
);

CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    image text,
    title text NOT NULL,
    content text NOT NULL,
    "date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL, 
    upvotes int NOT NULL DEFAULT 0,
    downvotes int NOT NULL DEFAULT 0,
    id_author int REFERENCES "user",
    id_community int NOT NULL REFERENCES community ON DELETE CASCADE
);

CREATE TABLE comment (
    id SERIAL PRIMARY KEY,
    content text NOT NULL,
    "date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    upvotes int NOT NULL DEFAULT 0,
    downvotes int NOT NULL DEFAULT 0,
    id_author int REFERENCES "user",
    id_post int NOT NULL REFERENCES post ON DELETE CASCADE
);

CREATE TABLE post_vote (
    vote_type vote_types NOT NULL,
    id_user int REFERENCES "user" ON DELETE CASCADE,
    id_post int REFERENCES post ON DELETE CASCADE,
    PRIMARY KEY (id_user, id_post)
);

CREATE TABLE report (
    id SERIAL PRIMARY KEY,
    reason text NOT NULL,
    "date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    id_admin int NOT NULL REFERENCES "admin",
    id_user int NOT NULL REFERENCES "user"
);

CREATE TABLE comment_report (
    report_id int PRIMARY KEY REFERENCES report ON DELETE CASCADE,
    id_comment int NOT NULL REFERENCES comment ON DELETE CASCADE
);

CREATE TABLE post_report (
    report_id int PRIMARY KEY REFERENCES report ON DELETE CASCADE,
    id_post int NOT NULL REFERENCES post ON DELETE CASCADE
);

CREATE TABLE community_report (
    report_id int PRIMARY KEY REFERENCES report ON DELETE CASCADE,
    id_community int NOT NULL REFERENCES community ON DELETE CASCADE
);

CREATE TABLE reply (
    reply_comment int PRIMARY KEY REFERENCES comment ON DELETE CASCADE,
    parent_comment int NOT NULL REFERENCES comment ON DELETE CASCADE
);

CREATE TABLE comment_vote (
    vote_type vote_types NOT NULL,
    id_user int REFERENCES "user" ON DELETE CASCADE,
    comment_id int REFERENCES comment ON DELETE CASCADE,
    PRIMARY KEY (id_user, comment_id)
);

CREATE TABLE request (
    id SERIAL PRIMARY KEY,
    "date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    status status_types NOT NULL DEFAULT 'pending',
    id_receiver int NOT NULL REFERENCES "user" ON DELETE CASCADE,
    id_sender int NOT NULL REFERENCES "user" ON DELETE CASCADE
);

CREATE TABLE follow_request (
    id int PRIMARY KEY REFERENCES request ON DELETE CASCADE
);

CREATE TABLE join_community_request (
    id int PRIMARY KEY REFERENCES request ON DELETE CASCADE,
    id_community int NOT NULL REFERENCES community ON DELETE CASCADE
);

CREATE TABLE notification (
    id SERIAL PRIMARY KEY,
    "read" boolean NOT NULL DEFAULT FALSE,
    id_request int NOT NULL REFERENCES request ON DELETE CASCADE
);

CREATE TABLE block_user (
    blocked_user int REFERENCES "user" ON DELETE CASCADE,
    blocker_user int REFERENCES "user" ON DELETE CASCADE,
    PRIMARY KEY (blocked_user, blocker_user)
);

CREATE TABLE follow_user (
    id_follower int REFERENCES "user" ON DELETE CASCADE,
    id_followed int REFERENCES "user" ON DELETE CASCADE,
    PRIMARY KEY (id_follower, id_followed)
);

CREATE TABLE community_member (
    id_user int REFERENCES "user" ON DELETE CASCADE,
    id_community int REFERENCES community ON DELETE CASCADE,
    PRIMARY KEY (id_user, id_community)
);