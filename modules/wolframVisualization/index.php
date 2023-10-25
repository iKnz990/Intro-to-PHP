<?php
include '../../core/header.php';
checkUserRole('admin');
?>

<div class="content-container">
    <div class="content">
        <script src="core/wolfram.js"></script>
        <script src="https://d3js.org/d3.v6.min.js"></script>

        <h1>Wolfram Visualization</h1>
        <p>This page uses the <a href="https://www.wolframalpha.com/">Wolfram API</a> to get the result of your query.
        </p></br>
        <p>
        <h3>
            Warning:
            Currently this only works with food data. It requires a customized parsing of the Wolfram API response. Data is parsed into a JSON object and then used to create a D3 infographic. I have filtered out the results that are not parsed correctly in the "Select a section" dropdown.
        </h3>
        </p>
        <h2>Query History</h2>
        <ul id="query-history">
            <p>
            <h3>
                Sample Queries:</br>
                banana vs apple vs pear</br>
                steak vs duck vs chicken vs lamb</br>
                mcdonalds big mac vs burger king whopper vs wendys baconator</br>
            </h3>
            </p>

        </ul>

        <h1>Enter your Query</h1>
        <input type="text" id="query-input" placeholder="Enter your query here">
        <button id="query-button">Submit Query</button>

        <div id="d3InfoResults">
            <h2>D3 Infographic</h2>
            <select id="section-select">
                <option value="" disabled selected>Select a section</option>
            </select>
            <button id="show-infographic">Show Infographic</button>
            <div id="chartArea"></div>
        </div>

        <div id="wolframRawResults">
            <h2 id="wolfram-header">Hide<span id="toggle-arrow">&#9660;</span></h2>
            <div id="wolfram-results">
                <h3>Wolfram Results(raw data)</h3>
                <button id="toggle-button">Display Text or Image</button>
                <div id="result-container"></div>
            </div>
        </div>
    </div>
</div>

<?php
include '../../core/footer.php';
?>