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
    <div id="myChart"><canvas id="canvas" width="1100" height="150" ></canvas></div>
  </div>
</template>

<script>
import Chart from "chart.js";
export default {
  mounted() {
    var ctx = document.getElementById("myChart").getContext("2d");
    var myChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
        datasets: [
          {
            label: "# of Votes",
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
              "rgba(255, 99, 132, 0.2)",
              "rgba(54, 162, 235, 0.2)",
              "rgba(255, 206, 86, 0.2)",
              "rgba(75, 192, 192, 0.2)",
              "rgba(153, 102, 255, 0.2)",
              "rgba(255, 159, 64, 0.2)"
            ],
            borderColor: [
              "rgba(255, 99, 132, 1)",
              "rgba(54, 162, 235, 1)",
              "rgba(255, 206, 86, 1)",
              "rgba(75, 192, 192, 1)",
              "rgba(153, 102, 255, 1)",
              "rgba(255, 159, 64, 1)"
            ],
            borderWidth: 1
          }
        ]
      },
      options: {
        scales: {
          yAxes: [
            {
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
