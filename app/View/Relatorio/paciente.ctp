<div class="">
    <div>        
        <h3>Relatório de pacientes</h3>
        <br/>
    </div>
    <div class="well">
        <form id="form-consultar" class="" data-action="<?php echo $this->Html->url(array("controller" => "relatorio", "action" => "ajax_relatorio_paciente")); ?>" method="post">
            <div class="row-divisao">
                <div class="form-group col-md-3">
                    <label class="control-label">Letra inicial: </label>
                    <select id="letra-inicial" class="form-control" name="letra_inicial">
                        <option value="TODOS">Todas as letras</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                        <option value="F">F</option>
                        <option value="G">G</option>
                        <option value="H">H</option>
                        <option value="I">I</option>
                        <option value="J">J</option>
                        <option value="K">K</option>
                        <option value="L">L</option>
                        <option value="M">M</option>
                        <option value="N">N</option>
                        <option value="O">O</option>
                        <option value="P">P</option>
                        <option value="Q">Q</option>
                        <option value="R">R</option>
                        <option value="S">S</option>
                        <option value="T">T</option>
                        <option value="U">U</option>
                        <option value="V">V</option>
                        <option value="X">X</option>
                        <option value="Y">Y</option>
                        <option value="W">W</option>
                        <option value="Z">Z</option>
                    </select>
                </div> 
                <div class="form-group col-md-9">
                    <label class="control-label">Nome: </label>
                    <input id="nome" class="form-control" name="nome" value="<?php echo $nome; ?>"/>
                </div> 
            </div>
            <div class="row-divisao">
                <div class="form-group col-md-6">
                    <label class="control-label">Data de cadastro: </label>
                    <input id="data-de" class="form-control" name="data_de" value="<?php echo $dataDE; ?>"/>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">até: </label>
                    <input id="data-ate" class="form-control" name="data_ate" value="<?php echo $dataATE; ?>"/>
                </div>
            </div>
            <div class="row-divisao">
                <div class="form-group col-md-6">
                    <label class="control-label">Data de inicio: </label>
                    <input id="data-inicio-de" class="form-control" name="data_inicio_de" value="<?php echo $dataInicioDE; ?>"/>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">até: </label>
                    <input id="data-inicio-ate" class="form-control" name="data_inicio_ate" value="<?php echo $dataInicioATE; ?>"/>
                </div>
            </div>
            <div style="padding: 15px;">             
                <div class="center-block" style="width: 150px;">
                    <button id="btn-search" type="button" onclick="consultar()" class="btn btn-primary "><i class="fa fa-search fa-lg"></i> Consultar</button>
                </div>
            </div>
        </form>
    </div>

    <hr/>
    <table id="tabela-pacientes" class="table table-responsive table-bordered">
        <thead>
            <tr>
                <th>Paciente</th>
                <th>E-mail</th>
                <th class="text-center">CPF</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>        
        <tbody>
            <tr>
                <td colspan='4'><p class='text-center'> Não há registros</p></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th>Paciente</th>
                <th>E-mail</th>
                <th class="text-center">CPF</th>
                <th class="text-center">Ações</th>
            </tr>
        </tfoot>
    </table>
</div>
<?php
echo $this->Html->css(array("datepicker.min.css", "style.relatorio.paciente.css?v=1.5"), null, array("block" => "css"));
echo $this->Html->script(array("bootstrap-datepicker.min.js", "jquery.maskedinput.min.js"), array("block" => "script"));
$this->start('script');
?>
<script type="text/javascript">
    var carregando = false;

    jQuery(document).ready(function () {
        var checkin = $('#data-de').datepicker({
            format: "dd/mm/yyyy",
            orientation: "top auto"
        }).on('changeDate', function (ev) {
            if (ev.date.valueOf() > checkout.date.valueOf()) {
                var newDate = new Date(ev.date);
                newDate.setDate(newDate.getDate() + 1);
                checkout.setValue(newDate);
            }
            checkin.hide();
            $('#data-ate')[0].focus();
        }).data('datepicker');

        var checkout = $('#data-ate').datepicker({
            format: "dd/mm/yyyy",
            orientation: "top auto"
        }).on('changeDate', function (ev) {
            checkout.hide();
        }).data('datepicker');

        var checkin2 = $('#data-inicio-de').datepicker({
            format: "dd/mm/yyyy",
            orientation: "top auto"
        }).on('changeDate', function (ev) {
            if (ev.date.valueOf() > checkout2.date.valueOf()) {
                var newDate = new Date(ev.date);
                newDate.setDate(newDate.getDate() + 1);
                checkout2.setValue(newDate);
            }
            checkin2.hide();
            $('#data-ate')[0].focus();
        }).data('datepicker');

        var checkout2 = $('#data-inicio-ate').datepicker({
            format: "dd/mm/yyyy",
            orientation: "top auto"
        }).on('changeDate', function (ev) {
            checkout2.hide();
        }).data('datepicker');

        jQuery("#data-de").mask("99/99/9999");
        jQuery("#data-ate").mask("99/99/9999");
        jQuery("#data-inicio-de").mask("99/99/9999");
        jQuery("#data-inicio-ate").mask("99/99/9999");

        consultar();
    });

    function mostrarEventos(iddespesa) {
        $("#paciente-" + iddespesa).toggle('slow');
    }

    function consultar() {
        if (!carregando) {
            carregando = true;
            jQuery("#btn-search").html('<i class="fa fa-refresh fa-lg"></i> Aguarde carregando...');
            var data = jQuery("#form-consultar").serialize();
            var formURL = jQuery("#form-consultar").attr("data-action");

            jQuery.ajax({
                url: formURL,
                type: 'POST',
                data: data,
                success: function (data, textStatus, jqXHR) {
                    jQuery("#tabela-pacientes tbody").html("");
                    jQuery("#tabela-pacientes tbody").html(data);
                    jQuery("#btn-search").html('<i class="fa fa-search fa-lg"></i> Consultar');
                    carregando = false;
                },
                error: function () {
                    jQuery("#btn-search").html('<i class="fa fa-search fa-lg"></i> Consultar');
                    carregando = false;
                }
            });
        }
    }
</script>
<?php
$this->end();
