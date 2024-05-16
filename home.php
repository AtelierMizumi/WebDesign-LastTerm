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
    <title>Home</title>
</head>
<body>
    <div class="nav">
        <div class="logo">
            <!-- <img src="asset/logo.png" href="home.php" class="rounded float-left" alt=""> -->
            <p><a href="home.php">Logo</a> </p>
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

            
            echo "<a href='edit.php?Id=$res_id'>Change Profile</a>";
            ?>

            <a href="php/logout.php"> <button class="btn">Log Out</button> </a>

        </div>
    </div>
    <main>
        <div class="main-box top">
          <div class="top">
            <div class="box">
                <p>Welcome <b><?php echo $res_Uname ?></b>, you currently have <?php echo $row ?> task(s)</p>
            </div>
            <div class="box">
                <button id="mbtn" class="btn btn-primary turned-button">Add a task</button>
            </div>
          </div>
          <div class="container mt-3">
            <div class="row" id="taskList">

            </div>
        </div>
        </div>
    </main>
    <div class="container">
    <!-- Trigger/Open The Modal -->
    <div id="modalDialog" class="modal">
        <div class="modal-content animate-top">
            <div class="modal-header">
                <h5 class="modal-title">Add a task</h5>
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
                    <input type="text" name="title" id="title" class="form-control" placeholder="Enter task's title" required="">
                </div>
                <div class="form-group">
                    <textarea name="content" id="content" class="form-control" placeholder="Enter content" rows="6" required></textarea>
                </div>
            </div>
                <div class="modal-footer">
                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    $('#taskList').empty();

                    // Append tasks to taskList
                    $.each(response, function(index, task) {
                        $('#taskList').append(`
                        <div class="col-lg-3 col-md-4 col-sm-12 mb-3 border border-4 border-danger p-2 mb-2 border-opacity-75">
                            <div class="card sticky-note">
                                <div class="card-body">
                                    <h5 class="card-title">${task.Title}</h5>
                                    <p class="card-text">${task.Content}</p>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="text-muted">${task.DateTime}</p>
                                        </div>
                                        <div>
                                            <button type="button" class="btn-close" data-task-id="${task.Id}" aria-label="Close"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `);
                    });
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