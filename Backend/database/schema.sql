CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255),
    role ENUM('admin', 'employer', 'job_seeker') NOT NULL,
    created_at DATETIME,
    updated_at DATETIME
);

CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    company_name VARCHAR(150) NOT NULL,
    website VARCHAR(255),
    logo VARCHAR(255),
    description TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at DATETIME,
    updated_at DATETIME
);

CREATE TABLE job_seekers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    resume VARCHAR(255),
    bio TEXT,
    created_at DATETIME,
    updated_at DATETIME
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL UNIQUE,
    description TEXT
);

CREATE TABLE job_functions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(120) NOT NULL,
    description TEXT
);

CREATE TABLE employment_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100),
    -- country VARCHAR(100) NOT NULL
);

CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    salary DECIMAL(10,2),
    company_id INT,
    job_function_id INT,
    employment_type_id INT,
    location_id INT,
    status ENUM('draft', 'published', 'closed') DEFAULT 'draft',
    posted_at DATETIME,
    created_at DATETIME,
    updated_at DATETIME
);

CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT,
    job_seeker_id INT,
    cover_letter TEXT,
    applied_at DATETIME,
    created_at DATETIME,
    updated_at DATETIME
);

CREATE TABLE saved_jobs (
    job_seeker_id INT,
    job_id INT,
    created_at DATETIME,
    updated_at DATETIME,
    PRIMARY KEY (job_seeker_id, job_id)
);

-- Foreign key relationships
ALTER TABLE companies
    ADD CONSTRAINT fk_companies_user_id 
    FOREIGN KEY (user_id) REFERENCES users(id);

ALTER TABLE job_seekers
    ADD CONSTRAINT fk_job_seekers_user_id
     FOREIGN KEY (user_id) REFERENCES users(id);

ALTER TABLE job_functions
    ADD CONSTRAINT fk_job_functions_category_id
    FOREIGN KEY (category_id) REFERENCES categories(id);

ALTER TABLE jobs
    ADD CONSTRAINT fk_jobs_company_id FOREIGN KEY (company_id) REFERENCES companies(id),
    ADD CONSTRAINT fk_jobs_job_function_id FOREIGN KEY (job_function_id) REFERENCES job_functions(id),
    ADD CONSTRAINT fk_jobs_employment_type_id FOREIGN KEY (employment_type_id) REFERENCES employment_types(id),
    ADD CONSTRAINT fk_jobs_location_id FOREIGN KEY (location_id) REFERENCES locations(id);

ALTER TABLE applications
    ADD CONSTRAINT fk_applications_job_id FOREIGN KEY (job_id) REFERENCES jobs(id),
    ADD CONSTRAINT fk_applications_job_seeker_id FOREIGN KEY (job_seeker_id) REFERENCES job_seekers(id);

ALTER TABLE saved_jobs
    ADD CONSTRAINT fk_saved_jobs_job_seeker_id FOREIGN KEY (job_seeker_id) REFERENCES job_seekers(id),
    ADD CONSTRAINT fk_saved_jobs_job_id FOREIGN KEY (job_id) REFERENCES jobs(id);
