<?php
// Start the session
session_start();

// Handle logout if the logout query parameter is present
if (isset($_GET['logout'])) {
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Redirect to the login page
    header("Location: index.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="asset/favicon.ico" type="image/ico">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/bootstrap.css">
    <title>Trang chủ</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <div class="logo-container navbar-brand">
                    <a class="logo" href="home.php">Fastodo</a>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link" href="#!">About</a></li>
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#!">All Products</a></li>
                                <li><hr class="dropdown-divider" /></li>
                                <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                                <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                            </ul>
                        </li> -->
                    </ul>
                    <form class="d-flex my-2 my-lg-0">
                        <input
                            class="form-control mr-sm-2 me-2"
                            id="searchBar"
                            type="search"
                            placeholder="Tìm kiếm"
                            aria-label="Tìm kiếm"
                        >
                        <a href="?logout=true" class="btn btn-primary fw-bold">Đăng xuất</a>
                    </form>
                </div>
            </div>
        </nav>
    <main>
        <div class="main-box">
            <button id="mbtn" class="btn btn-primary fw-bold"> <span style="color: #fff; font-size: 16px;">&#10133;</span>  Thêm</button>
            <div class="row g-0">
                <div class="sticky-top float-left" id="sticky-div"></div>
                    <div class="taskList">
                        <div class="mt-3 mx-1 task-container" id="task-container"></div>
                    </div>
            </div>
        </div>
    </main>
    <div class="container-fluid">
    <!-- Trigger/Open The Modal -->
        <div id="modalDialog" class="modal">
            <div class="modal-content animate-top">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Thêm một công việc cần làm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                </div>
                <form method="post" id="taskForm">
                    <div class="modal-body">
                        <!-- Form submission status -->
                        <div class="response"></div>
                        
                        <!-- Hidden input for task ID -->
                        <input type="hidden" name="taskId" id="taskId">
                      
                        <div class="form-group">
                            <input type="text" name="title" id="modalTaskTitle" class="form-control" placeholder="Tên công việc" required="">
                        </div>
                        <div class="form-group">
                            <textarea name="content" id="modalTaskContent" class="form-control" placeholder="Nội dung công việc" rows="6" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary fw-bold" id="submitButton">Thêm tác vụ mới</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="js/magic-grid.cjs.js"></script>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
    <script>
        $(document).ready(function() {
            const modal = $('#modalDialog');
            const span = $(".close");
            const taskForm = $('#taskForm');
            const modalTitle = $('#modalTitle');
            const modalTaskTitle = $('#modalTaskTitle');
            const modalTaskContent = $('#modalTaskContent');
            const submitButton = $('#submitButton');
            const searchInput = $('#searchBar');

            function loadTasks(searchQuery = '') {
                $.ajax({
                    url: 'php/fetch_tasks.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $('#task-container').empty();
                        let itemCount = 1;

                        // Filter tasks based on search query
                        const filteredTasks = response.filter(task => 
                            task.Title.toLowerCase().includes(searchQuery.toLowerCase()) || 
                            task.Content.toLowerCase().includes(searchQuery.toLowerCase())
                        );

                        filteredTasks.forEach(task => {
                            $('#task-container').append(`
                                <div class="item${itemCount} taskItem" style="background: #f9e2af; width: 320px; min-height: 180px; box-shadow: 0 0 10px 0 rgba(0,0,0,0.4); display: flex; justify-content: center; align-items: center; border-radius: 12px; padding: 20px; margin: 10px; flex-direction: column; position: relative;">
                                    <div style="flex-grow: 1;">
                                        <div style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                                            <div style="flex-grow: 1; margin-right: 20px">
                                                <h3 style="word-break: break-word;">${task.Title}</h3>
                                            </div>
                                            <div>
                                                <button type="button" class="btn-edit" data-task-id="${task.Id}" data-task-title="${task.Title}" data-task-content="${task.Content}" style="background-color: transparent; border: none; color: #000; font-size: 16px; font-weight: bold; cursor: pointer; text-decoration: underline;">Edit</button>
                                            </div>
                                        </div>
                                        <br>
                                        <p style="min-width: 280px; word-break: break-word;">${task.Content}</p>
                                    </div>
                                    <div style="display: flex; flex-direction: row; justify-content: space-between; margin-top: 20px; width: 100%;">
                                        <div style="flex-grow: 1;">
                                            <p class="text-muted">${task.DateTime}</p>
                                        </div>
                                        <div>
                                            <button class="task-delete-button" data-task-id="${task.Id}" aria-label="X" style="background-color: transparent; border: none; color: #000; font-size: 16px; font-weight: bold; cursor: pointer; text-decoration: underline;">Done</button>
                                        </div>
                                    </div>
                                </div>
                            `);
                            itemCount++;
                        });

                        const magicGrid = new MagicGrid({
                            container: "#task-container",
                            items: itemCount,
                            static: true,
                            gutter: 20,
                            useMin: true,
                            animate: true
                        });

                        magicGrid.listen();
                    },
                    error: function() {
                        console.error('Error loading tasks');
                    }
                });
            }

            loadTasks();
            
            searchInput.on('input', function() {
                const searchQuery = $(this).val();
                loadTasks(searchQuery);
            });

            // Function to show modal for editing or adding tasks
            function showModal(taskId = null) {
                if (taskId) {
                    const taskTitle = $(`[data-task-id="${taskId}"]`).data('task-title');
                    const taskContent = $(`[data-task-id="${taskId}"]`).data('task-content');


                    modalTitle.text('Chỉnh sửa');
                    $('#taskId').val(taskId);
                    modalTaskTitle.val(taskTitle);    // Populate title input field
                    modalTaskContent.val(taskContent);  // Populate content textarea field
                    submitButton.text('Lưu thay đổi');
                } else {
                    $('#taskId').val('');
                    $('#title').val('');
                    $('#content').val('');
                    modalTitle.text('Thêm công việc mới');
                    submitButton.html('<span style="color: #FEFEFE; font-size: 16px;">&#10133;</span> Thêm');
                }
                modal.show();
            }



            function hideModal() {
                modal.hide();
            }

            // Initialize modal
            modal.hide();

            // Event listener for opening the modal for adding a new task
            $('#mbtn').on('click', function() {
                showModal();
            });

            // Event listener for opening the modal for editing a task
            $(document).on('click', '.btn-edit', function() {
                const taskId = $(this).data('task-id');
                const taskTitle = $(this).data('task-title');
                const taskContent = $(this).data('task-content');
                showModal(taskId, taskTitle, taskContent);
            });

            // Event listener for closing the modal
            span.on('click', hideModal);

            $('body').on('click', function(e) {
                if ($(e.target).hasClass("modal")) {
                    hideModal();
                }
            });

            // Event listener for form submission
            taskForm.submit(function(e) {
                e.preventDefault();
                $('.modal-body').css('opacity', '0.5');
                $('.btn').prop('disabled', true);

                var taskForm = $(this);
                var url = $('#taskId').val() ? 'php/update_task.php' : 'php/add_task.php';
                var data = $('#taskId').val() ? 'update_task_submit=1&' + taskForm.serialize() : 'new_task_submit=1&' + taskForm.serialize();

                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        console.log('AJAX Success:', response);
                        $('.modal-body').css('opacity', '1');
                        $('.btn').prop('disabled', false);
                        modal.hide();
                        console.log('Operation successful. Reloading tasks...');
                        loadTasks();
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        $('.modal-body').css('opacity', '1');
                        $('.btn').prop('disabled', false);
                        modal.hide();
                    }
                });
            });

            // Event listener for task deletion
            $(document).on('click', '.task-delete-button', function() {
                const taskId = $(this).data('task-id');
                $.ajax({
                    type: 'POST',
                    url: 'php/delete_task.php',
                    data: { taskId: taskId },
                    success: function(response) {
                        console.log('Task deleted successfully');
                        loadTasks();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
</body>
<footer class="site-footer">
    <div class="footer-container">
        <div class="team-section">
            <div class="logo-container">
                <a class="logo" href="home.php">Fastodo</a>
            </div>

            <br>
            <ul class="team-list">
                <li>Trần Minh Thuận</li>
                <li>Trần Thành Vinh</li>
                <li>Y Adin Byã</li>
            </ul>
        </div>
        <div>
            <img src="./asset/shao-sitting.png" alt="Image" class="footer-image">
        </div>
    </div>
</footer>
</html>