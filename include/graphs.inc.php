<?php
$time = time();
$ratioAccessible = $graphsController->getRatioAccessible($conn);
$listeAnnees = $graphsController->getListeAnnees($conn);
$ratioAccessibleAnnees = $graphsController->getRatioAccessibleAnnees($conn, $listeAnnees);
$disponibleAnnees = $ratioAccessibleAnnees["disponible"];
$nonDisponibleAnnees = $ratioAccessibleAnnees["non_disponible"];
$nombreCumulAnnees = $graphsController->getCumulThesesAnnees($conn, $listeAnnees);
$sujetsCompte = $graphsController->getCompteMotsCles($conn);
$etablissements = $graphsController->getRegions($conn);
$ratioLangues = $graphsController->getRatioLangues($conn);
$soutenanceMois = $graphsController->soutenancesParMois($conn);
echo "<pre>";
    // var_dump($soutenanceMois);
echo "</pre>";
// ?>
<style>


    #graphs-container {
        margin-left: 2rem;
        margin-right: 2rem;
        padding: 2rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 1fr 1fr;
        grid-gap: 10px;
    }
    /* on each line put 2 graph */
    #graphs-container>section:nth-child(2n+1) {
        grid-column: 1/2;

    }

    #graphs-container>section:nth-child(2n) {
        grid-column: 2/3;
    }




    /**even childs */
    #graphs-container>section:nth-child(even) {
        background-color: #f5f5f5;
    }

    /**odd childs */
    #graphs-container>section:nth-child(odd) {
        background-color: #fff;
    }


    .graph {
        border-radius: 5px;
        grid-column: 1/2;
        box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.2);
    }

    .graph::after {
        content: " ";
        display: block;
    }


    @media (max-width: 768px) {
        #graphs-container {
            grid-template-columns: 1fr;
            grid-template-rows: 1fr 1fr 1fr 1fr;
            padding: 2px;
            margin: 2px;

        }
        #graphs-container>section:nth-child(2n+1) {
            grid-column: 1/2;
            grid-row: 1/2;
        }

        #graphs-container>section:nth-child(2n) {
            grid-column: 1/2;
            grid-row: 2/3;
        }

        #graphs-container>section:nth-child(3n) {
            grid-column: 1/2;
            grid-row: 3/4;
        }

        #graphs-container>section:nth-child(4n) {
            grid-column: 1/2;
            grid-row: 4/5;
        }
    }
        
    


</style>
<details <?php if(isset($file) && $file === "index.php") echo "open"; ?>>
    <summary><h3 class="search-result-h">Graphiques</h3></summary>
<article id="graphs-container">
    <section class="graph" id="sec_camembert"></section>
    <section class="graph" id="sec_enligne-annees"></section>
    <section class="graph" id="sec_enligne-cumul-annees"></section>
    <section class="graph" id="sec_nuage-mots_cles"></section>
    <section class="map" id="sec_map_France"></section>
    <section class="graph" id="sec_langues"></section>
    <section class="graph" id="sec_mois"></section>

</article>
</details>


<script defer>
    Highcharts.chart('sec_camembert', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        exporting: {
            enabled: true
        },
        title: {
            text: 'Ratio des thèses en ligne / hors ligne'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        ing: {
            buttons: {
                contextButton: {
                    menuItems: ["downloadPNG", "downloadJPEG", "downloadPDF", "downloadSVG", "separator", "downloadCSV", "downloadXLS", "viewData", "openInCloud"]
                }
            }
        },
        series: [{
            name: 'Thèses',
            colorByPoint: true,
            data: [{

                name: 'En ligne',
                y: <?php echo $ratioAccessible["disponible"] ?>,
            }, {

                name: 'Hors ligne',
                y: <?php echo $ratioAccessible["non_disponible"] ?>,
            }]
        }]
    });
</script>

