-----------------------------------------
-- Drop old schmema
-----------------------------------------

DROP TABLE IF EXISTS member_user CASCADE;
DROP TABLE IF EXISTS admin_user CASCADE;
DROP TABLE IF EXISTS community CASCADE;
DROP TABLE IF EXISTS post CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS post_vote CASCADE;
DROP TABLE IF EXISTS report CASCADE;
DROP TABLE IF EXISTS user_report CASCADE;
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

DROP TYPE IF EXISTS vote_types CASCADE;
DROP TYPE IF EXISTS genders CASCADE;
DROP TYPE IF EXISTS status_types CASCADE;

DROP FUNCTION IF EXISTS block_user() CASCADE;
DROP FUNCTION IF EXISTS vote_on_comment() CASCADE;
DROP FUNCTION IF EXISTS vote_on_post() CASCADE;
DROP FUNCTION IF EXISTS create_notification() CASCADE;
DROP FUNCTION IF EXISTS check_comment_date() CASCADE;
DROP FUNCTION IF EXISTS post_not_member_community() CASCADE;
DROP FUNCTION IF EXISTS comment_not_member_community() CASCADE;
DROP FUNCTION IF EXISTS comment_vote_not_member_community() CASCADE;
DROP FUNCTION IF EXISTS post_vote_not_member_community() CASCADE;
DROP FUNCTION IF EXISTS change_community_privacy() CASCADE;
DROP FUNCTION IF EXISTS verify_pk_follow_request() CASCADE;
DROP FUNCTION IF EXISTS verify_pk_join_community_request() CASCADE;

DROP TRIGGER IF EXISTS block_user ON block_user;
DROP TRIGGER IF EXISTS vote_on_comment ON comment_vote;
DROP TRIGGER IF EXISTS vote_on_post ON post_vote;
DROP TRIGGER IF EXISTS create_notification ON request;
DROP TRIGGER IF EXISTS check_comment_date ON comment;
DROP TRIGGER IF EXISTS post_not_member_community ON post;
DROP TRIGGER IF EXISTS comment_not_member_community ON comment;
DROP TRIGGER IF EXISTS comment_vote_not_member_community ON comment_vote;
DROP TRIGGER IF EXISTS post_vote_not_member_community ON post_vote;
DROP TRIGGER IF EXISTS change_community_privacy ON member_user;
DROP TRIGGER IF EXISTS verify_pk_follow_request ON follow_resquest;
DROP TRIGGER IF EXISTS verify_pk_join_community_request ON join_community_resquest;


-----------------------------------------
-- Types
-----------------------------------------

CREATE TYPE vote_types AS ENUM ('up', 'down');
CREATE TYPE genders AS ENUM ('male', 'female', 'other');
CREATE TYPE status_types AS ENUM ('accepted', 'denied', 'pending');

-----------------------------------------
-- Tables
-----------------------------------------

CREATE TABLE member_user (
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
    credibility int DEFAULT 0,
	remember_token text,
	recover_pass_token text
);

CREATE TABLE admin_user (
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
    id_owner int REFERENCES member_user ON DELETE SET NULL
);

CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    image text,
    title text NOT NULL,
    content text NOT NULL,
    time_stamp TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL, 
    upvotes int NOT NULL DEFAULT 0,
    downvotes int NOT NULL DEFAULT 0,
    id_author int REFERENCES member_user ON DELETE SET NULL,
    id_community int NOT NULL REFERENCES community ON DELETE CASCADE
);

CREATE TABLE comment (
    id SERIAL PRIMARY KEY,
    content text NOT NULL,
    time_stamp TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    upvotes int NOT NULL DEFAULT 0,
    downvotes int NOT NULL DEFAULT 0,
    id_author int REFERENCES member_user ON DELETE SET NULL,
    id_post int NOT NULL REFERENCES post ON DELETE CASCADE
);

CREATE TABLE post_vote (
    vote_type vote_types NOT NULL,
    id_user int REFERENCES member_user ON DELETE CASCADE,
    id_post int REFERENCES post ON DELETE CASCADE,
    PRIMARY KEY (id_user, id_post)
);

