<?php
include '../../core/header.php';
checkUserRole('admin');
?>

<div class="content-container">
    <div class="content">
        <script src="core/wolfram.js"></script>
        <script src="https://d3js.org/d3.v7.min.js"></script>


        <h1>Wolfram</h1>
        <p>This page uses the <a href="https://www.wolframalpha.com/">Wolfram API</a> to get the result of your query.
        </p> <input type="text" id="query-input" placeholder="Enter your query here">
        <button id="query-button">Submit Query</button>


        <h2>Query History</h2>
        <ul id="query-history"></ul>

        <select id="section-select">
        <option value="" disabled selected>Select a section</option>
        </select>
        <button id="show-infographic">Show Infographic</button>

            <div id="infographic"></div>

        <h3>Results</h3>
        <button id="toggle-button">Display Text or Image</button>
        <div id="result-container"></div>

    </div>
</div>

<?php
include '../../core/footer.php';
?>