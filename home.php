<?php
   session_start();

   include("php/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: index.php");
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/bootstrap-grid.min.css">
    <title>Trang chủ</title>
</head>
<body>
    <div class="nav">
        <div class="logo">
            <p><a href="home.php">Fastodo</a> </p>
        </div>
        <div class="right-links">
            <?php 
            $id = $_SESSION['id'];
            $query = mysqli_query($conn,"SELECT*FROM users WHERE Id=$id");
            $query2 = mysqli_query($conn,"SELECT*FROM lists WHERE UserId=$id");
            $row = mysqli_num_rows($query2);

            while($result = mysqli_fetch_assoc($query)){
                $res_Uname = $result['Username'];
                $res_Email = $result['Email'];
                $res_id = $result['Id'];
            }

            
            echo "<a href='edit.php?Id=$res_id' >Hiệu chỉnh</a>";
            ?>

            <a href="php/logout.php"> <button class="btn">Đăng xuất</button> </a>

        </div>
    </div>
    <main>
        <div class="main-box">
            <button id="mbtn" class="btn btn-primary">Thêm</button>
            <div class="row">
            <div class="sticky-top float-left" id="sticky-div">
                <!-- Add the control group here -->
            </div>
                <div class="taskList">
                    <div class="mt-3 mx-1 task-container" id="task-container">

                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="container-fluid">
    <!-- Trigger/Open The Modal -->
    <div id="modalDialog" class="modal">
        <div class="modal-content animate-top">
            <div class="modal-header">
                <h5 class="modal-title">Thêm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
            </div>
            <form method="post" id="addTaskForm">
            <div class="modal-body">
                <!-- Form submission status -->
                <div class="response"></div>
                
                <!-- Contact form -->
                <div class="form-group">
                    <input type="text" name="title" id="title" class="form-control" placeholder="Tên công việc" required="">
                </div>
                <div class="form-group">
                    <textarea name="content" id="content" class="form-control" placeholder="Nội dung công việc" rows="6" required></textarea>
                </div>
            </div>
                <div class="modal-footer">
                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
            </div>
        </div>
    </div>
    <script src="js/magic-grid.cjs.js"></script>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script>
    /*
    * Modal popup to add a task
    * When the user clicks the button, open the modal
    * When the user clicks on (x), close the modal
    * When the user clicks anywhere outside of the modal, close it
    */
    // Get the modal
    var modal = $('#modalDialog');
    modal.hide();

    // Get the button that opens the modal
    var btn = $("#mbtn");

    // Get the  element that closes the modal
    var span = $(".close");

    $(document).ready(function(){
        // When the user clicks the button, open the modal 
        btn.on('click', function() {
            modal.show();
        });
        
        // When the user clicks on  (x), close the modal
        span.on('click', function() {
            modal.hide();
        });
    });

    // When the user clicks anywhere outside of the modal, close it
    $('body').bind('click', function(e){
        if($(e.target).hasClass("modal")){
            modal.hide();
        }
    });
    </script>
    <script>
    $(document).ready(function() {
        // Function to load tasks
        function loadTasks() {
            $.ajax({
                url: 'php/fetch_tasks.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Clear existing tasks
                    $('#task-container').empty();

                    var itemCount = 1;
                    $.each(response, function(index, task) {
                        $('#task-container').append(`
                            <div class="item${itemCount}" style="background: antiquewhite;
                                            width: 320px;
                                            min-height: 180px;
                                            border-radius: 8px;
                                            box-shadow: 0 0 10px 0 rgba(0,0,0,0.4);
                                            display: flex;
                                            justify-content: center;
                                            align-items: center;
                                            border-radius: 12px;
                                            padding: 20px;
                                            margin: 10px;
                                            flex-direction: column;
                                            position: relative;">
                            <div style="flex-grow: 1;">
                                <h3>${task.Title}</h3>
                                <br>
                                <p>${task.Content}</p>
                            </div>
                            <div style="display: flex;
                                        flex-direction: row;
                                        justify-content: space-between;
                                        margin-top: 20px;
                                        width: 100%;">
                                <div style="flex-grow: 1;">
                                    <p class="text-muted">${task.DateTime}</p>
                                </div>
                                <div>
                                    <button type="button" class="btn-close" data-task-id="${task.Id}" aria-label="X" style="background-color: transparent; border: none; color: #000; font-size: 16px; font-weight: bold; cursor: pointer;">Done</button>
                                </div>
                            </div>
                        </div>
                        `);
                        itemCount++;
                    });

                    let magicGrid = new MagicGrid({
                        container: "#task-container", // Required. Can be a class, id, or an HTMLElement.
                        items: itemCount, // Set items dynamically based on task count
                        gutter: 20,
                        static: true,
                        useMin: true,
                        animate: true
                    });

                    magicGrid.listen();
                },
                error: function() {
                    console.log('Error loading tasks');
                }
            });
        }

        // Load tasks on page load
        loadTasks();

        // Refresh tasks on checkbox change
        $(document).on('change', '.form-check-input', function() {
            loadTasks();
        });

        // Form submission for adding new task
        $('#addTaskForm').submit(function(e) {
            e.preventDefault();
            $('.modal-body').css('opacity', '0.5');
            $('.btn').prop('disabled', true);

            $form = $(this);
            $.ajax({
                type: "POST",
                url: 'php/ajax_submit.php',
                data: 'new_task_submit=1&' + $form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == 1) {
                        $('#addTaskForm')[0].reset();
                        $('.response').html('' + response.message + '');
                        // Reload tasks after adding a new task
                        loadTasks();
                    } else {
                        $('.response').html('' + response.message + '');
                    }
                    $('.modal-body').css('opacity', '');
                    $('.btn').prop('disabled', false);
                }
            });
        });
    });
    </script>
</body>
</html>