-- Types
CREATE TYPE vote_types AS ENUM ('up', 'down');
CREATE TYPE genders AS ENUM ('male', 'female', 'other');
CREATE TYPE status_types AS ENUM ('accepted', 'denied', 'pending');

-- Tables
CREATE TABLE person
(
    id SERIAL PRIMARY KEY,
    username text UNIQUE NOT NULL,
    first_name text NOT NULL,
    last_name text NOT NULL,
    email text NOT NULL CONSTRAINT person_email_uk UNIQUE,
    password text NOT NULL
);

CREATE TABLE "user" (
    id int PRIMARY KEY REFERENCES person,
    birthday DATE NOT NULL CONSTRAINT user_birthday_min check (birthday <= (CURRENT_DATE - interval '12' year )),
    gender genders NOT NULL,
    photo text,
    private boolean NOT NULL,
    credibility int DEFAULT 0
);

CREATE TABLE "admin" (
    id int PRIMARY KEY REFERENCES person
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
    id_community int NOT NULL REFERENCES community
);

CREATE TABLE comment (
    id SERIAL PRIMARY KEY,
    content text NOT NULL,
    "date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    upvotes int NOT NULL DEFAULT 0,
    downvotes int NOT NULL DEFAULT 0,
    id_author int REFERENCES "user",
    id_post int NOT NULL REFERENCES post
);

CREATE TABLE post_vote (
    vote_type vote_types NOT NULL,
    id_user int NOT NULL REFERENCES "user",
    id_post int NOT NULL REFERENCES post,
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
    report_id int PRIMARY KEY REFERENCES report,
    id_comment int NOT NULL REFERENCES comment
);

CREATE TABLE post_report (
    report_id int PRIMARY KEY REFERENCES report,
    id_post int NOT NULL REFERENCES post
);

CREATE TABLE community_report (
    report_id int PRIMARY KEY REFERENCES report,
    id_community int NOT NULL REFERENCES community
);

CREATE TABLE reply (
    reply_comment int PRIMARY KEY REFERENCES comment, -- reply
    parent_comment int REFERENCES comment NOT NULL -- comment replied to
);

CREATE TABLE comment_vote (
    vote_type vote_types NOT NULL,
    id_user int NOT NULL REFERENCES "user",
    comment_id int NOT NULL REFERENCES comment,
    PRIMARY KEY (id_user, comment_id)
);

CREATE TABLE request (
    id SERIAL PRIMARY KEY,
    "date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    status status_types NOT NULL DEFAULT 'pending',
    id_receiver int NOT NULL REFERENCES "user",
    id_sender int NOT NULL REFERENCES "user"
);

CREATE TABLE follow_request (
    id int PRIMARY KEY REFERENCES request
);

CREATE TABLE join_community_request (
    id int PRIMARY KEY REFERENCES request,
    id_community int NOT NULL REFERENCES community
);

CREATE TABLE notification (
    id SERIAL PRIMARY KEY,
    "read" boolean NOT NULL DEFAULT FALSE,
    id_request int NOT NULL REFERENCES request
);

CREATE TABLE block_user (
    blocked_user int REFERENCES "user" NOT NULL,
    blocker_user int REFERENCES "user" NOT NULL,
    PRIMARY KEY (blocked_user, blocker_user)
);

CREATE TABLE follow_user (
    id_follower int REFERENCES "user" NOT NULL,
    id_followed int REFERENCES "user" NOT NULL,
    PRIMARY KEY (id_follower, id_followed)
);

CREATE TABLE community_member (
    id_user int REFERENCES "user" NOT NULL,
    id_community int REFERENCES community NOT NULL,
    PRIMARY KEY (id_user, id_community)
);

-----------------------
