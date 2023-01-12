<?php
$ratioAccessible = getRatioAccessible($conn);
$listeAnnees = getListeAnnees($conn);
$ratioAccessibleAnnees = getRatioAccessibleAnnees($conn, $listeAnnees);
$disponibleAnnees = $ratioAccessibleAnnees["disponible"];
$nonDisponibleAnnees = $ratioAccessibleAnnees["non_disponible"];
$nombreCumulAnnees = getCumulThesesAnnees($conn, $listeAnnees);
$sujetsCompte = getCompteMotsCles($conn);
?>
<article id="graphs-container">
    <section class="graph" id="sec_camembert"></section>
    <section class="graph" id="sec_enligne-annees"></section>
    <section class="graph" id="sec_enligne-cumul-annees"></section>
    <section class="graph" id="sec_nuage-mots_cles"></section>
</article>


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
            type: 'column'
        },
        exporting: {
            enabled: true
        },
        title: {
            text: 'Cumum du nombre de thèses par année'
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
                    echo '{
                        name : "'.htmlentities($mot["mot"]) .'",
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

<style>
    #sec_camembert {
        grid-column: 1fr;
        grid-row: 1;
    }

    #sec_enligne-annees {
        grid-row: 1fr;
        grid-column: 1/4;
    }

    #graphs-container {
        display: grid;
        /* lines of  */
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
</style>