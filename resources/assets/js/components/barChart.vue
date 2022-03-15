<template>
        <div class="col-lg-6">
                <div class="col-lg-12">
                    <center><label class="control-label">Statistik Halaman Yang Dikunjungi</label></center>
                </div>
                <div class="col-lg-12">
                    <canvas id="mybarChart"></canvas>
                </div>
        </div>
</template>
<script>
import Echo from 'laravel-echo'
export default {
  data(){
    return {
      hari: [],
      hit:  [],
      warna:[],
      mybarChart:''
    }
  },
  created() {
    let kthis = this;
    this.fetchChartBar();
    kthis.fetchEcho();
  },
  methods: {
    fetchChartBar(){
    let vthis = this;
      fetch('api/listPengunjung')
        .then(res => res.json())
        .then(res => {
          this.hari = res.data.hari;
          this.hit = res.data.hit;
          this.warna = res.data.warna;
          vthis.barchart(res.data.hari,res.data.hit,res.data.warna);
        })
        .catch(err => console.log(err));
    },
    barchart(hari,hit,warna)
    {
      var ctx = document.getElementById("mybarChart");
      this.mybarChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: hari,
          datasets: [{
            label: 'Halaman Yang Di Akses',
            backgroundColor: warna,
            data: hit
      }]
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }]
          }
      }
      });
    },
    fetchEcho (){
    let vk = this;
    let myEcho = new Echo({
      broadcaster: 'pusher',
      key: '9d7ee15d52b66128ec11',
      cluster: 'ap1',
      encrypted: true,
      disableStats: true
      });

      myEcho.channel("kunjungan").listen('pengunjung', function(e) {
        vk.fetchButton();
      });
    }
    ,
    fetchButton(){
      let vc = this;
      let vs = this;
      vs.fetchChartBar();
        vc.mybarChart.data.datasets[0].data=[this.hit];
      
      vc.mybarChart.update();

      
    }
  }
}
</script>