<div id="wrapper">
    <?php
    Template::cargar_vista('sidebar');
    ?>
    <div id="page-wrapper" class="gray-bg row">

        <?php Template::cargar_vista('navbar'); ?>
        <div class="row border-bottom"></div>
        <?php Template::cargar_vista('breadcrumb'); //Breadcrumb ?>

        <div class="col-lg-12">
            <div class="wrapper-content" id="form_crear_entrega_alquiler_div">
                <div class="row">
                    <div id="mensaje_info"></div>
                    <div class="alert alert-dismissable <?php echo!empty($mensaje) ? 'show ' . $mensaje["clase"] : 'hide' ?>">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                        <?php echo!empty($mensaje) ? $mensaje["mensaje"] : '' ?>
                    </div>
                </div>
                <?php
                $formAttr = array(
                    'method' => 'POST',
                    'id' => 'form_crear_entrega_alquiler',
                    'autocomplete' => 'off'
                );

                echo form_open(base_url('entregas_alquiler/guardar'), $formAttr);
                ?>

                <div class="row" style="margin-right: 0px">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="background-color: #D9D9D9;padding: 7px 0 7px 0px;">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3" style="padding-top: 7px;">
                            <span style="font-weight: bold">Empezar entrega desde </span>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <select class="form-control" name="campo[empezar_desde_type]" required="" data-rule-required="true" v-select2="entrega_alquiler.empezar_desde_type" @change="cambiarTipo(entrega_alquiler.empezar_desde_type)" :disabled="disabledEditar">
                                <option value="">Seleccione</option>
                                <option value="contrato_alquiler">Contrato de alquiler</option>
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <select  class="form-control" name="campo[empezar_desde_id]" v-select2="entrega_alquiler.empezar_desde_id" :disabled="entrega_alquiler.empezar_desde_type == '' || disabledHeader || disabledEditar" @change="cambiarEmpezable(entrega_alquiler.empezar_desde_id)">
                                <option value="">Seleccione</option>
                                <option value="{{empezable.id}}" v-for="empezable in empezables | orderBy 'codigo'" v-if="(empezable.estado_id == '2') || vista != 'crear'">{{empezable.codigo}} - {{empezable.cliente.nombre}}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="ibox border-bottom">
                    <div class="ibox-title">
                        <h5>Datos de la entrega</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                        </div>
                    </div>

                    <div class="ibox-content" style="display:block;">
                        <div class="row">
                            <?php
                            $info = !empty($info) ? array("info" => $info) : array();
                            echo modules::run('entregas_alquiler/ocultoformulario', $info);
                            ?>
                        </div>
                    </div>

                    <div class="ibox-title">
                        <h5>&Iacute;tems contratados</h5>
                        <!-- case #1682 quitar caret
                        <div class="ibox-tools">
                            <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                        </div>-->
                    </div>

                    <div class="ibox-content" style="display:block;">
                        <div class="row">
                            <?php
                            $info = !empty($info) ? array("info" => $info) : array();
                            echo modules::run('entregas_alquiler/ocultoformulario_items_entregados', $info);
                            ?>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
                <!-- Comentarios -->
                 <div class="row">
                     <vista_comments
                      v-if="config.vista === 'editar'"
                      :config="config"
                      :historial.sync="comentario.comentarios"
                      :modelo="comentario.comentable_type"
                      :registro_id="comentario.comentable_id"
                      ></vista_comments>
                    </div>
                 <!-- Comentarios -->
            </div>

            <div class="wrapper-content">
                <div class="row" id="subpanel">
                    <?php SubpanelTabs::visualizar('sp_entrega_alquiler_id='.$entrega_alquiler->id); ?>
                </div>
            </div>

        </div><!-- cierra .col-lg-12 -->
    </div><!-- cierra #page-wrapper -->
</div><!-- cierra #wrapper -->

    <?php
        echo Modal::config(array(
            "id" => "optionsModal",
            "size" => "sm"
        ))->html();
