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
DROP FUNCTION IF EXISTS updateSearch() CASCADE;


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
DROP TRIGGER IF EXISTS search_weight ON post;


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
    password text NOT NULL,
	remember_token text
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
    id_community int NOT NULL REFERENCES community ON DELETE CASCADE,
	search_weight tsvector
);

CREATE TABLE comment (
    id SERIAL PRIMARY KEY,
    content text NOT NULL,
    time_stamp TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    upvotes int NOT NULL DEFAULT 0,
    downvotes int NOT NULL DEFAULT 0,
    id_author int REFERENCES member_user ON DELETE SET NULL,
    id_post int NOT NULL REFERENCES post ON DELETE CASCADE,
	id_parent int REFERENCES comment ON DELETE CASCADE
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
    id_user int REFERENCES member_user ON DELETE SET NULL,
	reportable_id int,
	reportable_type text
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
    id_sender int NOT NULL REFERENCES member_user ON DELETE CASCADE,
	requestable_id int,
	requestable_type text
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

CREATE INDEX search_post ON post USING GIN(search_weight);


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
declare commentID INT;
declare authorID INT;
BEGIN
	IF TG_OP = 'INSERT' THEN
		commentID := NEW.id_comment;
		SELECT comment.id_author into authorID FROM comment WHERE NEW.id_comment = comment.id;
		IF EXISTS (
		SELECT *
			FROM comment
			WHERE NEW.id_comment = comment.id AND NEW.id_user = comment.id_author)
		THEN
			RAISE EXCEPTION 'A user cannot vote on their own comments.';
		END IF;
	
	ELSIF TG_OP = 'UPDATE' OR TG_OP = 'DELETE' THEN 
		commentID := OLD.id_comment;
		SELECT comment.id_author into authorID FROM comment WHERE OLD.id_comment = comment.id;
		IF OLD.vote_type = 'up' THEN
			UPDATE comment
				SET upvotes = upvotes - 1
				WHERE id = OLD.id_comment;
		ELSIF OLD.vote_type = 'down' THEN
			UPDATE comment
				SET downvotes = downvotes - 1
				WHERE id = OLD.id_comment;
		END IF;
	END IF;
	
	IF TG_OP = 'UPDATE' OR TG_OP = 'INSERT' THEN
		IF NEW.vote_type = 'up'
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
	END IF;

	UPDATE member_user
		SET credibility = sqrt(abs(subquery.upvotes - subquery.downvotes)) * sign(subquery.upvotes - subquery.downvotes)
		FROM (
			SELECT sum(sub.upvotes) AS upvotes, sum(sub.downvotes) AS downvotes FROM ( 
				(SELECT post.id_author AS id_author, sum(post.upvotes) AS upvotes, sum(post.downvotes) AS downvotes
					FROM post
					WHERE post.id_author = authorID
					GROUP BY post.id_author)
				UNION ALL
 				(SELECT comment.id_author AS id_author, sum(comment.upvotes) AS upvotes, sum(comment.downvotes) AS downvotes
					FROM comment
					WHERE comment.id_author = authorID
					GROUP BY comment.id_author)) AS sub

 			GROUP BY id_author) AS subquery
		WHERE member_user.id = authorID;
	
    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER vote_on_comment
    AFTER INSERT OR UPDATE OR DELETE ON comment_vote
    FOR EACH ROW
    EXECUTE PROCEDURE vote_on_comment();


CREATE FUNCTION vote_on_post() RETURNS TRIGGER AS
$BODY$
declare postID INT;
declare authorID INT;
BEGIN
	IF TG_OP = 'INSERT' THEN
		postID := NEW.id_post;
		SELECT post.id_author into authorID FROM post WHERE NEW.id_post = post.id;
		IF EXISTS (
		SELECT *
			FROM post
			WHERE NEW.id_post = post.id AND NEW.id_user = post.id_author )
		THEN
			RAISE EXCEPTION 'A user cannot vote on their own posts.';
		END IF;
        
	ELSIF TG_OP = 'UPDATE' OR TG_OP = 'DELETE' THEN
		postID := OLD.id_post;
		SELECT post.id_author into authorID FROM post WHERE OLD.id_post = post.id;
		IF OLD.vote_type = 'up' THEN
			UPDATE post
				SET upvotes = upvotes - 1
				WHERE id = OLD.id_post;
		ELSIF OLD.vote_type = 'down' THEN
			UPDATE post
				SET downvotes = downvotes - 1
				WHERE id = OLD.id_post;
		END IF;
	END IF;
	
	IF TG_OP = 'UPDATE' OR TG_OP = 'INSERT' THEN
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
	END IF;
	
	UPDATE member_user
		SET credibility = sqrt(abs(subquery.upvotes - subquery.downvotes)) * sign(subquery.upvotes - subquery.downvotes)
		FROM (
			SELECT sum(sub.upvotes) AS upvotes, sum(sub.downvotes) AS downvotes FROM ( 
				(SELECT post.id_author AS id_author, sum(post.upvotes) AS upvotes, sum(post.downvotes) AS downvotes
					FROM post
					WHERE post.id_author = authorID
					GROUP BY post.id_author)
				UNION ALL
 				(SELECT comment.id_author AS id_author, sum(comment.upvotes) AS upvotes, sum(comment.downvotes) AS downvotes
					FROM comment
					WHERE comment.id_author = authorID
					GROUP BY comment.id_author)) AS sub

 			GROUP BY id_author) AS subquery
		WHERE member_user.id = authorID;
	
    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER vote_on_post
    AFTER INSERT OR UPDATE OR DELETE ON post_vote
    FOR EACH ROW
    EXECUTE PROCEDURE vote_on_post(); 


-- SELECT sum(post.upvotes) AS upvotes
-- 				FROM post, member_user
-- 				WHERE post.id_author = 2
-- 				GROUP BY member_user.id
-- UNION	
-- SELECT sum(comment.upvotes) AS upvotes
-- 				FROM comment, member_user
-- 				WHERE comment.id_author = 2
-- 				GROUP BY member_user.id
-- 				;


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


CREATE FUNCTION updateSearch() RETURNS TRIGGER AS
$BODY$
BEGIN
	UPDATE post SET search_weight =
	setweight(to_tsvector(coalesce(new.title,'')), 'A') ||
	setweight(to_tsvector(coalesce(new.content,'')), 'B')
	WHERE post.id = new.id;
	RETURN new;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER search_weight
    AFTER INSERT ON post
    FOR EACH ROW
    EXECUTE PROCEDURE updateSearch();

-----------------------------------------
-- end
-----------------------------------------

-----------------------------------------
-- Populate the database
-----------------------------------------

INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('miguelGomes2', 'Miguel', 'Gomes', 'miguelGomes12@gmail.com','op[pjljkbfr','1999-10-21','male', 'img/avatar_male.png', FALSE, 2);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('SilvoMaria', 'Silvino', 'Martins', 'silvinitoMaria@gmail.com','uhgcvuili','1991-12-01','male', 'img/avatar_male.png', True, 0);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('augustoSilva1', 'Augusto', 'Silva', 'augustosilva_1@gmail.com','oilhiyhg','1986-04-23','male', 'img/avatar_male.png', FALSE, 230);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('mariaAlegre', 'Maria', 'Alegria', 'mariaAlegria10@gmail.com','llhitfrr','1999-06-15','female', 'img/avatar_female.png', FALSE, 592);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('MarianaAlecrim1', 'Mariana', 'Alecrim', 'marianocasAlecrim@gmail.com','uhjsdcdf','1998-02-11','female', 'img/avatar_female.png', TRUE, 210);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('MariaZequinha', 'Maria', 'Gomes', 'zequinhafeliz@gmail.com','lfigigkj','1988-12-05','female', 'img/avatar_female.png', FALSE, 10);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('Josefina132', 'Josefina', 'Marques', 'josefinamarques1@hotmail.com','ugkdhtgiy','1996-03-12','female', 'img/avatar_female.png', True, 335);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('AlbertoMartim', 'Alberto', 'Martins', 'albertofelizberto@yahoo.com','dykghjjhk','1999-05-21','male', 'img/avatar_male.png', FALSE, 20);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('DavidMati', 'David', 'Matias', 'davidmati88@hotmail.com','kb.kjb/l','1978-01-21','male', 'img/avatar_male.png', FALSE, 300);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('HenryMati', 'Henrique', 'Matilde', 'henrmaty1@gmail.com','cgtuyp','1999-03-21','male', 'img/avatar_male.png', FALSE, 290);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('fernandaAg', 'Fernanda', 'Agueiro', 'ferrAgueiro@gmail.com','hukhuded','1999-04-21','female', 'img/avatar_female.png', FALSE, 20);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('PatiFeia', 'Patricia', 'Faial', 'patyFaial@outlook.pt','uhivjllu','1994-06-21','female', 'img/avatar_female.png', FALSE, 532);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('Jorginho', 'Jorge', 'Santos', 'jorginhoFute@gmail.com','kftkuyku','1992-08-21','male', 'img/avatar_male.png', FALSE, 256);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('JoaoRatao', 'Joao', 'Almirante', 'almirantejoao@gmail.com','ckuftuyl','1989-07-21','male', 'img/avatar_male.png', FALSE, 550);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('chiliMartins', 'Francisca', 'Martins', 'chicaMartins42@gmail.com','ugliujkl','1987-09-21','female', 'img/avatar_female.png', True, 120);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('luisinha12', 'Luisa', 'Almeida', 'luisinhalmeida@gmail.com','jdryjytj','1982-10-21', 'female', 'img/avatar_female.png', FALSE, 400);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('InesMarques1', 'Ines', 'Marques', 'inezinha134@gmail.com','safggttw','1997-11-21', 'female', 'img/avatar_female.png', FALSE, 430);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('Manuelsilvino', 'Manuel', 'Silvino', 'chicoSilvino@gmail.com','jyjfdsd','1987-01-21','male', 'img/avatar_male.png', FALSE, 450);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('andreLopes2', 'Andre', 'Lopes', 'andrescuteiro@outlook.pt','44fgfeA','1979-12-21','male', 'img/avatar_male.png', True, 554);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('PedroFute', 'Pedro', 'Firmino', 'pedroFirmino23@gmail.com','GUYIHJLP9','1981-05-21','male', 'img/avatar_male.png', FALSE, 473);
INSERT INTO member_user (username, first_name, last_name, email, password, birthday, gender, photo, private, credibility) VALUES ('testUser', 'Test', 'User', 'testUser@fe.up.pt','$2y$10$7NH/HcO0j.PDdMQQ62fzZe1917dwe4OJ5JUI95MolRb/9fc.w2uKK','1999-01-01','male', 'img/avatar_male.png', FALSE, 0);
 
INSERT INTO admin_user (username, first_name, last_name, email, password) VALUES ('mafaldaSntos', 'Mafalda', 'Santos', 'mafaldaSantos@gmail.com', 'admin1');
INSERT INTO admin_user (username, first_name, last_name, email, password) VALUES ('joaoLuz', 'Joao', 'Luz', 'joaoLuz@gmail.com', 'admin2');
INSERT INTO admin_user (username, first_name, last_name, email, password) VALUES ('diogoSilva',  'Diogo', 'Silva', 'diogoSilva@gmail.com', 'admin3');
INSERT INTO admin_user (username, first_name, last_name, email, password) VALUES ('lilianaAlmeida',  'Liliana', 'Almeida', 'lilianaAlmeida@gmail.com', '$2y$10$7NH/HcO0j.PDdMQQ62fzZe1917dwe4OJ5JUI95MolRb/9fc.w2uKK');
 
INSERT INTO community (name, image, private, id_owner) VALUES ('FEUP','img/default_community.jpg', FALSE, 2);
INSERT INTO community (name, image, private, id_owner) VALUES ('UPorto','img/default_community.jpg', FALSE, 5);
INSERT INTO community (name, image, private, id_owner) VALUES ('UNL','img/default_community.jpg', FALSE, 8);
INSERT INTO community (name, image, private, id_owner) VALUES ('FMUP','img/default_community.jpg', FALSE, 12);
INSERT INTO community (name, image, private, id_owner) VALUES ('FAP','img/default_community.jpg', TRUE, 15);

INSERT INTO community_member (id_user, id_community) VALUES (1,1);
INSERT INTO community_member (id_user, id_community) VALUES (3,1);
INSERT INTO community_member (id_user, id_community) VALUES (4,1);
INSERT INTO community_member (id_user, id_community) VALUES (5,1);
INSERT INTO community_member (id_user, id_community) VALUES (6,1);
INSERT INTO community_member (id_user, id_community) VALUES (7,1);
INSERT INTO community_member (id_user, id_community) VALUES (8,1);
INSERT INTO community_member (id_user, id_community) VALUES (9,1);
INSERT INTO community_member (id_user, id_community) VALUES (10,1);
INSERT INTO community_member (id_user, id_community) VALUES (11,2);
INSERT INTO community_member (id_user, id_community) VALUES (12,2);
INSERT INTO community_member (id_user, id_community) VALUES (13,2);
INSERT INTO community_member (id_user, id_community) VALUES (14,2);
INSERT INTO community_member (id_user, id_community) VALUES (15,2);
INSERT INTO community_member (id_user, id_community) VALUES (16,2);
INSERT INTO community_member (id_user, id_community) VALUES (17,2);
INSERT INTO community_member (id_user, id_community) VALUES (18,3);
INSERT INTO community_member (id_user, id_community) VALUES (19,3);
INSERT INTO community_member (id_user, id_community) VALUES (20,3);
INSERT INTO community_member (id_user, id_community) VALUES (1,3);
INSERT INTO community_member (id_user, id_community) VALUES (2,3);
INSERT INTO community_member (id_user, id_community) VALUES (3,3);
INSERT INTO community_member (id_user, id_community) VALUES (4,3);
INSERT INTO community_member (id_user, id_community) VALUES (5,3);
INSERT INTO community_member (id_user, id_community) VALUES (6,4);
INSERT INTO community_member (id_user, id_community) VALUES (7,4);
INSERT INTO community_member (id_user, id_community) VALUES (8,4);
INSERT INTO community_member (id_user, id_community) VALUES (9,4);
INSERT INTO community_member (id_user, id_community) VALUES (10,4);
INSERT INTO community_member (id_user, id_community) VALUES (11,4);
INSERT INTO community_member (id_user, id_community) VALUES (12,4);
INSERT INTO community_member (id_user, id_community) VALUES (13,4);
INSERT INTO community_member (id_user, id_community) VALUES (13,5);
INSERT INTO community_member (id_user, id_community) VALUES (14,5);
INSERT INTO community_member (id_user, id_community) VALUES (15,5);
INSERT INTO community_member (id_user, id_community) VALUES (16,5);
 
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Estudantes desafiam país a conversar à janela com idosos','Parece uma cena de um filme ou um regresso ao passado, mas as conversas à janela ou à varanda podem ajudar quem está sozinho em casa. E tudo pode começar com um “ó vizinha!”. Recuperar uma antiga prática comunitária de comunicação com vizinhos e amigos à janela ou à varanda é o objectivo da campanha lançada por alunos do Instituto Politécnico de Viana do Castelo (IPVC) para contrariar o “isolamento” de idosos. “Trata-se de uma acção de proximidade e apoio às pessoas mais velhas que vivem, neste momento, sozinhas ou com contacto limitado com os seus familiares e amigos, na sequência do estado de emergência decretado em Portugal”, explica o IPVC numa nota enviada às redacções.', '2020-03-28',500, 56, 6, 2);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Universidade do Porto ajuda-te a fazer exercício físico à distância', 'Mesmo ficando em casa, deves continuar a manter-te activo e saudável. A pensar nisso, o Centro de Desporto da Universidade do Porto (CDUP-UP) lançou um conjunto de aulas de grupo online para promover a actividade física.', '2020-02-12',50, 43, 1, 1);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Politécnicos juntam-se e criam dois protótipos de ventiladores', 'Os Politécnicos de Viseu e Leiria anunciaram este domingo que desenvolveram, no espaço de uma semana, com apoio de uma rede, dois protótipos de ventiladores para tentar dar resposta à escassez destes equipamentos, face à pandemia da covid-19.', '2020-01-30',340, 322, 2, 2);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('E se o mundo acabasse amanhã?', 'Os teus medos teriam de se anular em prol do tempo que te restava. A tua ânsia por descobrir o propósito deste fim teria de ser inferior ao amor que te resta. O pânico seria estrondosamente maior que o provocado pelo noticiário e o ser humano beberia da sua própria efemeridade.', '2020-01-06',120, 23, 3, 3);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Como fazer melhorias com os Exames Nacionais?', '“Eu acabei o secundário no ano letivo 2018/2019, ou seja, estou no 1º ano da faculdade. Entrei em enfermagem mas vou repetir os exames para voltar a tentar entrar em medicina. A minha dúvida prende-se com o seguinte: ainda consigo melhorar a minha CFD através dos exames (BG e FQA)? Ouvi dizer que como tinha feito as disciplinas há 2 anos já não dava…”', '2019-12-21',140, 122, 4, 4);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('A (falsa) Evolução', 'Hoje encontramo-nos no BOOM digital mas nem sempre foi assim. Considero que antigamente era mais fácil encontrar um trabalho através do jornal do que nos dias de hoje, pois o papel era a única ferramenta de trabalho. Atualmente sabemos que a Era Tecnológica trouxe imensas noções positivas à sociedade do século XXI, para além dessas noções acrescentou profissões cujo o nome é um palavrão ao qual poucas pessoas mencionam, pois não se sentem confortáveis em expressar tal sem uma formação primeiro.', '2019-11-13',514, 152, 16, 5);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Deixei uma disciplina para trás, e agora?', 'Em 2016, fui ao fundo do poço. Reprovei no exame nacional de Física e Química A com 8,5 valores. Lembro-me como se fosse hoje, fui à escola olhei para a pauta e disse para mim mesma “isto não me aconteceu”. As candidaturas para a faculdade começaram, todos os meus amigos falavam sobre a faculdade, sobre quais eram as suas opções. Tentei afastar-me . Fugir da realidade nua e crua que tinha falhado. Falhado comigo. Com aquilo que eu tinha idealizado para mim.', '2020-02-08',10, 132, 6, 1);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('1.500 bolsas para quem vá estudar para o Interior', 'Manuel Heitor disse à agência Lusa que o programa vai ser lançado no Conselho de Ministros de quinta-feira, que se realiza em Bragança, começa a ser aplicado em 2020 e vai-se prolongar pela legislatura. “Sobretudo facilitar mais bolsas para estudantes de todo o país virem fazer trabalhos, projetos finais de curso, teses de mestrado ou doutoramento sobre o Interior de Portugal”, afirmou.', '2020-03-09',230, 157, 7, 2);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Não entrei em primeira fase nem no curso que eu queria…', 'Neste momento sou uma estudante de Mestrado, na área que me faz feliz e numa faculdade de excelência na área. Mas custou chegar até aqui, aliás continua a custar, não deixa de ser necessário todo o esforço. Com esperança que a minha história possa vir a influenciar e a ajudar alguém que passe pelo mesmo e acredito que cada vez mais isto vai acontecer uma vez que as médias de entrada não deixam de subir. Sempre tive na cabeça muito claro que queria seguir as áreas económicas, das ciências económicas.', '2019-12-15',122, 256, 8, 3);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Pré-requisitos 2020', 'Estão desde ontem disponíveis as informações relativas aos Pré-requisitos para a candidatura ao ensino superior 2020/2021. Caso concorras a determinados cursos é importante que leias com atenção esta informação, ou mesmo que realizes as provas de ingresso pedidas, verás a tua candidatura anulada. Mas vamos por partes.', '2020-03-26',511, 12, 9, 4);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('O meu Ano Sabático não está a funcionar', 'Desde muito nova que sempre tive uma grande ambição em ser uma pessoa bem sucedida, queria mudar o mundo dentro do que pudesse e ser uma melhor versão de mim mesma a cada dia, mas, com o passar do tempo, fui percebendo que nada era tão fácil quanto eu achava ser. Comecei a criar um pânico enorme da decisão do curso para onde queria ir, porque isso iria decidir o meu futuro e se seria ou não bem sucedida.', '2019-01-02',343, 36, 14, 5);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Carta à praxe', 'Olá Praxe,Queria desde já pedir desculpa por todos aqueles que te julgam sem nunca te terem conhecido. Desculpa por todas as tragédias que dizem vir assinadas por ti e por todos aqueles que sofreram contigo mas na verdade sofreram às mãos de quem usava o teu nome para praticar o mal. Desculpa por quem não honra o negro que tu lhes concedestes. Devo-te muito sabes? Por ti cresci e evolui. Aos 18 anos era uma miúda assustada, sozinha numa cidade que me era vagamente conhecida, e do nada conheço-te. És daquelas coisa que primeiro estranha-se e depois entranha-se. De início não éramos lá muito amigas…', '2020-01-14',432, 16, 11, 1);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Novo semestre, nova atitude', '2019 Foi O ano da mudança, o ano em que finalmente comecei a investir no meu futuro e na área em que realmente sonho vir a trabalhar um dia. Se foi fácil descobrir o que queria fazer no futuro? Não, de todo, mas com o passar do tempo consegui conciliar as duas áreas que me são mais atrativas, a comunicação e as empresas.', '2020-02-29',278, 39, 12, 2);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Os custos da Educação grátis', 'Um assunto que tem sido falado por aqui e ali, embora não seja muito desejado que se fale, é a falência mortífera do Serviço Nacional de Saúde. Mas não é só a saúde que está a falhar de forma catastrófica às mãos da má gestão do aparelho estatal! O meu nome é Guilherme Alexandre, sou um aluno de 12° ano de uma escola pública e eis o que tenho a dizer sobre o nosso Sistema de Ensino – não se preocupem, não vai ser um texto a reclamar dos exames.', '2019-11-30',237, 1, 13, 3);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Universidade de Coimbra e Politécnico de Setúbal cedem equipamento informático a alunos carenciados', 'A Universidade de Coimbra (UC) está a ceder equipamentos informáticos aos alunos carenciados que não os possuam, para não serem prejudicados com a suspensão das aulas presenciais face à pandemia da Covid-19, foi esta sexta-feira anunciado. “Com o objetivo de que nenhum estudante seja prejudicado devido à suspensão das atividades letivas presenciais em vigor, a Universidade de Coimbra está a ceder equipamentos informáticos para acesso ao ensino à distância a todos os seus alunos que não os possuam e estejam comprovadamente numa situação de carência económica”, anunciou hoje a UC, em nota de imprensa enviada à agência Lusa. Segundo a nota, todos os estudantes bolseiros e beneficiários do Fundo de Apoio Social da Universidade de Coimbra que não disponham de meios para seguir o ensino à distância “devem manifestar a sua necessidade junto dos Serviços de Ação Social da Universidade de Coimbra”.', '2020-02-28',198, 2, 14, 4);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Universidades mantêm propinas, mas garantem apoio a estudantes', 'Os reitores das universidades vão manter o pagamento das propinas, mas garantem que estão atentos e preparados para apoiar os estudantes que possam vir a ser financeiramente afectados pelos efeitos da pandemia da covid-19. Esta quarta-feira, 25 de Março, os reitores das universidades, assim como os presidentes dos institutos politécnicos, estiveram durante toda a tarde reunidos a partilhar experiências e medidas que estão a lançar para tentar minimizar os impactos da pandemia da covid-19 e do isolamento social em que vive actualmente a comunidade estudantil e todo o país.', '2020-01-12',178, 34, 15, 5);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Já é possível simular o tratamento de Covid-19 em pacientes virtuais', 'Com base nas fortes medidas de contingência no combate à COVID-19, implementadas nos mais diversos países do mundo, os profissionais de saúde estão sob elevada pressão para diagnosticarem e tratarem de forma eficiente e célere pacientes com sinais e sintomas suspeitos. Como resposta a esta situação extraordinária, a Take The Wind (TTW), empresa criadora da plataforma Body Interact – simulador de pacientes virtuais com vista ao desenvolvimento do raciocínio clínico e tomada de decisão – está a disponibilizar cenários clínicos com pacientes virtuais suspeitos do novo coronavírus para uso gratuito e ilimitado por estudantes e profissionais de saúde. Os cenários estão disponíveis no link: covid19.bodyinteract.com ', '2020-03-15',12, 125, 16, 1);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Afinal o prazo de inscrições nos Exames nacionais vai ser prolongado. ', 'O Ministério da Educação prolongou até 3 de Abril o prazo para a inscrição nos exames do 9.º ano e do ensino secundário, que oficialmente terminaria já na próxima semana (24 de Março). Numa nota já enviada às escolas, o Júri Nacional de Exames (JNE), responsável por toda logística daquelas provas, deu também já indicações do modo a que todas as inscrições se possam realizar à distância, já que as escolas estão encerradas devido ao perigo de infecção pela covid-19. As escolas deverão assim colocar os boletins de inscrição, “em formato editável, nas suas páginas electrónicas, de modo a que os alunos ou os seus pais os possam descarregar. Depois de preencherem o boletim, este deve ser enviado para o endereço de email disponibilizado pelo estabelecimento de ensino para o efeito.', '2020-01-17',23, 11, 17, 2);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Governo decreta encerramento de escolas a partir de segunda-feira', 'O Governo vai decretar o encerramento de todas as escolas do país, medida que entra em vigor a partir de segunda-feira, noticia o Observador. Esta é uma das informações que o Governo tem estado a dar aos partidos políticos, na série de reuniões ao longo desta quinta-feira, e que será oficialmente anunciada esta noite, depois da reunião do Conselho de Ministros.', '2020-02-19',123, 345, 18, 3);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Universidade do Porto já admite alargamento do ano letivo devido ao Covid-19', 'Desde que a epidemia do novo coronavírus chegou a Portugal, já são várias as escolas que decidiram fechar portas, no Porto e em Lisboa, depois de confirmados casos de professores e alunos infetados. Contudo, o impacto da chegada desta epidemia a Portugal vai agora além do ensino básico e secundário, com o governo a ordenar o encerramento de duas faculdades no norte do país, onde foram registados infetados, e o fecho voluntário de outras instituições de ensino superior. A Universidade do Porto, a instituição mais afetada até agora, admite “a extensão do calendário académico”. As instalações académicas partilhadas entre a Faculdade de Farmácia da Universidade do Porto (FFUP) e o Instituto de Ciências Biomédicas Abel Salazar (ICBAS) vão permanecer encerradas até ao dia 20 de março, depois de confirmado o caso de um aluno desta comunidade infetado por covid-19. De acordo com um comunicado enviado às redações, a reitoria diz que “as autoridades de saúde estão a notificar todas as pessoas que tiveram contacto direto e continuado com a estudante com covid-19, para que se remetam a um isolamento profilático nas suas residências”. São eles “colegas de turmas, grupos de trabalho, docentes e assistentes das aulas frequentadas”, lê-se.', '2020-02-24',175, 4, 19, 4);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Aprovada proposta que prevê atribuição automática de bolsas de estudo', 'A proposta do PS que propõe a criação de um processo de atribuição automática de bolsas de estudo de ação social foi aprovada por unanimidade na terça-feira, no âmbito da discussão na especialidade das propostas de alteração ao OE2020. A proposta apresentada pelo grupo parlamentar do Partido Socialista prevê que a medida abranja os estudantes que “ingressem no ensino superior através do concurso nacional e que, no ano letivo anterior, tenham sido beneficiários do escalão 01 do abono de família”. O PS considera que a medida agora proposta no âmbito do Orçamento do Estado para 2020 (OE2020) “tem como propósito reduzir a incerteza” e obter acesso mais célere “à ação social para quem transite do ensino secundário para o ensino profissional”.', '2020-01-28',246, 6, 15, 5);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Metade dos estudante do Ensino Superior está em burnout', 'Metade dos alunos portugueses que frequentam o Ensino Superior está em burnout, releva um estudo levado a cabo pelo professor do Instituto Superior de Psicologia Aplicada (ISPA), João Marôco, que foi esta sexta-feira apresentado no Congresso Nacional de Psicologia da Saúde.De acordo com a investigação, cujos dados são avançados pelo jornal Público, 52% dos alunos estão exaustos, sendo a Universidade de Aveiro a instituição de ensino onde a taxa de incidência é mais elevada – 64,9% dos seus alunos apresentam sintomas. “A situação é deveras preocupante (…) Nos contactos com outros professores e com alunos tenho constatado que os níveis de exaustão entre estes são mais elevados. E que a descrença sobre o que irão fazer com o que estão a estudar é também maior”, alertou.', '2019-10-29',265, 9, 1, 1);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Bullying: a partir de hoje já podes fazer denúncias anónimas', 'O Observatório Nacional do Bullying, lançado esta quinta-feira em Matosinhos, disponibiliza um questionário que permite a denúncia informal e anónima de casos de bullying em contexto escolar. “Este observatório é uma plataforma de denúncia informal de casos de bullying que pode ser utilizada por pessoas que estão neste momento a ser vítimas, que foram vítimas no passado, que testemunharam ou que tiveram conhecimento destas situações”, explicou Sofia Neves, coordenadora científica do Observatório Nacional do Bullying e presidente da Associação Plano i, responsável por esta iniciativa.', '2019-10-11',287, 10, 2, 2);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Trabalhadores-estudantes isentos de pagar IRS até quase 2.200 euros', 'O PS avançou na discussão do Orçamento de Estado na especialidade com uma proposta de “isentar de IRS os rendimentos auferidos por jovens estudantes que vivam com os pais, mas que tenham um contrato de trabalho e trabalhem para pagar os seus estudos, suportando IRS sobre esses rendimentos”, segundo o Jornal de Negócios. Revelaram entretanto que esta medida aplica-se aos jovens que sejam menores de 18 anos de idade e que não sejam emancipados, ou seja, que ainda sejam dependentes dos pais. A medida destina-se a jovens que estejam obrigatoriamente a frequentar um estabelecimento de ensino, ou seja, que estejam ainda a estudar. Cumprindo estes requisitos, o rendimento desses jovens não conta para apurar o IRS dos pais até ao limite anual de quase de 2.220 euros (cinco Indexantes de Apoios Sociais – IAS). ', '2019-11-09',378, 17, 3, 3);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Escrita de uma tese de mestrado', 'Mestrado, e agora? Para quem vai para uma área diferente da licenciatura pensa: nossa que bom! Posso ter melhores notas, visto que não tenho o professor X ou Y. Passas um mês na boa, parece que ainda estás de férias. Qual quê, nada disso. É nesse mês que os professores vão-te dizer os trabalhos que tens para fazer e quando chegas aos dois últimos meses do semestre vais ter medo. A PRESSÃO ESTÁ NO AR. E o medo vai aumentando até sensivelmente maio. Estou no segundo ano e o que aconteceu? Bem as minhas férias foram limitadas, estive várias vezes com o meu orientador para ver qual seria o possível tema da minha tese, desculpem, em mestrado diz-se dissertação. Preparem-se para não terem férias, para mudarem de tema algumas vezes.', '2019-12-05',200, 56, 4, 4);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Os meus pais acham que a escola é uma brincadeira', 'Amanhã me espera mais um dia – dia esse que vos conto – em que, pelo menos o suposto – ou imposto –  é que eu acorde às 7 em ponto.  É uma história de dúvida e incerteza, erros, paixão e beleza.  História esta que se tornou mais uma triste agravante da habitual triste rotina.  Uma tristeza que nunca se torna hábito embora rotineira, a tristeza de quem está incerto sobre presente e futuro, que chora quartas em terças e terças em segundas feiras.  Matemática é, às segundas, o meu primeiro bloco. Costumava adorar isto mas agora, com dois dígitos lectivos, não consigo manter o foco.', '2019-12-01',654, 79, 14, 5);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Ser licenciada: uma vitória com amargura', 'Bem vindo a mais uma das minhas aventuras!! Desta vez, trago-te uma notícia bastante recente e que me encheu o coração por completo. É com todo o orgulho que te digo que sou oficialmente licenciada!! Significa toda uma conquista com um sabor inigualável. Olhando para trás, consigo valorizar o meu esforço em todo este processo. Foram imensos os desafios, foram algumas as quedas e os tormentos, mas também confesso que sentirei saudades daquele friozinho na barriga com a chegada das avaliações, o convívio com os amigos, aquela rotina intensa que na altura só queremos que acabe mas pela qual mais tarde ficamos saudosos. Foi há meia dúzia de dias que estava finalmente a preparar-me para a minha defesa final. O grande dia tinha chegado. O júri estava reunido, a minha família estava presente, até a minha coordenadora de curso lá estava e o coração batia a mil. Felizmente tudo correu pelo melhor, tive uma excelente nota, recebi vários abraços do tamanho do mundo, lágrimas de felicidade foram derramadas e tudo parecia certo. O dever estava cumprido!', '2020-02-19',621, 88, 6, 1);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Pressão e Ansiedade? Gotcha!', 'Pressão e ansiedade. De certeza que alguma vez ao longo do teu percurso académico lidaste com um destes fatores. Todos sentimos diariamente a pressão de fazermos de tudo para termos uma média exemplar para entrarmos no curso que realmente queremos. Mas como lidar com a ansiedade? Eu não sou de longe a melhor pessoa para falar deste tema, até porque sofro diariamente com isto, mas acho importante partilharmos experiências uns com os outros e ajudarmo-nos mutuamente. Uma coisa que eu sinto que me ajuda bastante é sentir me bem comigo mesma. Pode parecer algo estranho mas ir confortável e arranjada para os testes faz me sentir mais confiante de que vai correr tudo bem. Também é importante aprendermos a respirar fundo nos picos de stress. Se te acontece ter brancas ou algo parecido durante os testes, respira. Refaz o exercício de novo e de cabeça limpa. Reorganiza as tuas ideias e tudo vai correr bem.', '2020-03-13',467, 43, 7, 2);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Troquei de Universidade', 'A minha escolha no que toca à universidade que iria frequentar mal acabasse o ensino secundário foi bastante simples. As minhas amigas mais velhas estavam todas na mesma instituição e como tudo o que ouvia me parecia incrível, inscrevi-me. Sabia que entrava garantidamente então não estava muito preocupada. Claro que as minhas expectativas aliadas às histórias que me contavam pintaram aquela universidade como a melhor do mundo.Parecia A escolha acertada, quando a confirmação que tinha entrado chegou por e-mail tive a certeza.', '2020-01-17',23, 244, 8, 3);
INSERT INTO post (title, content, time_stamp, upvotes, downvotes, id_author, id_community) VALUES ('Não me sinto integrada, e agora?', 'Quando estava quase a terminar o curso profissional (10°, 11°, 12°) resolvi que tinha chegado a altura de fazer a decisão da minha vida. Avanço com a candidatura para o Ensino Superior ou embarco no Mundo do Trabalho? Resolvi que ir atrás do que quero para mim futuramente iria ser a decisão mais acertada, e assim avancei com a minha candidatura. No entanto, tinha plena consciência de que as aprendizagens que tinha tido no curso profissional não eram suficientes para me candidatar nos Exames Nacionais. Até que me falaram em CTeSP. Canditime_stampi-me a um Curso Técnico e Superior Profissional, fiquei entusiasmada e imaginei como tudo seria.', '2020-03-26',599, 26, 9, 4);

