<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_tech.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machine Downtime Monitor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #222831;
            display: flex;
            flex-direction: column; /* Stack header and main container vertically */
            align-items: center;
            height: 100vh; /* Full viewport height */
            padding: 20px;
            overflow: hidden;
            font-family: 'Courier', sans-serif;
        }
        
        header {
            display: flex;
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
        
        .main-container {
            display: flex;
            justify-content: center; /* Center content horizontally */
            align-items: flex-start; /* Align content at the top */
            max-width: 1200px; /* Increased width for the main container */
            width: 100%; /* Full width */
            background: #3D3B40; /* Background for better visibility */
            border-radius: 8px; /* Rounded corners for visual appeal */
            padding: 20px; /* Padding around content */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Shadow for subtle depth */
        }
        
        .left-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-right: 20px; /* Space between left container and notifications/button */
        }
        
        .led {
            width: 120px; /* Size of the LED */
            height: 120px; /* Size of the LED */
            border-radius: 50%;
            background-color: gray;
            transition: background-color 0.5s;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5); /* Enhanced shadow for visibility */
            z-index: 1000; /* Ensure it stays on top of other elements */
        }
        
        .led.active {
            background-color: rgb(245, 10, 10);
        }
        
        .counter-container {
            margin-top: 10px; /* Space between LED and counter */
        }
        
        .counter {
            width: 50px; /* Width of the counter */
            height: 50px; /* Height of the counter */
            border-radius: 50%; /* Rounded shape */
            background-color: white; /* Background color of the counter */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
            color: black;
            border: 2px solid black; /* Border for visibility */
        }
        
        .notification-container {
            display: flex;
            flex-direction: column;
            max-width: 800px; /* Increased width for notification container */
            width: 100%; /* Full width up to the max-width */
            color: #747264;
        }
        
        .notifications {
            display: flex;
            flex-direction: column;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #fff;
            max-height: 600px; /* Height to accommodate approximately 5 notifications */
            overflow: hidden; /* Hide overflow */
        }
        
        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between; /* Space between heading and button */
        }
        
        .notifications h2 {
            margin: 0; /* Remove default margin */
        }
        
        .simulate-button {
            background-color: #007bff; /* Primary blue color */
            color: white; /* White text */
            border: none; /* Remove default border */
            padding: 6px 12px; /* Reduced padding */
            font-size: 12px; /* Smaller font size */
            font-weight: bold; /* Bold text */
            border-radius: 4px; /* Slightly smaller rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s, transform 0.3s; /* Smooth transitions */
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2); /* Reduced shadow for a lighter look */
        }
        
        .simulate-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
        
        .simulate-button:active {
            background-color: #004494; /* Even darker blue on click */
            transform: scale(0.98); /* Slightly reduce size on click */
        }
        
        .simulate-button:focus {
            outline: none; /* Remove default focus outline */
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.5); /* Blue outline on focus */
        }
        
        .notification-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            background-color: #f9f9f9;
            position: relative; /* Needed for positioning the "new" label */
        }

        .notification-box:hover {
            background-color: grey;
            color: white;
            cursor: pointer;
        }

        .selected {
            background-color: #FFFFE0; /* Light yellow color */
        }

        .check-button {
            display: none;
            background-color: #28a745; /* Green color for the check button */
            color: white; /* White text */
            border: none; /* Remove default border */
            padding: 6px 12px; /* Padding for the button */
            font-size: 12px; /* Font size */
            font-weight: bold; /* Bold text */
            border-radius: 4px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s; /* Smooth transition */
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2); /* Shadow for a lighter look */
        }

        .check-button:hover {
            background-color: #218838; /* Darker green on hover */
        }

        .check-button:active {
            background-color: #1e7e34; /* Even darker green on click */
            transform: scale(0.98); /* Slightly reduce size on click */
        }

        .check-button:focus {
            outline: none; /* Remove default focus outline */
            box-shadow: 0 0 0 2px rgba(40, 167, 69, 0.5); /* Green outline on focus */
        }

        .check-button.show {
            display: inline-block; /* Show the button */
        }

        .new-label {
            position: absolute; /* Position label within the notification box */
            top: 10px;
            right: 10px;
            background-color: #76ABAE; /* Bright orange color */
            color: white; /* White text */
            padding: 4px 8px;
            border-radius: 4px; /* Rounded corners for the label */
            font-size: 12px;
            font-weight: bold;
            z-index: 10; /* Ensure the label is above other content */
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }
        
        .pagination button {
            padding: 5px 10px;
            font-size: 14px;
            cursor: pointer;
            background-color: blue;
            border: blue;
            color: white;
            font-family: 'Courier', sans-serif;
        }
        
        .pagination #page-info {
            margin: 0 15px;
            font-size: 16px;
            color: white;
        }
        
        footer {
            text-align: center;
            padding: 8px;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 15px;
        }
        
        footer p {
            margin: 0;
        }

        .notification-box {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            position: relative;
        }
        .new-label {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #f00;
            color: #fff;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 12px;
        }
        .checkbox-container {
            display: flex;
            align-items: center;
        }
        .container-header {
            margin-bottom: 10px;
        }

        .led {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: green; /* Default color */
            border: 2px solid black;
            margin: 10px;
        }

        .counter-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .counter {
            font-size: 2em;
        }

        .notification-box {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            position: relative;
        }

        .new-label {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: yellow;
            color: black;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 0.8em;
        }

        .remove-button {
            margin-top: 10px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
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
            background-color: white; /* Green background */
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
            background-color: white; /* Red background */
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
    <header class="header">
        <h1>Andon Monitor - Technician</h1>
        <button id="logout-btn" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
        </button>
    </header>
    <div class="main-container">
        <div class="left-container">
            <div class="led" id="led"></div>
            <div class="counter-container">
                <div class="counter" id="counter">0</div>
            </div>
        </div>
        <div class="notification-container">
            <div class="notifications">
                <div class="header-container">
                    <h2>Stop Line Notifications</h2>
                </div>
                <div id="notification-container"></div>
            </div>
            <div class="pagination">
                <button id="prev" onclick="prevPage()">Previous</button>
                <span id="page-info">Page 1</span>
                <button id="next" onclick="nextPage()">Next</button>
            </div>
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

    <audio id="buzzer" src="buzzer.mp3"></audio>
    <footer>
        <h6>Developed by Denise Ira Jubilo, Raven Del Rosario, Justen Nicanor, & Carl John Zoleta <br>PUP Interns, 2024</h6>
    </footer>
    <script>
        let notifications = [];
        let currentPage = 1;
        const notificationsPerPage = 4;
        let buzzer = document.getElementById('buzzer'); // Reference to the audio element
        let buzzerPlaying = false; // Flag to check if buzzer is currently playing

        // Variables for logout button and confirmation dialog
        var logoutBtn = document.getElementById('logout-btn');
        var confirmationDialog = document.getElementById('confirmation-dialog');
        var confirmLogoutBtn = document.getElementById('confirm-logout');
        var cancelLogoutBtn = document.getElementById('cancel-logout');

        function fetchNotifications() {
            fetch('fetch_notifications.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (Array.isArray(data)) {
                        notifications = data;
                        updateNotifications();
                        updateCounter();
                    } else {
                        console.error('Unexpected data format:', data);
                    }
                })
                .catch(error => console.error('Error fetching notifications:', error));
        }

        function updateCounter() {
            const counterElement = document.getElementById('counter');
            const ledElement = document.getElementById('led');

            counterElement.textContent = notifications.length;

            // Change LED color based on counter value
            if (notifications.length > 0) {
                ledElement.style.backgroundColor = 'red'; // Set LED to red if counter > 0
                if (!buzzerPlaying) {
                    buzzer.loop = true; // Set buzzer to loop
                    buzzer.play(); // Start playing the buzzer sound continuously
                    buzzerPlaying = true;
                }
            } else {
                ledElement.style.backgroundColor = 'gray'; // Set LED to gray if counter == 0
                if (buzzerPlaying) {
                    buzzer.pause(); // Stop the buzzer sound
                    buzzer.currentTime = 0; // Reset playback to start
                    buzzerPlaying = false;
                }
            }
        }

        function updateNotifications() {
            const notificationContainer = document.getElementById('notification-container');
            notificationContainer.innerHTML = '';

            // Calculate the range of notifications to display based on currentPage
            const startIndex = (currentPage - 1) * notificationsPerPage;
            const endIndex = startIndex + notificationsPerPage;
            const pageNotifications = notifications.slice(startIndex, endIndex);

            // Determine if we're on the first page
            const isFirstPage = currentPage === 1;

            // Create and append notification boxes
            pageNotifications.forEach(notification => {
                const notificationBox = document.createElement('div');
                notificationBox.className = 'notification-box';
                notificationBox.dataset.id = notification.id;

                // Add "New" label only if on the first page
                if (isFirstPage && !notification.read) {
                    const newLabel = document.createElement('div');
                    newLabel.className = 'new-label';
                    newLabel.textContent = 'New';
                    notificationBox.appendChild(newLabel);
                }

                // Add notification details
                notificationBox.innerHTML += `
                    <strong>User ID:</strong> ${notification.id} <br>
                    <strong>Time:</strong> ${notification.time}
                `;

                // Create and add the "Remove" button
                const removeButton = document.createElement('button');
                removeButton.textContent = 'Done';
                removeButton.className = 'remove-button';
                removeButton.onclick = () => removeNotification(notificationBox);
                notificationBox.appendChild(removeButton);

                notificationContainer.appendChild(notificationBox);
            });

            // Update pagination controls
            const totalPages = Math.ceil(notifications.length / notificationsPerPage);
            document.getElementById('page-info').textContent = `Page ${currentPage}`;

            document.getElementById('prev').disabled = currentPage === 1;
            document.getElementById('next').disabled = currentPage === totalPages;
        }


        function removeNotification(notificationBox) {
            const notificationId = notificationBox.dataset.id;

            // Show confirmation dialog
            const confirmed = confirm('Did you fix the problem already?');
            if (!confirmed) {
                return; // Exit if not confirmed
            }

            // Send a POST request to delete the notification
            fetch('delete_notification.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    'id': notificationId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Remove from the DOM
                    notificationBox.remove();

                    // Remove from the notifications array
                    notifications = notifications.filter(n => n.id !== parseInt(notificationId));

                    // Refresh the notifications and counter
                    updateNotifications();
                    updateCounter();
                } else {
                    console.error('Failed to remove notification:', data.message);
                }
            })
            .catch(error => console.error('Error removing notification:', error));
        }
        

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                updateNotifications();
            }
        }

        function nextPage() {
            const totalPages = Math.ceil(notifications.length / notificationsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                updateNotifications();
            }
        }

        // Show the confirmation dialog when the logout button is clicked
        logoutBtn.onclick = function() {
            confirmationDialog.style.display = 'flex';
        };

        // Confirm logout
        confirmLogoutBtn.onclick = function() {
            window.location.href = 'login_tech.html'; // Redirect to logout PHP file
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

        // Initial fetch and set interval to fetch periodically
        fetchNotifications();
        setInterval(fetchNotifications, 1000); // Fetch every second (adjust as needed)

    </script>
</body>

</html>
