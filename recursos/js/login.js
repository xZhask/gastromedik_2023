function remove_addClass(contenedor, link) {
  $(contenedor + " .activo").removeClass("activo");
  $(link).addClass("activo");
}
function abrir_form(div, contenido) {
  $.ajax({
    method: "POST",
    url: "formularios/" + contenido,
  }).done(function (html) {
    $(div).html(html);
  });
}
/* VALIDACIÓN */
const frmlogin = document.querySelector("#frmlogin");

frmlogin.addEventListener("submit", function (e) {
  e.preventDefault();
  var datax = $("#frmlogin").serializeArray();
  $.ajax({
    method: "POST",
    url: "sistema/controlador/controlador.php",
    data: datax,
  }).done(function (respuesta) {
    if (respuesta === "INICIO") {
      window.location.assign("index.php");
    } else {
      alert("DATOS INCORRECTOS");
    }
  });
});
