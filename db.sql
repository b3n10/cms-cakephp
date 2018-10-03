DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id SERIAL PRIMARY KEY NOT NULL,
	email VARCHAR(255) NOT NULL,
	password VARCHAR(255) NOT NULL,
	created timestamp,
	modified timestamp
);

DROP TABLE IF EXISTS articles;
CREATE TABLE articles (
  id SERIAL PRIMARY KEY NOT NULL,
	user_id INT NOT NULL REFERENCES users(id),
	title VARCHAR(255) NOT NULL,
	slug VARCHAR(191) NOT NULL UNIQUE,
	body TEXT,
	published BOOLEAN DEFAULT FALSE,
	created timestamp,
	modified timestamp
);

DROP TABLE IF EXISTS tags;
CREATE TABLE tags (
  id SERIAL PRIMARY KEY NOT NULL,
	title VARCHAR(191) UNIQUE,
	created timestamp,
	modified timestamp
);

DROP TABLE IF EXISTS articles_tags;
CREATE TABLE articles_tags (
	article_id INT NOT NULL REFERENCES articles(id),
	tag_id INT NOT NULL REFERENCES tags(id),
	PRIMARY KEY (article_id, tag_id)
);

INSERT INTO users (
	email,
	password,
	created,
	modified
	) VALUES (
	'cakephp@example.com',
	'sekret',
	NOW(),
	NOW()
);

INSERT INTO articles (
	user_id,
	title,
	slug,
	body,
	published,
	created,
	modified
	) VALUES (
	1,
	'First Post',
	'first-post',
	'This is the first post.',
	TRUE,
	now(),
	now()
);