INSERT INTO comment (content, time_stamp, upvotes, downvotes, id_author, id_post) VALUES ('Excelente artigo!','2020-03-18',8, 4, 2, 20);
INSERT INTO comment (content, time_stamp, upvotes, downvotes, id_author, id_post) VALUES ('Não o conseguiria descrever melhor','2020-01-29',12, 2, 14, 22);
INSERT INTO comment (content, time_stamp, upvotes, downvotes, id_author, id_post) VALUES ('Vão, experimentem e criem a vossa opinião.','2020-02-19',100, 20, 18, 3);
INSERT INTO comment (content, time_stamp, upvotes, downvotes, id_author, id_post) VALUES ('O ranking do “Financial Times”, que seleciona','2019-12-08',134, 23, 16, 6);
INSERT INTO comment (content, time_stamp, upvotes, downvotes, id_author, id_post) VALUES ('Adorei cada palavra','2020-01-13',28, 2, 13, 22);
INSERT INTO comment (content, time_stamp, upvotes, downvotes, id_author, id_post) VALUES ('É sempre bom ver pessoas tão jovens a patrticipar nestes assuntos','2020-03-12',87, 7, 7, 8);
INSERT INTO comment (content, time_stamp, upvotes, downvotes, id_author, id_post) VALUES ('Isso não faz sentido nenhum','2020-03-27',1, 0, 1, 12);
INSERT INTO comment (content, time_stamp, upvotes, downvotes, id_author, id_post) VALUES ('Precisamos de mais ajuda e apoio emocional!','2020-03-26',0, 5, 16, 30);
INSERT INTO comment (content, time_stamp, upvotes, downvotes, id_author, id_post) VALUES ('Fora de contexto...','2020-02-17',9, 15, 15, 26);
INSERT INTO comment (content, time_stamp, upvotes, downvotes, id_author, id_post) VALUES ('O ranking do “Financial Times”, que seleciona','2020-03-27',26, 19, 11, 29);

