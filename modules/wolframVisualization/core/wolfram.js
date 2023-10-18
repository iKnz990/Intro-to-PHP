var queryHistory = [];
var displayMode = "text";  // Default display mode
var textResultsHtml = "";  // Store text results
var imgResultsHtml = "";  // Store image results

$(document).ready(function () {
    $("#query-button").click(function () {
        sendQuery();
    });

    $("#toggle-button").click(function () {
        toggleDisplayMode();
    });
});

function handleResponse(xml) {
    textResultsHtml = "";
    imgResultsHtml = "";
    var hasResults = false;  // Flag to check if any results were returned

    $(xml).find('pod').each(function () {
        hasResults = true;  // Results were found
        var title = $(this).attr('title');
        var resultText = $(this).find('plaintext').text();
        var resultImgSrc = $(this).find('img').attr('src');

        textResultsHtml += "<h3>" + title + "</h3>";
        imgResultsHtml += "<h3>" + title + "</h3>";

        if (resultText) {
            textResultsHtml += "<p>" + resultText + "</p>";
        } else {
            textResultsHtml += "";
        }

        if (resultImgSrc) {
            imgResultsHtml += "<img src='" + resultImgSrc + "' alt='" + title + " image' />";
        }
    });

    if (!hasResults) {
        textResultsHtml = "<p>No results found.</p>";
        imgResultsHtml = "<p>No results found.</p>";
    }

    updateDisplay();
}


function sendQuery() {
    var input = $("#query-input").val();
    if (input) {
        queryHistory.push(input);
        updateQueryHistory();
    }

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

function toggleDisplayMode() {
    displayMode = (displayMode === "text") ? "image" : "text";
    updateDisplay();
}

function updateDisplay() {
    if (displayMode === "text") {
        $("#result-container").html(textResultsHtml);
    } else {
        $("#result-container").html(imgResultsHtml);
    }
}