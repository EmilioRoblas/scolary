<?php 
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<html>

<h5 class="card-text">Firma digital</h5>

<form method="post" action="service/firma/procesoFirma.php" enctype="multipart/form-data">
	<div id="signature-pad">
		<div style="border:solid 1px teal; width:360px;height:110px;padding:3px;position:relative;">
			<div id="note" onmouseover="my_function();">Aquí va su firma</div>
			<canvas id="the_canvas" width="350px" height="100px"></canvas>
		</div>
		<div style="margin:10px;">
			<input type="hidden" id="signature" name="signature">
			<input type="hidden" id="id_firma" name="id_firma" value="<?php echo $_SESSION['id_autorizacion'] ?>">
			<button type="button" id="clear_btn" class="btn btn-danger" data-action="clear"><span class="glyphicon glyphicon-remove"></span> Borrar</button>
			<button type="submit" id="save_btn" class="btn btn-primary" data-action="save-png"><span class="glyphicon glyphicon-ok"></span> Guardar firma</button>
		</div>
	</div>
</form>
	
<script>
var wrapper = document.getElementById("signature-pad");
var clearButton = wrapper.querySelector("[data-action=clear]");
var savePNGButton = wrapper.querySelector("[data-action=save-png]");
var canvas = wrapper.querySelector("canvas");
var el_note = document.getElementById("note");
var signaturePad;
signaturePad = new SignaturePad(canvas);

clearButton.addEventListener("click", function (event) {
	document.getElementById("note").innerHTML="Aquí va su firma";
	signaturePad.clear();
});
savePNGButton.addEventListener("click", function (event){
	if (signaturePad.isEmpty()){
		alert("Please provide signature first.");
		event.preventDefault();
	}else{
		var canvas  = document.getElementById("the_canvas");
		var dataUrl = canvas.toDataURL();
		document.getElementById("signature").value = dataUrl;
	}
});
function my_function(){
	document.getElementById("note").innerHTML="";
}
</script>

</html>