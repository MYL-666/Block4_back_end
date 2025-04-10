<?php
// ================= PROFILE DOUGHNUT CHART =================
// Generate a doughnut chart for the profile page
if($title=='Me'){
?>
<script>
    // ================= DOUGHNUT CHART =================
    // Get the percentage value from PHP
    const percentage = <?php echo $precentage; ?>; 
    // Get the context of the progress chart
    const ctx = document.getElementById('progressChart').getContext('2d');
    // Create a new doughnut chart
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
      data: [percentage, 100 - percentage],
      backgroundColor: ['#22c55e', '#e5e7eb'], 
      borderWidth: 0,
    }]
  },
  options: {
    cutout: '70%',
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      tooltip: { enabled: false },
      legend: { display: false }
    }
  }
});
</script>
<?php 
}
?>