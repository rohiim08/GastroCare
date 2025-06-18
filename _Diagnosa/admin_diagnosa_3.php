<?php
session_start();
require_once 'C:\xampp\htdocs\gastrocare\db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../_Login/login.php');
    exit;
}

// Handle AJAX delete diagnosis result
if (isset($_POST['ajax_delete_result'])) {
    $result_id = $_POST['result_id'];
    $stmt = $conn->prepare("DELETE FROM diagnosis_results WHERE id = ?");
    $stmt->bind_param("i", $result_id);
    $success = $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => $success]);
    exit;
}

// Handle AJAX delete question
if (isset($_POST['ajax_delete_question'])) {
    $question_id = $_POST['question_id'];
    $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->bind_param("i", $question_id);
    $success = $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => $success]);
    exit;
}

// Handle add question
if (isset($_POST['add_question'])) {
    $session = $_POST['session'];
    $question_text = $_POST['question_text'];
    $field_name = $_POST['field_name'];
    $input_type = $_POST['input_type'];

    // Validate and prepare options
    $options = $_POST['options'] ?? '[]';
    if ($options && !json_decode($options)) {
        echo "<script>alert('Format JSON untuk options tidak valid'); window.location.href='admin_diagnosa_3.php';</script>";
        exit;
    }
    $options = $options ?: '[]'; // Default to empty array if not provided

    // Validate and prepare points
    $points = $_POST['points'] ?? '{}';
    if ($points && !json_decode($points)) {
        echo "<script>alert('Format JSON untuk points tidak valid'); window.location.href='admin_diagnosa_3.php';</script>";
        exit;
    }
    $points = $points ?: '{}'; // Default to empty object if not provided

    $stmt = $conn->prepare("INSERT INTO questions (session, question_text, field_name, input_type, options, points) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $session, $question_text, $field_name, $input_type, $options, $points);
    if ($stmt->execute()) {
        echo "<script>alert('Pertanyaan baru berhasil ditambahkan'); window.location.href='admin_diagnosa_3.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan pertanyaan'); window.location.href='admin_diagnosa_3.php';</script>";
    }
    $stmt->close();
}

// Handle edit question
if (isset($_POST['edit_question'])) {
    $question_id = $_POST['question_id'];
    $session = $_POST['session'];
    $question_text = $_POST['question_text'];
    $field_name = $_POST['field_name'];
    $input_type = $_POST['input_type'];

    // Validate and prepare options
    $options = $_POST['options'] ?? '[]';
    if ($options && !json_decode($options)) {
        echo "<script>alert('Format JSON untuk options tidak valid'); window.location.href='admin_diagnosa_3.php';</script>";
        exit;
    }
    $options = $options ?: '[]';

    // Validate and prepare points
    $points = $_POST['points'] ?? '{}';
    if ($points && !json_decode($points)) {
        echo "<script>alert('Format JSON untuk points tidak valid'); window.location.href='admin_diagnosa_3.php';</script>";
        exit;
    }
    $points = $points ?: '{}';

    $stmt = $conn->prepare("UPDATE questions SET session = ?, question_text = ?, field_name = ?, input_type = ?, options = ?, points = ? WHERE id = ?");
    $stmt->bind_param("isssssi", $session, $question_text, $field_name, $input_type, $options, $points, $question_id);

    if ($stmt->execute()) {
        echo "<script>alert('Pertanyaan berhasil diperbarui'); window.location.href='admin_diagnosa_3.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui pertanyaan'); window.location.href='admin_diagnosa_3.php';</script>";
    }
    $stmt->close();
}

// Pagination for diagnosis results
$results_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);
$offset = ($page - 1) * $results_per_page;

// Fetch total number of diagnosis results
$total_results_query = $conn->query("SELECT COUNT(*) as total FROM diagnosis_results");
$total_results = $total_results_query->fetch_assoc()['total'];
$total_pages = ceil($total_results / $results_per_page);