CREATE TABLE report (
    id SERIAL PRIMARY KEY,
    reason text NOT NULL,
    time_stamp TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    id_admin int NOT NULL REFERENCES admin_user,
    id_user int NOT NULL REFERENCES member_user ON DELETE SET NULL
);

CREATE TABLE comment_report (
    id_report int PRIMARY KEY REFERENCES report ON DELETE CASCADE,
    id_comment int NOT NULL REFERENCES comment ON DELETE CASCADE
);

CREATE TABLE post_report (
    id_report int PRIMARY KEY REFERENCES report ON DELETE CASCADE,
    id_post int NOT NULL REFERENCES post ON DELETE CASCADE
);

CREATE TABLE community_report (
    id_report int PRIMARY KEY REFERENCES report ON DELETE CASCADE,
    id_community int NOT NULL REFERENCES community ON DELETE CASCADE
);

CREATE TABLE user_report (
    id_report int PRIMARY KEY REFERENCES report ON DELETE CASCADE,
    id_user int NOT NULL REFERENCES member_user ON DELETE CASCADE
);

CREATE TABLE reply (
    reply_comment int PRIMARY KEY REFERENCES comment ON DELETE CASCADE,
    parent_comment int NOT NULL REFERENCES comment ON DELETE CASCADE
);

CREATE TABLE comment_vote (
    vote_type vote_types NOT NULL,
    id_user int REFERENCES member_user ON DELETE CASCADE,
    id_comment int REFERENCES comment ON DELETE CASCADE,
    PRIMARY KEY (id_user, id_comment)
);

CREATE TABLE request (
    id SERIAL PRIMARY KEY,
    time_stamp TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    status status_types NOT NULL DEFAULT 'pending',
    id_receiver int NOT NULL REFERENCES member_user ON DELETE CASCADE,
    id_sender int NOT NULL REFERENCES member_user ON DELETE CASCADE
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
    is_read boolean NOT NULL DEFAULT FALSE,
    id_request int UNIQUE NOT NULL REFERENCES request ON DELETE CASCADE
);

CREATE TABLE block_user (
    blocked_user int REFERENCES member_user ON DELETE CASCADE,
    blocker_user int REFERENCES member_user ON DELETE CASCADE,
    PRIMARY KEY (blocked_user, blocker_user)
);

CREATE TABLE follow_user (
    id_follower int REFERENCES member_user ON DELETE CASCADE,
    id_followed int REFERENCES member_user ON DELETE CASCADE,
    PRIMARY KEY (id_follower, id_followed)
);

CREATE TABLE community_member (
    id_user int REFERENCES member_user ON DELETE CASCADE,
    id_community int REFERENCES community ON DELETE CASCADE,
    PRIMARY KEY (id_user, id_community)
);

-----------------------------------------
-- INDEXES
-----------------------------------------

CREATE INDEX post_author_index ON post USING hash(id_author);

CREATE INDEX admin_index ON report USING hash(id_admin);

CREATE INDEX post_index ON comment USING hash(id_post);

CREATE INDEX post_search_index ON post
USING GIST ((setweight(to_tsvector('portuguese', title),'A') || 
       setweight(to_tsvector('portuguese', content), 'B')));

CREATE INDEX comment_search_index ON comment
USING GIST (to_tsvector('portuguese', content));

CREATE INDEX community_search_index ON community
USING GIST (to_tsvector('portuguese',name));


-----------------------------------------
-- TRIGGERS and UDFs
-----------------------------------------

CREATE FUNCTION block_user() RETURNS TRIGGER AS 
$BODY$ 
BEGIN 
	IF EXISTS (
		SELECT *
			FROM follow_user 
			WHERE NEW.blocked_user = id_follower AND NEW.blocker_user = id_followed)  
		THEN
			DELETE
				FROM follow_user
				WHERE NEW.blocked_user = id_follower AND NEW.blocker_user = id_followed; 
	END IF;
	
	IF EXISTS (
			SELECT *
			FROM follow_user 
	 		WHERE NEW.blocker_user = id_follower AND NEW.blocked_user = id_followed)  
		THEN 
			DELETE
				FROM follow_user
				WHERE NEW.blocker_user = id_follower AND NEW.blocked_user = id_followed; 
	END IF;

	RETURN NEW; 
