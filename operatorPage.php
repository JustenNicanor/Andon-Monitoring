<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Andon Monitoring System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Handjet:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: "Courier", sans-serif;
            font-size: 20px;
            font-weight: 500;
            font-style: normal;
            font-variation-settings:
            "ELGR" 1,
            "ELSH" 2;
            text-align: center;
            padding: 20px;
            background-color: #424242;
            overflow: hidden;
        }

        /* Header Styles */
        header {
            display: flex;
            height: 70px;
            background-color: #ccc;
            border-radius: 1rem;
            align-items: center;
            position: relative;
        }

        #status-input{
            background: transparent;
            margin: 10px 0 0 0;
            text-align: center;
            color: white;
            padding: 5px 0;
            font-size: 15px;
            width: 40%;
            border: 2px solid white;
            border-radius: 1rem;
        }

        .header-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-grow: 1;
            margin-top: 5px;
        }

        header h1 {
            margin: 0;
            color: black;
        }
        
        #logout-button {
            background: #fff;
            border: none;
            color: #45474B;
            font-size: 25px;
            cursor: pointer;
            border-radius: 10px;
            padding: 10px 15px;
            position: absolute;
            right: 20px;
        }

        #logout-button:hover {
            color: #fff;
            background-color: grey;
        }

        #date-time-container {
            margin-top: 2px;
        }

        #date-time {
            margin-top: 2px;
            color: black;
            font-size: 15px;
        }

        /* Main Content Styles */
        main {
            padding: 20px;
        }

        .leds {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .led {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid black;
            box-shadow: 0 9px #999;
            margin-bottom: 20px;
        }

        .led.off {
            background-color: #fff;
        }

        .led.red {
            background-color: red;
        }

        .led.orange {
            background-color: orange;
        }

        .led button {
            display: inline-block;
            padding: 15px 25px;
            margin-top: 200px;
            font-size: 15px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            outline: none;
            color: #000;
            background-color: #F5E7B2;
            border: 1px solid black;
            border-radius: 5rem;
            font-family: "Handjet", sans-serif;
            font-weight: 700;
            font-style: normal;
            font-variation-settings:
            "ELGR" 1,
            "ELSH" 2;
        }

        button:hover {
            background-color: grey;
        }

        button:active {
            background-color: #E0A75E;
            transform: translateY(4px);
        }

        button.pressed {
            background-color: green;
            color: white; 
        }

        /* Updated CSS for idle-options */
        .idle-options {
            display: none;
            flex-direction: column;
            align-items: center; /* Center child elements horizontally */
            justify-content: center; /* Center child elements vertically */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
            border-radius: 15px; /* Increased border radius for a softer look */
            padding: 40px 60px; /* Increased padding for better spacing */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Slight shadow for better visibility */
            z-index: 1000; /* Ensure it appears on top */
            width: 80%; /* Adjust width as needed */
            max-width: 300px; /* Ensure it doesn't get too wide */
            text-align: center; /* Center the text */
        }

        .idle-options select,
        .idle-options input {
            font-size: 16px;
            background-color: #fff;
            margin: 5px 0;
            padding: 7px;
            display: block; /* Ensure input and select elements take up their own line */
            width: 100%; /* Make them full width within the container */
            box-sizing: border-box; /* Include padding in the width calculation */
        }

        .idle-options input {
            margin-left: 0; /* Remove margin-left to align with select */
        }
        
        .idle-options button {
            background-color: #F9D689;
            color: #000;
            border: 1px solid black;
            border-radius: 5rem;
            cursor: pointer;
            margin-top: 5px;
        }

        .idle-options button:hover {
            background-color: #E0A75E;
        }

        #close-idle-options {
            position: absolute;
            top: 0px;
            right: 0px;
            background: none;
            border: none;
            color: #000;
            cursor: pointer;
        }

        #close-idle-options:hover {
            color: red;
        }

        .status {
            display: flex;
            justify-content: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            max-width: 600px;
            border: 2px solid white;
            margin-top: 45px;
            background-color: #FFF8E8;
        }

        th {
            font-size: 17px;
            padding: 10px;
            font-weight: 600; 
            text-align: center;
            border-bottom: 1px solid black;
            background-color: #ccc;
            color: #000;
        }

        td {
            font-size: 16px;
            padding: 10px;
            text-align: center;
            color: #000;
        }

        span {
            padding: 10px;
            font-weight: 700; 
            text-align: center;
            color: black;
        }

        .reset {
            display: inline-flex;
            padding: 5px 15px;
            margin-top: 10px;
            font-size: 15px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            outline: none;
            color: #fff;
            background-color: #04AA6D;
            border: none;
            border-radius: 5rem;
            box-shadow: 0 9px #999;
            font-family: "Handjet", sans-serif;
            font-weight: 500;
            font-style: normal;
            font-variation-settings:
            "ELGR" 1,
            "ELSH" 2;
        }

        .error-message {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
            justify-content: center;
            align-items: center;
            z-index: 1000; /* Ensure it appears on top */
        }

        .error-message-content {
            background-color: #fff;
            padding: 20px;
            border: 5px solid darkgrey;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .error-message p {
            margin: 0 0 20px 0;
            font-size: 16px;
        }

        .error-message button {
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
        }

        .error-message button#confirm-error {
            font-family: "Courier", "san-serif";
            background-color: white; /* Green background */
            color: #333;
            border: 1px solid black;
        }

        .error-message button#confirm-error:hover {
            font-family: "Courier", "san-serif";
            background-color: grey;
            color: white;
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

        /* Footer Styles */
        footer {
            background-color: #424242;
            color: lightgrey;
            text-align: center;
            margin-left: 1rem;
            position: fixed;
            bottom: 0;
            width: 94%;
            font-size: 10px;
        }


        @media (max-width: 768px) {
            header, main, footer {
                padding: 10px; 
            }

            .led {
                width: 60px; 
                height: 60px;
            }

            button {
                padding: 10px 15px; 
                font-size: 0.875rem; 
            }

            table {
                font-size: 0.875rem; 
            }
        }

        @media (max-width: 480px) {
            header h1 {
                font-size: 1.25rem; 
            }

            #date-time {
                font-size: 0.75rem; 
            }

            .led {
                width: 50px; 
                height: 50px;
            }

            button {
                padding: 8px 12px; 
                font-size: 0.75rem; 
            }

            table {
                font-size: 0.75rem; 
            }
        }

        #reset-button, #reboot-button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            outline: none;
            color: #000;
            background-color: #F5E7B2;
            border: 1px solid black;
            border-radius: 5rem;
            font-family: "Handjet", sans-serif;
            font-weight: 700;
            font-style: normal;
            font-variation-settings:
            "ELGR" 1,
            "ELSH" 2;
            margin-top: 10px;
        }

        #reset-button:hover, #reboot-button:hover {
            background-color: grey;
        }

        #reset-button:active, #reboot-button:active {
            background-color: #E0A75E;
            transform: translateY(4px);
        }

    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Andon Monitoring</h1>
            <input type="hidden" id="hidden-date-time" name="date_time">
            <div id="date-time-container">
                <p id="date-time"></p>
                <!-- Add this hidden input field -->

            </div>
        </div>
        <button id="logout-button">
            <i class="fas fa-sign-out-alt"></i>
        </button>
    </header>
    <input type="text" name="status" id="status-input" readonly>    
    <main>
    <div class="leds">
            <div id="orange-led" class="led">
                <button id="idle-button">DOWNTIME</button>
                <div class="idle-options" id="idle-options">
                <button id="close-idle-options" class="close-button">X</button>
                    <select id="idle-type">
                        <option value="cr-break">CR Break</option>
                        <option value="water-break">Water Break</option>
                        <option value="setup">Setup</option>
                        <option value="dummy">Dummy</option>
                        <option value="preparation">Preparation</option>
                        <option value="other">Other</option>
                    </select>
                    <input type="text" id="other-reason" placeholder="Please specify" style="display: none;">
                    <button id="confirm-idle">Confirm</button>
                </div>
            </div>
            <div id="red-led" class="led">
                <button id="red-button">STOPLINE</button>
            </div>
        </div>
        <div class="status">
            <table>
                <tr>
                    <th>Status</th>
                    <th>Duration <br> (hh:mm:ss)</th>
                    <th>Live Time <br> (hh:mm:ss)</th>
                </tr>
                <tr>
                    <td>Stopline</td>
                    <td><span id="red-duration" class="timer">00:00:00</span></td>
                    <td><span id="red-live-time" class="timer">00:00:00</span></td>
                </tr>
                <tr>
                    <td>Downtime</td>
                    <td><span id="orange-duration" class="timer">00:00:00</span></td>
                    <td><span id="orange-live-time" class="timer">00:00:00</span></td>
                </tr>
                <tr>
                    <td>Total Duration</td>
                    <td colspan="2"><span id="combined-duration" class="timer">00:00:00</span></td>
                </tr>
            </table>
        </div>
        <button id="reset-button">TURN OFF</button>
        <button id="reboot-button">REBOOT</button> 
    </main>
    <div id="reboot-dialog" class="confirmation-dialog">
        <div class="confirmation-dialog-content">
            <p>Are you sure you want to reboot? All timers will be set to 00:00:00 if you do so.</p>
            <button id="confirm-reboot">Yes</button>
            <button id="cancel-reboot">No</button>
        </div>
    </div>
    <div id="confirmation-dialog" class="confirmation-dialog">
        <div class="confirmation-dialog-content">
            <p>Are you sure you want to Logout?</p>
            <button id="confirm-logout">Yes</button>
            <button id="cancel-logout">No</button>
        </div>
    </div>
    <div id="error-message" class="error-message">
    <div class="error-message-content">
        <p>Please specify a reason for "Other".</p>
        <button id="confirm-error">OK</button>
    </div>
