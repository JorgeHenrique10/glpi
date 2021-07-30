$(document).ready(function(){
    $.ajax({
        url: "http://glpi.ints.org.br/plugins/dashboard/front/metrics/barchartcategory.php",
        method: "GET",
        success: function(data){
            console.log(data);
            var Quantidade = [];
            var Categoria = [];
            
            for(var i in data){
                Categoria.push(data[i].Categoria);
                Quantidade.push(data[i].Quantidade)
            }

            var chartdata = {
                labels: Categoria,
                datasets:[
                    {
                        label: "Chamados",
                        borderColor:'rgba(255,255,255,1)',
                        borderWidth: 1,
                        backgroundColor: 'rgba(70,110,85,0.9)',
                        data: Quantidade
                    }
                ]
            };


            var ctx = $("#categorias");
            var categorias = new Chart(ctx,{
                type: 'bar',
                data: chartdata,
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
        error: function(data){
            console.log(data);
        }
    });
});
