CREATE DATABASE IF NOT EXISTS student_db;
USE student_db;

CREATE TABLE users(
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE,
  password VARCHAR(255)
);

CREATE TABLE students(
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  dob DATE,
  gender ENUM('Male','Female'),
  course VARCHAR(50),
  subjects VARCHAR(200),
  phone VARCHAR(20),
  address TEXT
);

CREATE TABLE attendance(
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  attendance_date DATE NOT NULL,
  status ENUM('P','A','L') NOT NULL,
  FOREIGN KEY(student_id) REFERENCES students(id) ON DELETE CASCADE
);

CREATE TABLE exam_marks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject VARCHAR(100) NOT NULL,
    marks INT NOT NULL,
    total_marks INT NOT NULL,
    exam_date DATE NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);