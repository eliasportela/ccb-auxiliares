<?php defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Api extends CI_Controller {
	
	function __construct(){
		parent::__construct();
	}

	//Principal
	public function Home()
	{
		$this->load->view('index');
	}

	//Gera Lista
	public function ListarServicos()
	{

		$igreja = $this->input->get('igreja');
		$mes = $this->input->get('mes');
		$ano = $this->input->get('ano');
		$resultado = [];

		if ($igreja and $mes) {

			$igreja = (int)$igreja;

			$sql = "SELECT id_evento, DATE_FORMAT(data, '%d-%m') as data from evento where id_igreja = $igreja and MONTH(data) = $mes and YEAR(data) = $ano ORDER BY data";
			$eventos = $this->Crud_model->Query($sql);

			//die(var_dump($eventos));

			if ($eventos) {
				# code...
				foreach ($eventos as $evento) {

					$sql = "select s.id_servico, u.nome, t.ds_servico 
						from servicos s 
						INNER JOIN usuario u ON (u.id_usuario = s.id_user) 
						INNER JOIN evento e ON (e.id_evento = s.id_evento) 
						INNER JOIN tipo_servico t ON (t.id_tipo_servico = s.id_tipo_servico) 
						WHERE e.id_evento = $evento->id_evento ORDER BY t.id_tipo_servico asc";

						$resultado[] = array('data' => $evento->data,'servicos' => $this->Crud_model->Query($sql));
				}
			}
			
			if (!$resultado){
				$this->output->set_status_header('204');
			}else {
				$json = $resultado;
				echo json_encode($json,JSON_UNESCAPED_UNICODE);
				$this->output->set_status_header('200');
			}

		}
	}

	public function ordernar($p)
	{
		$a = [];
		$qtd = count($p);
		$ultimo = $p[$qtd-1];

		$a[0] = $p[$qtd-1];

		for ($i=0; $i < $qtd-1; $i++) { 
			if ($i < $qtd-1) {
				$a[$i+1] = $p[$i];
			}
		}

		return $a;
	}

	//geracao de servicos
	public function Gerar()
	{
		//===Eventos===
		//data do inicio da geracao
		$data_inicio = '2017-12-31';
		//igreja
		$igreja = 1;
		//Tipo de Evento
		$tipo_evento = 1;
		//===Serviços===
		//usuario q vai inciar a lista
		$usuario_inicio = 3;
		//Intervalo de Dias q vai repetir
		$intervalo = 7;
		//Quantas vezes vai repetir
		$repetir = 9;
		//buscando os servicos
		$servicosModel = $this->Crud_model->Query('select id_tipo_servico from tipo_servico where fg_ativo = 1 order by id_tipo_servico');
		foreach ($servicosModel as $servico) {
			$servicos[] = $servico->id_tipo_servico;
		}
		//Buscando os usuario conforme a igreja
		$usuarios = $this->Crud_model->Query('select id_usuario from usuario where id_igreja = '.$igreja.' and fg_ativo = 1');
		
		$qtdUsuario = $this->Crud_model->Query('select count(*) as qtd from usuario where id_igreja = '.$igreja.' and fg_ativo = 1');
		$qtdUsuario = $qtdUsuario[0]->qtd;

		//verifica a chave do usuario inicial
		while ($u =  current($usuarios)){
			if ($u->id_usuario == $usuario_inicio) {
				$userInicio = key($usuarios);
				break;
			}
			next($usuarios);
		}
		//se nao existir da um erro
		if (!isset($userInicio)) {
			die(var_dump("Erro ao processar usuario inicial"));
		}
		
		//quebrando a data
		$quebrarDatas = explode("-", $data_inicio);
		list($ano, $mes, $dia) = $quebrarDatas;

		//die(var_dump($servicos));
		//insere os eventos
		for ($i=0; $i < $repetir ; $i++) {
			$aux = $intervalo * $i;
			$data_servico = mktime(0,0,0,$mes,$dia + $aux,$ano);	
			$data_model = array('id_tipo_evento'=>$tipo_evento,'data'=>date('Y-m-d',$data_servico),'id_igreja'=>$igreja);
			//inserindo no bd
			$res[] = $this->Crud_model->InsertId('evento',$data_model);
		}

		if ($res):
			//Insere os servicos dentro dos eventos
			foreach ($res as $cont) {
				foreach ($servicos as $servico) {
					$data_model = array('id_user' => $usuarios[$userInicio % $qtdUsuario]->id_usuario,'id_evento' => $cont,'id_tipo_servico' => $servico);
					$res2 = $this->Crud_model->Insert('servicos',$data_model);
					$userInicio++;
				}
				$servicos = $this->ordernar($servicos);
			}
			$this->output->set_status_header('200');
		else:
			$this->output->set_status_header('400');
		endif;
	}

	//Login
	public function Login() {

		$sql = "select * from usuario";

		$resultado = $this->Crud_model->Query($sql);
		
		if (!$resultado){
			$this->output->set_status_header('400');
		}else {
			$json = $resultado;
			echo json_encode($json,JSON_UNESCAPED_UNICODE);
			}
		
	}

	

	
}