INSERT INTO reply (reply_comment, parent_comment) VALUES (2,5);

INSERT INTO report (reason, time_stamp, id_admin, id_user, reportable_id, reportable_type) VALUES ('Conteúdo impróprio', '2020-03-27',1,5,1, 'App\UserReport');
INSERT INTO report (reason, time_stamp, id_admin, id_user, reportable_id, reportable_type) VALUES ('Vocabulário impróprio', '2020-03-18',2, 9, 2,'App\CommentReport');
INSERT INTO report (reason, time_stamp, id_admin, id_user, reportable_id, reportable_type) VALUES ('SPAM', '2020-03-15',3,12, 3,'App\PostReport');
INSERT INTO report (reason, time_stamp, id_admin, id_user, reportable_id, reportable_type) VALUES ('Conteúdo impróprio', '2020-03-26',4,17,4, 'App\CommunityReport');

INSERT INTO comment_report (id_report, id_comment) VALUES (2,5);
INSERT INTO post_report (id_report, id_post) VALUES (3,3);
INSERT INTO community_report (id_report, id_community) VALUES (4,2);
INSERT INTO user_report (id_report, id_user) VALUES (1,8);

INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 1, 9);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 3, 2);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('down', 1, 3);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 1, 4);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 1, 5);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('down', 15, 6);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 1, 7);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 1, 8);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 2, 9);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('down', 2,10);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('down', 16,11);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('down', 2,12);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('down', 3,13);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 3,14);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 3,15);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('down', 14,16);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 3,17);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 4,18);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('down', 5,19);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 5,20);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 16,21);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 6,22);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 6,23);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('down', 6,24);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 6,25);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 15,26);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('down', 7,27);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 5,28);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 3,29);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('down', 8,30);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 9,1);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 10 ,2);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 10 ,3);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('down', 10 ,4);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 11 ,5);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 14 ,6);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 11 ,7);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 11 ,8);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('down', 11 ,9);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 12 ,10);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 13 ,11);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 12 ,14);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 13 ,12);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 11 ,14);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 12 ,15);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('down', 13 ,16);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 15 ,17);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 15 ,18);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 15 ,19);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 15 ,20);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('down', 14 ,21);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 16 ,22);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 17 ,23);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 17 ,24);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 17 ,25);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 16 ,26);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('down', 17 ,27);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 18 ,28);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 18 ,29);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 18 ,30);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 18 ,1);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 18 ,2);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 19 ,3);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 19 ,4);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 20,5);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('down', 13,6);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('up', 20,7);
INSERT INTO post_vote (vote_type, id_user, id_post) VALUES ('down', 20,8);

