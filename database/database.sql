-- Types
DROP TYPE IF EXISTS vote_types CASCADE;
CREATE TYPE vote_types AS ENUM ('up', 'down');

DROP TYPE IF EXISTS genders CASCADE;
CREATE TYPE genders AS ENUM ('male', 'female', 'other');

DROP TYPE IF EXISTS status_types CASCADE;
CREATE TYPE status_types AS ENUM ('accepted', 'denied', 'pending');


-- Tables
DROP TABLE IF EXISTS person CASCADE;
CREATE TABLE person
(
    id SERIAL PRIMARY KEY,
    username text UNIQUE NOT NULL,
    first_name text NOT NULL,
    last_name text NOT NULL,
    email text NOT NULL CONSTRAINT person_email_uk UNIQUE,
    password text NOT NULL
);

DROP TABLE IF EXISTS "user" CASCADE;
CREATE TABLE "user" (
    id int PRIMARY KEY REFERENCES person,
    birthday DATE NOT NULL CONSTRAINT user_birthday_min check (birthday <= (CURRENT_DATE - interval '12' year )),
    gender genders NOT NULL,
    photo text,
    private boolean NOT NULL,
    credibility int DEFAULT 0
);

DROP TABLE IF EXISTS "admin" CASCADE;
CREATE TABLE "admin" (
    id int PRIMARY KEY REFERENCES person
);

DROP TABLE IF EXISTS community CASCADE;
CREATE TABLE community (
    id SERIAL PRIMARY KEY,
    name text UNIQUE NOT NULL,
    image text,
    private boolean NOT NULL,
    id_owner int REFERENCES "user"
);

DROP TABLE IF EXISTS post CASCADE;
CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    image text,
    title text NOT NULL,
    content text NOT NULL,
    "date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL, 
    upvotes int NOT NULL DEFAULT 0,
    downvotes int NOT NULL DEFAULT 0,
    id_author int REFERENCES "user",
    id_community int NOT NULL REFERENCES community
);

DROP TABLE IF EXISTS comment CASCADE;
CREATE TABLE comment (
    id SERIAL PRIMARY KEY,
    content text NOT NULL,
    "date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    upvotes int NOT NULL DEFAULT 0,
    downvotes int NOT NULL DEFAULT 0,
    id_author int REFERENCES "user",
    id_post int NOT NULL REFERENCES post
);

DROP TABLE IF EXISTS post_vote CASCADE;
CREATE TABLE post_vote (
    vote_type vote_types NOT NULL,
    id_user int NOT NULL REFERENCES "user",
    id_post int NOT NULL REFERENCES post,
    PRIMARY KEY (id_user, id_post)
);

DROP TABLE IF EXISTS report CASCADE;
CREATE TABLE report (
    id SERIAL PRIMARY KEY,
    reason text NOT NULL,
    "date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    id_admin int NOT NULL REFERENCES "admin",
    id_user int NOT NULL REFERENCES "user"
);

DROP TABLE IF EXISTS comment_report CASCADE;
CREATE TABLE comment_report (
    report_id int PRIMARY KEY REFERENCES report,
    id_comment int NOT NULL REFERENCES comment
);

DROP TABLE IF EXISTS post_report CASCADE;
CREATE TABLE post_report (
    report_id int PRIMARY KEY REFERENCES report,
    id_post int NOT NULL REFERENCES post
);

DROP TABLE IF EXISTS community_report CASCADE;
CREATE TABLE community_report (
    report_id int PRIMARY KEY REFERENCES report,
    id_community int NOT NULL REFERENCES community
);

DROP TABLE IF EXISTS reply CASCADE;
CREATE TABLE reply (
    reply_comment int PRIMARY KEY REFERENCES comment,
    parent_comment int REFERENCES comment NOT NULL
);

DROP TABLE IF EXISTS comment_vote CASCADE;
CREATE TABLE comment_vote (
    vote_type vote_types NOT NULL,
    id_user int NOT NULL REFERENCES "user",
    comment_id int NOT NULL REFERENCES comment,
    PRIMARY KEY (id_user, comment_id)
);

DROP TABLE IF EXISTS request CASCADE;
CREATE TABLE request (
    id SERIAL PRIMARY KEY,
    "date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    status status_types NOT NULL DEFAULT 'pending',
    id_receiver int NOT NULL REFERENCES "user",
    id_sender int NOT NULL REFERENCES "user"
);

DROP TABLE IF EXISTS follow_request CASCADE;
CREATE TABLE follow_request (
    id int PRIMARY KEY REFERENCES request
);

DROP TABLE IF EXISTS join_community_request CASCADE;
CREATE TABLE join_community_request (
    id int PRIMARY KEY REFERENCES request,
    id_community int NOT NULL REFERENCES community
);

DROP TABLE IF EXISTS notification CASCADE;
CREATE TABLE notification (
    id SERIAL PRIMARY KEY,
    "read" boolean NOT NULL DEFAULT FALSE,
    id_request int NOT NULL REFERENCES request
);

DROP TABLE IF EXISTS block_user CASCADE;
CREATE TABLE block_user (
    blocked_user int REFERENCES "user" NOT NULL,
    blocker_user int REFERENCES "user" NOT NULL,
    PRIMARY KEY (blocked_user, blocker_user)
);

DROP TABLE IF EXISTS follow_user CASCADE;
CREATE TABLE follow_user (
    id_follower int REFERENCES "user" NOT NULL,
    id_followed int REFERENCES "user" NOT NULL,
    PRIMARY KEY (id_follower, id_followed)
);

DROP TABLE IF EXISTS community_member CASCADE;
CREATE TABLE community_member (
    id_user int REFERENCES "user" NOT NULL,
    id_community int REFERENCES community NOT NULL,
    PRIMARY KEY (id_user, id_community)
);
