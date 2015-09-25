CREATE TABLE blog (
  title VARCHAR(255) NOT NULL,
  url VARCHAR(255) NOT NULL,
  preview TEXT,
  body TEXT,
  author INT,
  timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  publishdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  tags TEXT,
  status BOOLEAN DEFAULT true,
  seodescription VARCHAR(155),
  id SERIAL
);

CREATE TABLE tags (
  title VARCHAR(255) NOT NULL,
  id SERIAL
);

/*
Defines default posts per page to show
This can be changed via GUI anytime
*/
INSERT INTO settings(module, key, value) VALUES
('Blog', 'postsPerPage', 10),
('Blog', 'rssChannelTitle', 'Fennec RSS'),
('Blog', 'rssChannelDescription', 'This is a description for Fennec RSS'),
('Blog', 'rssTotalPosts', 10);
