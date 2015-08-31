CREATE TABLE blog (
  title VARCHAR(255) NOT NULL,
  url VARCHAR(255) NOT NULL,
  preview TEXT,
  body TEXT,
  author INT,
  timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  publishdate TIMESTAMP DEFAULT CURRENT_TIEMSTAMP,
  tags TEXT,
  status BOOLEAN DEFAULT true,
  seodescription VARCHAR(155),
  id SERIAL
);

CREATE TABLE tags (
  title VARCHAR(255) NOT NULL,
  id SERIAL
);

