<?php
include '../../core/header.php';
checkUserRole('admin');

?>
<div class="content-container">
    <div class="content">

        <head>
            <title>Wolfram API Example</title>

            <script>
                var queryHistory = [];

                $(document).ready(function () {
                    $("#query-button").click(function () {
                        sendQuery();
                    });
                    $("#result-img").hide();  // Hide the image by default

                });

                function handleResponse(xml) {
                    var resultsHtml = "";

                    $(xml).find('pod').each(function () {
                        var title = $(this).attr('title');
                        var resultText = $(this).find('plaintext').text();
                        var resultImgSrc = $(this).find('img').attr('src');

                        resultsHtml += "<h3>" + title + "</h3>";

                        if (resultText) {
                            resultsHtml += "<p>" + resultText + "</p>";
                        } else {
                            resultsHtml += "<p>No text result for this pod.</p>";
                        }

                        if (resultImgSrc) {
                            resultsHtml += "<img src='" + resultImgSrc + "' alt='" + title + " image' />";
                        }
                    });

                    if (resultsHtml) {
                        $("#result-text").html(resultsHtml);
                    } else {
                        $("#result-text").text("No result found.");
                    }
                }

                function sendQuery() {
                    var input = $("#query-input").val();
                    queryHistory.push(input);
                    updateQueryHistory();

                    var url = "wolfram_proxy.php?input=" + encodeURIComponent(input);

                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: "xml",
                        success: handleResponse,
                        error: function () {
                            alert("Error: Could not connect to Wolfram API");
                        }
                    });

                    $("#query-input").val('');  // Clear the text box
                }

                function updateQueryHistory() {
                    var historyHtml = queryHistory.map(function (query) {
                        return '<li>' + query + '</li>';
                    }).join('');
                    $("#query-history").html(historyHtml);
                }
            </script>
        </head>

        <h1>Wolfram API Example</h1>
        <p>This page uses the Wolfram API to get the result of your query.</p>
        <input type="text" id="query-input" placeholder="Enter your query here">
        <button id="query-button">Submit Query</button>
        <div id="result-text"></div>
        <img id="result-img" alt="Result image" />
        <h2>Query History</h2>
        <ul id="query-history"></ul>
    </div>
</div>

<?php
include '../../core/footer.php';

?>