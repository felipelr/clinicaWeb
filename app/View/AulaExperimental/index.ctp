<input type="hidden" id="totalRegistros" value="<?php echo $totalRegistros; ?>"/>
<div class="">
    <h3>Pacientes disponíveis para importação de cadastro</h3>
    <button type="button" class="btn btn-success" onclick="importar()"><i class="fa fa-download fa-lg"></i> Importar</button>
</div>

<hr/>
<table class="table table-hover table-responsive table-striped" id="contents-table">
    <thead>
        <tr>
            <th class="text-center col-sm-1"><input type="checkbox" id="checkTodos"/></th>
            <th>Paciente</th>
            <th>E-mail</th>
            <th class="text-center col-sm-2">Data Nascimento</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
</div>
<?php
$this->start('css');
echo $this->Html->css(array("icheck/all.css", "jspanel/jquery-ui.min.css", "jspanel/jquery.jspanel.css"), null, array("block" => "css"));
$this->end();
$this->start('script');
echo $this->Html->script(array("icheck/icheck.min.js", "jspanel/jquery-ui.min.js", "jquery.ui.touch-punch.min.js",
    "jspanel/mobile-detect.min.js", "jspanel/jquery.jspanel.js"), array("block" => "script"));
?>
<script type="text/javascript">
    var length_ = 15;
    var start_ = 0;
    jQuery(document).ready(function () {
        $('#checkTodos').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            increaseArea: '20%' // optional
        });
        $('#checkTodos').on('ifChecked', function () {
            $(".itens").iCheck('check');
        });
        $('#checkTodos').on('ifUnchecked', function () {
            $(".itens").iCheck('uncheck');
        });

        var totalRegistros = $("#totalRegistros").val();
        if (totalRegistros > 0) {
            pesquisar(length_, start_);
        } else {
            showNaoHaRegistros();
        }

        $(window).scroll(function () {
            if ($(window).scrollTop() + $(window).height() == $(document).height()) {
                if ((start_ + 15) < totalRegistros) {
                    start_ = start_ + 15;
                    pesquisar(length_, start_);
                }
            }
        });
    });

    function pesquisar(length, start) {
        $.ajax({
            url: $NOME_APLICACAO + "/aula_experimental/ajax_retornar_todos",
            type: 'POST',
            data: {length: length, start: start},
            success: function (data, textStatus, jqXHR) {
                if (data !== null) {
                    var jsonArray = $.parseJSON(data);
                    if (jsonArray.length > 0) {
                        var html = '';
                        $.each(jsonArray, function (index, value) {
                            html += '<tr> <td class="text-center col-sm-1"> <input class="itens" type="checkbox" value="' + value.a.idaulaexperimental + '"/> </td> <td>' + value.a.nome + ' ' + value.a.sobrenome + '</td> <td>' + value.a.email + '</td> <td class="text-center col-sm-2">' + value.a.data_nascimento + '</td> </tr>';
                        });
                        if (start == 0) {
                            $('#contents-table tbody').html(html);
                        } else {
                            $('#contents-table tbody').append(html);
                        }
                        $('.itens').iCheck({
                            checkboxClass: 'icheckbox_minimal-blue',
                            increaseArea: '20%' // optional
                        });
                    } else {
                        if (start == 0) {
                            showNaoHaRegistros();
                        }
                    }
                } else {
                    if (start == 0) {
                        showNaoHaRegistros();
                    }
                }
            },
            error: function () {
                if (start == 0) {
                    showNaoHaRegistros();
                }
            }
        });
    }

    function showNaoHaRegistros() {
        $('#contents-table tbody').html('<tr><td colspan="4"><p class="text-center" style="width: 100%;">Não há registros</p></td></tr>');
    }

    function importar() {
        var arrayItens = $('.itens');
        var ids = "";
        for (var i = 0; i < arrayItens.size(); i++) {
            if ($(arrayItens[i]).is(":checked")) {
                ids += $(arrayItens[i]).val() + ",";
            }
        }
        if (ids.length == 0) {
            $.jsPanel({
                paneltype: 'hint',
                theme: 'warning',
                position: {top: 70, right: 0},
                size: {width: 400, height: 'auto'},
                content: "<div style='padding: 20px;'>Por favor selecione pelo menos 1 registro.</div>"
            });
        } else {
            ids = ids.substring(0, ids.length - 1);
            $.ajax({
                url: $NOME_APLICACAO + "/aula_experimental/ajax_importar_pacientes",
                type: 'POST',
                data: {ids: ids},
                success: function (data, textStatus, jqXHR) {
                    start_ = 0;
                    pesquisar(length_, start_);
                    $.jsPanel({
                        paneltype: 'hint',
                        theme: 'success',
                        position: {top: 70, right: 0},
                        size: {width: 400, height: 'auto'},
                        content: "<div style='padding: 20px;'>Paciente(s) importados com sucesso.</div>"
                    });
                },
                error: function () {
                    $.jsPanel({
                        paneltype: 'hint',
                        theme: 'danger',
                        position: {top: 70, right: 0},
                        size: {width: 400, height: 'auto'},
                        content: "<div style='padding: 20px;'>Falha na importação.</div>"
                    });
                }
            });
        }
    }
</script>
<?php
$this->end();
