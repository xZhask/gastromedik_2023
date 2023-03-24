<?php
session_start();
if (empty($_SESSION['active'])) {
  header('location: login.php');
}
require_once 'sistema/ado/clsProcedimiento.php';
require_once 'sistema/ado/clsConsultas.php';
require_once 'sistema/ado/clsMedicamentos.php';
$objProcedimiento = new clsProcedimiento();
$objConsultas = new clsConsultas();
$objMedicamentos = new clsMedicamentos();
$TIposDePago = $objConsultas->ListarTiposPago();
$listadoProc = $objProcedimiento->ListarProcedimientos();
$listadoMedicamentos = $objMedicamentos->ListarMedicamento();
$listadoProductos = $objMedicamentos->ListarProductos();
$array = [];
while ($row = $listadoProc->fetch(PDO::FETCH_NAMED)) {
  $nombre = $row['nombre'];
  array_push($array, $nombre);
}

$medicamentos = [];
while ($row = $listadoMedicamentos->fetch(PDO::FETCH_NAMED)) {
  $medicamento = $row['nombre'];
  array_push($medicamentos, $medicamento);
}
$productos = [];
while ($row = $listadoProductos->fetch(PDO::FETCH_NAMED)) {
  $producto = $row['nombre'];
  array_push($productos, $producto);
}
$cargo = $_SESSION['cargo'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://kit.fontawesome.com/47b4aaa3bf.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="recursos/js/jquery-ui-1.12.1/jquery-ui.min.css" />
  <script language="javascript" src="recursos/js/jquery-3.4.1.min.js"></script>
  <script language="javascript" src="recursos/js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="recursos/css/estilos.css" />
  <link rel="icon" type="image/png" href="recursos/img/favicon.png" />
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <title>Inicio</title>
</head>

<body>
  <div class="bg-dark">
    <div class="modal">
      <a href="javascript:void(0)" class="btn-close" onclick="cerrarmodal()">
        <i class="fas fa-times"></i>
      </a>
      <form id="frmregistrarcita">
        <h2>Registrar Cita</h2>
        <input type="hidden" id="AccionCita" name="accion" value="REGISTRAR_CITA" />
        <input type="hidden" id="CodigoCita" name="CodigoCita" />
        <input type="hidden" id="TipoPaciente" name="TipoPaciente" />
        <div class="grupo-inputs">
          <input type="text" class="txt-search" placeholder="Número de Documento" id="NroDocCita" name="NroDocCita" />
          <button type="button" class="btn-search" onclick="ObtenerDatosPaciente()">
            <i class="fas fa-search"></i>
          </button>
        </div>
        <input type="text" placeholder="Nombre de Paciente" id="NombrePacienteC" name="NombrePacienteC" readonly class="textdisabled" />
        <div class="cont-input-toggle">
          <div class="controls-colum">
            <label for="NombrePacienteCita">Nombres:</label>
            <input type="text" name="NombrePacienteCita" id="NombrePacienteCita" readonly class="textdisabled" />
          </div>
          <div class="controls-colum">
            <label for="ApellidosPacienteCita">Apellidos:</label>
            <input type="text" name="ApellidosPacienteCita" id="ApellidosPacienteCita" readonly class="textdisabled" />
          </div>
          <div class="controls-colum">
            <label for="fecha_nac">Fecha de Nac.:</label>
            <input type="date" name="FechaNacCita" id="FechaNacCita" />
          </div>
        </div>
        <div class="check-toggle">
          <div class="checkbox">
            <input type="checkbox" name="menor" id="menor" onclick="validar_menor()">
            <label for="menor">Menor de Edad</label>
          </div>
        </div>
        <input type="hidden" id="IdMovitoCita" name="IdMovitoCita">
        <input type="text" class="MotivoCita" id="MovitoCita" name="MovitoCita" placeholder="Motivo de Cita">
        <input type="number" id="PrecioMotivoCita" name="PrecioMotivoCita" placeholder="Precio de Proc./Consulta">
        <div class="grupo-inputs">
          <input type="date" name="FechaCita" id="FechaCita" />
          <input type="time" name="HoraCita" id="HoraCita" />
        </div>
        <input type="text" placeholder="N° de celular" id="NroCelularCita" name="NroCelularCita" />
        <button type="button" onclick="RegistrarCita()">
          Registrar
        </button>
      </form>
      <form id="frmregistrarpaciente">
        <h2>Registrar Paciente</h2>
        <input type="hidden" id="AccionPaciente" name="accion" value="REGISTRAR_PACIENTE" />
        <div class="grupo-inputs">
          <input type="text" class="txt-search" placeholder="Número de Documento" id="NroDocPaciente" name="NroDocPaciente" />
          <button type="button" class="btn-search" onclick="BuscarPersonaPaciente()">
            <i class="fas fa-search"></i>
          </button>
        </div>
        <input type="text" placeholder="Nombres de Paciente" class="textdisabled" id="NombrePaciente" name="NombrePaciente" readonly />
        <input type="text" placeholder="Apellidos de Paciente" class="textdisabled" id="ApellidosPaciente" name="ApellidosPaciente" readonly />
        <div id="checkPaciente" class="check-toggle">
          <div class="checkbox">
            <input type="checkbox" name="menorPaciente" id="menorPaciente" onclick="validar_menorPaciente()">
            <label for="menorPaciente">Menor de Edad</label>
          </div>
        </div>
        <label for="">Fecha de Nacimiento</label>
        <input type="date" name="fechanac" id="fechanac" />
        <input type="text" placeholder="N° Celular" id="NroCelular" name="NroCelular" />
        <button type="button" onclick="RegistrarPaciente()">Registrar</button>
      </form>
      <form id="frmregistrarusuario">
        <h2>Registrar Usuario</h2>
        <input type="hidden" id="AccionUsuario" name="accion" value="REGISTRAR_USUARIO" />
        <div class="grupo-inputs">
          <input type="text" class="txt-search" placeholder="Número de Documento" id="NroDocPersonal" name="NroDocPersonal" />
          <button type="button" class="btn-search" onclick="BuscarPersonaPersonal()">
            <i class="fas fa-search"></i>
          </button>
        </div>
        <input type="text" placeholder="Nombres de Personal" id="NombrePersonal" name="NombrePersonal" readonly class="textdisabled" />
        <input type="text" placeholder="Apellidos de Personal" id="ApellidosPersonal" name="ApellidosPersonal" readonly class="textdisabled" />
        <input type="text" placeholder="Usuario o Nickname" id="Nick" name="Nick" />
        <input type="text" placeholder="Contraseña" id="Pass" name="Pass" />
        <select name="idcargo" id="idcargo">
          <!-- AJAX-->
        </select>
        <select name="EstadoUsuario" id="EstadoUsuario">
          <option value="A">ACTIVO</option>
          <option value="I">INACTIVO</option>
        </select>
        <button type="button" onclick="RegistrarPersonal()">
          Registrar
        </button>
      </form>
      <form id="frmregistrarcargo">
        <h2>Registrar Cargo</h2>
        <input type="text" placeholder="Nombre de Nuevo Cargo" id="NombreCargo" name="NombreCargo" />
        <button type="button" onclick="RegistrarCargo()">Registrar</button>
      </form>
      <form id="frmregistrarpago">
        <h2>Registrar Pago</h2>
        <input type="hidden" id="AccionPago" name="accion" value="REGISTRAR_PAGO" />
        <input type="hidden" id="IdCita" name="IdCita" placeholder="ID DE CITA" readonly>
        <input type="text" placeholder="Nombres de Paciente" name="NombrePago" id="NombrePago" readonly />
        <input type="text" id="MotivoPago" name="MotivoPago" placeholder="Motivo de Cita" readonly />
        <label for="PrecioPago">Precio Total:</label>
        <input type="text" placeholder="Precio" id="PrecioPago" name="PrecioPago" readonly />
        <label for="ACuentaPago">A cuenta:</label>
        <input type="text" placeholder="A Cuenta" id="ACuentaPago" name="ACuentaPago" />
        <select name="tipopago" id="tipopago">
          <option value="0">TIPO DE PAGO</option>
          <?php while ($row = $TIposDePago->fetch(PDO::FETCH_NAMED)) { ?>
            <option value="<?php echo $row['idtipopago']; ?>"><?php echo $row['tipopago']; ?></option>
          <?php } ?>
        </select>
        <button type="button" id="btn-regPago" onclick="RegistrarPago()">Registrar</button>
      </form>
      <form id="frmraperturarcaja">
        <h2>Apertura de Caja</h2>
        <input type="number" placeholder="Monto inicial" id="MontoInicial" name="MontoInicial" min="1" />
        <button type="button" onclick="aperturarcaja()">Registrar</button>
      </form>
      <form id="frmRegistrarAtencion">
        <h2 id="NombresAtencion">
          APELLIDOS Y NOMBRES, DE PACIENTE
          <br />
          <span>Dni: - | Edad: - años</span>
        </h2>
        <input type="hidden" id="AccionAtencion" name="accion" value="REGISTRAR_ATENCION" />
        <input type="hidden" name="ate_idatencion" id="ate_idatencion">
        <input type="hidden" name="dni_atencion" id="dni_atencion">
        <input type="hidden" id="typeAction" name="typeAction" value="REGISTRAR" />
        <fieldset class="signos-vitales">
          <legend>
            Signos vitales
          </legend>
          <div class="grupo-inputs">
            <div class="grupo-controls">
              <label for="ate_fc">FC:</label>
              <input type="text" name="ate_fc" id="ate_fc" readonly />
            </div>
            <div class="grupo-controls">
              <label for="ate_pa">PA:</label>
              <input type="text" name="ate_pa" id="ate_pa" readonly />
            </div>
            <div class="grupo-controls">
              <label for="ate_temp">T°:</label>
              <input type="text" name="ate_temp" id="ate_temp" readonly />
            </div>
            <div class="grupo-controls">
              <label for="ate_so2">So2:</label>
              <input type="text" name="ate_so2" id="ate_so2" readonly />
            </div>
            <div class="grupo-controls">
              <label for="ate_peso">PESO:</label>
              <input type="text" name="ate_peso" id="ate_peso" readonly />
            </div>
          </div>
        </fieldset>
        <fieldset>
          <legend>
            Antecedentes
          </legend>
          <div class="grupo-inputs">
            <div class="grupo-controls w60 cont-gr-radios">
              <div class="group-radios">
                <label>HEPATITIS: </label>
                <div class="radio">
                  <input type="radio" class="radio-si" name="hep" id="hepsi" value="SI" />
                  <label for="hepsi">SI</label>
                  <input type="radio" name="hep" id="hepno" value="NO" checked />
                  <label for="hepno">NO</label>
                </div>
              </div>
              <div class="group-radios">
                <label>DIABETES: </label>
                <div class="radio">
                  <input type="radio" class="radio-si" name="dm" id="dmsi" value="SI" />
                  <label for="dmsi">SI</label>
                  <input type="radio" name="dm" id="dmno" value="NO" checked />
                  <label for="dmno">NO</label>
                </div>
              </div>
              <div class="group-radios">
                <label>HIV: </label>
                <div class="radio">
                  <input type="radio" class="radio-si" name="hiv" id="hivsi" value="SI" />
                  <label for="hivsi">SI</label>
                  <input type="radio" name="hiv" id="hivno" value="NO" checked />
                  <label for="hivno">NO</label>
                </div>
              </div>
              <div class="group-radios">
                <label>HTA: </label>
                <div class="radio">
                  <input type="radio" class="radio-si" name="hta" id="htasi" value="SI" />
                  <label for="htasi">SI</label>
                  <input type="radio" name="hta" id="htano" value="NO" checked />
                  <label for="htano">NO</label>
                </div>
              </div>
              <div class="group-radios w100">
                <label for="alergias">ALERGIAS: </label>
                <input type="text" name="alergias" id="alergias" value="-">
              </div>
            </div>
            <div class="grupo-controls w60 pd-left1">
              <div class="group-radios w100">
                <label>CIRUGÍAS: </label>
                <input type="text" name="cirugias" id="cirugias" value="-">
              </div>
              <div class="group-radios w100">
                <label>ENDOSCPÍA: </label>
                <input type="text" name="endoscopias" id="endoscopias" value="-">
              </div>
              <div class="group-radios w100">
                <label>COVID: </label>
                <div class="radio">
                  <input type="radio" name="covid" id="covidsi" value="SI" />
                  <label for="covidsi">SI</label>
                  <input type="radio" name="covid" id="covidno" value="NO" checked />
                  <label for="covidno">NO</label>
                </div>
              </div>
            </div>
          </div>
        </fieldset>
        <fieldset class="mgt5">
          <div class="grupo-inputs">
            <div class="grupo-controls w60">
              <label for="ate_molestia">Molestia Principal:</label>
              <textarea name="ate_molestia" id="ate_molestia" cols="30" rows="3" value="-"></textarea>
            </div>
            <div class="grupo-controls w60">
              <label for="ate_antecedentes">Antecedentes:</label>
              <textarea name="ate_antecedentes" id="ate_antecedentes" cols="30" rows="3" value="-"></textarea>
            </div>
          </div>
        </fieldset>
        <fieldset class="mgt5">
          <div class="cont-contenido-atencion">
            <div class="contenido-atencion">
              <h2>ANAMNESIS</h2>
              <textarea name="txtanamnesis" id="txtanamnesis" cols="30" rows="9" placeholder="Ingresar texto" value="-"></textarea>
            </div>
            <div class="contenido-atencion">
              <h2>EXAMEN FÍSICO</h2>
              <textarea name="txtexamenfisico" id="txtexamenfisico" cols="30" rows="9" placeholder="Ingresar texto" value="-"></textarea>
            </div>
            <div class="contenido-atencion">
              <h2>DIAGNÓSTICO</h2>
              <textarea name="txtdiagnostico" id="txtdiagnostico" cols="30" rows="9" placeholder="Ingresar texto" value="-"></textarea>
            </div>
            <div class="contenido-atencion">
              <h2>TRATAMIENTO</h2>
              <textarea name="txttratamiento" id="txttratamiento" cols="30" rows="9" placeholder="Ingresar texto" value="-"></textarea>
            </div>
          </div>
        </fieldset>
        <button type="button" id="btnregistraratencion" onclick="RegistrarAtencion()">Registrar</button>
      </form>
      <form id="frmEleccionPago">
        <h2>¿Desea Registrar Pago?</h2>
        <div class="count-grupobotones ta-center">
          <button type="button" class="btneleccion" id="btnsi_mostrarpago" onclick="MostrarPagar()">SI</button>
          <button type="button" class="btneleccion btnsecundario" onclick="cerrarmodal()">NO</button>
        </div>
      </form>
      <form id="frmRegistrarGasto">
        <h2>Registrar Gasto</h2>
        <input type="hidden" id="AccionGasto" name="accion" value="REGISTRAR_GASTO" />
        <input type="text" placeholder="Descripción de Gasto" id="DescripcionGasto" name="DescripcionGasto" min="1" />
        <input type="number" placeholder="Monto de Gasto" id="MontoGasto" name="MontoGasto" min="1" />
        <button type="button" id="btnregistrargasto" onclick="registrargasto()">Registrar</button>
      </form>
      <form id="frmRegistrarSignosV">
        <h2>Registrar Signos Vitales</h2>
        <input type="hidden" id="AccionSignosVitales" name="accion" value="REGISTRAR_SIGNOS_VITALES" />
        <input type="hidden" name="idatencion_signos" id="idatencion_signos">
        <div class="grupo-inputs">
          <div class="grupo-controls w60">
            <label for="fr_signosv">Frec.Card.:</label>
            <input type="text" name="fr_signosv" id="fr_signosv" />
          </div>
          <div class="grupo-controls w60">
            <label for="pa_signosv">Pres.Arterial:</label>
            <input type="text" name="pa_signosv" id="pa_signosv" />
          </div>
          <div class="grupo-controls w60">
            <label for="peso_signosv">Peso:</label>
            <input type="text" name="peso_signosv" id="peso_signosv" />
          </div>
          <div class="grupo-controls w60">
            <label for="so2_signosv">SO2:</label>
            <input type="text" name="so2_signosv" id="so2_signosv" />
          </div>
          <div class="grupo-controls w60">
            <label for="temp_signosv">Temp:</label>
            <input type="text" name="temp_signosv" id="temp_signosv" />
          </div>
        </div>
        <button type="button" id="btnregistrarsignos" onclick="registrarSignosVitales()">Registrar</button>
      </form>
      <form id="frmHistorial">
        <h2 id="h2Paciente">
          APELLIDOS Y NOMBRES, DE PACIENTE
          <br />
          <span>Dni: 48193845 | Edad: 27 años</span>
        </h2>
        <input type="hidden" name="IdPacienteHistoria" id="IdPacienteHistoria">
        <div class="cont-historia">
          <div class="aside_historia">
            <h2 class="h2-historial">Atenciones</h2>
            <ul id="ul-atenciones">
              <!-- AJAX -->
            </ul>
            <h2 class="h2-historial">Otros Exámenes</h2>
            <ul id="ul-otrosexamenes">
              <!-- AJAX -->
            </ul>
          </div>
          <div class="consulta_historia">
            <div class="cont-opciones-examenes"><button type="button" id="btnEditAtencion">Editar Atención</button></div>
            <div class="hist_signos datosconsulta">
              <h4>SIGNOS VITALES</h4>
              <div class="cont-signos">
                <p id="hist_fc"><span>FC :</span>-</p>
                <p id="hist_pa"><span>PA :</span>-</p>
              </div>
              <div class="cont-signos">
                <p id="hist_t"><span>T° :</span>-</p>
                <p id="hist_so2"><span>So2 :</span>-</p>
              </div>
              <div class="cont-signos">
                <p id="hist_peso"><span>PESO:</span>-</p>
              </div>
            </div>
            <div class="w60 datosconsulta">
              <p id="hist_fecha">Fecha : </p>
              <label>Antecedentes:</label>
              <p id="hist_antecedente">Miau</p>
            </div>
            <div class="w60 datosconsulta">
              <label>Molestia Principal: </label>
              <p id="hist_molestia">-</p>
            </div>
            <fieldset>
              <label>ANAMNESIS: </label>
              <p id="hist_anamnesis">-</p>
            </fieldset>
            <fieldset>
              <label>EXAMEN FÍSICO: </label>
              <p id="hist_exfisico">-</p>
            </fieldset>
            <fieldset>
              <label>DIAGNÓSTICO: </label>
              <p id="hist_diagnostico">-</p>
            </fieldset>
            <fieldset>
              <label>TRATAMIENTO: </label>
              <p id="hist_tratamiento">-</p>
            </fieldset>
            <div class="cont-examen" id="cont-examen">
              <!-- <iframe src="formularios/examenes/1628352820.pdf" width="100%" height="900px">
              </iframe> -->
            </div>
            <div class="cont-imagenes" id="cont-imagenes">
              <!-- <div class="imagen">
                <img src="formularios/filesImgs/16456828/16286076081.jpg" alt="">
              </div>
              <div class="imagen"><img src="formularios/filesImgs/16456828/16286076082.jpg" alt=""></div> -->
            </div>
          </div>
        </div>
      </form>
      <form id="frmRegistrarProcedimiento">
        <h2>Registrar Procedimiento</h2>
        <input type="hidden" id="AccionProcedimiento" name="accion" value="REGISTRAR_PROCEDIMIENTO" />
        <input type="hidden" name="idprocedimiento" id="idprocedimiento">
        <input type="text" placeholder="Nombre de Procedimiento" id="NombreProcedimiento" name="NombreProcedimiento" />
        <input type="number" placeholder="Precio de Procedimiento" id="PrecioProcedimiento" name="PrecioProcedimiento" min="1" />
        <button type="button" id="btnregistrarProcedimiento" onclick="RegistrarProcedimiento()">Registrar</button>
      </form>
      <form id="frmregistrarEstablecimiento">
        <h2>Registrar Establecimiento</h2>
        <input type="text" placeholder="Nombre de Nuevo Establecimiento" id="NombreEstablecimiento" name="NombreEstablecimiento" />
        <button type="button" onclick="RegistrarEstablecimiento()">Registrar</button>
      </form>
      <form id="frmregistrarcita_externa">
        <h2>Registrar Cita Externa</h2>
        <input type="hidden" id="AccionCitaExterna" name="accion" value="REGISTRAR_CITA_EXTERNA" />
        <input type="hidden" id="CodigoCitaExterna" name="CodigoCitaExterna" />
        <select class="establecimiento" name="establecimientoCitaExterna" id="establecimientoCitaExterna">
          <!-- Ajax -->
        </select>
        <div class="grupo-inputs">
          <input type="text" class="txt-search" placeholder="Número de Documento de Paciente" id="NroDocCitaExt" name="NroDocCitaExt" />
          <button type="button" class="btn-search" onclick="ObtenerDatosPacienteCExt()">
            <i class="fas fa-search"></i>
          </button>
        </div>
        <input type="text" placeholder="Nombre de Paciente" id="NombrePacienteCExt" name="NombrePacienteCExt" readonly class="textdisabled" />
        <input type="hidden" id="IdMovitoCitaExterna" name="IdMovitoCitaExterna">
        <input type="text" class="MotivoCita" id="MovitoCitaExterna" name="MovitoCitaExterna" placeholder="Motivo de CitaExterna">
        <input type="number" id="PrecioMotivoCitaExterna" name="PrecioMotivoCitaExterna" placeholder="Precio de Proc./Consulta">
        <div class="grupo-inputs">
          <input type="date" name="FechaCitaExterna" id="FechaCitaExterna" />
          <input type="time" name="HoraCitaExterna" id="HoraCitaExterna" />
        </div>
        <button type="button" onclick="RegistrarCitaExterna()">
          Registrar
        </button>
      </form>
      <form id="frmRegistrarTratamiento">
        <h2>Registrar Tratamiento</h2>
        <input type="hidden" id="AccionTratamiento" name="accion" value="REGISTRAR_TRATAMIENTO" />
        <input type="hidden" name="idatencion_trat" id="idatencion_trat">
        <input type="hidden" name="idmedicamento" id="idmedicamento">
        <input type="text" placeholder="Nombre de Medicamento" id="NombreMedicamento_Trat" name="NombreMedicamento_Trat" />
        <div class="cont-groupbotones cont-tratamiento">
          <input type="text" placeholder="Indicacion de Tratamiento" id="IndicacionTratamiento" name="IndicacionTratamiento" />
          <button type="button" id="btnAgregarCarrito" onclick="AgregarCarrito()">Agregar</button>
        </div>
        <div class="cont-tabla">
          <table>
            <thead>
              <tr>
                <th>Item</th>
                <th>Cod.</th>
                <th>Medicamento</th>
                <th>Indicación</th>
                <th>Quitar</th>
              </tr>
            </thead>
            <tbody id="tbTratamiento">
              <!--Ajax-->
            </tbody>
          </table>
        </div>
        <button type="button" id="btnregistrarTratamiento" onclick="RegistrarTratamiento()">Registrar</button>
      </form>
      <form id="frmRegistrarProducto">
        <h2>Registrar Movimiento Almacén</h2>
        <input type="hidden" name="accion" value="REGISTRAR_MOVIMIENTO_ALM" />
        <div class="group-radios w100">
          <div class="radio w100">
            <input type="radio" name="movimientoalmacen" id="I" value="I" />
            <label for="I">INGRESO</label>
            <input type="radio" name="movimientoalmacen" id="S" value="S" />
            <label for="S">SALIDA</label>
          </div>
        </div>
        <input type="text" placeholder="Nombre de Medic./Insumo" id="NombreProducto" name="NombreProducto" />
        <input type="hidden" name="IdProducto" id="IdProducto">
        <input type="hidden" name="StockProducto" id="StockProducto">
        <input type="number" placeholder="Cantidad" id="CantidadProducto" name="CantidadProducto" min="1" />
        <input type="text" placeholder="Descripción" id="Descripcion" name="Descripcion" />
        <button type="button" id="btnregistrarMovimientoA" onclick="RegistrarMovimientoA()">Registrar</button>
      </form>
      <form id="frmKardex">
        <h2>Kardex</h2>
        <input type="hidden" name="accion" value="KARDEX" />
        <input type="text" placeholder="Nombre de Medic./Insumo" id="NombreProductoKardex" name="NombreProductoKardex" />
        <input type="hidden" name="IdProductoKardex" id="IdProductoKardex">
        <div class="cont-groupbotones Controls-Kardex">
          <label for="KardexDesde">Desde :</label>
          <div class="cont-control">
            <input type="date" name="KardexDesde" id="KardexDesde" />
          </div>
          <label for="KardexHasta">Hasta :</label>
          <div class="cont-control">
            <input type="date" name="KardexHasta" id="KardexHasta" />
          </div>
          <button class="btn-secundario" type="button" onclick="Kardex()">
            Generar
          </button>
        </div>
        <label id="lblstockactual">Stock Actual:</label>
        <div class="cont-tabla">
          <table>
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>Registrado Por</th>
                <th>Ingreso</th>
                <th>Salida</th>
                <th>Saldo</th>
              </tr>
            </thead>
            <tbody id="tbKardex">
              <!-- AJAX -->
            </tbody>
            <tfoot></tfoot>
          </table>
        </div>
      </form>
      <form id="frmBuscarCitas">
        <h2>Buscar Citas</h2>
        <div class="grupo-inputs">
          <input type="text" class="txt-search" placeholder="Número de Documento" id="NroDocBuscarCita" name="NroDocBuscarCita" />
          <button type="button" class="btn-search" onclick="ObtenerCitas()">
            <i class="fas fa-search"></i>
          </button>
        </div>
        <label id="lblNombreBuscarCitas">Nombre :</label>
        <div class="cont-tabla">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Motivo de Consulta</th>
                <th>Estado</th>
                <th>Estado Atención</th>
                <th>Imp.</th>
              </tr>
            </thead>
            <tbody id="tbBusquedaCitas">
              <!-- AJAX -->
            </tbody>
            <tfoot></tfoot>
          </table>
        </div>
      </form>
      <form id="frmCambioPass">
        <h2>Cambiar Contraseña</h2>
        <input type="hidden" name="accion" value="CAMBIAR_PASS" />
        <input type="hidden" name="IdUsuario" id="IdUsuario" />
        <input type="password" placeholder="Ingrese Nueva Contraseña" id="pass1" name="pass1" />
        <input type="password" placeholder="Repetir Contraseña" id="pass2" name="pass2" />
        <button type="button" onclick="CambiarPass()">Registrar</button>
      </form>
    </div>
  </div>
  <header>
    <div class="cont-logo">
      <!-- <img src="recursos/img/SVG/gmRecurso 1.svg" alt="" /> -->
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 429.54 446.29">
        <g id="Capa_2" data-name="Capa 2">
          <g id="Capa_2-2" data-name="Capa 2">
            <path id="A" fill="#ecf0f1" d="M0,0V214.53H202.48S209,185,195,126c0,0-38.82-112-18.41-126Z" />
            <path id="C1" fill="#ecf0f1" d="M0,229.82V335.71s15.5-42.21,56.5-42.21,141,51.65,147.49-63.68Z" />
            <path id="B" fill="#ecf0f1" d="M223.66,0H429.54V214.53h-53s7.18-82.21-49.91-94.12S215.81,159.5,223.66,0Z" />
            <path id="D" fill="#ecf0f1" d="M429.54,229.82V446.29H222.89V415.71s166.8-46.56,153.71-185.89Z" />
            <path id="C3" fill="#ecf0f1" d="M204,446.29V420.41s-35.49,20.09-89.49-13.91-50-60-74-50,30.21,89.79,30.21,89.79Z" />
            <path id="C2" fill="#ecf0f1" d="M0,400.31v46H15.42S-.49,429.13,0,400.31Z" />
          </g>
        </g>
      </svg>
      <span>Gastro - Medik</span>
    </div>
    <div class="cont-sesion">
      <p><?php echo $_SESSION['apellidos'] . ', ' . $_SESSION['nombre']; ?></p>
      <i class="fas fa-power-off btn-off" id="btn-off"></i>
    </div>
  </header>
  <div class="wrapper">
    <div class="cont-menu-responsive">
      <i class="fas fa-bars btn-toggle"></i>
    </div>
    <aside id="aside">
      <picture>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 429.54 446.29">
          <g id="Capa_2" data-name="Capa 2">
            <g id="Capa_2-2" data-name="Capa 2">
              <path id="A" fill="#a5b1c2" d="M0,0V214.53H202.48S209,185,195,126c0,0-38.82-112-18.41-126Z" />
              <path id="C1" fill="#f7b731" d="M0,229.82V335.71s15.5-42.21,56.5-42.21,141,51.65,147.49-63.68Z" />
              <path id="B" fill="#ccae62" d="M223.66,0H429.54V214.53h-53s7.18-82.21-49.91-94.12S215.81,159.5,223.66,0Z" />
              <path id="D" fill="#3867d6" d="M429.54,229.82V446.29H222.89V415.71s166.8-46.56,153.71-185.89Z" />
              <path id="C3" fill="#f7b731" d="M204,446.29V420.41s-35.49,20.09-89.49-13.91-50-60-74-50,30.21,89.79,30.21,89.79Z" />
              <path id="C2" fill="#f7b731" d="M0,400.31v46H15.42S-.49,429.13,0,400.31Z" />
            </g>
          </g>
        </svg>
      </picture>
      <h2>Gastro - Medik</h2>
      <nav>
        <ul class="menu">
          <li id="li-hoy" onclick="abrirformHoy()" class="activo">
            <a href="javascript:void(0)">Hoy</a>
          </li>
          <?php if ($cargo == 1 || $cargo == 4) { ?>
            <li id="li-citas" onclick="abrirformCitas()" class="activo">
              <a href="javascript:void(0)">Citas</a>
            </li>
          <?php } ?>
          <li id="li-pacientes" onclick="abrirformPacientes()">
            <a href="javascript:void(0)">Pacientes</a>
          </li>
          <?php if ($cargo == 1 || $cargo == 4) { ?>
            <li id="li-atenciones" onclick="abrirformAtenciones()">
              <a href="javascript:void(0)">Atenciones</a>
            </li>
            <li id="li-caja" onclick="abrirformCaja()">
              <a href="javascript:void(0)">Caja</a>
            </li>
            <li id="li-procedimientos" onclick="abrirProcedimientos()">
              <a href="javascript:void(0)">Procedimientos</a>
            </li>
            <li id="li-usuarios" onclick="abrirUsuarios()">
              <a href="javascript:void(0)">Usuarios</a>
            </li>
          <?php } ?>
          <?php if ($cargo == 1) { ?>
            <li id="li-reportes" onclick="abrirReportes()">
              <a href="javascript:void(0)">Reportes</a>
            </li>
          <?php } ?>
          <?php if ($cargo == 1 || $cargo == 4) { ?>
            <li id="li-externos" onclick="abrirExternos()">
              <a href="javascript:void(0)">Proc. Externos</a>
            </li>
            <li id="li-medicamentos" onclick="abrirMedicamentos()">
              <a href="javascript:void(0)">Medicamentos e Insumos</a>
            </li>
            <li id="li-pendientes" onclick="abrirPendientes()">
              <a href="javascript:void(0)">P.Pendientes</a>
            </li>
          <?php } ?>
          <li id="li-cambiarpass" onclick="abrirCambioPass(<?php echo $_SESSION['iduser']; ?>)">
            <a href="javascript:void(0)">Cambiar Contraseña</a>
          </li>
        </ul>
      </nav>
    </aside>
    <div id="contenido" class="contenido">
      <!-- HOY - AJAX -->
    </div>
  </div>
  <script src="recursos/js/functions.js"></script>
  <script src="recursos/js/main.js"></script>
  <script>
    abrirformHoy()
    verificarcajaabierta()
    fechanac.max = new Date().toISOString().split('T')[0]
    FechaCita.min = new Date().toISOString().split('T')[0]
    KardexDesde.max = new Date().toISOString().split('T')[0]
    KardexHasta.max = new Date().toISOString().split('T')[0]
    //HoraCita.min = new TimeRanges()

    var items = <?= json_encode($array) ?>;
    $("#MovitoCita").autocomplete({
      source: items,
      select: function(event, item) {
        filtro = item.item.value;
        $.ajax({
            method: "POST",
            url: 'sistema/controlador/controlador.php',
            data: {
              "accion": "OBTENER_PROC_NOMBRE",
              "filtro": filtro
            }
          })
          .done(function(resultado) {
            json = JSON.parse(resultado);
            tipo_atencion = json.tipo_atencion;
            $("#IdMovitoCita").val(tipo_atencion[0].idtipoatencion);
            $("#PrecioMotivoCita").val(tipo_atencion[0].precio);
          });
      }
    });
    $("#MovitoCitaExterna").autocomplete({
      source: items,
      select: function(event, item) {
        filtro = item.item.value;
        $.ajax({
            method: "POST",
            url: 'sistema/controlador/controlador.php',
            data: {
              "accion": "OBTENER_PROC_NOMBRE",
              "filtro": filtro
            }
          })
          .done(function(resultado) {
            json = JSON.parse(resultado);
            tipo_atencion = json.tipo_atencion;
            $("#IdMovitoCitaExterna").val(tipo_atencion[0].idtipoatencion);
          });
      }
    });
    var medicamentos = <?= json_encode($medicamentos) ?>;
    $("#NombreMedicamento_Trat").autocomplete({
      source: medicamentos,
      select: function(event, item) {
        filtro = item.item.value;
        $.ajax({
            method: "POST",
            url: 'sistema/controlador/controlador.php',
            data: {
              "accion": "OBTENER_MEDICAMENTO_NOMBRE",
              "filtro": filtro
            }
          })
          .done(function(resultado) {
            json = JSON.parse(resultado);
            medicamento = json.medicamento;
            console.log(resultado)
            $("#idmedicamento").val(medicamento[0].idmedicina);
          });
      }
    });
    var productos = <?= json_encode($productos) ?>;
    $("#NombreProducto").autocomplete({
      source: productos,
      select: function(event, item) {
        filtro = item.item.value;
        $.ajax({
            method: "POST",
            url: 'sistema/controlador/controlador.php',
            data: {
              "accion": "OBTENER_MEDICAMENTO_NOMBRE",
              "filtro": filtro
            }
          })
          .done(function(resultado) {
            json = JSON.parse(resultado);
            medicamento = json.medicamento;
            console.log(resultado)
            $("#IdProducto").val(medicamento[0].idmedicina);
            $("#StockProducto").val(medicamento[0].stock);
          });
      }
    });
    $("#NombreProductoKardex").autocomplete({
      source: productos,
      select: function(event, item) {
        filtro = item.item.value;
        $.ajax({
            method: "POST",
            url: 'sistema/controlador/controlador.php',
            data: {
              "accion": "OBTENER_MEDICAMENTO_NOMBRE",
              "filtro": filtro
            }
          })
          .done(function(resultado) {
            json = JSON.parse(resultado);
            medicamento = json.medicamento;
            console.log(resultado)
            $("#IdProductoKardex").val(medicamento[0].idmedicina);
            $("#lblstockactual").html('Stock Actual : ' + medicamento[0].stock)

          });
      }
    });

    function AgregarCarrito() {
      codigo = $("#idmedicamento").val()
      medicamento = $("#NombreMedicamento_Trat").val()
      indicacion = $("#IndicacionTratamiento").val()
      if (codigo === '') {
        alert("SELECCIONE UN MEDICAMENTO")
      } else {
        $.ajax({
            method: "POST",
            url: 'sistema/controlador/controlador.php',
            data: {
              "accion": "AGREGAR_CARRITO",
              "codigo": codigo,
              "medicamento": medicamento,
              "indicacion": indicacion
            }
          })
          .done(function(html) {
            $("#tbTratamiento").html(html)
            $("#idmedicamento").val('')
            $("#NombreMedicamento_Trat").val('')
            $("#IndicacionTratamiento").val('')
          });
      }
    }

    function QuitarCarrito(codigo) {
      $.ajax({
          method: "POST",
          url: 'sistema/controlador/controlador.php',
          data: {
            "accion": "QUITAR_CARRITO",
            "codigo": codigo
          }
        })
        .done(function(html) {
          $("#tbTratamiento").html(html);
        });
    }

    function RegistrarTratamiento() {
      $('#btnregistrarTratamiento').prop('disabled', true)
      datax = $('#frmRegistrarTratamiento').serializeArray()
      $.ajax({
        method: 'POST',
        url: 'sistema/controlador/controlador.php',
        data: datax,
      }).done(function(respuesta) {
        console.log(respuesta)
        if (respuesta === 'SE REGISTRÓ TRATAMIENTO') {
          Swal.fire('SE REGISTRÓ CORRECTAMENTE', 'se ha registrado la atención y tratamiento', 'success')
          idatencion = $('#idatencion_trat').val()
          generarPDF(idatencion)
          cerrarmodal()
          CancelarTratamiento()

        } else {
          Swal.fire('Ocurrió un error', respuesta, 'error')
        }
        $('#btnregistrarTratamiento').prop('disabled', false)
      })
    }

    function imprimirticket($idcita) {
      $.ajax({
          method: "POST",
          url: 'recursos/impresionticket/ticket.php',
          data: {
            "idcita": $idcita
          }
        })
        .done(function(respuesta) {
          if (respuesta === 1) {
            console.log('Imprimiendo....');
          } else {
            console.log('Error');
          }
        });
    }
    var keep_alive = false;
    $(document).bind("click keydown keyup mousemove", function() {
      keep_alive = true;
    });
    setInterval(function() {
      if (keep_alive) {
        pingServer();
        keep_alive = false;
      }
    }, 1200000);

    function pingServer() {
      $.ajax('/keepAlive');
    }
  </script>
</body>

</html>