<?php 
require_once(dirname(__FILE__).'/autoload.php');
protegeArquivo(basename(__FILE__));
class produtos extends base{
	private $titulo;
	private $textarea;
	private $created;
	private $array = array();
	
	public function __construct($campos=array()){
		parent::__construct();
		$this->tabela = "produtos";
		if (sizeof($campos)<=0) {
			$this->campos_valores = array(
				"id_produtos" => null,
				"nome" => null,
				"valor_produto" => null,
				"preco" => null,
				"quantidade_estoque" => null,
				"codigo_barras" => null,
				"categoria" => null,
				"descricao" => null,
				"data_adicao" => null,
				"data_atualizacao" => null,
			);
		}else{
			$this->campos_valores = $campos;
		}
		$this->campopk = "produto_id";
	}//construct
	public function existeRegistro($campo=null, $valor=null){
		if ($campo!=NULL && $valor!=NULL):
			is_numeric($valor) ? $valor = $valor : $valor = "'".$valor."'";
			$this->extras_select = "WHERE $campo=$valor";
			$this->selecionaTudo($this);
			if($this->linhasafetadas >0):
				return TRUE;
			else:
				return FALSE;
			endif;
		else:
			$this->trataerro(__FILE__,__FUNCTION__,NULL,'Faltam parâmetros para executar a função', TRUE);
		endif;
	}
	public function ShowAll($order = 'asc', $limit = null){
		
	}
	public function ShowSingle($cpf = null, $ru = null, $nome = null){
		
	}
	public function destaque($id = null){
		if ($id != null) {
			$cursos_has_anexos = new cursos_has_anexos();
			$cursos_has_anexos->extras_select = ", cursos, anexos where cursos_has_anexos.cursos_id_produtos = $id and cursos_has_anexos.destaque_anexo = 1 and cursos.id_produtos = cursos_has_anexos.cursos_id_produtos and anexos.id_anexo = cursos_has_anexos.anexos_id_anexo order by anexos.id_anexo desc limit 1";	
		}
		$cursos_has_anexos->selecionaTudo($cursos_has_anexos);
		$obj = $cursos_has_anexos->retornaDados();
		return $obj->nome_anexo;
	}
	public function galeria($id = null){
		if ($id != null) {
			$cursos_has_anexos = new cursos_has_anexos();
			$cursos_has_anexos->extras_select = " ,anexos where cursos_has_anexos.cursos_id = $id and anexos.id = anexos_id and anexos.destaque is null";
		}
		$cursos_has_anexos->selecionaTudo($cursos_has_anexos);
		while ($obj = $cursos_has_anexos->retornaDados()) {
			$cursos_has_anexos->array[] = $obj;
		}
		return $cursos_has_anexos->array;
	}
}