END 
$BODY$ 
LANGUAGE plpgsql;

CREATE TRIGGER block_user 
	AFTER INSERT ON block_user 
	FOR EACH ROW 
	EXECUTE PROCEDURE block_user();


CREATE FUNCTION vote_on_comment() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
		SELECT *
			FROM comment
			WHERE NEW.id_comment = comment.id AND NEW.id_user = comment.id_author)
		THEN
			RAISE EXCEPTION 'A user cannot vote on their own comments.';
	ELSIF NEW.vote_type = 'up'
	THEN
		UPDATE comment
			SET upvotes = upvotes + 1
			WHERE id = NEW.id_comment;
	ELSIF NEW.vote_type = 'down'
	THEN
		UPDATE comment
			SET downvotes = downvotes + 1
			WHERE id = NEW.id_comment;
    END IF;
	
	UPDATE member_user 
		SET credibility = credibility + sqrt(abs(subquery.upvotes - subquery.downvotes)) * sign(subquery.upvotes - subquery.downvotes) 
		FROM 
			(SELECT comment.id_author AS author, comment.upvotes AS upvotes, comment.downvotes AS downvotes
				FROM comment
				WHERE comment.id = NEW.id_comment) AS subquery 
		WHERE member_user.id = subquery.author;
	
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER vote_on_comment
    AFTER INSERT ON comment_vote
    FOR EACH ROW
    EXECUTE PROCEDURE vote_on_comment();


CREATE FUNCTION vote_on_post() RETURNS TRIGGER AS
$BODY$
BEGIN
	IF TG_OP = 'INSERT'
	THEN IF EXISTS (
		SELECT *
			FROM post
			WHERE NEW.id_post = post.id AND NEW.id_user = post.id_author )
		THEN
			RAISE EXCEPTION 'A user cannot vote on their own posts.';
		END IF;
        
	ELSIF TG_OP = 'UPDATE' OR TG_OP = 'DELETE'
	THEN IF OLD.vote_type = 'up'
		THEN
			UPDATE post
				SET upvotes = upvotes - 1
				WHERE id = OLD.id_post;
	ELSIF OLD.vote_type = 'down'
		THEN
			UPDATE post
				SET downvotes = downvotes - 1
				WHERE id = OLD.id_post;
    	END IF;
	END IF;
	
	IF NEW.vote_type = 'up'
	THEN
		UPDATE post
			SET upvotes = upvotes + 1
			WHERE id = NEW.id_post;
	ELSIF NEW.vote_type = 'down'
	THEN
		UPDATE post
			SET downvotes = downvotes + 1
			WHERE id = NEW.id_post;
    END IF;
	
	UPDATE member_user
		SET credibility = credibility + sqrt(abs(subquery.upvotes - subquery.downvotes)) * sign(subquery.upvotes - subquery.downvotes) 
		FROM (
			SELECT post.id AS post_id, post.id_author AS author, post.upvotes AS upvotes, post.downvotes AS downvotes
				FROM post
				WHERE post.id = NEW.id_post) AS subquery
		WHERE member_user.id = subquery.author;
	
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER vote_on_post
    AFTER INSERT OR UPDATE OR DELETE ON post_vote
    FOR EACH ROW
    EXECUTE PROCEDURE vote_on_post();


CREATE FUNCTION create_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO notification (is_read, id_request) VALUES (FALSE, NEW.id);

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER create_notification
    AFTER INSERT ON request
    FOR EACH ROW
    EXECUTE PROCEDURE create_notification();

CREATE FUNCTION check_comment_date() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
		SELECT *
			FROM post
			WHERE NEW.id_post = id AND NEW.time_stamp < time_stamp)
		THEN
			RAISE EXCEPTION 'The comment''s date must be after the post''s date. %', New.id_post ;
	END IF;
	
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER check_comment_date
    AFTER INSERT ON comment
    FOR EACH ROW
    EXECUTE PROCEDURE check_comment_date();
	

