<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_admin.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locator Map</title>
    <head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

    <style>
        body {
            display: flex;
            flex-direction: column;
            margin: 0;
            background-color: #000;
            font-family: Arial, sans-serif;
            overflow: hidden; /* Prevent scrolling */
        }

        header {
            display: flex;
            font-family: 'Courier', sans-serif;
            align-items: center; /* Vertically center items */
            background-color: #333;
            color: #fff;
            padding: 10px 5%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            width: 100%;
            margin-bottom: 5px;
            box-sizing: border-box; /* Include padding in width calculation */
            position: relative; /* Positioning context for button alignment */
        }

        header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            flex: 1; /* Take up remaining space to center the text */
            text-align: center; /* Center text within its space */
        }


        header p {
            margin: 0;
            font-size: 1rem; /* Adjusted for better fit */
            color: #ccc;
        }

        .block-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            padding: 10px;
            flex-grow: 1;
            box-sizing: border-box; /* Include padding in flex-grow calculation */
            max-height: calc(100vh - 80px); /* Adjust to account for header height */
            overflow: auto; /* Allow scrolling if necessary */
        }

        .block {
            display: grid;
            grid-template-columns: repeat(3, 30px); /* Adjusted for 5 columns */
            grid-template-rows: repeat(15, 30px); /* Adjusted for 6 rows */
            margin: 10px;
            padding: 10px;
            padding-bottom: 0px;
            background-color: #fff;
            border: 2px solid #ccc;
            border-radius: 8px; /* Slightly reduced border radius */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Reduced shadow for smaller blocks */
            position: relative; /* Ensure block-name is positioned relative to block */
        }

        .block-name {
            position: absolute;
            top: -20px; /* Adjust as needed to position above the block */
            left: 0;
            right: 0;
            text-align: center;
            font-size: 1rem;
            font-weight: bold;
            color: #333;
            background-color: #fff;
            padding: 2px 5px;
            border-bottom: 2px solid #ccc; /* Optional: Adds a line between name and block */
        }

        .circle {
            width: 30%; /* Consistent size */
            height: 30%; /* Consistent size */
            background-color: #ccc;
            box-shadow: 0 3px;
            border-radius: 50%;
            border: 1px solid black;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin: auto;
        }

        @keyframes blink {
            0% { background-color: red; }
            50% { background-color: orange; }
            100% { background-color: red; }
        }

        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            overflow: auto;
        }

        .modal-table {
            width: 100%;
            border-collapse: collapse;
        }

        .modal-table th, .modal-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .modal-table th {
            background-color: #f4f4f4;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* LOGOUT FUNCTIONALITY */

        header .logout-btn {
            background: #fff;
            border: none;
            color: #45474B;
            font-size: 15px;
            cursor: pointer;
            border-radius: 10px;
            padding: 10px 20px; /* Adjust padding for a better look */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: auto; /* Push button to the right */
        }

        header .logout-btn:hover {
            color: #fff;
            background-color: grey;
        }
        /* Custom Confirmation Dialog Styles */
        .confirmation-dialog {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            justify-content: center;
            align-items: center;
            z-index: 1000; /* Ensure it appears on top */
        }

        .confirmation-dialog-content {
            background-color: lightgrey;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .confirmation-dialog-content p {
            font-family: 'Courier', sans-serif;
        }

        .confirmation-dialog p {
            margin: 0 0 20px 0;
            font-size: 16px;
        }

        .confirmation-dialog button {
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: black;
            border: 1px solid gray;
        }

        .confirmation-dialog button:hover {
            color: white;
            border: 1px solid black;
        }

        #confirm-logout {
            font-family: "Courier", "san-serif";
            background-color: white; 
            color: #333;
            border: 1px solid black;
        }

        #confirm-logout:hover {
            font-family: "Courier", "san-serif";
            background-color: grey;
            color: white;
        }

        #cancel-logout {
            font-family: "Courier", "san-serif";
            background-color: white; 
            color: #333;
            border: 1px solid black;
        }

        #cancel-logout:hover {
            font-family: "Courier", "san-serif";
            background-color: grey;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1>Andon Locator Map</h1>
        <button id="logout-btn" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
        </button>
    </header>
    <div class="block-container">
        <div class="block" data-block-name="Block 1">
            <div class="block-name">ABD1 ASSY</div>
            <div class=""></div>
            <div class=""></div>
            <div id="circle1" class="circle"></div>
            <div id="circle2" class="circle"></div>
        </div>
    </div>
    <!-- Modal HTML -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <table class="modal-table">
                <tr>
                    <td colspan="3" id="circle-id">Not Available</td>
                </tr>
                <tr>
                    <td colspan="3" id="status">Unknown</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <th>Duration <br> (hh:mm:ss)</th>
                    <th>Time Stop</th>
                </tr>
                <tr>
                    <td>Stopline</td>
                    <td><span id="red-duration" class="timer">00:00:00</span></td>
                    <td rowspan="2"><span id="date-time" class="timer">Not Available</span></td>
                </tr>
                <tr>
                    <td>Downtime</td>
                    <td><span id="orange-duration" class="timer">00:00:00</span></td>
                </tr>
                <tr>
                    <td>Total Duration</td>
                    <td colspan="2"><span id="combined-duration" class="timer">00:00:00</span></td>
                </tr>
            </table>
        </div>
    </div>
    <!-- Confirmation Dialog -->
    <div id="confirmation-dialog" class="confirmation-dialog">
        <div class="confirmation-dialog-content">
            <p>Are you sure you want to log out?</p>
            <button id="confirm-logout">Yes</button>
            <button id="cancel-logout">No</button>
        </div>
    </div>
