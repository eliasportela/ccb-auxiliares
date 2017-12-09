<!DOCTYPE html>
<html>
<head>
    <title>App Rodizio</title>
    <meta charset="utf-8">

</head>
<body>

<form id="gerarServicos">
	<label>Usuário Início</label>
	<select name="id_usuario">
		<option value="1">Elias</option>
	</select>
	<br>
	<label>Repetir</label>
	<select name="repetir">
		<option value="1">Semanalmente</option>
	</select>
	<br>
	<button type="submit">Gerar</button>
</form>

<script type="text/javascript" src="<?=base_url('assets/js/vendor/jquery-3.2.1.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/adm/servico.js')?>"></script>

</body>
</html>