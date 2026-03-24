-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 24, 2026 at 10:16 AM
-- Server version: 10.6.25-MariaDB
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unmqwlgl_sql`
--

-- --------------------------------------------------------

--
-- Table structure for table `ai_usage`
--

CREATE TABLE `ai_usage` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `usage_date` date NOT NULL,
  `usage_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ai_usage`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Stand-in structure for view `bc`
-- (See below for the actual view)
--
CREATE TABLE `bc` (
`id` int(11)
,`name` varchar(100)
);

-- --------------------------------------------------------

--
-- Table structure for table `query_logs`
--

CREATE TABLE `query_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sql_text` text NOT NULL,
  `execution_time_ms` float DEFAULT NULL,
  `status` enum('success','error') NOT NULL,
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `query_logs`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `registration_attempts`
--

CREATE TABLE `registration_attempts` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `device_fingerprint` varchar(255) DEFAULT NULL,
  `success` tinyint(1) DEFAULT 0,
  `failure_reason` varchar(255) DEFAULT NULL,
  `attempted_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration_attempts`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `saved_queries`
--

CREATE TABLE `saved_queries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `sql_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `registration_ip` varchar(45) DEFAULT NULL,
  `device_fingerprint` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `status` enum('active','banned') DEFAULT 'active',
  `verify_token` varchar(64) DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `last_registration_attempt` datetime DEFAULT current_timestamp(),
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `force_logout` tinyint(1) DEFAULT 0,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_2_test1`
--