// Fetch diagnosis results for current page
$stmt = $conn->prepare("SELECT dr.id, dr.user_id, u.username, dr.total_score, dr.diagnosis_title, dr.diagnosis_text, dr.answers, dr.created_at FROM diagnosis_results dr JOIN users u ON dr.user_id = u.id ORDER BY dr.created_at DESC LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $results_per_page, $offset);
$stmt->execute();
$results = $stmt->get_result();

// Fetch questions
$questions = $conn->query("SELECT * FROM questions ORDER BY session, id");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Diagnosa - GastroCare</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="../_Template/template.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        nav {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: #fff;
        }

        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            padding-top: 100px;
        }

        .header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            margin: 0;
            font-size: 2em;
            font-weight: 600;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 1em;
            opacity: 0.9;
        }

        .nav-links {
            margin-top: 10px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 5px 10px;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        .section {
            background: white;
            padding: 20px;
            border-radius: 1px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding-top: 60px;
        }

        .section h2 {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th,
        td {
            border: 1px solid #e0e0e0;
            padding: 10px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }

        td {
            background-color: white;
        }

        .diagnosis-table th:nth-child(1),
        .diagnosis-table td:nth-child(1) {
            width: 5%;
        }

        .diagnosis-table th:nth-child(2),
        .diagnosis-table td:nth-child(2) {
            width: 15%;
        }

        .diagnosis-table th:nth-child(3),
        .diagnosis-table td:nth-child(3) {
            width: 10%;
        }

        .diagnosis-table th:nth-child(4),
        .diagnosis-table td:nth-child(4) {
            width: 30%;
        }

        .diagnosis-table th:nth-child(5),
        .diagnosis-table td:nth-child(5) {
            width: 20%;
        }

        .diagnosis-table th:nth-child(6),
        .diagnosis-table td:nth-child(6) {
            width: 20%;
        }

        .questions-table th:nth-child(1),
        .questions-table td:nth-child(1) {
            width: 5%;
        }

        .questions-table th:nth-child(2),
        .questions-table td:nth-child(2) {
            width: 8%;
        }

        .questions-table th:nth-child(3),
        .questions-table td:nth-child(3) {
            width: 25%;
        }

        .questions-table th:nth-child(4),
        .questions-table td:nth-child(4) {
            width: 15%;
        }

        .questions-table th:nth-child(5),
        .questions-table td:nth-child(5) {
            width: 10%;
        }

        .questions-table th:nth-child(6),
        .questions-table td:nth-child(6) {
            width: 17%;
        }

        .questions-table th:nth-child(7),
        .questions-table td:nth-child(7) {
            width: 15%;
        }

        .questions-table th:nth-child(8),
        .questions-table td:nth-child(8) {
            width: 10%;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #2c3e50;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .btn {
            padding: 8px 16px;
            margin: 4px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            transition: background-color 0.3s, transform 0.1s;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn.primary {
            background-color: #3498db;
            color: white;
        }

        .btn.primary:hover {
            background-color: #2980b9;
        }

        .btn.danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn.danger:hover {
            background-color: #c0392b;
        }

        .btn.info {
            background-color: #2ecc71;
            color: white;
        }

        .btn.info:hover {
            background-color: #27ae60;
        }

        .btn.warning {
            background-color: #f39c12;
            color: white;
        }

        .btn.warning:hover {
            background-color: #e67e22;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 16px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #2980b9;
        }

        .pagination a.disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .pagination a.disabled:hover {
            background-color: #cccccc;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            width: 90%;
            max-width: 600px;
            max-height: 68vh;
            overflow-y: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-content h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .progress-container {
            margin: 15px 0;
            position: relative;
            padding-top: 30px;
            border-radius: 20px;
        }

        .progress-container .bar {
            display: flex;
            height: 20px;
            background: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            width: 95%;
            margin: auto;
        }

        .progress-container .bar .section {
            flex: 1;
        }

        .progress-container .bar .green {
            background: #28a745;
        }

        .progress-container .bar .light-green {
            background: #90ee90;
        }

        .progress-container .bar .yellow {
            background: #ffc107;
        }

        .progress-container .bar .orange {
            background: #ff8c00;
        }

        .progress-container .bar .red {
            background: #dc3545;
        }

        .progress-container .marker-container {
            position: relative;
            height: 20px;
        }

        .progress-container .marker {
            position: absolute;
            top: -10px;
            width: 30px;
            height: 30px;
            background: #2c3e50;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            transition: left 0.3s ease;
        }

        .progress-container .labels {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 14px;
            color: white;
            font-weight: 500;
        }

        .progress-container .labels span {
            flex: 1;
            text-align: center;
        }

        .detail-item {
            margin-bottom: 15px;
        }

        .detail-item strong {
            display: inline-block;
            width: 120px;
            color: #2c3e50;
            font-weight: 500;
        }

        .answers-list {
            list-style: none;
            padding: 0;
            margin: 10px 0;
        }

        .answers-list li {
            padding: 10px;
            background: #f8f9fa;
            margin-bottom: 5px;
            border-radius: 4px;
            font-size: 14px;
        }

        .notification {
            display: none;
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #2ecc71;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            font-size: 14px;
            max-width: 90%;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .notification.error {
            background: #e74c3c;
        }

        .notification .timer {
            font-weight: bold;
        }

        .notification .btn {
            padding: 6px 12px;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .admin-container header {
                padding: 10px;
                padding-top: 80px;
            }

            .nav-links {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            .nav-links a {
                margin: 5px 0;
                padding: 8px;
                width: 100%;
                text-align: center;
            }

            .section {
                padding-top: 50px;
            }

            table {
                font-size: 12px;
            }

            th,
            td {
                padding: 6px;
            }

            .btn {
                padding: 6px 12px;
                font-size: 12px;
            }

            .modal-content {
                width: 95%;
                padding: 15px;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .progress-container .bar {
                height: 25px;
            }

            .progress-container .marker {
                width: 30px;
                height: 30px;
                font-size: 12px;
            }

            .progress-container .labels {
                font-size: 10px;
            }

            .notification {
                max-width: 95%;
                font-size: 12px;
                padding: 8px 12px;
                bottom: 10px;
            }

            .notification .btn {
                padding: 4px 8px;
                font-size: 10px;
            }
        }
    </style>
</head>

<body>
    <nav>
        <div class="nav-header">
            <a href="../admin.php" class="nav-close"><img src="assets/close.png" alt="Tutup" /></a>
            <a href="#" class="nav-logo"><img src="assets/logo.png" alt="Logo"></a>
            <div class="nav-profile">
                <img src="assets/user.png" alt="profile">
                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <i class='bx bx-caret-down'></i>
                <div class="profile-dropdown">
                    <button class="logout-button" onclick="window.location.href='../_Login/logout.php'">Logout</button>
                </div>
            </div>
        </div>
    </nav>

    <div id="deleteNotification" class="notification">
        <span id="deleteMessage"></span>
        <span class="timer" id="deleteTimer">10</span>
        <button class="btn warning" onclick="undoDelete()">Undo</button>
    </div>

    <div class="admin-container">
        <div class="header">
            <h1>Admin Diagnosa - GastroCare</h1>
            <!-- <p>Manage Diagnosis Results and Questions Efficiently</p> -->
            <div class="nav-links">
                <a href="#manage-diagnosis">Manage Diagnosis Results</a>
                <a href="#add-question">Add New Question</a>
                <a href="#manage-questions">Manage Questions</a>
            </div>
        </div>

        <!-- Manage Diagnosis Results -->
        <div class="section" id="manage-diagnosis">
            <h2>Manage Diagnosis Results</h2>
            <div class="table-container">
                <table class="diagnosis-table">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Total Score</th>
                        <th>Diagnosis Title</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = $results->fetch_assoc()): ?>
                        <tr id="result-<?php echo $row['id']; ?>">
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo $row['total_score']; ?></td>
                            <td><?php echo htmlspecialchars($row['diagnosis_title']); ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn info" onclick='openDetailsModal(<?php echo json_encode($row); ?>)'>Details</button>
                                    <form method="post" style="display:inline;" onsubmit="return handleDelete(event, 'result', <?php echo $row['id']; ?>)">
                                        <input type="hidden" name="result_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
            <div class="pagination">
                <a href="?page=<?php echo $page - 1; ?>" class="<?php echo $page <= 1 ? 'disabled' : ''; ?>">Prev</a>
                <span>Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                <a href="?page=<?php echo $page + 1; ?>" class="<?php echo $page >= $total_pages ? 'disabled' : ''; ?>">Next</a>
            </div>
        </div>

        <!-- Add New Question -->
        <div class="section" id="add-question">
            <h2>Add New Question</h2>
            <form method="post">
                <div class="form-group">
                    <label>Session</label>
                    <input type="number" name="session" required min="1" max="6">
                </div>
                <div class="form-group">
                    <label>Question Text</label>
                    <textarea name="question_text" required></textarea>
                </div>
                <div class="form-group">
                    <label>Field Name</label>
                    <input type="text" name="field_name" required>
                </div>
                <div class="form-group">
                    <label>Input Type</label>
                    <select name="input_type" required>
                        <option value="select">Select</option>
                        <option value="checkbox">Checkbox</option>
                        <option value="number">Number</option>
                        <option value="button">Button</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Options (JSON format, e.g., ["option1", "option2"])</label>
                    <textarea name="options"></textarea>
                </div>
                <div class="form-group">
                    <label>Points (JSON format, e.g., {"option1": 2, "option2": 1})</label>
                    <textarea name="points"></textarea>
                </div>
                <button type="submit" name="add_question" class="btn primary">Add Question</button>
            </form>
        </div>

        <!-- Existing Questions -->
        <div class="section" id="manage-questions">
            <h2>Existing Questions</h2>
            <div class="table-container">
                <table class="questions-table">
                    <tr>
                        <th>ID</th>
                        <th>Session</th>
                        <th>Question</th>
                        <th>Field Name</th>
                        <th>Input Type</th>
                        <th>Options</th>
                        <th>Points</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = $questions->fetch_assoc()): ?>
                        <tr id="question-<?php echo $row['id']; ?>">
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['session']; ?></td>
                            <td><?php echo htmlspecialchars($row['question_text']); ?></td>
                            <td><?php echo htmlspecialchars($row['field_name']); ?></td>
                            <td><?php echo $row['input_type']; ?></td>
                            <td><?php echo htmlspecialchars($row['options']); ?></td>
                            <td><?php echo htmlspecialchars($row['points']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn primary" onclick='openEditModal(<?php echo json_encode($row); ?>)'>Edit</button>
                                    <form method="post" style="display:inline;" onsubmit="return handleDelete(event, 'question', <?php echo $row['id']; ?>)">
                                        <input type="hidden" name="question_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>

        <!-- Edit Question Modal -->
        <div id="editQuestionModal" class="modal">
            <div class="modal-content">
                <h3>Edit Pertanyaan</h3>
                <form method="post" id="editQuestionForm">
                    <input type="hidden" name="question_id" id="edit_question_id">
                    <div class="form-group">
                        <label for="edit_session">Session</label>
                        <input type="number" name="session" id="edit_session" required min="1" max="6">
                    </div>
                    <div class="form-group">
                        <label for="edit_question_text">Question Text</label>
                        <textarea name="question_text" id="edit_question_text" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_field_name">Field Name</label>
                        <input type="text" name="field_name" id="edit_field_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_input_type">Input Type</label>
                        <select name="input_type" id="edit_input_type" required>
                            <option value="select">Select</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="number">Number</option>
                            <option value="button">Button</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_options">Options (JSON format, e.g., ["option1", "option2"])</label>
                        <textarea name="options" id="edit_options"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_points">Points (JSON format, e.g., {"option1": 2, "option2": 1})</label>
                        <textarea name="points" id="edit_points"></textarea>
                    </div>
                    <div class="modal-buttons">
                        <button type="submit" name="edit_question" class="btn primary">Save Changes</button>
                        <button type="button" onclick="closeEditModal()" class="btn danger">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Details Modal -->
        <div id="detailsModal" class="modal">
            <div class="modal-content">
                <h3>Diagnosis Result Details</h3>
                <div class="detail-item"><strong>User ID:</strong> <span id="detail_user_id"></span></div>
                <div class="detail-item"><strong>Username:</strong> <span id="detail_username"></span></div>
                <div class="detail-item"><strong>Total Score:</strong> <span id="detail_total_score"></span></div>
                <div class="progress-container">
                    <h2>Skala Kondisi Lambung</h2>
                    <div class="bar">
                        <div class="section green"></div>
                        <div class="section light-green"></div>
                        <div class="section yellow"></div>
                        <div class="section orange"></div>
                        <div class="section red"></div>
                    </div>
                    <div class="marker-container">
                        <div class="marker" id="detail_marker">0</div>
                    </div>
                    <div class="labels">
                        <span>0-8 (Aman)</span>
                        <span>9-16 (Aman Menengah)</span>
                        <span>17-24 (Menengah)</span>
                        <span>25-32 (Waspada)</span>
                        <span>33-40 (Kritis)</span>
                    </div>
                </div>
                <div class="detail-item"><strong>Diagnosis Title:</strong> <span id="detail_diagnosis_title"></span></div>
                <div class="detail-item"><strong>Diagnosis Text:</strong>
                    <p id="detail_diagnosis_text"></p>
                </div>
                <div class="detail-item"><strong>Answers:</strong>
                    <ul id="detail_answers" class="answers-list"></ul>
                </div>
                <div class="detail-item"><strong>Created At:</strong> <span id="detail_created_at"></span></div>
                <div class="modal-buttons">
                    <button type="button" onclick="closeDetailsModal()" class="btn primary">Close</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-content">
        <div class="container">
            <div class="footer-content-content">
                <h2 class="footer-content-logo">GASTROCARE</h2>
                <div class="footer-content-main">
                    <h3 class="footer-content-title">Butuh Bantuan untuk Kesehatan Lambung Anda?</h3>
                    <p class="footer-content-desc">
                        Dapatkan wawasan akurat, analisis bertenaga AI, dan panduan yang didukung pakar tentang semua kondisi terkait lambung.
                    </p>
                </div>
                <div class="footer-content-bottom">
                    <p class="copyright">© 2025 GASTROCARE</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="../main.js"></script>
    <script src="../_Template/profile.js"></script>
    <script>
        let deleteTimeout = null;
        let pendingDelete = null;

        // Ensure notification is hidden on page load
        window.addEventListener('load', () => {
            const notification = document.getElementById('deleteNotification');
            notification.style.display = 'none';
            pendingDelete = null;
            if (deleteTimeout) {
                clearInterval(deleteTimeout);
                deleteTimeout = null;
            }
        });

        // Smooth scroll with offset for anchor links
        document.querySelectorAll('.nav-links a').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                const headerHeight = document.querySelector('nav').offsetHeight;
                const offsetPosition = targetElement.offsetTop - headerHeight - 10;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            });
        });

        function openEditModal(question) {
            document.getElementById('edit_question_id').value = question.id;
            document.getElementById('edit_session').value = question.session;
            document.getElementById('edit_question_text').value = question.question_text;
            document.getElementById('edit_field_name').value = question.field_name;
            document.getElementById('edit_input_type').value = question.input_type;
            document.getElementById('edit_options').value = question.options;
            document.getElementById('edit_points').value = question.points;
            document.getElementById('editQuestionModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editQuestionModal').style.display = 'none';
        }

        function openDetailsModal(data) {
            document.getElementById('detail_user_id').textContent = data.user_id || 'N/A';
            document.getElementById('detail_username').textContent = data.username || 'N/A';
            document.getElementById('detail_total_score').textContent = data.total_score || '0';
            document.getElementById('detail_diagnosis_title').textContent = data.diagnosis_title || 'N/A';
            document.getElementById('detail_diagnosis_text').textContent = data.diagnosis_text || 'N/A';
            document.getElementById('detail_created_at').textContent = data.created_at || 'N/A';

            const score = Math.max(0, Math.min(40, parseInt(data.total_score) || 0));
            const marker = document.getElementById('detail_marker');
            marker.textContent = score;
            const percentage = (score / 40) * 100;
            marker.style.left = `calc(${percentage}% - 15px)`;

            const answersList = document.getElementById('detail_answers');
            answersList.innerHTML = '';
            try {
                const answers = JSON.parse(data.answers || '{}');
                const sortedAnswers = Object.entries(answers).sort((a, b) => {
                    const sessionA = answers[a[0]].session || 0;
                    const sessionB = answers[b[0]].session || 0;
                    return sessionA - sessionB;
                });
                for (const [question, {
                        answer,
                        points
                    }] of sortedAnswers) {
                    const li = document.createElement('li');
                    li.innerHTML = `<strong>${question}</strong>: ${answer} (${points} poin)`;
                    answersList.appendChild(li);
                }
            } catch (e) {
                const li = document.createElement('li');
                li.textContent = 'No answers available';
                answersList.appendChild(li);
            }

            document.getElementById('detailsModal').style.display = 'flex';
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').style.display = 'none';
        }

        function confirmDelete(message) {
            return confirm(message);
        }

        function handleDelete(event, type, id) {
            event.preventDefault();
            const message = type === 'result' ? 'Apakah Anda yakin ingin menghapus hasil diagnosis ini?' : 'Apakah Anda yakin ingin menghapus pertanyaan ini?';
            if (!confirmDelete(message)) {
                return false;
            }

            // Hide the row immediately
            const row = document.getElementById(`${type}-${id}`);
            row.style.display = 'none';

            // Show notification with timer
            const notification = document.getElementById('deleteNotification');
            const messageElement = document.getElementById('deleteMessage');
            const timerElement = document.getElementById('deleteTimer');
            messageElement.textContent = type === 'result' ? 'Hasil diagnosis dihapus' : 'Pertanyaan dihapus';
            notification.style.display = 'flex';

            // Store pending delete
            pendingDelete = {
                type,
                id,
                row
            };

            // Start 10-second timer
            let timeLeft = 10;
            timerElement.textContent = timeLeft;
            deleteTimeout = setInterval(() => {
                timeLeft--;
                timerElement.textContent = timeLeft;
                if (timeLeft <= 0) {
                    clearInterval(deleteTimeout);
                    finalizeDelete();
                }
            }, 1000);

            return false;
        }

        function undoDelete() {
            if (!pendingDelete) return;

            clearInterval(deleteTimeout);
            const notification = document.getElementById('deleteNotification');
            notification.style.display = 'none';

            // Restore the row
            pendingDelete.row.style.display = '';

            // Clear pending delete
            pendingDelete = null;
            deleteTimeout = null;
        }

        async function finalizeDelete() {
            const notification = document.getElementById('deleteNotification');
            notification.style.display = 'none';

            if (!pendingDelete) return;

            const {
                type,
                id
            } = pendingDelete;
            const formData = new FormData();
            formData.append(type === 'result' ? 'ajax_delete_result' : 'ajax_delete_question', true);
            formData.append(type === 'result' ? 'result_id' : 'question_id', id);

            try {
                const response = await fetch('admin_diagnosa_3.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    alert(type === 'result' ? 'Hasil diagnosis berhasil dihapus' : 'Pertanyaan berhasil dihapus');
                } else {
                    throw new Error('Deletion failed');
                }
            } catch (error) {
                console.error('Error deleting:', error);
                pendingDelete.row.style.display = '';
                notification.classList.add('error');
                notification.style.display = 'flex';
                document.getElementById('deleteMessage').textContent = 'Gagal menghapus data';
                setTimeout(() => {
                    notification.style.display = 'none';
                    notification.classList.remove('error');
                }, 3000);
            } finally {
                pendingDelete = null;
                deleteTimeout = null;
            }
        }

        // Close modals on outside click
        document.getElementById('editQuestionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
        document.getElementById('detailsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailsModal();
            }
        });
    </script>
</body>

</html>