CREATE FUNCTION post_not_member_community() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
		SELECT *
			FROM member_user, community
			WHERE NEW.id_community = community.id AND community.private = TRUE AND NEW.id_author = member_user.id AND 
			NOT EXISTS (
				SELECT *
					FROM community_member CM
					WHERE CM.id_user = NEW.id_author AND CM.id_community = NEW.id_community))
				THEN
					RAISE EXCEPTION 'The user cannot post in/on this community % % %', NEW.id, NEW.id_community, NEW.id_author;
	END IF;

	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER post_not_member_community
    AFTER INSERT ON post
    FOR EACH ROW
    EXECUTE PROCEDURE post_not_member_community();
	

CREATE FUNCTION comment_not_member_community() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS 
		(SELECT * FROM member_user, community, post
	 	 WHERE NEW.id_post = post.id AND post.id_community = community.id AND community.private = TRUE AND NEW.id_author = member_user.id AND 
		 NOT EXISTS(SELECT * FROM community_member CM WHERE CM.id_user = NEW.id_author AND CM.id_community = community.id)) THEN
	   RAISE EXCEPTION 'The user cannot comment on this post because you''re not member of the community % % %', NEW.id, NEW.id_post, NEW.id_author;
	END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER comment_not_member_community
    AFTER INSERT ON comment
    FOR EACH ROW
    EXECUTE PROCEDURE comment_not_member_community();


CREATE FUNCTION comment_vote_not_member_community() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS 
		(SELECT * FROM member_user, community, post, comment
	 	 WHERE NEW.id_comment = comment.id AND comment.id_post = post.id AND post.id_community = community.id AND community.private = TRUE AND NEW.id_user = member_user.id AND 
		 NOT EXISTS(SELECT * FROM community_member CM WHERE CM.id_user = NEW.id_user AND CM.id_community = community.id)) THEN
	   RAISE EXCEPTION 'The user cannot vote on this comment because you''re not member of the community % %', NEW.id_user, NEW.id_comment;
	END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER comment_vote_not_member_community
    AFTER INSERT ON comment_vote
    FOR EACH ROW
    EXECUTE PROCEDURE comment_vote_not_member_community();


CREATE FUNCTION post_vote_not_member_community() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
		SELECT *
		FROM member_user, community, post
	 	 	WHERE NEW.id_post = post.id AND post.id_community = community.id AND community.private = TRUE AND NEW.id_user = member_user.id AND 
		 	NOT EXISTS (
				SELECT *
					FROM community_member CM
					WHERE CM.id_user = NEW.id_user AND CM.id_community = community.id))
				THEN
					RAISE EXCEPTION 'The user cannot vote on this post because you''re not member of the community % %', NEW.id_user, NEW.id_post;
	END IF;

	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER post_vote_not_member_community
    AFTER INSERT ON post_vote
    FOR EACH ROW
    EXECUTE PROCEDURE post_vote_not_member_community();


CREATE FUNCTION verify_pk_follow_request() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
		SELECT *
			FROM join_community_request
			WHERE id = NEW.id)
		THEN
			RAISE EXCEPTION 'A follow request cannot be associated with the same request as a join community request.';
	END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER verify_pk_follow_request
    AFTER INSERT ON follow_request
    FOR EACH ROW
    EXECUTE PROCEDURE verify_pk_follow_request();
	

CREATE FUNCTION verify_pk_join_community_request() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
		SELECT *
			FROM follow_request
			WHERE id = NEW.id)
		THEN 
			RAISE EXCEPTION 'A join community request cannot be associated with the same request as a follow request.';
	END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER verify_pk_join_community_request
    AFTER INSERT ON join_community_request
    FOR EACH ROW
    EXECUTE PROCEDURE verify_pk_join_community_request();
	
CREATE FUNCTION change_community_privacy() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE community
		SET private = FALSE
		WHERE id_owner = OLD.id AND private = TRUE;
	RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER change_community_privacy
    BEFORE DELETE ON member_user
    FOR EACH ROW
    EXECUTE PROCEDURE change_community_privacy();

-----------------------------------------
-- end
-----------------------------------------