<!DOCTYPE html>
<html>
<head>
  <title>Picker API Quickstart</title>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="/traingoogleapi/css/index.css">
</head>
<style>
</style>
<body>  
  <div>
    <ul class="horizontal-list">
        <li><button id="authorize_button" onclick="handleAuthClick()">Authorize</button></li>
        <li><button id="signout_button" onclick="handleSignoutClick()">Sign Out</button></li>
        <li>
            <h3 for="sheet-select">Select Sheet:</h3>
            <select id="sheet-select"></select>
        </li>
        <li>
            <h3 for="start-cell">Start Cell:</h3>
            <input type="text" id="start-cell" placeholder="e.g., A1">
        </li>
        <li>
            <h3 for="end-cell">End Cell:</h3>
            <input type="text" id="end-cell" placeholder="e.g., B10">
        </li>
        <li><button type="button" onclick="handleReadData()">Read Data</button></li>
    </ul>
</div>
  <script src="/traingoogleapi/js/main.js"></script>
  <script async defer src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script>
  <script async defer src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>
</body>
</html>