<script defer>
    Highcharts.chart('sec_enligne-annees', {
        chart: {
            type: 'column'
        },
        exporting: {
            enabled: true
        },
        title: {
            text: 'Thèses soutenues par années'
        },
        xAxis: {
            categories: [
                <?php
                foreach ($listeAnnees as $annee) {
                    echo "'" . $annee . "',";
                }
                ?>
            ],
            crosshair: true
        },
        yAxis: {
            title: {
                text: 'Nombre'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} thèses</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        ing: {
            buttons: {
                contextButton: {
                    menuItems: ["downloadPNG", "downloadJPEG", "downloadPDF", "downloadSVG", "separator", "downloadCSV", "downloadXLS", "viewData", "openInCloud"]
                }
            }
        },
        series: [{
                name: 'En ligne ',
                data: [<?php printIntArray($disponibleAnnees); ?>]

            },
            {
                name: 'Hors ligne',
                data: [<?php printIntArray($nonDisponibleAnnees); ?>]

            }
        ]
    });
</script>

<script defer>
    Highcharts.chart('sec_enligne-cumul-annees', {
        chart: {
            type: 'line'
        },
        exporting: {
            enabled: true
        },
        title: {
            text: 'Cumul du nombre de thèses par année'
        },
        xAxis: {
            categories: [
                <?php
                foreach ($listeAnnees as $annee) {
                    echo "'" . $annee . "',";
                }
                ?>
            ],
            crosshair: true
        },
        yAxis: {
            title: {
                text: 'Nombre'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} thèses</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        ing: {
            buttons: {
                contextButton: {
                    menuItems: ["downloadPNG", "downloadJPEG", "downloadPDF", "downloadSVG", "separator", "downloadCSV", "downloadXLS", "viewData", "openInCloud"]
                }
            }
        },
        series: [{
                name: 'Thèses ',
                data: [<?php printIntArray($nombreCumulAnnees); ?>]

            }
        ]
    });
</script>

<script defer>
// chart word cloud 

    Highcharts.chart('sec_nuage-mots_cles', {
        chart: {
            type: 'wordcloud',
            backgroundColor: 'transparent',
            style: {
                fontFamily: 'sans-serif'
            }
        },
        exporting: {
            enabled: true
        },
        title: {
            text: 'Mots les plus utilisés dans les thèses'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.weight:.1f}</b>'
        },
        series: [{
            type: 'wordcloud',
            data: [
                <?php
                foreach ($sujetsCompte as $mot) {
                    // cut the string to 15 characters and add '...' if it is longer
                    $mot["mot"] = substr($mot["mot"], 0, 15) . (strlen($mot["mot"]) > 15 ? '...' : '');
                    echo '{
                        name : "'.addslashes($mot["mot"]) .'",
                        weight : "'.$mot["nb"].'"
                    },';

                }
                ?>
            ],
            name: 'Occurrences'
        }],
        ing: {
            buttons: {
                contextButton: {
                    menuItems: ["downloadPNG", "downloadJPEG", "downloadPDF", "downloadSVG", "separator", "downloadCSV", "downloadXLS", "viewData", "openInCloud"]
                }
            }
        },
    });

</script>
<script>
(async () => {

    const topology = await fetch(
        'https://code.highcharts.com/mapdata/countries/fr/custom/fr-all-mainland.topo.json'
    ).then(response => response.json());

    // Prepare demo data. The data is joined to map using value of 'hc-key'
    // property by default. See API docs for 'joinBy' for more info on linking
    // data and map.
    const data = [
        <?php 
            foreach($etablissements as $etablissement) {
                if($etablissement["id"] == NULL)
                    continue;
                echo "['".$etablissement["id"]."', ".$etablissement["compte"]."]";
                if ($etablissement != end($etablissements)) {
                    echo ",";
                }

            }
            ?>
    ];

    // Create the chart
    Highcharts.mapChart('sec_map_France', {
        chart: {
            map: topology
        },

        title: {
            text: 'Thèses par région'
        },

        subtitle: {
            text: 'Source map: <a href="http://code.highcharts.com/mapdata/countries/fr/fr-all.topo.json">France</a>'
        },

        mapNavigation: {
            enabled: true,
            buttonOptions: {
                verticalAlign: 'bottom'
            }
        },

        colorAxis: {
            min: 0
        },

        series: [{
            data: data,
            name: 'Thèses :',
            states: {
                hover: {
                    color: '#BADA55'
                }
            },
            dataLabels: {
                enabled: true,
                format: '{point.name}'
            }
        }]
    });

})();

    </script>
<script>
    Highcharts.chart("sec_langues", {
        chart: {
            type: "pie"
        },
        exporting: {
            enabled: true
        },
        title: {
            text: "Langues des thèses"
        },
        tooltip: {
            pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>"
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: "pointer",
                dataLabels: {
                    enabled: true,
                    format: "<b>{point.name}</b>: {point.percentage:.1f} %",
                    style: {
                        color:
                            (Highcharts.theme &&
                                Highcharts.theme.contrastTextColor) ||
                            "black"
                    }
                }
            }
        },
        series: [{
            name: "Langues",
            colorByPoint: true,
            data: [
                <?php
                foreach ($ratioLangues as $value) {
                    echo "{name: '" . $value["id"] . "', y: " . $value["compte"] . "},";
                }
                ?>
            ]
        }]
    });
</script>
<script>
    // id:sec_mois , type: line
    Highcharts.chart("sec_mois", {
        chart: {
            type: "pie"
        },
        exporting: {
            enabled: true
        },
        title: {
            text: "Nombre de thèses par mois"
        },
        tooltip: {
            headerFormat: "<span style='font-size:10px'>{point.key}</span><table>",
            pointFormat:
                "<tr><td style='color:{series.color};padding:0'>{series.name}: </td>" +
                "<td style='padding:0'><b>{point.y:.1f} thèses</b></td></tr>",
            footerFormat: "</table>",
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        ing: {
            buttons: {
                contextButton: {
                    menuItems: [
                        "downloadPNG",
                        "downloadJPEG",
                        "downloadPDF",
                        "downloadSVG",
                        "separator",
                        "downloadCSV",
                        "downloadXLS",
                        "viewData",
                        "openInCloud"
                    ]
                }
            }
        },
        series: [{
            name: "Thèses ",
            data: [
                <?php
                foreach ($soutenanceMois as $liste) {
                    echo "{name: '" . $liste["mois"] . "', y: " . $liste["compte"] . "},";
                }
                ?>
            ]
        }]
    });


</script>