<?php
/*
$query  = $all_sermons
$query1 = $speakers
$query2 = $years
$query3 = $latest_year
*/

$clearFilters = trim($_GET["clearfilters"]);
if (strlen($clearFilters) == 0)
    $clearFilters = 0;

if ($clearFilters) {
    unset($_SESSION["sermons_speakerFilter"]);
    unset($_SESSION["sermons_yearFilter"]);
}


//get latest year
$latest_year = mysqli_query($mysqli_connection,"SELECT YEAR(theDate) FROM sermons WHERE isActive='True' ORDER BY theDate DESC LIMIT 1");

if (mysqli_num_rows($latest_year)) {
    while ($row = mysqli_fetch_row($latest_year)) {
        $sermonYear = $row[0];
    }
}


//process filters
$speakerFilter = mysqli_real_escape_string($mysqli_connection,trim(strtolower($_GET["speaker_filter"])));
$yearFilter = mysqli_real_escape_string($mysqli_connection,trim(strtolower($_GET["year_filter"])));

$filterQuery = "";
if ($speakerFilter == "all") {
    $_SESSION["sermons_speakerFilter"] = $speakerFilter;
} elseif (strlen($speakerFilter)) {
    $filterQuery .= " AND speaker LIKE '" . mysqlize($mysqli_connection, $speakerFilter) . "'";
} else {
    $speakerFilter = "all";
    $_SESSION["sermons_speakerFilter"] = $speakerFilter;
}

if (strlen($yearFilter) > 0) {
    $_SESSION["sermons_yearFilter"] = $yearFilter;
} else {
    $yearFilter = $sermonYear;
    $_SESSION["sermons_yearFilter"] = $yearFilter;
}
$filterQuery .= " AND YEAR(theDate) = '$yearFilter'";

$_SESSION["sermons_filterQuery"]   = $filterQuery;
$_SESSION["sermons_speakerFilter"] = $speakerFilter;
$_SESSION["sermons_yearFilter"] = $yearFilter;


//get all sermons
$all_sermons_query = sprintf("SELECT sermons.id, sermons.title, sermons.description, sermons.theDate, serviceTimes.title, sermons.speaker, sermons.audioFile, sermons.videoFile FROM sermons INNER JOIN serviceTimes ON sermons.serviceTimeID = serviceTimes.id WHERE isActive='True' %s ORDER BY theDate DESC, serviceTimes.xorder DESC, sermons.id DESC", $_SESSION["sermons_filterQuery"]);

$all_sermons = mysqli_query($mysqli_connection,$all_sermons_query);


//get distinct list of sermon speakers
$speakers_query = sprintf("SELECT DISTINCT speaker FROM sermons WHERE isActive='True' ORDER BY speaker");
$speakers = mysqli_query($mysqli_connection,$speakers_query);

//get distinct list of sermon years
$years_query = sprintf("SELECT DISTINCT YEAR(theDate) FROM sermons WHERE isActive='True' ORDER BY theDate DESC");
$years = mysqli_query($mysqli_connection,$years_query);