INSERT INTO comment_vote (vote_type, id_user, id_comment) VALUES ('up', 3, 1);
INSERT INTO comment_vote (vote_type, id_user, id_comment) VALUES ('up', 16, 2);
INSERT INTO comment_vote (vote_type, id_user, id_comment) VALUES ('down', 9, 3);
INSERT INTO comment_vote (vote_type, id_user, id_comment) VALUES ('up', 14, 4);
INSERT INTO comment_vote (vote_type, id_user, id_comment) VALUES ('up', 11, 5);
INSERT INTO comment_vote (vote_type, id_user, id_comment) VALUES ('down', 15, 6);
INSERT INTO comment_vote (vote_type, id_user, id_comment) VALUES ('up', 17, 7);
INSERT INTO comment_vote (vote_type, id_user, id_comment) VALUES ('up', 20, 8);
INSERT INTO comment_vote (vote_type, id_user, id_comment) VALUES ('up', 16, 9);
INSERT INTO comment_vote (vote_type, id_user, id_comment) VALUES ('up', 13, 10);
INSERT INTO comment_vote (vote_type, id_user, id_comment) VALUES ('down', 4, 6);
INSERT INTO comment_vote (vote_type, id_user, id_comment) VALUES ('up', 14, 9);

INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-02-18', 'pending', 1,18, 1, 'App\FollowRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-02-19', 'pending', 3,4, 2, 'App\FollowRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-02-21', 'accepted', 5,1, 3, 'App\FollowRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-02-25', 'pending', 7,9, 4, 'App\FollowRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-02-27', 'pending', 8,2, 5, 'App\FollowRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-02-29', 'pending', 11,15, 6, 'App\FollowRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-03-3', 'accepted', 13,17, 7, 'App\FollowRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-03-5', 'pending', 14,5, 8, 'App\FollowRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-03-6', 'pending', 16,6, 9, 'App\FollowRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-03-8', 'denied', 17,7, 10, 'App\FollowRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-03-12', 'pending', 18,11, 11, 'App\FollowRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-03-16', 'pending', 19,13, 12, 'App\FollowRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-03-20', 'accepted', 20,14, 13, 'App\FollowRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-03-22', 'pending', 4,16, 14, 'App\FollowRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-03-26', 'denied', 12,3, 15, 'App\FollowRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-02-27', 'pending', 5,2, 16, 'App\JoinCommunityRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-02-29', 'pending', 5,15, 17, 'App\JoinCommunityRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-02-10', 'pending', 5,17, 18, 'App\JoinCommunityRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-02-12', 'pending', 5,6, 19, 'App\JoinCommunityRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-02-13', 'denied', 5,6, 20, 'App\JoinCommunityRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-02-14', 'pending', 5,7, 21, 'App\JoinCommunityRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-02-17', 'pending', 5,11, 22, 'App\JoinCommunityRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-03-21', 'pending', 5,13, 23, 'App\JoinCommunityRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-03-23', 'pending', 5,14, 24, 'App\JoinCommunityRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-03-24', 'denied', 5,16, 25, 'App\JoinCommunityRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-03-25', 'pending', 5,3, 26, 'App\JoinCommunityRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-02-18', 'pending', 5,18, 27, 'App\JoinCommunityRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-02-19', 'pending', 5,4, 28, 'App\JoinCommunityRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-02-21', 'accepted', 5,1, 29, 'App\JoinCommunityRequest');
INSERT INTO request (time_stamp, status, id_receiver, id_sender, requestable_id, requestable_type) VALUES ('2020-02-25', 'pending', 5,9, 30, 'App\JoinCommunityRequest');

