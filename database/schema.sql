CREATE DATABASE tutor_match;
USE tutor_match;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,

    role ENUM('student','tutor','admin') NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE student_profiles (
    student_id INT PRIMARY KEY,
    university VARCHAR(100),
    major VARCHAR(100),
    graduation_year INT,

    FOREIGN KEY (student_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE
);

CREATE TABLE tutor_profiles (
    tutor_id INT PRIMARY KEY,

    bio TEXT,
    hourly_rate DECIMAL(8,2),
    learning_style VARCHAR(100),

    average_rating DECIMAL(3,2) DEFAULT 0,

    FOREIGN KEY (tutor_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE
);

CREATE TABLE subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE tutor_subjects (
    tutor_id INT,
    subject_id INT,

    PRIMARY KEY (tutor_id, subject_id),

    FOREIGN KEY (tutor_id)
        REFERENCES tutor_profiles(tutor_id)
        ON DELETE CASCADE,

    FOREIGN KEY (subject_id)
        REFERENCES subjects(subject_id)
        ON DELETE CASCADE
);

CREATE TABLE tutor_availability (
    availability_id INT AUTO_INCREMENT PRIMARY KEY,

    tutor_id INT NOT NULL,

    available_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,

    FOREIGN KEY (tutor_id)
        REFERENCES tutor_profiles(tutor_id)
        ON DELETE CASCADE
);

CREATE TABLE tutoring_sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,
    tutor_id INT NOT NULL,
    subject_id INT NOT NULL,

    session_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,

    status ENUM(
        'pending',
        'confirmed',
        'completed',
        'cancelled'
    ) DEFAULT 'pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (student_id)
        REFERENCES student_profiles(student_id),

    FOREIGN KEY (tutor_id)
        REFERENCES tutor_profiles(tutor_id),

    FOREIGN KEY (subject_id)
        REFERENCES subjects(subject_id)
);

CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,

    session_id INT NOT NULL,

    student_id INT NOT NULL,
    tutor_id INT NOT NULL,

    rating INT CHECK (rating BETWEEN 1 AND 5),

    review_text TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (session_id)
        REFERENCES tutoring_sessions(session_id),

    FOREIGN KEY (student_id)
        REFERENCES student_profiles(student_id),

    FOREIGN KEY (tutor_id)
        REFERENCES tutor_profiles(tutor_id)
);