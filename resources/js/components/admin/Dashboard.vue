<template>
  <div id="dashboard">
    <div class="card-group">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Nombre d'utilisateurs</h5>
          <p class="card-text display-4">{{ this.collection.userCount }}</p>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Nombre de messages sur le forum</h5>
          <p class="card-text display-4">{{ this.collection.topicCount }}</p>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Nombre d'articles de blog</h5>
          <p class="card-text display-4">{{ this.collection.postCount }}</p>
        </div>
      </div>
    </div>
    <div class="chart-container pt-3" style="position: relative; min-height:50vh;">
      <canvas id="canvas" width="1100" height="350"></canvas>
    </div>
  </div>
</template>

<script>
import Chart from "chart.js";
export default {
  mounted() {
    var ctx = document.getElementById("canvas").getContext("2d");
    var myChart = new Chart(ctx, {
      type: "line",
      data: {
        labels: Object.keys(this.collection.moneyCount),
        datasets: [
          {
            label: "Argent total en banque",
            data: Object.values(this.collection.moneyCount),
            backgroundColor: "rgba(255, 99, 132, 0.2)",
            borderColor: "rgba(255, 99, 132, 1)",
            borderWidth: 1
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        title: {
          display: true,
          text: "Montant total des comptes en banque du serveur"
        },
        tooltips: {
          mode: "index",
          intersect: false
        },
        hover: {
          mode: "nearest",
          intersect: true
        },
        scales: {
          xAxes: [
            {
              display: true,
              scaleLabel: {
                display: true,
                labelString: "Jour"
              }
            }
          ],
          yAxes: [
            {
              display: true,
              scaleLabel: {
                display: true,
                labelString: "Argent"
              },
              ticks: {
                beginAtZero: true
              }
            }
          ]
        }
      }
    });
  },
  props: {
    collection: {
      type: Object,
      required: true
    }
  }
};
</script>