INSERT INTO follow_request (id) VALUES (1);
INSERT INTO follow_request (id) VALUES (2);
INSERT INTO follow_request (id) VALUES (3);
INSERT INTO follow_request (id) VALUES (4);
INSERT INTO follow_request (id) VALUES (5);
INSERT INTO follow_request (id) VALUES (6);
INSERT INTO follow_request (id) VALUES (7);
INSERT INTO follow_request (id) VALUES (8);
INSERT INTO follow_request (id) VALUES (9);
INSERT INTO follow_request (id) VALUES (10);
INSERT INTO follow_request (id) VALUES (11);
INSERT INTO follow_request (id) VALUES (12);
INSERT INTO follow_request (id) VALUES (13);
INSERT INTO follow_request (id) VALUES (14);
INSERT INTO follow_request (id) VALUES (15);

INSERT INTO join_community_request (id, id_community) VALUES (16, 1);
INSERT INTO join_community_request (id, id_community) VALUES (17, 1);
INSERT INTO join_community_request (id, id_community) VALUES (18, 1);
INSERT INTO join_community_request (id, id_community) VALUES (19, 2);
INSERT INTO join_community_request (id, id_community) VALUES (20, 2);
INSERT INTO join_community_request (id, id_community) VALUES (21, 2);
INSERT INTO join_community_request (id, id_community) VALUES (22, 2);
INSERT INTO join_community_request (id, id_community) VALUES (23, 3);
INSERT INTO join_community_request (id, id_community) VALUES (24, 3);
INSERT INTO join_community_request (id, id_community) VALUES (25, 3);
INSERT INTO join_community_request (id, id_community) VALUES (26, 4);
INSERT INTO join_community_request (id, id_community) VALUES (27, 4);
INSERT INTO join_community_request (id, id_community) VALUES (28, 5);
INSERT INTO join_community_request (id, id_community) VALUES (29, 5);
INSERT INTO join_community_request (id, id_community) VALUES (30, 5);