</div>
    <footer>
        <p>Developed by <br>Denise Ira Jubilo, Raven Del Rosario, Justen Nicanor, and Carl John Zoleta <br>Student Interns, PUP, 2024</p>
    </footer>
</body>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        console.log('DOM fully loaded and parsed');

        let redStartTime = null;
        let orangeStartTime = null;
        let redTotalDuration = 0;
        let orangeTotalDuration = 0;
        let redLiveStartTime = null;
        let orangeLiveStartTime = null;

        const redButton = document.getElementById('red-button');
        const orangeButton = document.getElementById('idle-button');
        const resetButton = document.getElementById('reset-button');
        const rebootButton = document.getElementById('reboot-button');
        const logoutButton = document.getElementById('logout-button');
        const redLed = document.getElementById('red-led');
        const orangeLed = document.getElementById('orange-led');
        const redDurationDisplay = document.getElementById('red-duration');
        const orangeDurationDisplay = document.getElementById('orange-duration');
        const redLiveTimeDisplay = document.getElementById('red-live-time');
        const orangeLiveTimeDisplay = document.getElementById('orange-live-time');
        const combinedDurationDisplay = document.getElementById('combined-duration');
        const statusInput = document.getElementById('status-input');
        const confirmationDialog = document.getElementById('confirmation-dialog');
        const confirmLogoutButton = document.getElementById('confirm-logout');
        const cancelLogoutButton = document.getElementById('cancel-logout');
        const idleOptions = document.getElementById('idle-options');
        const idleTypeSelect = document.getElementById('idle-type');
        const otherReasonInput = document.getElementById('other-reason');
        const confirmIdleButton = document.getElementById('confirm-idle');
        const errorMessage = document.getElementById('error-message');
        const confirmErrorButton = document.getElementById('confirm-error');
        const closeIdleOptionsButton = document.getElementById('close-idle-options');
        const rebootDialog = document.getElementById('reboot-dialog');
        const confirmRebootButton = document.getElementById('confirm-reboot');
        const cancelRebootButton = document.getElementById('cancel-reboot');

        let redLiveInterval = null;
        let orangeLiveInterval = null;
        let isRedOn = false;
        let isOrangeOn = false;
        let isTimerRunning = false;

        idleTypeSelect.value = 'cr-break';
        otherReasonInput.style.display = 'none';

        function saveData() {
            setCurrentDateTime();

            const redDuration = formatSecondsToTime(redTotalDuration);
            const orangeDuration = formatSecondsToTime(orangeTotalDuration);
            const dateTime = document.getElementById('hidden-date-time').value;
            const combinedDuration = formatSecondsToTime(redTotalDuration + orangeTotalDuration);
            const status = statusInput.value;

            const formData = new FormData();
            formData.append('red_duration', redDuration);
            formData.append('orange_duration', orangeDuration);
            formData.append('date_time', dateTime);
            formData.append('combined_duration', combinedDuration);
            formData.append('status', status);

            fetch('insert_data.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log('Data saved:', data);
            })
            .catch(error => console.error('Error saving data:', error));
        }

        function setCurrentDateTime() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            const datetime = `${year}-${month}-${day}T${hours}:${minutes}:${seconds}`;
            document.getElementById('hidden-date-time').value = datetime;
        }

        function fetchData() {
            fetch('fetch_data.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        redDurationDisplay.textContent = data.data.red_duration || '00:00:00';
                        orangeDurationDisplay.textContent = data.data.orange_duration || '00:00:00';
                        document.getElementById('date-time').textContent = data.data.date_time || new Date().toISOString().slice(0, 19).replace('T', ' ');
                        combinedDurationDisplay.textContent = data.data.combined_duration || '00:00:00';

                        // Restore timers if LED is on
                        if (data.data.red_duration) {
                            redTotalDuration = formatTimeToSeconds(data.data.red_duration);
                        }
                        if (data.data.orange_duration) {
                            orangeTotalDuration = formatTimeToSeconds(data.data.orange_duration);
                        }

                        // Optionally, update other parts of the UI if needed
                    } else {
                        console.error('Error fetching data:', data.message);
                    }
                })
                .catch(error => console.error('Error fetching data:', error));
        }
         // Initial fetch
         fetchData();

        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            const formattedDateTime = now.toLocaleString('en-US', options);
            document.getElementById('date-time').textContent = formattedDateTime;

            document.getElementById('hidden-date-time').value = now.toISOString().slice(0, 19).replace('T', ' ');
        }

        function formatTimeToSeconds(time) {
            const parts = time.split(':');
            return parseInt(parts[0], 10) * 3600 + parseInt(parts[1], 10) * 60 + parseInt(parts[2], 10);
        }

        function formatSecondsToTime(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = Math.floor(seconds % 60);
            return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        }

        function updateTimers() {
            const now = Date.now();

            if (isRedOn) {
                const redLiveDuration = (now - redLiveStartTime) / 1000;
                redLiveTimeDisplay.textContent = formatSecondsToTime(redLiveDuration);

                redTotalDuration += (now - redStartTime) / 1000;
                redStartTime = now;
                redDurationDisplay.textContent = formatSecondsToTime(redTotalDuration);
                updateCombinedDuration();
            }

            if (isOrangeOn) {
                const orangeLiveDuration = (now - orangeLiveStartTime) / 1000;
                orangeLiveTimeDisplay.textContent = formatSecondsToTime(orangeLiveDuration);

                orangeTotalDuration += (now - orangeStartTime) / 1000;
                orangeStartTime = now;
                orangeDurationDisplay.textContent = formatSecondsToTime(orangeTotalDuration);
                updateCombinedDuration();
            }
        }

        function updateCombinedDuration() {
            const combinedDuration = redTotalDuration + orangeTotalDuration;
            combinedDurationDisplay.textContent = formatSecondsToTime(combinedDuration);
        }

        function updateStatus() {
            let statusText = '';
            if (isRedOn) {
                statusText = 'Stop Line';
            } else if (isOrangeOn) {
                switch (idleTypeSelect.value) {
                    case 'cr-break':
                        statusText = 'CR Break';
                        break;
                    case 'water-break':
                        statusText = 'Water Break';
                        break;
                    case 'setup':
                        statusText = 'Setup';
                        break;
                    case 'dummy':
                        statusText = 'Dummy';
                        break;
                    case 'preparation':
                        statusText = 'Preparation';
                        break;
                    case 'other':
                        statusText = otherReasonInput.value.trim(); // Ensure the value from otherReasonInput is used
                        break;
                }
            } else {
                statusText = 'Running';
            }
            statusInput.value = statusText; // Update the status input field
        }

        function turnRedLedOn() {
            if (isOrangeOn) {
                turnOrangeLedOff();
            }
            redLed.classList.remove('off');
            redLed.classList.add('red');
            if (!isRedOn) {
                isRedOn = true;
                redStartTime = Date.now();
                redLiveStartTime = redStartTime;
                redLiveInterval = setInterval(updateTimers, 100);
                isTimerRunning = true; // Timer started
                updateStatus();
                saveData();
            }
        }

        function turnRedLedOff() {
            if (isRedOn) {
                if (redLiveInterval !== null) {
                    clearInterval(redLiveInterval);
                    redLiveInterval = null;
                }
                redLiveTimeDisplay.textContent = formatSecondsToTime((Date.now() - redLiveStartTime) / 1000);
                redTotalDuration += (Date.now() - redStartTime) / 1000;
                redDurationDisplay.textContent = formatSecondsToTime(redTotalDuration);
                redStartTime = null;
                isRedOn = false;
                redLed.classList.add('off');
                redLed.classList.remove('red');
                updateStatus();
            }
            // Check if no other timers are running
            if (!isRedOn && !isOrangeOn) {
                isTimerRunning = false; // No timers running
            }
        }

        function turnOrangeLedOn() {
            if (isRedOn) {
                turnRedLedOff();
            }
            orangeLed.classList.remove('off');
            orangeLed.classList.add('orange');
            if (!isOrangeOn) {
                isOrangeOn = true;
                orangeStartTime = Date.now();
                orangeLiveStartTime = orangeStartTime;
                orangeLiveInterval = setInterval(updateTimers, 100);
                disableIdleButton();
                isTimerRunning = true; // Timer started
                updateStatus();
                saveData();
            }
        }

        function turnOrangeLedOff() {
            if (isOrangeOn) {
                if (orangeLiveInterval !== null) {
                    clearInterval(orangeLiveInterval);
                    orangeLiveInterval = null;
                }
                orangeLiveTimeDisplay.textContent = formatSecondsToTime((Date.now() - orangeLiveStartTime) / 1000);
                orangeTotalDuration += (Date.now() - orangeStartTime) / 1000;
                orangeDurationDisplay.textContent = formatSecondsToTime(orangeTotalDuration);
                orangeStartTime = null;
                isOrangeOn = false;
                orangeLed.classList.add('off');
                orangeLed.classList.remove('orange');
                enableIdleButton();
                updateStatus();
            }
            // Check if no other timers are running
            if (!isRedOn && !isOrangeOn) {
                isTimerRunning = false; // No timers running
            }
        }

        function disableIdleButton() {
            orangeButton.disabled = true;
        }

        function enableIdleButton() {
            orangeButton.disabled = false;
        }

        function reset() {
            turnRedLedOff();
            turnOrangeLedOff();
            saveData();
            fetchData();
            isTimerRunning = false; // Reset timer state
        }

        function reboot() {
            // Reset all timers
            redTotalDuration = 0;
            orangeTotalDuration = 0;
            redDurationDisplay.textContent = '00:00:00';
            orangeDurationDisplay.textContent = '00:00:00';
            combinedDurationDisplay.textContent = '00:00:00';

            // Save the reset state
            saveData();

            // Optionally, refresh data from the server
            fetchData();
        }

        function showRebootDialog() {
            rebootDialog.style.display = 'flex';
        }

        function hideRebootDialog() {
            rebootDialog.style.display = 'none';
        }

        function confirmLogout() {
            saveData(); // Save data on logout
            window.location.href = 'login.html';
        }

        redButton.addEventListener('click', () => {
            turnRedLedOn();
        });

        redButton.addEventListener('dblclick', turnRedLedOff);

        orangeButton.addEventListener('click', () => {
            if (!isOrangeOn) {
                idleOptions.style.display = 'block';
            }
        });

        confirmIdleButton.addEventListener('click', () => {
            if (idleTypeSelect.value === 'other' && !otherReasonInput.value.trim()) {
                errorMessage.style.display = 'flex'; // Show error message if "Other" reason is empty
                return;
            }
            turnOrangeLedOn(); // Turn on the orange LED
            idleOptions.style.display = 'none'; // Hide idle options dialog
            updateStatus(); // Update status with the new input
        });

        idleTypeSelect.addEventListener('change', () => {
            if (idleTypeSelect.value === 'other') {
                otherReasonInput.style.display = 'block'; // Show input field if "Other" is selected
                otherReasonInput.focus(); // Focus on the input field
            } else {
                otherReasonInput.style.display = 'none'; // Hide input field otherwise
                otherReasonInput.value = ''; // Clear input value
            }
        });

        closeIdleOptionsButton.addEventListener('click', () => {
            idleOptions.style.display = 'none';
            otherReasonInput.value = ''; // Clear the input
            updateStatus(); // Update status here
        });

        otherReasonInput.addEventListener('input', () => {
            if (idleTypeSelect.value === 'other') {
                updateStatus(); // Update status if "Other" option is selected and input changes
            }
        });

        confirmErrorButton.addEventListener('click', () => {
            errorMessage.style.display = 'none';
        });

        resetButton.addEventListener('click', () => {
            reset();
        });

        rebootButton.addEventListener('click', () => {
            showRebootDialog();
        });

        confirmRebootButton.addEventListener('click', () => {
            hideRebootDialog();
            reboot();
        });

        logoutButton.addEventListener('click', () => {
            if (isRedOn || isOrangeOn) {
                alert('You must turn off both the red and orange indicators before logging out.');
            } else {
                confirmationDialog.style.display = 'flex';
            }
        });

        cancelRebootButton.addEventListener('click', () => {
            hideRebootDialog();
        });

        cancelLogoutButton.addEventListener('click', () => {
            confirmationDialog.style.display = 'none';
        });

        confirmLogoutButton.addEventListener('click', () => {
            if (isRedOn || isOrangeOn) {
                alert('You must turn off both the red and orange indicators before logging out.');
                return; // Prevent logout if any LED is still on
            }
            confirmLogout(); // Proceed with logout if no LED is active
        });

        window.addEventListener('beforeunload', (event) => {
            if (isTimerRunning) {
                const message = 'You have an active timer. Are you sure you want to leave?';
                event.returnValue = message; // For modern browsers
                return message; // For older browsers
            }
        });

        setInterval(updateDateTime, 1000);
    });
</script>
</html>