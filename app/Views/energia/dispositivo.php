<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-line me-2"></i>
            Lecturas de Energía - <?= esc($dispositivo['nombre']) ?>
        </h1>
        <a href="<?= base_url('admin/dispositivos') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <!-- Gráfico general de consumo -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Gráfico de Consumo</h6>
        </div>
        <div class="card-body">
            <canvas id="graficoConsumo"></canvas>
        </div>
    </div>

    <!-- Gauges de variables -->
    <div class="row">
        <?php
        $gauges = [
            ['id'=>'Voltaje','unidad'=>'V','max'=>300,'alert'=>null],
            ['id'=>'Corriente','unidad'=>'A','max'=>25,'alert'=>['yellow'=>14,'red'=>18]],
            ['id'=>'Potencia','unidad'=>'W','max'=>4400,'alert'=>['yellow'=>3000,'red'=>4000]],
            ['id'=>'Kwh','unidad'=>'kWh','max'=>100,'alert'=>null]
        ];
        foreach($gauges as $g):
        ?>
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= $g['id'] ?> (<?= $g['unidad'] ?>)</h6></div>
                <div class="card-body text-center">
                    <div id="gauge<?= $g['id'] ?>" style="height: 300px;"></div>
                    <div id="valor<?= $g['id'] ?>" data-unidad="<?= $g['unidad'] ?>" class="mt-2" style="font-size: 24px; font-weight: bold;">
                        0 <?= $g['unidad'] ?> <span id="trend<?= $g['id'] ?>"></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Tabla de Lecturas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Historial de Lecturas</h6></div>
        <div class="card-body">
            <?php if (empty($lecturas)): ?>
                <div class="alert alert-info">No hay lecturas disponibles para este dispositivo.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="tablaLecturas">
                        <thead>
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Voltaje (V)</th>
                                <th>Corriente (A)</th>
                                <th>Potencia (W)</th>
                                <th>Energía (kWh)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lecturas as $lectura): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i:s', strtotime($lectura['fecha'])) ?></td>
                                    <td><?= number_format($lectura['voltaje'], 2) ?></td>
                                    <td><?= number_format($lectura['corriente'], 2) ?></td>
                                    <td><?= number_format($lectura['potencia'], 2) ?></td>
                                    <td><?= number_format($lectura['kwh'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($lecturas)): ?>
    const lecturas = <?= json_encode($lecturas) ?>;
    function ultimoValor(variable) { return lecturas.length ? lecturas[lecturas.length-1][variable] : 0; }
    function valorAnterior(variable) { return lecturas.length > 1 ? lecturas[lecturas.length-2][variable] : 0; }

    // Gráfico de consumo
    const ctxConsumo = document.getElementById('graficoConsumo').getContext('2d');
    const labels = lecturas.map(l => new Date(l.fecha).toLocaleString());
    const datosPotencia = lecturas.map(l => l.potencia);
    new Chart(ctxConsumo,{
        type:'line',
        data:{labels:labels,datasets:[{label:'Potencia (W)',data:datosPotencia,borderColor:'rgb(75,192,192)',tension:0.1,fill:false}]},
        options:{responsive:true,scales:{y:{beginAtZero:true}}}
    });

    function crearGauge(id,label,max,valor,alertLimits=null){
        const chart = echarts.init(document.getElementById(id));
        const option = {
            series:[{
                type:'gauge',
                startAngle:200,endAngle:-20,
                min:0,max:max,splitNumber:10,
                progress:{show:true,width:30},
                pointer:{show:true,length:80,radius:'60%',width:8},
                axisLine:{lineStyle:{width:30,color:alertLimits?[[alertLimits.red[0]/max,'#FF4C4C'],[alertLimits.yellow[0]/max,'#FFEB3B'],[1,'#4CAF50']]:[[1,'#4CAF50']]}},
                axisTick:{distance:-45,splitNumber:5,lineStyle:{width:2,color:'#999'}},
                splitLine:{distance:-52,length:14,lineStyle:{width:3,color:'#999'}},
                axisLabel:{distance:-20,color:'#999',fontSize:16},
                title:{show:true,offsetCenter:[0,'70%'],fontSize:16,color:'#333',text:label},
                detail:{valueAnimation:true,fontSize:30,fontWeight:'bolder',formatter:'{value}',color: function(v){
                    if(!alertLimits) return '#333';
                    if(v>=alertLimits.red[0]) return '#FF4C4C';
                    if(v>=alertLimits.yellow[0]) return '#FFEB3B';
                    return '#4CAF50';
                }},
                data:[{value:valor}]
            }]
        };
        chart.setOption(option);
        return chart;
    }

    const gaugeVoltaje = crearGauge('gaugeVoltaje','Voltaje (V)',300,ultimoValor('voltaje'));
    const gaugeCorriente = crearGauge('gaugeCorriente','Corriente (A)',25,ultimoValor('corriente'),{yellow:14,red:18});
    const gaugePotencia = crearGauge('gaugePotencia','Potencia (W)',4400,ultimoValor('potencia'),{yellow:3000,red:4000});
    const gaugeKwh = crearGauge('gaugeKwh','kWh',100,ultimoValor('kwh'));

    function actualizarGauge(chart,valor,alertLimits,elemento,trendEl){
        chart.setOption({
            series:[{data:[{value:valor}]}],
            detail:{fontSize: valor>=alertLimits?.red?50:30, color: function(v){
                if(!alertLimits) return '#333';
                if(valor>=alertLimits.red) return '#FF4C4C';
                if(valor>=alertLimits.yellow) return '#FFEB3B';
                return '#4CAF50';
            }}
        });

        if(elemento){
            elemento.textContent = valor.toFixed(2)+' '+elemento.dataset.unidad;
            elemento.style.color = (alertLimits && valor>=alertLimits.red) ? '#FF4C4C' :
                                   (alertLimits && valor>=alertLimits.yellow) ? '#FFEB3B' : '#4CAF50';
        }
        if(trendEl){
            const diff = valor - valorAnterior(elemento.dataset.unidad.toLowerCase());
            trendEl.innerHTML = diff>0?' &#9650;':(diff<0?' &#9660;':' &#8212;');
        }
    }

    setInterval(()=>{
        actualizarGauge(gaugeVoltaje,ultimoValor('voltaje'),null,document.getElementById('valorVoltaje'),document.getElementById('trendVoltaje'));
        actualizarGauge(gaugeCorriente,ultimoValor('corriente'),{yellow:14,red:18},document.getElementById('valorCorriente'),document.getElementById('trendCorriente'));
        actualizarGauge(gaugePotencia,ultimoValor('potencia'),{yellow:3000,red:4000},document.getElementById('valorPotencia'),document.getElementById('trendPotencia'));
        actualizarGauge(gaugeKwh,ultimoValor('kwh'),null,document.getElementById('valorKwh'),document.getElementById('trendKwh'));
    },2000);
    <?php endif; ?>
});
</script>

<?= $this->endSection() ?>
