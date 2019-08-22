<?php
echo $this->Html->css(array("datepicker.min.css", "select2/select2.min.css", "icheck/all.css"), null, array("block" => "css"));
echo $this->Html->script(array("bootstrap-datepicker.min.js", "jquery.maskedinput.min.js", "jquery.maskMoney.min.js", "jquery-validate.min.js", "select2/select2.full.min.js",
    "icheck/icheck.min.js"), array("block" => "script"));
?>
<div class="">
    <div class="col-xs-12">
        <h3>Gerencimento de Caixas</h3>
        <hr/>
        <form id="form-consultar" class="form-inline" data-action="<?php echo $this->Html->url(array("controller" => "caixa", "action" => "gerenciar")); ?>" method="post">
            <div class="form-group div-radios">
                <label class="radio-inline">
                    <input type="radio" name="radio_data_filtro" id="radio-filtro-1" value="1" checked > Data Abertura
                </label>
                <label class="radio-inline">
                    <input type="radio" name="radio_data_filtro" id="radio-filtro-2" value="2" > Data Fechamento
                </label>
            </div>
            <div class="form-group">
                <label class="control-label text-center" style="width: 40px;">De:</label>
            </div>
            <div class="form-group">
                <input id="data-filtro-de" class="form-control" name="data_filtro_de" value="<?php echo date("d/m/Y"); ?>"/>
            </div>
            <div class="form-group">
                <label class="control-label text-center" style="width: 40px;">Até</label>
            </div>
            <div class="form-group">
                <input id="data-filtro-ate" class="form-control" name="data_filtro_ate" value="<?php echo date("d/m/Y"); ?>"/>
            </div>
            <div class="form-group div-radios" style="margin-right: 15px;">
                <label class="radio-inline">
                    <input type="radio" name="radio_data" id="radio-data-1" value="1" checked > Hoje
                </label>
                <label class="radio-inline">
                    <input type="radio" name="radio_data" id="radio-data-2" value="2" > Esta semana
                </label>
                <label class="radio-inline">
                    <input type="radio" name="radio_data" id="radio-data-3" value="3" > Este mês
                </label>
            </div>
            <div style="padding-top: 15px;" class="col-lg-8 col-md-10 col-xs-12">
                <button id="btn-consultar" type="button" onclick="consultar()" class="btn btn-primary center-block"><i class="fa fa-lg fa-search"></i> Consultar</button>
            </div>
        </form>
    </div>
    <hr/>
    <h4>Caixas Abertos</h4>
    <div id="row-abertos" class="row">
        <div class="col-xs-12"><p>Não há registros</p></div>
    </div>
    <hr/>
    <h4>Caixas Fechados</h4>
    <div id="row-fechados" class="row">
        <div class="col-xs-12"><p>Não há registros</p></div>
    </div>