CREATE TABLE `user_2_test1` (
  `id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_2_test1`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_13_student`
--

CREATE TABLE `user_13_student` (
  `name` varchar(25) DEFAULT NULL,
  `rollno` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_13_student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_14_student`
--

CREATE TABLE `user_14_student` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_14_student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_17_student`
--

CREATE TABLE `user_17_student` (
  `id` int(11) NOT NULL,
  `email_id` varchar(100) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_17_student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_17_student1`
--

CREATE TABLE `user_17_student1` (
  `id` int(11) NOT NULL,
  `email_id` varchar(100) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_17_student2`
--

CREATE TABLE `user_17_student2` (
  `id` int(11) NOT NULL,
  `email_id` varchar(100) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_38_person`
--

CREATE TABLE `user_38_person` (
  `person_id` int(11) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_38_students`
--

CREATE TABLE `user_38_students` (
  `studentID` int(11) DEFAULT NULL,
  `lastName` varchar(10) DEFAULT NULL,
  `firstName` varchar(10) DEFAULT NULL,
  `address` varchar(10) DEFAULT NULL,
  `city` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_40_Students`
--

CREATE TABLE `user_40_Students` (
  `student_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_40_Students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_44_janibasha_Info`
--

CREATE TABLE `user_44_janibasha_Info` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `subject` varchar(30) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `exam_date` date DEFAULT NULL,
  `Age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_44_janibasha_Info`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_44_patients`
--

CREATE TABLE `user_44_patients` (
  `patient_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `disease` varchar(100) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_64_employees`
--

CREATE TABLE `user_64_employees` (
  `emp_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `department` varchar(30) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `hire_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_64_employees`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_67_department`
--

CREATE TABLE `user_67_department` (
  `dept_id` int(11) DEFAULT NULL,
  `dept_name` varchar(30) DEFAULT NULL,
  `location` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_67_department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_67_employee`
--

CREATE TABLE `user_67_employee` (
  `emp_id` int(11) DEFAULT NULL,
  `emp_name` varchar(30) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `department` varchar(20) DEFAULT NULL,
  `designation` varchar(20) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_67_employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_67_product`
--

CREATE TABLE `user_67_product` (
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(30) DEFAULT NULL,
  `category` varchar(20) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `supplier` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_67_product`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_67_student`
--

CREATE TABLE `user_67_student` (
  `roll_no` int(11) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `class` varchar(10) DEFAULT NULL,
  `section` char(1) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_67_student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_70_Docentes`
--

CREATE TABLE `user_70_Docentes` (
  `id_docente` int(11) NOT NULL,
  `nombre` varchar(60) DEFAULT NULL,
  `id_mentor` int(11) DEFAULT NULL,
  `salario` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_70_EmpleadosAreaComercial`
--

CREATE TABLE `user_70_EmpleadosAreaComercial` (
  `NumEmpleado` char(6) DEFAULT NULL,
  `NombreCompleto` varchar(100) DEFAULT NULL,
  `Salario` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_70_Estudiantes`
--

CREATE TABLE `user_70_Estudiantes` (
  `id_estudiante` int(11) NOT NULL,
  `nombre` varchar(60) DEFAULT NULL,
  `carrera` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_70_Materias`
--

CREATE TABLE `user_70_Materias` (
  `id_materia` int(11) NOT NULL,
  `nombre_materia` varchar(60) DEFAULT NULL,
  `creditos` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_74_Persons`
--

CREATE TABLE `user_74_Persons` (
  `PersonID` int(11) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `FirstName` varchar(255) NOT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_77_ChucVu`
--

CREATE TABLE `user_77_ChucVu` (
  `MaCV` int(11) NOT NULL,
  `TenCV` char(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_77_PhongBan`
--

CREATE TABLE `user_77_PhongBan` (
  `MaPB` varchar(2) NOT NULL,
  `TenPB` char(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_78_customers`
--

CREATE TABLE `user_78_customers` (
  `id` int(11) DEFAULT NULL,
  `fname` text DEFAULT NULL,
  `lname` text DEFAULT NULL,
  `membership` text DEFAULT NULL,
  `member_since` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_78_customers`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_79_branch`
--

CREATE TABLE `user_79_branch` (
  `branchid` varchar(10) NOT NULL,
  `branchname` varchar(20) DEFAULT NULL,
  `hod` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_82_temp`
--

CREATE TABLE `user_82_temp` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_83_customers`
--

CREATE TABLE `user_83_customers` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `city` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_83_customers`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_83_orders`
--

CREATE TABLE `user_83_orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_83_orders`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_83_student`
--

CREATE TABLE `user_83_student` (
  `name` varchar(200) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_83_student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_84_Student`
--

CREATE TABLE `user_84_Student` (
  `ID` int(11) DEFAULT NULL,
  `Name` varchar(30) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_84_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_85_Marks`
--

CREATE TABLE `user_85_Marks` (
  `mark_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_85_Marks`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_86_Department`
--

CREATE TABLE `user_86_Department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_87_clienti`
--

CREATE TABLE `user_87_clienti` (
  `id_cliente` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `citta` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_87_clienti`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_87_ordini`
--

CREATE TABLE `user_87_ordini` (
  `id_ordine` int(11) NOT NULL,
  `data_ordine` date DEFAULT NULL,
  `importo` decimal(8,2) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_87_ordini`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_90_employee`
--

CREATE TABLE `user_90_employee` (
  `employee_ID` int(11) DEFAULT NULL,
  `employee_Name` varchar(100) DEFAULT NULL,
  `employee_Salary` int(11) DEFAULT NULL,
  `employee_Dept_Name` varchar(100) DEFAULT NULL,
  `employee_EmailID` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_90_employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_90_Persons`
--

CREATE TABLE `user_90_Persons` (
  `ID` int(11) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `FirstName` varchar(200) NOT NULL,
  `Age` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_90_Persons`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_90_StudentMarks`
--

CREATE TABLE `user_90_StudentMarks` (
  `student_ID` int(11) DEFAULT NULL,
  `student_Name` varchar(200) DEFAULT NULL,
  `PHY` int(11) DEFAULT NULL,
  `MATHS` int(11) DEFAULT NULL,
  `HINDI` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_90_StudentMarks`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_90_Students`
--

CREATE TABLE `user_90_Students` (
  `student_ID` int(11) DEFAULT NULL,
  `student_Name` varchar(100) DEFAULT NULL,
  `student_PhoneNUM` int(11) DEFAULT NULL,
  `student_emailID` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_90_Students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_91_department`
--

CREATE TABLE `user_91_department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `hod_name` varchar(50) DEFAULT NULL,
  `location` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_91_department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_91_employee`
--

CREATE TABLE `user_91_employee` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `hire_date` date NOT NULL,
  `job_title` varchar(50) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_91_employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_91_Persons`
--

CREATE TABLE `user_91_Persons` (
  `ID` int(11) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `FirstName` varchar(255) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_91_student`
--

CREATE TABLE `user_91_student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `class` varchar(20) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_91_student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_91_student_marks`
--

CREATE TABLE `user_91_student_marks` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `subject` varchar(30) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `exam_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_91_student_marks`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_93_Course`
--

CREATE TABLE `user_93_Course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_93_Course`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_93_Department`
--

CREATE TABLE `user_93_Department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_93_Department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_93_Employee`
--

CREATE TABLE `user_93_Employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_93_Employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_93_Student`
--

CREATE TABLE `user_93_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_93_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_93_students`
--

CREATE TABLE `user_93_students` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `bike_model` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_93_students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_94_students`
--

CREATE TABLE `user_94_students` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_95_Employee`
--

CREATE TABLE `user_95_Employee` (
  `Emp_ID` int(11) NOT NULL,
  `Emp_Name` varchar(50) NOT NULL,
  `Salary` decimal(10,2) DEFAULT NULL,
  `Dept` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_95_Employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_95_Student`
--

CREATE TABLE `user_95_Student` (
  `Roll_No` int(11) DEFAULT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Course` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_95_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_96_employees`
--

CREATE TABLE `user_96_employees` (
  `salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_96_Employees`
--

CREATE TABLE `user_96_Employees` (
  `EmpID` int(11) NOT NULL,
  `EmpName` varchar(50) NOT NULL,
  `Salary` decimal(10,2) DEFAULT NULL,
  `Department` varchar(30) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `hire` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_96_Employees`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_96_products`
--

CREATE TABLE `user_96_products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_96_products`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_96_teachers`
--

CREATE TABLE `user_96_teachers` (
  `teacher_id` int(11) NOT NULL,
  `teacher_name` varchar(50) DEFAULT NULL,
  `subject` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_96_teachers`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_96_users`
--

CREATE TABLE `user_96_users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_96_users`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_97_users`
--

CREATE TABLE `user_97_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_97_users`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_98_department`
--

CREATE TABLE `user_98_department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `hod_name` varchar(50) DEFAULT NULL,
  `location` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_98_department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_98_Student`
--

CREATE TABLE `user_98_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_98_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_98_student_marks`
--

CREATE TABLE `user_98_student_marks` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `subject` varchar(30) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `exam_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_98_student_marks`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_99_categories`
--

CREATE TABLE `user_99_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_99_users`
--

CREATE TABLE `user_99_users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_100_EMPLOYEE`
--

CREATE TABLE `user_100_EMPLOYEE` (
  `NAME` varchar(10) DEFAULT NULL,
  `EMP_ID` varchar(14) DEFAULT NULL,
  `DEPT` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_100_EMPLOYEE`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_100_SALES`
--

CREATE TABLE `user_100_SALES` (
  `NAME` char(5) DEFAULT NULL,
  `EMP_ID` varchar(8) DEFAULT NULL,
  `DEPT` char(7) DEFAULT NULL,
  `SALARY` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_100_SALES`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_100_student`
--

CREATE TABLE `user_100_student` (
  `NAME` char(10) DEFAULT NULL,
  `STU_ID` varchar(20) DEFAULT NULL,
  `BRANCH` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_100_student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_100_ZOO`
--

CREATE TABLE `user_100_ZOO` (
  `NO` int(5) DEFAULT NULL,
  `NAME` char(10) DEFAULT NULL,
  `EXPENDITURE` int(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_100_ZOO`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_102_cafe_menu`
--

CREATE TABLE `user_102_cafe_menu` (
  `item_id` int(11) DEFAULT NULL,
  `item_name` varchar(30) DEFAULT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_102_cafe_menu`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_102_mobile_store`
--

CREATE TABLE `user_102_mobile_store` (
  `mobile_id` int(11) NOT NULL,
  `brand` varchar(30) DEFAULT NULL,
  `model` varchar(30) DEFAULT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_102_mobile_store`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_102_users`
--

CREATE TABLE `user_102_users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_102_users`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_104_Course`
--

CREATE TABLE `user_104_Course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_104_Course`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_104_department`
--

CREATE TABLE `user_104_department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `hod_name` varchar(50) DEFAULT NULL,
  `location` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_104_department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_104_student`
--

CREATE TABLE `user_104_student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `class` varchar(20) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_104_student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_104_student_marks`
--

CREATE TABLE `user_104_student_marks` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `subject` varchar(30) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `exam_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_104_student_marks`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_105_department`
--

CREATE TABLE `user_105_department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `hod_name` varchar(50) DEFAULT NULL,
  `location` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_105_department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_105_student`
--

CREATE TABLE `user_105_student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `class` varchar(20) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_105_student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_105_student_marks`
--

CREATE TABLE `user_105_student_marks` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `subject` varchar(30) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `exam_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_105_student_marks`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_106_COURSE`
--

CREATE TABLE `user_106_COURSE` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_106_Department`
--

CREATE TABLE `user_106_Department` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_106_Department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_106_Employee`
--

CREATE TABLE `user_106_Employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_106_Employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_106_student`
--

CREATE TABLE `user_106_student` (
  `roll_no` varchar(20) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `BRANCH` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_106_student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_106_Student`
--

CREATE TABLE `user_106_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_107_Employees`
--

CREATE TABLE `user_107_Employees` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `job_title` varchar(50) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `hire_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_107_teachers`
--

CREATE TABLE `user_107_teachers` (
  `teacher_id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `department` varchar(50) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL CHECK (`salary` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_107_Teachers`
--

CREATE TABLE `user_107_Teachers` (
  `Teacher_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `hire_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_107_Teachers`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_107_users`
--

CREATE TABLE `user_107_users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `created_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_107_users`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_108_department`
--

CREATE TABLE `user_108_department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `hod_name` varchar(50) DEFAULT NULL,
  `location` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_108_department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_108_student`
--

CREATE TABLE `user_108_student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `class` varchar(20) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_108_student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_108_student_marks`
--

CREATE TABLE `user_108_student_marks` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `subject` varchar(30) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `exam_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_108_student_marks`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_110_employee`
--

CREATE TABLE `user_110_employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `department` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_110_Employees`
--

CREATE TABLE `user_110_Employees` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) NOT NULL,
  `department` varchar(50) DEFAULT NULL,
  `job_title` varchar(50) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `hire_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_110_students`
--

CREATE TABLE `user_110_students` (
  `student_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_110_students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_111_employees`
--

CREATE TABLE `user_111_employees` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(100) NOT NULL,
  `department` varchar(50) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `hire_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_111_users`
--

CREATE TABLE `user_111_users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `created_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_111_users`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_112_employees`
--

CREATE TABLE `user_112_employees` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `salary` decimal(12,2) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_112_users`
--

CREATE TABLE `user_112_users` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_112_users`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_114_Course`
--

CREATE TABLE `user_114_Course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_114_Course`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_114_Department`
--

CREATE TABLE `user_114_Department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_114_Department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_114_Marks`
--

CREATE TABLE `user_114_Marks` (
  `mark_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_114_Marks`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_114_Student`
--

CREATE TABLE `user_114_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_114_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_115_Course`
--

CREATE TABLE `user_115_Course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_115_Course`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_115_Department`
--

CREATE TABLE `user_115_Department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_115_Department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_115_Employee`
--

CREATE TABLE `user_115_Employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_115_Employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_115_MARKS`
--

CREATE TABLE `user_115_MARKS` (
  `mark_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `marks_obtained` decimal(5,2) NOT NULL,
  `max_marks` decimal(5,2) NOT NULL,
  `exam_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_115_Student`
--

CREATE TABLE `user_115_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_115_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_116_students`
--

CREATE TABLE `user_116_students` (
  `roll_no` varchar(20) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_116_students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_116_users`
--

CREATE TABLE `user_116_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `created_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_117_students`
--

CREATE TABLE `user_117_students` (
  `roll_no` int(11) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `ph_no` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_117_students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_118_Course`
--

CREATE TABLE `user_118_Course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_118_Course`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_118_Department`
--

CREATE TABLE `user_118_Department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_118_Department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_118_Marks`
--

CREATE TABLE `user_118_Marks` (
  `mark_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_118_Marks`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_118_Student`
--

CREATE TABLE `user_118_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_118_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_119_course`
--

CREATE TABLE `user_119_course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_119_course`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_119_Department`
--

CREATE TABLE `user_119_Department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_119_Employees`
--

CREATE TABLE `user_119_Employees` (
  `EmployeeID` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Salary` decimal(10,2) DEFAULT NULL,
  `HireDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_120_Course`
--

CREATE TABLE `user_120_Course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_120_Course`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_120_Department`
--

CREATE TABLE `user_120_Department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_120_Department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_120_Employee`
--

CREATE TABLE `user_120_Employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_120_Employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_120_Student`
--

CREATE TABLE `user_120_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_121_customers`
--

CREATE TABLE `user_121_customers` (
  `customer_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `membership_type` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `join_date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_121_Employee`
--

CREATE TABLE `user_121_Employee` (
  `EmpId` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `HireDate` date DEFAULT NULL,
  `Salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_121_employees`
--

CREATE TABLE `user_121_employees` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `department` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_121_Student`
--

CREATE TABLE `user_121_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_121_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_122_Customers`
--

CREATE TABLE `user_122_Customers` (
  `CustomerID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_122_Employee`
--

CREATE TABLE `user_122_Employee` (
  `EmployeeID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `HireDate` date DEFAULT NULL,
  `Salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_122_Employees`
--

CREATE TABLE `user_122_Employees` (
  `Employee_ID` int(11) NOT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `HireDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_122_Student_info`
--

CREATE TABLE `user_122_Student_info` (
  `College_Id` int(2) DEFAULT NULL,
  `College_name` varchar(30) DEFAULT NULL,
  `Branch` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_124_Course`
--

CREATE TABLE `user_124_Course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_124_Course`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_124_Department`
--

CREATE TABLE `user_124_Department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_124_Department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_124_Employee`
--

CREATE TABLE `user_124_Employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_124_Employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_124_Student`
--

CREATE TABLE `user_124_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_124_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_124_students`
--

CREATE TABLE `user_124_students` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` date DEFAULT curdate(),
  `bike_model` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_124_students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_125_department`
--

CREATE TABLE `user_125_department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_125_Department`
--

CREATE TABLE `user_125_Department` (
  `Dept_ID` int(11) NOT NULL,
  `Dept_Name` varchar(50) DEFAULT NULL,
  `Location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_125_Department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_125_employees`
--

CREATE TABLE `user_125_employees` (
  `emp_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_125_students`
--

CREATE TABLE `user_125_students` (
  `student_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `course` varchar(50) DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_125_students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_125_users`
--

CREATE TABLE `user_125_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `created_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_127_business`
--

CREATE TABLE `user_127_business` (
  `business_id` int(11) NOT NULL,
  `business_name` varchar(50) DEFAULT NULL,
  `owner_name` varchar(50) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `revenue` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_127_business`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_127_customer`
--

CREATE TABLE `user_127_customer` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(50) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_127_customer`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_127_employee`
--

CREATE TABLE `user_127_employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `salary` decimal(12,2) DEFAULT NULL,
  `department` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_127_employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_127_students`
--

CREATE TABLE `user_127_students` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `course` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_127_students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_128_Employee`
--

CREATE TABLE `user_128_Employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_128_Employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_128_orders`
--

CREATE TABLE `user_128_orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product` text DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `order_date` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_128_Student`
--

CREATE TABLE `user_128_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_128_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_128_users`
--

CREATE TABLE `user_128_users` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `created_at` text DEFAULT NULL,
  `age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_128_users`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_129_Employee`
--

CREATE TABLE `user_129_Employee` (
  `EmployeeID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL,
  `HireDate` date DEFAULT NULL,
  `Salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_130_Course`
--

CREATE TABLE `user_130_Course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_130_Course`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_130_Department`
--

CREATE TABLE `user_130_Department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `area` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_130_Department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_130_Employee`
--

CREATE TABLE `user_130_Employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_130_Employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_130_Student`
--

CREATE TABLE `user_130_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_130_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_131_department`
--

CREATE TABLE `user_131_department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `hod_name` varchar(50) DEFAULT NULL,
  `location` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_131_department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_131_Marks`
--

CREATE TABLE `user_131_Marks` (
  `mark_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_131_student`
--

CREATE TABLE `user_131_student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `class` varchar(20) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_131_student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_131_Student`
--

CREATE TABLE `user_131_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_131_student_marks`
--

CREATE TABLE `user_131_student_marks` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `subject` varchar(30) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `exam_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_131_student_marks`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_133_Course`
--

CREATE TABLE `user_133_Course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_133_Course`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_133_Department`
--

CREATE TABLE `user_133_Department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_133_Department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_133_Employee`
--

CREATE TABLE `user_133_Employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_133_Employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_133_Persons`
--

CREATE TABLE `user_133_Persons` (
  `PersonID` int(11) DEFAULT NULL,
  `LastName` varchar(255) DEFAULT NULL,
  `FirstName` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_133_Student`
--

CREATE TABLE `user_133_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_134_Course`
--

CREATE TABLE `user_134_Course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL,
  `rank` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_134_Course`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_134_Department`
--

CREATE TABLE `user_134_Department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `staff` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_134_Department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_134_Employee`
--

CREATE TABLE `user_134_Employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_134_Employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_134_Student`
--

CREATE TABLE `user_134_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_134_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_135_Marks`
--

CREATE TABLE `user_135_Marks` (
  `mark_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_135_Student`
--

CREATE TABLE `user_135_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_136_students`
--

CREATE TABLE `user_136_students` (
  `student_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_136_students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_137_customers`
--

CREATE TABLE `user_137_customers` (
  `customer_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_137_Employee`
--

CREATE TABLE `user_137_Employee` (
  `EmployeeID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL,
  `HireDate` date DEFAULT NULL,
  `Salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_137_employees`
--

CREATE TABLE `user_137_employees` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_137_Student`
--

CREATE TABLE `user_137_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_138_students`
--

CREATE TABLE `user_138_students` (
  `roll_no` int(11) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_138_students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_139_Course`
--

CREATE TABLE `user_139_Course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_139_Course`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_139_Department`
--

CREATE TABLE `user_139_Department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_139_Department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_139_Employee`
--

CREATE TABLE `user_139_Employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_139_Employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_139_Student`
--

CREATE TABLE `user_139_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_139_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_140_Course`
--

CREATE TABLE `user_140_Course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_140_Course`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_140_Department`
--

CREATE TABLE `user_140_Department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_140_Department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_140_Employee`
--

CREATE TABLE `user_140_Employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `AGE` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_140_Employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_140_Student`
--

CREATE TABLE `user_140_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_140_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_140_students`
--

CREATE TABLE `user_140_students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `marks` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_141_Business`
--

CREATE TABLE `user_141_Business` (
  `BusinessID` int(11) NOT NULL,
  `BusinessName` varchar(100) NOT NULL,
  `OwnerName` varchar(100) DEFAULT NULL,
  `BusinessType` varchar(50) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `City` varchar(100) DEFAULT NULL,
  `ZipCode` varchar(10) DEFAULT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `RegistrationDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_141_Business`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_141_Customers`
--

CREATE TABLE `user_141_Customers` (
  `CustomerID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `City` varchar(100) DEFAULT NULL,
  `ZipCode` varchar(10) DEFAULT NULL,
  `RegistrationDate` date DEFAULT curdate(),
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_141_Customers`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_141_Employees`
--

CREATE TABLE `user_141_Employees` (
  `Emp_ID` int(11) NOT NULL,
  `Emp_Name` varchar(20) DEFAULT NULL,
  `Emp_Age` int(11) DEFAULT NULL,
  `Emp_City` varchar(20) DEFAULT NULL,
  `Salary` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_141_Employees`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_141_Student`
--

CREATE TABLE `user_141_Student` (
  `StudentID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Age` int(11) DEFAULT NULL,
  `Grade` decimal(4,2) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_141_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_141_Students`
--

CREATE TABLE `user_141_Students` (
  `StudentID` int(11) NOT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Grade` decimal(4,2) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_142_Customers`
--

CREATE TABLE `user_142_Customers` (
  `CustomerID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `ZipCode` varchar(10) DEFAULT NULL,
  `salary` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_142_Customers`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_142_users`
--

CREATE TABLE `user_142_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_143_Customers`
--

CREATE TABLE `user_143_Customers` (
  `CustomerID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Age` int(11) DEFAULT NULL CHECK (`Age` > 0 and `Age` < 100),
  `Country` varchar(50) DEFAULT 'USA'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_143_Employes`
--

CREATE TABLE `user_143_Employes` (
  `CustomerID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Age` int(11) DEFAULT NULL CHECK (`Age` > 0 and `Age` < 100),
  `Country` varchar(50) DEFAULT 'USA',
  `Salary` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_143_Employes`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_144_mobile`
--

CREATE TABLE `user_144_mobile` (
  `name` char(50) DEFAULT NULL,
  `company` char(50) DEFAULT NULL,
  `expenditure` char(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_144_mobile`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_144_users`
--

CREATE TABLE `user_144_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `age` int(11) DEFAULT NULL,
  `branch` int(11) DEFAULT NULL,
  `section` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_144_users`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_146_Course`
--

CREATE TABLE `user_146_Course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_146_Course`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_146_Department`
--

CREATE TABLE `user_146_Department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_146_Department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_146_Employee`
--

CREATE TABLE `user_146_Employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_146_Employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_146_Student`
--

CREATE TABLE `user_146_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_146_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_147_Marks`
--

CREATE TABLE `user_147_Marks` (
  `mark_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_147_Student`
--

CREATE TABLE `user_147_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_147_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_148_student_marks`
--

CREATE TABLE `user_148_student_marks` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `subject` varchar(30) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `exam_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_148_student_marks`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_149_students`
--

CREATE TABLE `user_149_students` (
  `roll_no` int(11) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_149_students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_150_course`
--

CREATE TABLE `user_150_course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) DEFAULT NULL,
  `creadits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_150_course`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_151_students`
--

CREATE TABLE `user_151_students` (
  `roll_no` int(11) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_151_students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_152_students`
--

CREATE TABLE `user_152_students` (
  `roll_no` int(11) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_152_students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_153_Department`
--

CREATE TABLE `user_153_Department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `AREA` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_153_Department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_153_Employee`
--

CREATE TABLE `user_153_Employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `AGE` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_153_Employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_153_orders`
--

CREATE TABLE `user_153_orders` (
  `order_id` int(11) NOT NULL,
  `customer_name` text NOT NULL,
  `customer_email` text DEFAULT NULL,
  `product_name` text NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `order_status` text DEFAULT NULL,
  `payment_method` text DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_153_Student`
--

CREATE TABLE `user_153_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_153_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_153_students`
--

CREATE TABLE `user_153_students` (
  `student_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `age` int(11) DEFAULT NULL,
  `grade` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_155_students`
--

CREATE TABLE `user_155_students` (
  `student_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `course` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_156_students`
--

CREATE TABLE `user_156_students` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `course` varchar(50) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_159_Employees`
--

CREATE TABLE `user_159_Employees` (
  `EmployeeID` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Department` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_160_courses`
--

CREATE TABLE `user_160_courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_160_orders`
--

CREATE TABLE `user_160_orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_160_profiles`
--

CREATE TABLE `user_160_profiles` (
  `user_id` int(11) NOT NULL,
  `bio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_160_students`
--

CREATE TABLE `user_160_students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_160_users`
--

CREATE TABLE `user_160_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_160_users`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_162_Nasabah`
--

CREATE TABLE `user_162_Nasabah` (
  `Id` int(11) NOT NULL,
  `Nama` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_162_Nasabah`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_162_ViewKekayaanNasabah`
--

CREATE TABLE `user_162_ViewKekayaanNasabah` (
  `Id` int(11) DEFAULT NULL,
  `Kekayaan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_162_ViewKekayaanNasabah`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_168_department`
--

CREATE TABLE `user_168_department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `hod_name` varchar(50) DEFAULT NULL,
  `location` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_168_department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_168_Student`
--

CREATE TABLE `user_168_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_168_student_marks`
--

CREATE TABLE `user_168_student_marks` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `subject` varchar(30) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `exam_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_168_student_marks`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_170_student_marks`
--

CREATE TABLE `user_170_student_marks` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `subject` varchar(30) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `exam_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_170_student_marks`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_173_department`
--

CREATE TABLE `user_173_department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `hod_name` varchar(50) DEFAULT NULL,
  `location` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_173_department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_173_Student`
--

CREATE TABLE `user_173_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_173_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_173_student_marks`
--

CREATE TABLE `user_173_student_marks` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `subject` varchar(30) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `exam_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_173_student_marks`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_180_Employees`
--

CREATE TABLE `user_180_Employees` (
  `EmployeeID` int(11) NOT NULL,
  `EmployeeName` varchar(100) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Department` varchar(50) DEFAULT NULL,
  `Salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_181_Employees`
--

CREATE TABLE `user_181_Employees` (
  `EmployeeID` int(11) NOT NULL,
  `EmployeeName` varchar(100) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Department` varchar(50) DEFAULT NULL,
  `Salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_184_friends`
--

CREATE TABLE `user_184_friends` (
  `id` int(11) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `email` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_184_friends`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_185_mascota`
--

CREATE TABLE `user_185_mascota` (
  `id_mas` varchar(20) NOT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `especie` varchar(20) DEFAULT NULL,
  `raza` varchar(20) DEFAULT NULL,
  `sexo` varchar(20) DEFAULT NULL,
  `peso` int(11) DEFAULT NULL,
  `condicion_medica` varchar(50) DEFAULT NULL,
  `cc` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_185_propietario`
--

CREATE TABLE `user_185_propietario` (
  `cc` varchar(30) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_186_departments`
--

CREATE TABLE `user_186_departments` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_186_departments`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_186_emp`
--

CREATE TABLE `user_186_emp` (
  `empid` int(11) NOT NULL,
  `empname` varchar(50) DEFAULT NULL,
  `dept` varchar(50) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_186_emp`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_186_orders`
--

CREATE TABLE `user_186_orders` (
  `order_id` int(11) NOT NULL,
  `empid` int(11) DEFAULT NULL,
  `order_amount` int(11) DEFAULT NULL,
  `order_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_186_orders`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_193_student_marks`
--

CREATE TABLE `user_193_student_marks` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `subject` varchar(30) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `exam_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_196_Course`
--

CREATE TABLE `user_196_Course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_196_Course`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_196_Department`
--

CREATE TABLE `user_196_Department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_196_Department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_196_Employee`
--

CREATE TABLE `user_196_Employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_196_Employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_196_Student`
--

CREATE TABLE `user_196_Student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_196_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_197_kavya`
--

CREATE TABLE `user_197_kavya` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_197_kavya`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_198_email`
--

CREATE TABLE `user_198_email` (
  `email_id` int(11) NOT NULL,
  `sender` varchar(50) NOT NULL,
  `receiver` varchar(50) NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `sent_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_198_email`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_198_employee`
--

CREATE TABLE `user_198_employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) NOT NULL,
  `department` varchar(30) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `hire_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_198_employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_198_teacher`
--

CREATE TABLE `user_198_teacher` (
  `teacher_id` int(11) NOT NULL,
  `teacher_name` varchar(50) DEFAULT NULL,
  `subject` varchar(30) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_198_teacher`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_198_vanaja`
--

CREATE TABLE `user_198_vanaja` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `age` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_198_vanaja`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_198_worker`
--

CREATE TABLE `user_198_worker` (
  `worker_id` int(11) NOT NULL,
  `worker_name` varchar(50) NOT NULL,
  `person` varchar(30) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_198_worker`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_199_Customers`
--

CREATE TABLE `user_199_Customers` (
  `CustomerID` int(11) NOT NULL,
  `FirstName` varchar(255) DEFAULT NULL,
  `LastName` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Country` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_199_Employees`
--

CREATE TABLE `user_199_Employees` (
  `EmployeeID` int(11) NOT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `DepartmentID` int(11) DEFAULT NULL,
  `Salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_199_Students`
--

CREATE TABLE `user_199_Students` (
  `student_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_301_ALUNO`
--

CREATE TABLE `user_301_ALUNO` (
  `MATRICULA` int(11) NOT NULL,
  `NOME` varchar(100) DEFAULT NULL,
  `IDADE` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_301_DISCIPLINA`
--

CREATE TABLE `user_301_DISCIPLINA` (
  `ID_DISCIPLINA` int(11) NOT NULL,
  `NOME` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_302_ALUNO`
--

CREATE TABLE `user_302_ALUNO` (
  `MATRICULA` int(11) NOT NULL,
  `NOME` varchar(100) DEFAULT NULL,
  `IDADE` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_302_DISCIPILNA`
--

CREATE TABLE `user_302_DISCIPILNA` (
  `ID_DISCIPLINA` int(11) NOT NULL,
  `NOME` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_303_ALUNO`
--

CREATE TABLE `user_303_ALUNO` (
  `MATRICULA` int(11) NOT NULL,
  `NOME` varchar(30) DEFAULT NULL,
  `IDADE` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_303_ALUNO`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_303_DISCIPLINA`
--

CREATE TABLE `user_303_DISCIPLINA` (
  `ID_DISCIPLINA` int(11) NOT NULL,
  `NOME` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_304_ALUNO`
--

CREATE TABLE `user_304_ALUNO` (
  `MATRICULA` int(11) NOT NULL,
  `NOME` varchar(100) DEFAULT NULL,
  `IDADE` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_304_DISCIOLINA`
--

CREATE TABLE `user_304_DISCIOLINA` (
  `id_disciplina` int(11) NOT NULL,
  `nome` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_305_ALUNO`
--

CREATE TABLE `user_305_ALUNO` (
  `MATRICULA` int(11) NOT NULL,
  `NOME` varchar(100) DEFAULT NULL,
  `IDADE` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_305_DISCIPLINA`
--

CREATE TABLE `user_305_DISCIPLINA` (
  `ID_DISCIPLINA` int(11) NOT NULL,
  `NOME` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_306_DISCIPLINA`
--

CREATE TABLE `user_306_DISCIPLINA` (
  `ID_DISCIPLINA` int(11) NOT NULL,
  `NOME` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_306_LEKOS_LEKOS`
--

CREATE TABLE `user_306_LEKOS_LEKOS` (
  `Matricula_meleco` int(11) NOT NULL,
  `NOME` varchar(100) DEFAULT NULL,
  `IDADE` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_307_ALUNO`
--

CREATE TABLE `user_307_ALUNO` (
  `MATRICULA` int(11) NOT NULL,
  `NOME` varchar(100) DEFAULT NULL,
  `IDADE` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_307_DISCIPLINA`
--

CREATE TABLE `user_307_DISCIPLINA` (
  `ID_DISCIPLINA` int(11) NOT NULL,
  `NOME` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_308_ALUNO`
--

CREATE TABLE `user_308_ALUNO` (
  `MATRICULA` int(11) NOT NULL,
  `NOME` varchar(100) DEFAULT NULL,
  `IDADE` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_308_DISCIPLINA`
--

CREATE TABLE `user_308_DISCIPLINA` (
  `ID_DISCIPLINA` int(11) NOT NULL,
  `NOME` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_309_aluno`
--

CREATE TABLE `user_309_aluno` (
  `matricula` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `idade` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_309_disciplina`
--

CREATE TABLE `user_309_disciplina` (
  `id_diciplina` int(11) NOT NULL,
  `nome` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_310_Aluno`
--

CREATE TABLE `user_310_Aluno` (
  `matricula` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `idade` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_310_disciplina`
--

CREATE TABLE `user_310_disciplina` (
  `id_disciplina` int(11) NOT NULL,
  `nome` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_311_ALUNO`
--

CREATE TABLE `user_311_ALUNO` (
  `MATRICULA` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `idade` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_311_DISCIPLINA`
--

CREATE TABLE `user_311_DISCIPLINA` (
  `ID_DISCIPLINA` int(11) NOT NULL,
  `NOME` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_312_ALUNO`
--

CREATE TABLE `user_312_ALUNO` (
  `MATRICULA` int(11) NOT NULL,
  `NOME` varchar(100) DEFAULT NULL,
  `IDADE` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_312_DISCIPLINA`
--

CREATE TABLE `user_312_DISCIPLINA` (
  `ID_DISCIPLINA` int(11) NOT NULL,
  `NOME` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_313_ALUNO`
--

CREATE TABLE `user_313_ALUNO` (
  `MATRICULA` int(11) NOT NULL,
  `NOME` varchar(100) DEFAULT NULL,
  `IDADE` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_313_DISCIPLINA`
--

CREATE TABLE `user_313_DISCIPLINA` (
  `ID_DISCIPLINA` int(11) NOT NULL,
  `NOME` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_314_aluno`
--

CREATE TABLE `user_314_aluno` (
  `matricula` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `idade` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_314_disciplina`
--

CREATE TABLE `user_314_disciplina` (
  `id_disciplina` int(11) NOT NULL,
  `nome` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_315_ALUNO`
--

CREATE TABLE `user_315_ALUNO` (
  `MATRÍCULA` int(11) NOT NULL,
  `NOME` varchar(100) DEFAULT NULL,
  `IDADE` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_315_DISCIPLINA`
--

CREATE TABLE `user_315_DISCIPLINA` (
  `ID_DISCIPLINA` int(11) NOT NULL,
  `NOME` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_316_ALUNO`
--

CREATE TABLE `user_316_ALUNO` (
  `MATRICULA` int(11) NOT NULL,
  `NOME` varchar(100) DEFAULT NULL,
  `IDADE` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_316_DISCIPLINA`
--

CREATE TABLE `user_316_DISCIPLINA` (
  `ID_DISCIPLINA` int(11) NOT NULL,
  `NOME` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_317_ALUNO`
--

CREATE TABLE `user_317_ALUNO` (
  `MATRICULA` int(11) NOT NULL,
  `NOME` varchar(100) DEFAULT NULL,
  `IDADE` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_317_DISCIPLINA`
--

CREATE TABLE `user_317_DISCIPLINA` (
  `ID_DISCIPLINA` int(11) NOT NULL,
  `NOME` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_321_DEPARTMENT`
--

CREATE TABLE `user_321_DEPARTMENT` (
  `DEPT_ID` int(11) DEFAULT NULL,
  `DEPT_NAME` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_321_DEPARTMENT`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_321_EMPLOYEE`
--

CREATE TABLE `user_321_EMPLOYEE` (
  `EMP_ID` int(11) DEFAULT NULL,
  `NAME` varchar(20) DEFAULT NULL,
  `SALARY` int(11) DEFAULT NULL,
  `DEPARTMENT` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_321_EMPLOYEE`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_322_ACCOUNT`
--

CREATE TABLE `user_322_ACCOUNT` (
  `Acc_No` int(11) NOT NULL,
  `Name` varchar(30) DEFAULT NULL,
  `Balance` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_322_ACCOUNT`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_323_department`
--

CREATE TABLE `user_323_department` (
  `dep_id` int(11) DEFAULT NULL,
  `dept_name` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_323_department`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_323_employee`
--

CREATE TABLE `user_323_employee` (
  `emp_id` int(11) DEFAULT NULL,
  `emp_name` varchar(30) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `department` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_323_employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_323_orders`
--

CREATE TABLE `user_323_orders` (
  `order_id` int(11) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `emp_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_324_ACCOUNT`
--

CREATE TABLE `user_324_ACCOUNT` (
  `acc_no` int(11) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `balance` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_324_ACCOUNT`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_325_account`
--

CREATE TABLE `user_325_account` (
  `name` varchar(30) DEFAULT NULL,
  `balance` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_325_account`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_326_emp`
--

CREATE TABLE `user_326_emp` (
  `emp_id` int(11) DEFAULT NULL,
  `emp_name` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_326_employee`
--

CREATE TABLE `user_326_employee` (
  `emp_id` int(11) DEFAULT NULL,
  `emp_name` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_327_account`
--

CREATE TABLE `user_327_account` (
  `acc_no` int(11) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `balance` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_332_new_students`
--

CREATE TABLE `user_332_new_students` (
  `id` int(11) DEFAULT NULL,
  `salary` float DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_333_vendas`
--

CREATE TABLE `user_333_vendas` (
  `id` int(11) NOT NULL,
  `produto` varchar(50) DEFAULT NULL,
  `quant` int(11) DEFAULT NULL,
  `valor_unitario` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_333_vendas`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_334_teacher`
--

CREATE TABLE `user_334_teacher` (
  `name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_335_cursos`
--

CREATE TABLE `user_335_cursos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `profissao` varchar(100) DEFAULT NULL,
  `nascimento` varchar(100) DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `altura` decimal(4,2) DEFAULT NULL,
  `nacionalidade` varchar(100) DEFAULT NULL,
  `carga` int(11) DEFAULT NULL,
  `totaulas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_335_cursos`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_339_sita`
--

CREATE TABLE `user_339_sita` (
  `sr` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_339_sita`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_339_student`
--

CREATE TABLE `user_339_student` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_339_student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_342_Categorias`
--

CREATE TABLE `user_342_Categorias` (
  `ID_Categorias` int(11) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_343_employees`
--

CREATE TABLE `user_343_employees` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_345_bought`
--

CREATE TABLE `user_345_bought` (
  `order_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `purchase_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_345_bought`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_345_cars`
--

CREATE TABLE `user_345_cars` (
  `id` int(11) DEFAULT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `listing_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_345_cars`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_345_customer`
--

CREATE TABLE `user_345_customer` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_345_customer`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_345_customer_orders`
--

CREATE TABLE `user_345_customer_orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_date` date DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_345_customer_orders`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_345_sellers`
--

CREATE TABLE `user_345_sellers` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_345_sellers`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_345_sold`
--

CREATE TABLE `user_345_sold` (
  `order_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_345_sold`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_345_views`
--

CREATE TABLE `user_345_views` (
  `id` int(11) DEFAULT NULL,
  `car_id` int(11) DEFAULT NULL,
  `view_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_345_views`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_346_bank_settlements`
--

CREATE TABLE `user_346_bank_settlements` (
  `bank_ref_id` varchar(20) NOT NULL,
  `transaction_id` varchar(20) DEFAULT NULL,
  `beneficiary_name` varchar(100) DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `settled_amount` decimal(18,2) DEFAULT NULL,
  `applied_fx_rate` decimal(10,6) DEFAULT NULL,
  `settled_amount_in_inr` decimal(18,2) DEFAULT NULL,
  `settlement_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_346_bank_settlements`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_346_internal_transactions`
--

CREATE TABLE `user_346_internal_transactions` (
  `transaction_id` varchar(20) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT NULL,
  `fx_rate` decimal(10,6) DEFAULT NULL,
  `amount_in_inr` decimal(18,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_346_internal_transactions`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_347_Employee`
--

CREATE TABLE `user_347_Employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `job` varchar(50) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_347_Employee`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_348_Student`
--

CREATE TABLE `user_348_Student` (
  `RegNo` int(4) DEFAULT NULL,
  `NAME` varchar(20) DEFAULT NULL,
  `Address` varchar(40) DEFAULT NULL,
  `age` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_350_Departments`
--

CREATE TABLE `user_350_Departments` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_350_Departments`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_352_customers`
--

CREATE TABLE `user_352_customers` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_352_customers`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_352_forecast`
--

CREATE TABLE `user_352_forecast` (
  `customer_id` int(11) NOT NULL,
  `forecast_amount` float DEFAULT NULL,
  `forecast_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_352_forecast`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_352_orders`
--

CREATE TABLE `user_352_orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` float DEFAULT NULL,
  `order_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_352_orders`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_358_student_details`
--

CREATE TABLE `user_358_student_details` (
  `Student_id` int(11) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `AGE` int(11) DEFAULT NULL,
  `Department` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_358_student_details`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_359_Persons`
--

CREATE TABLE `user_359_Persons` (
  `PersonID` int(11) DEFAULT NULL,
  `LastName` varchar(255) DEFAULT NULL,
  `FirstName` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_366_Course`
--

CREATE TABLE `user_366_Course` (
  `courseID` char(8) NOT NULL,
  `courseTitle` varchar(50) NOT NULL,
  `Cost` decimal(6,2) DEFAULT NULL CHECK (`Cost` >= 0),
  `Credits` int(11) DEFAULT 2 CHECK (`Credits` between 0 and 200)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_366_Course`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_366_Semester`
--

CREATE TABLE `user_366_Semester` (
  `semesterID` char(5) NOT NULL,
  `semesterCode` int(11) DEFAULT NULL CHECK (`semesterCode` between 1 and 4),
  `Year` int(11) DEFAULT NULL CHECK (`Year` between 2000 and 9999)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_366_Semester`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_366_Student`
--

CREATE TABLE `user_366_Student` (
  `stdNo` char(5) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `givennames` varchar(50) NOT NULL,
  `Dept` char(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_366_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_369_Course`
--

CREATE TABLE `user_369_Course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(255) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_369_Course`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_373_employees`
--

CREATE TABLE `user_373_employees` (
  `employee_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_373_employees`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_374_users`
--

CREATE TABLE `user_374_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_375_Class`
--

CREATE TABLE `user_375_Class` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_378_batch`
--

CREATE TABLE `user_378_batch` (
  `_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`_id`)),
  `company_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`company_id`)),
  `voucherNumber` text DEFAULT NULL,
  `StockItemEntries` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`StockItemEntries`)),
  `parentType` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_378_batch`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_380_employees`
--

CREATE TABLE `user_380_employees` (
  `emp_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `job_title` varchar(50) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_380_employees`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_382_students`
--

CREATE TABLE `user_382_students` (
  `SID` int(11) NOT NULL,
  `SNAME` varchar(40) DEFAULT NULL,
  `Maths` int(11) DEFAULT NULL,
  `Physics` int(11) DEFAULT NULL,
  `Chemistry` int(11) DEFAULT NULL,
  `Total` int(11) GENERATED ALWAYS AS (`Maths` + `Physics` + `Chemistry`) VIRTUAL,
  `Average` decimal(5,2) GENERATED ALWAYS AS ((`Maths` + `Physics` + `Chemistry`) / 3) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_387_students`
--

CREATE TABLE `user_387_students` (
  `student_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `course` varchar(50) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_388_ventas`
--

CREATE TABLE `user_388_ventas` (
  `ventaID` int(11) NOT NULL,
  `Cliente` varchar(100) DEFAULT NULL,
  `producto` varchar(100) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `DNI` char(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_390_students`
--

CREATE TABLE `user_390_students` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_390_students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_390_students1`
--

CREATE TABLE `user_390_students1` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_390_students2`
--

CREATE TABLE `user_390_students2` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_391_employees`
--

CREATE TABLE `user_391_employees` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `commission` decimal(10,2) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `job_title` varchar(50) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_391_employees`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_395_COURSE`
--

CREATE TABLE `user_395_COURSE` (
  `CID` varchar(25) NOT NULL,
  `CNAME` char(25) DEFAULT NULL,
  `FEES` int(11) DEFAULT NULL,
  `STARTDATE` date DEFAULT NULL,
  `TID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_395_COURSE`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_395_Posting`
--

CREATE TABLE `user_395_Posting` (
  `P_ID` int(11) NOT NULL,
  `Department` varchar(50) DEFAULT NULL,
  `Place` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_395_Posting`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_395_Teacher`
--

CREATE TABLE `user_395_Teacher` (
  `T_ID` int(11) NOT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Department` varchar(50) DEFAULT NULL,
  `Date_of_join` date DEFAULT NULL,
  `Salary` int(11) DEFAULT NULL,
  `Gender` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_395_Teacher`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_395_TRAINER`
--

CREATE TABLE `user_395_TRAINER` (
  `TID` int(200) NOT NULL,
  `TNAME` char(35) DEFAULT NULL,
  `CITY` char(35) DEFAULT NULL,
  `HIREDATE` date DEFAULT NULL,
  `SALARY` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_395_TRAINER`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_399_students`
--

CREATE TABLE `user_399_students` (
  `id` int(11) NOT NULL,
  `marks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_399_students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_401_std_info`
--

CREATE TABLE `user_401_std_info` (
  `sno` int(11) NOT NULL,
  `sname` varchar(50) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `sage` int(11) DEFAULT NULL,
  `course` varchar(50) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_402_Students`
--

CREATE TABLE `user_402_Students` (
  `SRN` int(11) DEFAULT NULL,
  `S_name` varchar(20) DEFAULT NULL,
  `Cid` int(11) DEFAULT NULL,
  `C_name` varchar(20) DEFAULT NULL,
  `Marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_402_Students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_407_Student`
--

CREATE TABLE `user_407_Student` (
  `s_no` int(11) NOT NULL,
  `s_name` varchar(20) DEFAULT NULL,
  `s_class` varchar(10) NOT NULL,
  `s_addr` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_407_Student`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_408_employee_demographics`
--

CREATE TABLE `user_408_employee_demographics` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `birth_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_408_employee_demographics`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_408_employee_salary`
--

CREATE TABLE `user_408_employee_salary` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `occupation` varchar(50) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_408_employee_salary`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_408_parks_departments`
--

CREATE TABLE `user_408_parks_departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_408_parks_departments`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_408_users`
--

CREATE TABLE `user_408_users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_408_users`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_411_Author`
--

CREATE TABLE `user_411_Author` (
  `aid` int(11) NOT NULL,
  `authorname` varchar(50) DEFAULT NULL,
  `citizenship` varchar(50) DEFAULT NULL,
  `birthyear` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_411_Author`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_412_Students`
--

CREATE TABLE `user_412_Students` (
  `StudentID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Gender` varchar(1) NOT NULL,
  `Grade` int(2) NOT NULL,
  `Age` int(2) NOT NULL,
  `City` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_412_Students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_415_students`
--

CREATE TABLE `user_415_students` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_415_students`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_416_Customer`
--

CREATE TABLE `user_416_Customer` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_416_ServiceOption`
--

CREATE TABLE `user_416_ServiceOption` (
  `service_id` int(11) NOT NULL,
  `service_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_418_Railway_Corp`
--

CREATE TABLE `user_418_Railway_Corp` (
  `corp_name` varchar(100) NOT NULL,
  `corp_type` varchar(50) DEFAULT NULL,
  `corp_size` varchar(50) DEFAULT NULL,
  `hq_location` varchar(100) DEFAULT NULL,
  `founding_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_418_Railway_Corp`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_418_Stop_Point`
--

CREATE TABLE `user_418_Stop_Point` (
  `stop_code` varchar(10) NOT NULL,
  `stop_name` varchar(100) DEFAULT NULL,
  `stop_type` varchar(50) DEFAULT NULL,
  `physical_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_418_Stop_Point`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_419_Citas`
--

CREATE TABLE `user_419_Citas` (
  `Id_cita` int(11) NOT NULL,
  `Id_cliente` int(11) NOT NULL,
  `Id_barbero` int(11) NOT NULL,
  `Id_servicio` int(11) NOT NULL,
  `Fecha_cita` date NOT NULL,
  `Hora_cita` time NOT NULL,
  `Estado_cita` varchar(50) NOT NULL,
  `Observaciones` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_419_Citas`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_420_Students`
--

CREATE TABLE `user_420_Students` (
  `StudentID` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_422_estados_ticket`
--

CREATE TABLE `user_422_estados_ticket` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_422_estados_ticket`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_422_tickets`
--

CREATE TABLE `user_422_tickets` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `estado_id` int(11) NOT NULL,
  `asunto` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_cierre` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_422_usuarios`
--

CREATE TABLE `user_422_usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `rol` enum('usuario','soporte','admin') NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_425_users`
--

CREATE TABLE `user_425_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_425_users`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_426_kunal10`
--

CREATE TABLE `user_426_kunal10` (
  `CID` int(11) NOT NULL,
  `CName` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_426_kunal10`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_426_kunal11`
--

CREATE TABLE `user_426_kunal11` (
  `OrderID` int(11) NOT NULL,
  `Amt` int(11) DEFAULT NULL,
  `CID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_426_kunal11`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_427_boats`
--

CREATE TABLE `user_427_boats` (
  `bid` int(11) DEFAULT NULL,
  `bname` varchar(30) DEFAULT NULL,
  `color` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_427_boats1`
--

CREATE TABLE `user_427_boats1` (
  `bid` int(11) DEFAULT NULL,
  `bname` varchar(30) DEFAULT NULL,
  `color` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_427_boats2`
--

CREATE TABLE `user_427_boats2` (
  `bid` int(11) DEFAULT NULL,
  `bname` varchar(30) DEFAULT NULL,
  `color` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_427_boats2`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_427_sailors`
--

CREATE TABLE `user_427_sailors` (
  `sid` int(11) DEFAULT NULL,
  `sname` varchar(30) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `age` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_427_sri`
--

CREATE TABLE `user_427_sri` (
  `sno` int(4) DEFAULT NULL,
  `sname` varchar(10) DEFAULT NULL,
  `class` char(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_427_sri1`
--

CREATE TABLE `user_427_sri1` (
  `sno` int(4) DEFAULT NULL,
  `stu_name` varchar(10) DEFAULT NULL,
  `class` char(5) DEFAULT NULL,
  `address` varchar(5) DEFAULT NULL,
  `marks` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_427_sri1`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_428_DEPARTMENT`
--

CREATE TABLE `user_428_DEPARTMENT` (
  `D_ID` int(11) NOT NULL,
  `D_NAME` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_429_MARKS`
--

CREATE TABLE `user_429_MARKS` (
  `rollno` int(11) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_429_MARKS`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_429_STUDENT`
--

CREATE TABLE `user_429_STUDENT` (
  `rollno` int(11) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `stream` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_429_STUDENT`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Table structure for table `user_430_customers`
--

CREATE TABLE `user_430_customers` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_430_products`
--

CREATE TABLE `user_430_products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_databases`
--

CREATE TABLE `user_databases` (
  `user_id` int(11) NOT NULL,
  `db_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_databases`
-- Data has been removed for security reasons.
-- --------------------------------------------------------

--
-- Structure for view `bc`
--
DROP TABLE IF EXISTS `bc`;

CREATE ALGORITHM=UNDEFINED DEFINER=`unmqwlgl_teluguscripter`@`localhost` SQL SECURITY DEFINER VIEW `bc`  AS SELECT `user_425_users`.`id` AS `id`, `user_425_users`.`name` AS `name` FROM `user_425_users` ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ai_usage`
--
ALTER TABLE `ai_usage`
  ADD CONSTRAINT `ai_usage_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `query_logs`
--
ALTER TABLE `query_logs`
  ADD CONSTRAINT `query_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `saved_queries`
--
ALTER TABLE `saved_queries`
  ADD CONSTRAINT `saved_queries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_128_orders`
--
ALTER TABLE `user_128_orders`
  ADD CONSTRAINT `user_128_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_160_orders`
--
ALTER TABLE `user_160_orders`
  ADD CONSTRAINT `user_160_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_160_profiles`
--
ALTER TABLE `user_160_profiles`
  ADD CONSTRAINT `user_160_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_databases`
--
ALTER TABLE `user_databases`
  ADD CONSTRAINT `user_databases_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
