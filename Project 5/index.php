<?php
require_once 'db_connect.php';

$stmt = $pdo->query("SELECT name FROM chat ORDER BY name");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Chat Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        section {
            margin-bottom: 20px;
        }
        label {
            display: inline-block;
            width: 80px;
        }
        textarea {
            width: 400px;
            height: 100px;
        }
        #userList {
            border: 1px solid #ccc;
            padding: 10px;
        }
        #statusMessage {
            font-weight: bold;
            margin-top: 10px;
        }
        #listeningStatus {
            margin-top: 10px;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
<h1>Simple Chat Application</h1>

<section id="userListSection">
    <h2>Current Users</h2>
    <div id="userList">
        <ul>
            <?php foreach ($users as $u): ?>
                <li><?php echo htmlspecialchars($u['name']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>

<section id="updateSection">
    <h2>Update Your Chat</h2>
    <form id="updateForm">
        <div>
            <label for="myName">Name:</label>
            <input type="text" id="myName" name="myName" value="joe" />
        </div>
        <div>
            <label for="myPassword">Password:</label>
            <input type="password" id="myPassword" name="myPassword" value="joe123" />
        </div>
        <div>
            <label for="myContent">Chat Content:</label><br>
            <textarea id="myContent" name="myContent"></textarea>
        </div>
    </form>
    <div id="statusMessage"></div>
</section>

<section id="listenSection">
    <h2>Listening to Another User</h2>
    <form id="listenForm">
        <div>
            <label for="listenName">User Name:</label>
            <input type="text" id="listenName" name="listenName" value="mo" />
        </div>
    </form>
    <div id="listeningStatus"></div>
    <div>
        <label for="listenContent">Heard Content:</label><br>
        <textarea id="listenContent" readonly></textarea>
    </div>
</section>

<script>
document.getElementById('myContent').addEventListener('keyup', function () {
    var name = document.getElementById('myName').value;
    var password = document.getElementById('myPassword').value;
    var content = document.getElementById('myContent').value;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_chat.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        var statusMessage = document.getElementById('statusMessage');
        if (this.status === 200) {
            var response = this.responseText;
            if (response === 'OK') {
                statusMessage.textContent = 'Update successful.';
                statusMessage.style.color = 'green'; 
            } else {
                statusMessage.textContent = 'Update failed: ' + response;
                statusMessage.style.color = 'red'; 
            }
        } else {
            statusMessage.textContent = 'Server error during update.';
            statusMessage.style.color = 'red'; 
        }
    };
    xhr.send('name=' + encodeURIComponent(name) + '&password=' + encodeURIComponent(password) + '&content=' + encodeURIComponent(content));
});


function listenToUser() {
    var listenName = document.getElementById('listenName').value;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'get_chat.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.status === 200) {
            var response = this.responseText;
            if (response.indexOf('ERROR') === 0) {
                document.getElementById('listeningStatus').textContent = 'No such user or error occurred.';
                document.getElementById('listenContent').value = '';
            } else {
                document.getElementById('listeningStatus').textContent = 'Listening to ' + listenName;
                document.getElementById('listenContent').value = response;
            }
        } else {
            document.getElementById('listeningStatus').textContent = 'Server error during listen request.';
        }
    };
    xhr.send('name=' + encodeURIComponent(listenName));
}

setInterval(listenToUser, 5000); 
</script>
</body>
</html>