</div>
<script type="text/javascript">
    var carregando = false;

    jQuery(document).ready(function () {
        var checkinFiltroAte = $('#data-filtro-ate').datepicker({
            format: "dd/mm/yyyy",
            orientation: "top auto"
        }).on('changeDate', function (ev) {
            checkinFiltroAte.hide();
        }).data('datepicker');

        var checkinFiltroDe = $('#data-filtro-de').datepicker({
            format: "dd/mm/yyyy",
            orientation: "top auto"
        }).on('changeDate', function (ev) {
            if (ev.date.valueOf() > checkinFiltroAte.date.valueOf()) {
                var newDate = new Date(ev.date);
                newDate.setDate(newDate.getDate() + 1);
                checkinFiltroAte.setValue(newDate);
            }
            checkinFiltroDe.hide();
            $('#data-filtro-ate')[0].focus();
        }).data('datepicker');

        jQuery("#data-filtro-de").mask("99/99/9999");
        jQuery("#data-filtro-ate").mask("99/99/9999");

        $('.div-radios').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });

        $('#radio-data-1').on('ifChecked', function (event) {
            var now = new Date();
            checkinFiltroDe.setValue(now);
            checkinFiltroAte.setValue(now);
        });
        $('#radio-data-2').on('ifChecked', function (event) {
            var dateLastSunday = "<?php echo date("w") == 0 ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', (strtotime("last Sunday"))); ?>";
            var dateNesxtSaturday = "<?php echo date("w") == 6 ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', (strtotime("next Saturday"))); ?>";
            var now = new Date(dateLastSunday);
            var dateFim = new Date(dateNesxtSaturday);
            checkinFiltroDe.setValue(now);
            checkinFiltroAte.setValue(dateFim);
        });
        $('#radio-data-3').on('ifChecked', function (event) {
            var fistDay = "<?php echo date("Y-m-01 H:i:s"); ?>";
            var lastDay = "<?php echo date("Y-m-t H:i:s"); ?>";
            var now = new Date(fistDay);
            var dateFim = new Date(lastDay);
            checkinFiltroDe.setValue(now);
            checkinFiltroAte.setValue(dateFim);
        });
        
        consultar();
    });



    function consultar() {
        if (!carregando) {
            carregando = true;
            jQuery("#btn-consultar").html('<i class="fa fa-refresh fa-lg"></i> Aguarde carregando...');
            var data = jQuery("#form-consultar").serialize();
            var formURL = jQuery("#form-consultar").attr("data-action");

            jQuery.ajax({
                url: formURL,
                type: 'POST',
                data: data,
                success: function (data, textStatus, jqXHR) {
                    var htmlAbertos = '';
                    var htmlFechados = '';

                    if (data !== null) {
                        var json = $.parseJSON(data);
                        $.each(json.caixas, function (index, value) {
                            if (value.caf.aberto == 1) {
                                var caixa = value.c.descricao;
                                var dataAbertura = new Date(value.caf.data_abertura);
                                var saldoInicial = parseFloat(value.caf.saldo_inicial).toFixed(2);

                                var strDataAbertura = dataAbertura.getDate() +
                                        '/' + (dataAbertura.getMonth() + 1) +
                                        '/' + dataAbertura.getFullYear() +
                                        ' ' + dataAbertura.getHours() +
                                        ':' + dataAbertura.getMinutes();

                                htmlAbertos += '' +
                                        '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">' +
                                        '<div class="panel panel-info">' +
                                        '<div class="panel-heading bg-aqua text-uppercase"><i class="fa fa-lg fa-exclamation-circle"></i> ' + caixa + '</div>' +
                                        '<div class="panel-body">' +
                                        '<span class="info-box-text"><span class="text-capitalize text-bold">Data Abertura:</span> ' + strDataAbertura + '</span>' +
                                        '<span class="info-box-text"><span class="text-capitalize text-bold">Data Fechamento:</span> --/--/-- --:--</span>' +
                                        '</div>' +
                                        '<ul class="list-group">' +
                                        '<li class="list-group-item">Saldo Inicial: <span class="text-blue text-bold">R$ ' + saldoInicial + '</span></li>' +
                                        '</ul>' +
                                        '</div>' +
                                        '</div>';
                            } else {
                                var caixa = value.c.descricao;
                                var dataAbertura = new Date(value.caf.data_abertura);
                                var dataFechamento = new Date(value.caf.data_fechamento);
                                var saldoInicial = parseFloat(value.caf.saldo_inicial).toFixed(2);
                                var valorMovimento = parseFloat(value.cm.valorMovimento).toFixed(2);
                                var valorDeclarado = parseFloat(value.cm.valorDeclarado).toFixed(2);

                                var strDataAbertura = dataAbertura.getDate() +
                                        '/' + (dataAbertura.getMonth() + 1) +
                                        '/' + dataAbertura.getFullYear() +
                                        ' ' + dataAbertura.getHours() +
                                        ':' + dataAbertura.getMinutes();

                                var strDataFechamento = dataFechamento.getDate() +
                                        '/' + (dataFechamento.getMonth() + 1) +
                                        '/' + dataFechamento.getFullYear() +
                                        ' ' + dataFechamento.getHours() +
                                        ':' + dataFechamento.getMinutes();

                                var diferencaCaixa = valorDeclarado - valorMovimento;
                                var classCor = diferencaCaixa < 0 ? "text-red" : "text-green";

                                htmlFechados += '' +
                                        '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">' +
                                        '<div class="panel panel-success">' +
                                        '<div class="panel-heading bg-green text-uppercase"><i class="fa fa-lg fa-check-circle"></i> ' + caixa + '</div>' +
                                        '<div class="panel-body">' +
                                        '<span class="info-box-text"><span class="text-capitalize text-bold">Data Abertura:</span> ' + strDataAbertura + '</span>' +
                                        '<span class="info-box-text"><span class="text-capitalize text-bold">Data Fechamento:</span> ' + strDataFechamento + '</span>' +
                                        '</div>' +
                                        '<ul class="list-group">' +
                                        '<li class="list-group-item">Saldo Inicial: <span class="text-green text-bold">R$ ' + saldoInicial + '</span></li>' +
                                        '<li class="list-group-item">Fechamento: <span class="' + classCor + ' text-bold">R$ ' + diferencaCaixa + '</span></li>' +
                                        '</ul>' +
                                        '</div>' +
                                        '</div>';
                            }
                        });
                    }

                    if (htmlAbertos == '') {
                        htmlAbertos = '<div class="col-xs-12"><p>Não há registros</p></div>';
                    }
                    if (htmlFechados == '') {
                        htmlFechados = '<div class="col-xs-12"><p>Não há registros</p></div>';
                    }

                    $("#row-abertos").html(htmlAbertos);
                    $("#row-fechados").html(htmlFechados);

                    jQuery("#btn-consultar").html('<i class="fa fa-search fa-lg"></i> Consultar');
                    carregando = false;
                },
                error: function () {
                    jQuery("#btn-consultar").html('<i class="fa fa-search fa-lg"></i> Consultar');
                    carregando = false;
                }
            });
        }
    }
</script>