INSERT INTO follow_user (id_followed, id_follower) VALUES (1,18);
INSERT INTO follow_user (id_followed, id_follower) VALUES (3,4);
INSERT INTO follow_user (id_followed, id_follower) VALUES (5,1);
INSERT INTO follow_user (id_followed, id_follower) VALUES (7,9);
INSERT INTO follow_user (id_followed, id_follower) VALUES (8,2);
INSERT INTO follow_user (id_followed, id_follower) VALUES (11,15);
INSERT INTO follow_user (id_followed, id_follower) VALUES (13,17);
INSERT INTO follow_user (id_followed, id_follower) VALUES (14,5);
INSERT INTO follow_user (id_followed, id_follower) VALUES (16,6);
INSERT INTO follow_user (id_followed, id_follower) VALUES (17,7);
INSERT INTO follow_user (id_followed, id_follower) VALUES (18,11);
INSERT INTO follow_user (id_followed, id_follower) VALUES (19,13);
INSERT INTO follow_user (id_followed, id_follower) VALUES (20,14);
INSERT INTO follow_user (id_followed, id_follower) VALUES (4,16);
INSERT INTO follow_user (id_followed, id_follower) VALUES (16,4);
INSERT INTO follow_user (id_followed, id_follower) VALUES (12,3);

INSERT INTO block_user (blocked_user, blocker_user) VALUES (1,8);
INSERT INTO block_user (blocked_user, blocker_user) VALUES (16,4);
INSERT INTO block_user (blocked_user, blocker_user) VALUES (1,18);
INSERT INTO block_user (blocked_user, blocker_user) VALUES (11,8);
INSERT INTO block_user (blocked_user, blocker_user) VALUES (3,10);

-----------------------------------------
-- end
-----------------------------------------