</body>



    <script>
    // Variables to store the current user IDs and modal state
    var currentUserId1 = 1; // Default User ID for circle1
    var currentUserId2 = 2; // Default User ID for circle2
    var isModalOpen = false; // Track if the modal is open

    // Get the modal, the close button, and the circle ID element
    var modal = document.getElementById("modal");
    var closeBtn = document.getElementsByClassName("close")[0];
    var circleIdElement = document.getElementById("circle-id");

    // Variables for logout button and confirmation dialog
    var logoutBtn = document.getElementById('logout-btn');
    var confirmationDialog = document.getElementById('confirmation-dialog');
    var confirmLogoutBtn = document.getElementById('confirm-logout');
    var cancelLogoutBtn = document.getElementById('cancel-logout');

    // Function to fetch data from the server and update circles and modal
    function fetchData(user_id) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_andon_data.php?user_id=' + user_id, true);
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                var data = JSON.parse(xhr.responseText);
                console.log('Fetched Data for User ID ' + user_id + ':', data); // Debug: Log the fetched data

                if (data) {
                    // Update the modal with data
                    if (isModalOpen) { // Only update modal if it is open
                        document.getElementById('red-duration').textContent = data.red_duration || '00:00:00';
                        document.getElementById('orange-duration').textContent = data.orange_duration || '00:00:00';
                        document.getElementById('combined-duration').textContent = data.combined_duration || '00:00:00';
                        document.getElementById('status').textContent = data.status || 'Unknown';

                        
                        // Update the combined date-time cell
                        var dateTime = data.date_time || 'Not Available';
                        document.getElementById('date-time').textContent = dateTime;
                    }

                    // Update the circle color based on status
                    updateCircleColor(user_id, data.status);
                } else {
                    console.error('No data received or data is malformed.');
                }
            } else {
                console.error('Error fetching data:', xhr.status, xhr.statusText);
            }
        };
        xhr.onerror = function() {
            console.error('Request failed');
        };
        xhr.send();
    }

    // Function to update circle color based on status
    function updateCircleColor(user_id, status) {
        var circleId = 'circle' + user_id; // Assume circle ID is dynamic based on user_id
        var circle = document.getElementById(circleId);
        if (circle) {
            console.log('Updating color for', circleId, 'with status', status); // Debug: Log status and circle ID
            switch (status) {
                case 'Running':
                case '':
                    circle.style.backgroundColor = 'green';
                    break;
                case 'Stop Line':
                    circle.style.backgroundColor = 'red';
                    break;
                case 'Water Break':
                case 'Setup':
                case 'Dummy':
                case 'Preparation':
                case 'CR Break':
                case 'Other':
                    circle.style.backgroundColor = 'orange';
                    break;
                default:
                    circle.style.backgroundColor = 'orange';
                    break;
            }
        }
    }

    // Click handlers for circles
    document.getElementById('circle1').onclick = function() {
        circleIdElement.textContent = 'User ID ' + currentUserId1; // Update the text to reflect currentUserId1
        fetchData(currentUserId1); // Fetch data for User ID 1
        modal.style.display = "flex";
        isModalOpen = true; // Set modal state to open
    };

    document.getElementById('circle2').onclick = function() {
        circleIdElement.textContent = 'User ID ' + currentUserId2; // Update the text to reflect currentUserId2
        fetchData(currentUserId2); // Fetch data for User ID 2
        modal.style.display = "flex";
        isModalOpen = true; // Set modal state to open
    };

    // When the user clicks on <span> (x), close the modal
    closeBtn.onclick = function() {
        modal.style.display = "none";
        isModalOpen = false; // Set modal state to closed
    };

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            isModalOpen = false; // Set modal state to closed
        }
    };

    // Automatically reload data for each circle every second
    function autoReload() {
        if (!isModalOpen) { // Fetch data only if modal is not open
            fetchData(currentUserId1); // Fetch data for circle1
            fetchData(currentUserId2); // Fetch data for circle2
        }
    }

    // Set interval to call autoReload function every second
    setInterval(autoReload, 1000);

    // Initial fetch for circles
    fetchData(currentUserId1);
    fetchData(currentUserId2);

    // Show the confirmation dialog when the logout button is clicked
    logoutBtn.onclick = function() {
        confirmationDialog.style.display = 'flex';
    };

    // Confirm logout
    confirmLogoutBtn.onclick = function() {
        window.location.href = 'login_admin.html'; // Redirect to logout PHP file
    };

    // Cancel logout
    cancelLogoutBtn.onclick = function() {
        confirmationDialog.style.display = 'none'; // Hide the confirmation dialog
    };

    // Close confirmation dialog when clicking outside
    window.onclick = function(event) {
        if (event.target == confirmationDialog) {
            confirmationDialog.style.display = 'none'; // Hide the confirmation dialog
        }
    };

</script>
</html>