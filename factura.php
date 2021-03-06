<style type="text/css">

    .titulo1{
        font-weight:bold;
        font-size: 18px;
        text-align: center;
    }

    .titulo2{
        font-weight:bold;
        text-decoration: underline;
        font-size: 14px;
        padding-top: 10px;
    }

    .titulo3{
        padding-top: 20px;
    }

    .tabla_items{
        border: 1px solid black;
        border-collapse: collapse;
        padding-top: 10px;
    }

    .tabla_items th{
        border: 1px solid black;
    }

    .tabla_items td{
        border: 1px solid black;
        padding: 2px;
    }

    .numero{
        text-align: right;
    }

    .rojo{
        color:red;
    }


</style>

    <?php

    $descuento_total = 0;



    ?>
<div id="container">

    <table style="width: 100%;">
        <!--seccion de cabecera-->
        <tr>
            <td rowspan="3"><img id="logo" src="<?php $logo = !empty($factura->empresa->logo)?$factura->empresa->logo:'default.jpg'; echo $this->config->item('logo_path').'/'.$logo;?>" alt="Logo" border="0" height="96px" /></td>
            <td class="titulo1">FACTURA</td>
        </tr>
        <tr>
            <td class="titulo1">*** DOCUMENTO NO FISCAL ***</td>
        </tr>
        <tr>
            <td class="titulo1">No. de Factura: <span class="rojo"><?php echo $factura->codigo?></span></td>
        </tr>

        <!--datos de la empresa-->
        <tr>
            <td><br><br><?php echo strtoupper($factura->empresa->nombre);?></td>
            <td><br><br>Fecha: <?php echo date('d-m-Y', time())?></td>
        </tr>
        <tr>
            <td><?php echo strtoupper($factura->empresa->ruc);?></td>
            <td>Pagar antes de: <?php  
            $date = DateTime::createFromFormat('d/m/Y H:i', $factura->fecha_hasta.' 00:00');
            echo $date->format('d-m-Y');

            //echo  $factura->setFechaHastaAttributePdf();  se estaba usando este metodo, pero mandada un error, tuve q user php para salir rapido, corregir, Kimi
            ?></td>
        </tr>
        <tr>
          <td><?php echo strtoupper($factura->empresa->descripcion);?></td>
          <td>Preparado por: <?php echo $factura->vendedor->nombre.' '.$factura->vendedor->apellido?></td>
        </tr>
        <tr>
            <td><?php echo $factura->empresa->telefono; ?></td>
            <td>Centro : <?php echo $factura->centro->nombre; ?></td>
        </tr>

        <!--division-->
        <tr>
            <td colspan="2" style="border-bottom: 1px solid black;"></td>
        </tr>

        <!--datos del cliente-->
        <tr>
            <td class="titulo2">CLIENTE:</td>
            <td class="titulo2">ENTREGAR EN:</td>
        </tr>
        <tr>
            <td><?php echo $factura->cliente->nombre;?></td>
            <td> <?php echo $factura->centros_fac->direccion; ?></td>
        </tr>
        <tr>
          <td><?php echo $factura->centros_fac->nombre; ?>       </td>
          <td></td>
        </tr>
        <tr>
            <td><?php echo $factura->cliente->identificacion;?></td>
            <td></td>
        </tr>


        <!--tabla de items-->
        <tr>
            <td colspan="2">

                <table style="width: 100%;" class="tabla_items">
                    <thead>
                        <tr>
                            <th>Categor&iacute;a</th>
                            <th>Item</th>
                            <th>Atributo</th>
                            <th>Cant.</th>
                            <th>Unidad</th>
                            <th>Precio unitario</th>
                            <th>Descuento</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($factura->items as $item):?>
                        <tr>
                            <td><?php echo $item->categoria->nombre;?></td>
                            <td><?php echo $item->inventario_item->nombre;?></td>
                            <td><?php
                            $attributeoutput = '';
                            
                            try {
                                if ($item->atributo_id <> 0){
                                    $attributeoutput = $item->getAttributes[0]->nombre;
                                } else {
                                    $attributeoutput = $item->atributo_text;
                                }
                            } catch (Exception $ex) {
                                $attributeoutput = $item->atributo_text;    
                            }
                            
                            echo $attributeoutput;?></td>
                            <td class="numero"><?php echo $item->cantidad;?></td>
                            <td style="text-align: center;"><?php echo $item->unidad->nombre;?></td>
                            <td class="numero">$<?php echo $item->precio_unidad;?></td>
                            <td class="numero">$<?php 
                            $descuento = ($item->descuento*$item->precio_total) / 100;
                            $descuento_total += $descuento;
                            echo $descuento;?></td>
                            <td class="numero">$<?php echo $item->precio_total;?></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>            </td>
        </tr>

        <!--pie de tabla de items-->
        <tr>
            <td>Término de pago: <?php echo $factura->termino_pago2->valor;?></td>
            <td rowspan="3">

                <table style="width: 100%">
                    <tr>
                        <td>Subtotal:</td>
                        <td class="numero">$<?php echo $factura->subtotal?></td>
                    </tr>
                    <tr>
                        <td>Descuento:</td>
                        <td class="numero">$<?php echo number_format($descuento_total, 2, '.','');?></td>
                    </tr>
                    <tr>
                        <td>Impuestos:</td>
                        <td class="numero">$<?php echo $factura->impuestos?></td>
                    </tr>
                    <tr>
                        <td>Total:</td>
                        <td class="numero">$<?php echo $factura->total?></td>
                    </tr>
                    <tr>
                        <td>Cobros:</td>
                        <td class="numero">$<?php 
                        echo number_format($factura->total_facturado(), 2, '.',''); ?></td>
                    </tr>
                    <tr>
                        <td>Saldos:</td>
                        <td class="numero">$<?php echo number_format($factura->total - $factura->total_facturado(), 2,'.',''); ?></td>
                    </tr>
                </table>            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><br></td>
        </tr>

        <!--division-->
        <tr>
            <td colspan="2" style="border-bottom: 1px solid black;"></td>
        </tr>

        <tr>
            <td class="titulo3">Observaciones:</td>
            <td class="titulo3">Autorizaciones:</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;"><?php echo $factura->comentario;?></td>
            <td style="border: 1px solid black;"><?php 
            //if estado is por_cobrar then usuario + fecha y hora de actualizacion 
            $estado = $factura->estado;
            if ($estado == 'por_cobrar' OR $estado == 'cobrado_parcial' OR $estado == 'cobrado_completo') {
                $autorizadopor = $history->usuario;
                $autorizadoel = $factura->updated_at->format('d-m-Y');
                echo $autorizadopor . '<br />' . $autorizadoel;
                //ver cambio
            }
            ?><br><br><br></td>
        </tr>
    </table>

</div>