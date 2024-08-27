CREATE TABLE trips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    start_date DATE,
    cover TEXT
);

CREATE TABLE days (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trip_id INT,
    day_number INT NOT NULL,
    date DATE,
    FOREIGN KEY (trip_id) REFERENCES trips(id) ON DELETE CASCADE
);

CREATE TABLE stages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    day_id INT,
    stage_number INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    location VARCHAR(255),
    latitude DECIMAL (10, 7),
    longitude DECIMAL (10, 7),
    image TEXT,
    FOREIGN KEY (day_id) REFERENCES days(id) ON DELETE CASCADE
);