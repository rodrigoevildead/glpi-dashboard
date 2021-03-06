
<?php

if($data_ini == $data_fin) {
$datas = "LIKE '".$data_ini."%'";	
}	

else {
$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";	
}

$query4 = "
SELECT glpi_itilcategories.completename as cat_name, COUNT(glpi_tickets.id) as cat_tick, glpi_itilcategories.id
FROM glpi_tickets
LEFT JOIN glpi_itilcategories
ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
AND glpi_tickets.entities_id IN ( ".$id_ent.")
GROUP BY glpi_itilcategories.id
ORDER BY `cat_tick` DESC
LIMIT 5 ";

$result4 = $DB->query($query4) or die('erro');

$arr_grf4 = array();
while ($row_result = $DB->fetch_assoc($result4))		
	{ 
		$v_row_result = $row_result['cat_name']." (".$row_result['id'].")";
		$arr_grf4[$v_row_result] = $row_result['cat_tick'];			
	} 
	
$grf4 = array_keys($arr_grf4) ;
$quant4 = array_values($arr_grf4) ;
$soma4 = array_sum($arr_grf4);

$grf_3a = json_encode($grf4);
$quant_2a = implode(',',$quant4);


echo "
<script type='text/javascript'>

$(function () {
        $('#graf4').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Top 5 - ".__('Tickets by Category','dashboard')."'
            },
           
            xAxis: {
                categories: $grf_3a,
                labels: {
                    rotation: 0,
                    align: 'right',
                    style: {
                        fontSize: '11px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
        tooltip: {
                valueSuffix: ''
            },
            plotOptions: {
                bar: {
                    pointPadding: 0.2,
                    borderWidth: 0,  
                    borderWidth: 2,
                	  borderColor: 'white',
                	  shadow:true,         
                	  showInLegend: false,
                }
            },
            series: [{
                name: '".__('Tickets','dashboard')."',
                data: [$quant_2a],
                dataLabels: {
                    enabled: true,                    
                    //color: '#000099',
                    align: 'center',
                    x: 20,
                    y: 1,
                    style: {
                        //fontSize: '13px',
                        //fontFamily: 'Verdana, sans-serif'
                    }
                }    
            }]
        });
    });



		</script>"; ?>
