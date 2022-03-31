<html>
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://use.typekit.net/oov2wcw.css">
        <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha512-s+xg36jbIujB2S2VKfpGmlC3T5V2TF3lY48DX7u2r9XzGzgPsa6wTpOQA7J9iffvdeBN0q9tKzRxVxw1JviZPg==" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <main class="empty">

            <div class="selectCountry">
                <div class="insideDiv">
                    Dados Covid-19 do país
                    <select id="countries">
                        <option value="empty">-----</option>
                    </select>
                </div>
            </div>
            
            <div class="loaderIcon">
                <div class="lds-ring">
                    <div></div>
                </div>
            </div>

            <div class="dataCountrySelected">
                <div class="nameCountry"></div>
                <div class="dataCountry">
                    <div class="leftSide">
                        <div class="countryFlag"></div>
                    </div>
                    <div div class="rightSide">
                        <canvas id="chart-area"></canvas>
                    </div>
                </div>
            </div>

            <div class="graphDeathAndCases"></div>

            <div class="main_st">
                <div class="pais_maior_caso">
                    <h2>Países com maior aumento de casos recentes</h2>
                    <div class="halfs">
                        <div class="left-half">
                            <table>
                                <tr>
                                    <th class="pais">Países</th>
                                    <th class="pais_num">N° Óbitos</th>
                                </tr>
                            </table>
                        </div>
                        <div class="right-half">
                            <canvas class="maior_caso"></canvas>
                        </div>
                    </div>
                </div>

                <div class="pais_maior_morte">
                    <h2>Países com maior aumento de óbitos recentes</h2>
                    <div class="halfs">
                        <div class="left-half">
                            <table>
                                <tr>
                                    <th class="pais">Países</th>
                                    <th class="pais_num">N° Óbitos</th>
                                </tr>
                            </table>
                        </div>
                        <div class="right-half">
                            <canvas class="maior_morte"></canvas>
                        </div>
                    </div>
                </div>

                <div class="pais_maior_in">
                    <h2>Países com maior quantidade total de casos</h2>
                    <div class="halfs">
                        <div class="left-half">
                            <table>
                                <tr>
                                    <th class="pais">Países</th>
                                    <th class="pais_num">N° Casos</th>
                                </tr>
                            </table>
                        </div>
                        <div class="right-half">
                            <canvas class="maior_caso_all"></canvas>
                        </div>
                    </div>
                </div>

                <div class="pais_maior_dea">
                    <h2>Países com maior quantidade de óbitos</h2>
                    <div class="halfs">
                        <div class="left-half">
                            <table>
                                <tr>
                                    <th class="pais">Países</th>
                                    <th class="pais_num">N° Mortes</th>
                                </tr>
                            </table>
                        </div>
                        <div class="right-half">
                            <canvas class="maior_morte_all"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </body>

    <script>

        Chart.defaults.global.defaultFontColor = "#223a50";
        function verificaValor(valor){
            valor = ((valor == null) ? 0 : valor);
            return valor;
        }

        var ctx = document.getElementsByClassName("line-chart");

        var maior_caso = document.getElementsByClassName("maior_caso");
        var maior_caso_all = document.getElementsByClassName("maior_caso_all");

        var maior_morte = document.getElementsByClassName("maior_morte");
        var maior_morte_all = document.getElementsByClassName("maior_morte_all");


        $.post("php.php", {
            param: JSON.stringify("1")
        },
        function(data){
            data = JSON.parse(data);
            for(let i = 0; i < data.response.length; i++){
                $("#countries").append('<option value="'+data.response[i].toLowerCase()+'">'+data.response[i]+'</option>');
            }
        })
        $('#countries').change(function(){
            val = $(this).val();
            if( val == "empty"){
                $("main").removeClass("empty");
                $("main").addClass("empty");
            }else{
                $("main").removeClass("empty");
                $("main").addClass("loading");
                
                $.post("php.php", {
                    param: JSON.stringify("2#"+val)
                },
                function(data){
                    data = data.split("|");
                    allCountries = JSON.parse(data[0]);
                    data = JSON.parse(data[1]);

                    countryName = data.parameters.country;
                    
                    let all = verificaValor(data.response[0].cases.total);
                    let rec = verificaValor(data.response[0].cases.recovered);
                    let active = verificaValor(data.response[0].cases.active);
                    let newCases = verificaValor(data.response[0].cases.new);
                    let dea = verificaValor(data.response[0].deaths.total);
                    let newDeaths = verificaValor(data.response[0].deaths.new);

                    $(".nameCountry").html('<img src="https://www.countryflags.io/'+allCountries[countryName]+'/shiny/64.png">'+countryName);

                    let totalCasos = (all).toLocaleString('pt-BR');
                    let recuperados = (rec).toLocaleString('pt-BR');
                    let casosAtivos = (active).toLocaleString('pt-BR');
                    let obitos = (dea).toLocaleString('pt-BR');
                    let novosCasos = (newCases).toLocaleString('pt-BR');
                    let novosObitos = (newDeaths).toLocaleString('pt-BR');
                    $(".dataCountrySelected .leftSide").html("<p>Total de Casos: "+totalCasos+"</p><p>Recuperados: "+recuperados+"</p><p>Casos Ativos: "+casosAtivos+"</p><p>Óbitos: "+obitos+"</p><p>Novos Casos: "+novosCasos+"</p><p>Novos Óbitos: "+novosObitos+"</p>");

                    $(".confirm-opt h1").text("Total de Casos: "+totalCasos);
                    $(".recovered-opt h1").text("Recuperados: "+recuperados);
                    $(".active-opt h1").text("Casos Ativos: "+casosAtivos);
                    $(".death-opt h1").text("Óbitos: "+obitos);

                    var config = {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                            data: [
                                rec,
                                active,
                                dea,
                            ],
                            backgroundColor: [
                                "#ffa245",
                                "#6bcccc",
                                "#af84ff",
                            ],
                                label: 'Datas'
                            }],
                            labels: [
                                "Recuperados",
                                "Casos Ativos",
                                "Óbitos"
                            ]
                        },
                        options: {
                            responsive: true,
                            scaleFontColor: "#FFFFFF",
                            legend: {
                            position: 'bottom',
                            },
                            title: {
                            display: false,
                            text: 'Chart.js Doughnut Chart'
                            },
                            animation: {
                            animateScale: true,
                            animateRotate: true
                            },
                            tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var dataset = data.datasets[tooltipItem.datasetIndex];
                                var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                                    return previousValue + currentValue;
                                });
                                var currentValue = dataset.data[tooltipItem.index];
                                var percentage = Math.floor(((currentValue/total) * 100)+0.5);         
                                return percentage + "%";
                                }
                            }
                            }
                        }
                    };
                    $.post("php.php", {
                        param: JSON.stringify("4#"+countryName)
                    },
                    function(data){
                        $(".graphDeathAndCases").html('<canvas class="graphDeaths"></canvas><canvas class="graphCases"></canvas>');
                        data = JSON.parse(data);
                        array_deaths = [];
                        array_cases = [];
                        dataCases = [];
                        dataDeaths = [];
                        for(let i = (data.response).length - 1; i > 0; i--){
                            if(i > 0){
                                if((i + 1) < data.response.length){
                                    if(data.response[i].day !== data.response[i+1].day){
                                        let newDeaths = 0;
                                        if(data.response[i].deaths.new !== null){
                                            newDeaths = data.response[i].deaths.new.toString().replace("+", "");
                                        }
                                        let newCases = 0;
                                        if(data.response[i].cases.new !== null){
                                            newCases = data.response[i].cases.new.toString().replace("+", "");
                                        }
                                        if((newCases > 0) && (newDeaths > 0)){
                                            dataCases.push(data.response[i].day);
                                            dataDeaths.push(data.response[i].day);
                                            array_deaths.push(parseInt(newDeaths));
                                            array_cases.push(parseInt(newCases));
                                        }else{
                                            novosCasos = data.response[i+1].cases.new;
                                            if(novosCasos !== null){
                                                if((newCases == 0) && (data.response[i+1].cases.new.toString().replace("+", "") < 100)){
                                                    dataCases.push(data.response[i].day);
                                                    array_cases.push(parseInt(newCases));
                                                }
                                            }

                                            novosObitos = data.response[i+1].deaths.new;
                                            if(novosObitos !== null){
                                                if((newDeaths == 0) && (data.response[i+1].deaths.new.toString().replace("+", "") < 100)){
                                                    dataDeaths.push(data.response[i].day);
                                                    array_deaths.push(parseInt(newDeaths));
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $("main").removeClass("loading");
                        var ctx = document.getElementById('chart-area').getContext('2d');
                        window.myPie = new Chart(ctx, config);

                        var ctx = document.getElementsByClassName("graphDeaths");
                        var chartGraph = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: dataDeaths,
                                datasets: [{
                                    label: countryName,
                                    data: array_deaths,
                                    borderWidth: 3,
                                    lineTension: 0.01,
                                    pointRadius: 0,
                                    pointHoverRadius: 5,
                                    pointHitRadius: 5,
                                    pointHoverBackgroundColor: "rgba(255, 159, 64, 0.2)",
                                    borderColor: "rgba(255, 159, 64)",
                                    backgroundColor: 'transparent',
                                }]
                            },
                            options: {
                                legend: {
                                    display: false
                                },
                                responsive: true,
                                scaleFontColor: "#FFFFFF",
                                title: {
                                    display: true,
                                    text: 'Óbitos'
                                },
                                hover: {
                                    mode: 'nearest',
                                    intersect: true
                                },
                                scales: {
                                    xAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Dias'
                                        }
                                    }],
                                    yAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Óbitos'
                                        }
                                    }]
                                }
                            }
                        });


                        var ctx = document.getElementsByClassName("graphCases");
                        var chartGraph = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: dataCases,
                                datasets: [{
                                    label: countryName,
                                    data: array_cases,
                                    borderWidth: 3,
                                    lineTension: 0.01,
                                    pointRadius: 0,
                                    pointHoverRadius: 5,
                                    pointHitRadius: 5,
                                    pointHoverBackgroundColor: "rgba(255, 159, 64, 0.2)",
                                    borderColor: "rgba(255, 159, 64)",
                                    backgroundColor: 'transparent',
                                }]
                            },
                            options: {
                                legend: {
                                    display: false
                                },
                                responsive: true,
                                scaleFontColor: "#FFFFFF",
                                title: {
                                    display: true,
                                    text: 'Casos'
                                },
                                hover: {
                                    mode: 'nearest',
                                    intersect: true
                                },
                                scales: {
                                    xAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Dias'
                                        }
                                    }],
                                    yAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Casos'
                                        }
                                    }]
                                }
                            }
                        });
                    })
                })
            }
        })



        $.post("php.php", {
            param: JSON.stringify("3")
        },
        function(data){
            data = JSON.parse(data);
            // AUMENTO CASOS ÚLTIMO DIA
            aumento_casos = (data.response).sort(( (a,b) => a.cases.new - b.cases.new ));
            let maior_aumento = aumento_casos.length - 1;
            country_array = [];
            cases_array = [];
            for(let i = maior_aumento; i > (maior_aumento - 5); i--){
                num = (aumento_casos[i].cases.new).split("+");
                num = (parseInt(num[1])).toLocaleString('pt-BR');

                country_array.push(aumento_casos[i].country);
                cases_array.push(parseInt(num.replace(".", "")));
                $(".pais_maior_caso table").append('<tr><td class="pais">'+aumento_casos[i].country+'</td><td class="pais_num">+'+num+'</td></tr>');
            }
            // Type, Data e Options
            var chartGraph = new Chart(maior_caso, {
                type: 'bar',
                data: {
                    labels: country_array,
                    datasets: [{
                        label: "Cases",
                        data: cases_array,
                        backgroundColor:["rgba(255, 99, 132, 0.2)",
                                        "rgba(255, 159, 64, 0.2)",
                                        "rgba(255, 205, 86, 0.2)",
                                        "rgba(75, 192, 192, 0.2)",
                                        "rgba(54, 162, 235, 0.2)",
                                        "rgba(153, 102, 255, 0.2)",
                                        "rgba(201, 203, 207, 0.2)"],
                        borderColor:["rgb(255, 99, 132)",
                                    "rgb(255, 159, 64)",
                                    "rgb(255, 205, 86)",
                                    "rgb(75, 192, 192)",
                                    "rgb(54, 162, 235)",
                                    "rgb(153, 102, 255)",
                                    "rgb(201, 203, 207)"],
                        borderWidth:1
                    }]
                },
                options: {        
                    legend: {
                        display: false
                    }
                }
            });

            // AUMENTO MORTES ÚLTIMO DIA
            aumento_mortes = (data.response).sort(( (a,b) => a.deaths.new - b.deaths.new ));
            maior_aumento = aumento_mortes.length - 1;
            country_array = [];
            deaths_array = [];
            for(let i = maior_aumento; i > (maior_aumento - 5); i--){
                num = (aumento_mortes[i].deaths.new).split("+");
                num = (parseInt(num[1])).toLocaleString('pt-BR');

                country_array.push(aumento_mortes[i].country);
                deaths_array.push(parseInt(num.replace(".", "")));
                $(".pais_maior_morte table").append('<tr><td class="pais">'+aumento_mortes[i].country+'</td><td class="pais_num">+'+num+'</td></tr>');
            }
            // Type, Data e Options
            var chartGraph = new Chart(maior_morte, {
                type: 'bar',
                data: {
                    labels: country_array,
                    datasets: [{
                        label: "Deaths",
                        data: deaths_array,
                        backgroundColor:["rgba(255, 99, 132, 0.2)","rgba(255, 159, 64, 0.2)","rgba(255, 205, 86, 0.2)","rgba(75, 192, 192, 0.2)","rgba(54, 162, 235, 0.2)","rgba(153, 102, 255, 0.2)","rgba(201, 203, 207, 0.2)"],
                        borderColor:["rgb(255, 99, 132)","rgb(255, 159, 64)","rgb(255, 205, 86)","rgb(75, 192, 192)","rgb(54, 162, 235)","rgb(153, 102, 255)","rgb(201, 203, 207)"],
                        borderWidth:1
                    }]
                },
                options: {        
                    legend: {
                        display: false
                    }
                }
            });


            // MAIS CASOS
            mais_casos = (data.response).sort(( (a,b) => a.cases.total - b.cases.total ));
            maior_aumento = mais_casos.length - 1;
            country_array = [];
            cases_array = [];
            for(let i = maior_aumento; i > (maior_aumento - 5); i--){
                num = mais_casos[i].cases.total;
                num = parseInt(num).toLocaleString('pt-BR');

                country_array.push(mais_casos[i].country);
                cases_array.push(parseInt(num.replace(".", "").replace(".", "")));
                $(".pais_maior_in table").append('<tr><td class="pais">'+mais_casos[i].country+'</td><td class="pais_num">+'+num+'</td></tr>');
            }
            // Type, Data e Options
            var chartGraph = new Chart(maior_caso_all, {
                type: 'bar',
                data: {
                    labels: country_array,
                    datasets: [{
                        label: "Cases",
                        data: cases_array,
                        backgroundColor:["rgba(255, 99, 132, 0.2)","rgba(255, 159, 64, 0.2)","rgba(255, 205, 86, 0.2)","rgba(75, 192, 192, 0.2)","rgba(54, 162, 235, 0.2)","rgba(153, 102, 255, 0.2)","rgba(201, 203, 207, 0.2)"],
                        borderColor:["rgb(255, 99, 132)","rgb(255, 159, 64)","rgb(255, 205, 86)","rgb(75, 192, 192)","rgb(54, 162, 235)","rgb(153, 102, 255)","rgb(201, 203, 207)"],
                        borderWidth:1
                    }]
                },
                options: {        
                    legend: {
                        display: false
                    }
                }
            });

            // MAIS MORTES
            mais_mortes = (data.response).sort(( (a,b) => a.deaths.total - b.deaths.total));
            maior_aumento = mais_mortes.length - 1;
            country_array = [];
            deaths_array = [];
            for(let i = maior_aumento; i > (maior_aumento - 5); i--){
                num = mais_mortes[i].deaths.total;
                num = parseInt(num).toLocaleString('pt-BR');

                country_array.push(mais_mortes[i].country);
                deaths_array.push(parseInt(num.replace(".", "")));
                $(".pais_maior_dea table").append('<tr><td class="pais">'+mais_mortes[i].country+'</td><td class="pais_num">+'+num+'</td></tr>');
            }
            // Type, Data e Options
            var chartGraph = new Chart(maior_morte_all, {
                type: 'bar',
                data: {
                    labels: country_array,
                    datasets: [{
                        label: "Deaths",
                        data: deaths_array,
                        backgroundColor:["rgba(255, 99, 132, 0.2)","rgba(255, 159, 64, 0.2)","rgba(255, 205, 86, 0.2)","rgba(75, 192, 192, 0.2)","rgba(54, 162, 235, 0.2)","rgba(153, 102, 255, 0.2)","rgba(201, 203, 207, 0.2)"],
                        borderColor:["rgb(255, 99, 132)","rgb(255, 159, 64)","rgb(255, 205, 86)","rgb(75, 192, 192)","rgb(54, 162, 235)","rgb(153, 102, 255)","rgb(201, 203, 207)"],
                        borderWidth:1
                    }]
                },
                options: {        
                    legend: {
                        display: false
                    }
                }
            });
        });
        Chart.defaults.global.defaultFontColor = "#223a50";
    </script>
</html>