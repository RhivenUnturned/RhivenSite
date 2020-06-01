/**
 * Carte en temps réel
 * auteur: Ceans <ceansbzh@gmail.com> avec l'aide de Nexis <nexis@nexisrealms.com>
 */

/* Max Refreshes */
var MaxRefresh = 60;
/* Refresh Interval */
var RefreshInterval = 6000;
/* Hidden Players */
var HiddenPlayers = [];
/* Heartbeat Refresh Counter */
var RefreshCounter = 10;
/* Total Refresh Counter */
var TotalRefreshes = 0;
/* First Page Load */
var FirstLoad = true;
/* Page Timeout */
var Timeout = false;

/**
 * INITIATE LIVEMAP
 * This function queries the API for active server data. If any active servers
 * are found, all relevant data is then queried and loaded to the page
 */
function init() {
    console.log("Démarrage de la carte...");

    $.ajax({
        dataType: "json",
        type: "GET",
        url: "/livemap/server",

        success: function(data) {
            LoadLivemap(data);
        },
        error: function(xhr, status, error) {
            AjaxErrorHandler(xhr, status, error);
        }
    });
}

/**
 * LOAD INITIAL LIVEMAP
 * This function loads the initial livemap template and if successful
 * refreshes the livemap to add connected players to view. This function
 * only executes on the first page load.
 * @param {obj} data JSON server data
 */
function LoadLivemap(data) {
  console.log("Chargement de la carte...");
  console.log(data.Server.map);

  document.getElementById("map-background").src = "uploads/carte/" + data.Server.map + ".png";
}