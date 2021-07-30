$(document).ready(function(){
    $.ajax({
        url: "http://glpi.ints.org.br/plugins/dashboard/front/metrics/linechartmonth.php",
        method: "GET",
        success: function(data){
            console.log(data);
            var Mes = [];
            var Quantidade_Chamados = [];
      
            for(var i in data){
                Mes.push(data[i].Mes);
                Quantidade_Chamados.push(data[i].Quantidade_Chamados)
            }

            var chartdata = {
                labels: Mes,
                datasets:[
                    {
                        data:Quantidade_Chamados,
                        borderWidth:2,
                        borderColor:'rgba(255,255,255,1)',
                        backgroundColor: 'transparent'
                    }
                ]
            };


            Chart.defaults.global.legend.display = false;
            var ctx = $("#line-chart");
            var chartGraph = new Chart(ctx, {
                type: 'line',
                data: chartdata
            });

        },
        error: function(data){
            console.log(data);
        }
    });
});
